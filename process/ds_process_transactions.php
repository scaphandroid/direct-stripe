<?php
/**
 * Created by PhpStorm.
 * User: nahuel
 * Date: 20/06/2018
 * Time: 17:49
 */
defined( 'ABSPATH' ) or die( 'Please!' );


class ds_process_transactions {

    public function __construct() {
        // Stripe
        if ( ! class_exists('Stripe\Stripe')) {
            require_once( DSCORE_PATH . 'vendor/autoload.php' );
        }
        //Functions
        if ( ! class_exists('ds_process_functions')) {
            require_once( DSCORE_PATH . 'process/ds_process_functions.php');
        }

        $this->ds_process();
    }

    /**
     * Heart of the action; button triggered
     *
     * @since 2.1.4
     */
    function ds_process()
    {
        //Retrieve Data
        require_once( DSCORE_PATH . 'process/ds_retrieve_data.php');

        //Process API Keys
        \ds_process_functions::api_keys( $d_stripe_general );

        //Process User
        $user = \ds_process_functions::check_user_process( $email_address, $d_stripe_general, $custom_role, $token, $params );

        //Process Transaction
        try {

            // Charge for setup fee
            if( !empty( $setup_fee) ){
                $setupfeedata = array(
                    "amount" => $setup_fee,
                    "currency" => $currency,
                    "description" => __('One time setup fee ', 'direct-stripe') . $description
                );
                if($user === false ) {
                    $setupfeedata['source' ] = $token;
                } else {
                    $setupfeedata['customer'] = $user['stripe_id'];
                }
                $fee = \Stripe\InvoiceItem::create( $setupfeedata );
            }

            //Charge
            if( $params['type'] === 'payment' || $params['type'] === 'donation') {

                $subscription = false;

                $chargerdata = array(
                    'amount'      => $amount,
                    'currency'    => $currency,
                    'capture'     => $capture,
                    'description' => $description
                );
                if($user === false ) {
                    $chargerdata['source' ] = $token;
                } else {
                    $chargerdata['customer'] = $user['stripe_id'];
                }
                $charge   = \Stripe\Charge::create( $chargerdata );

            } elseif( $params['type'] === 'subscription' ) {

                $charge = false;

                // create new subscription to plan
                $subscriptiondata = array(
                    "items" => array(
                        array(
                            "plan" => $amount,
                        ),
                    ),
                    "coupon"   => $coupon,
                    "metadata"	=> array(
                        "description" => $description
                    )
                );
                if($user === false ) {
                    $subscriptiondata['source' ] = $token;
                } else {
                    $subscriptiondata['customer'] = $user['stripe_id'];
                }
                $subscription = \Stripe\Subscription::create( $subscriptiondata );

                /*$subscription_id = $subscription->id;

                //infos
                $plan = $subscription->plan;
                $plan_amount = $plan->amount;*/
            }


        } catch (Exception $e) {
            $e = $e;
            error_log("Something wrong happened:" . $e->getMessage() );
        }

        //Retrieve Meta Data
        require_once( DSCORE_PATH . 'process/ds_retrieve_meta.php');
        //Process Meta Data
        if( $charge && $d_stripe_general['direct_stripe_check_records'] !== true || $subscription && $d_stripe_general['direct_stripe_check_records'] !== true ) {
            $post_id = \ds_process_functions::logs_meta( $logsdata, $params );
            if( $user ){
                $user_id = \ds_process_functions::user_meta( $logsdata, $params, $user );
            }
        } else {
            $post_id = false;
            $user_id = false;
        }

        //Process emails
        if( $charge ) {
            $email = \ds_process_functions::process_emails( $charge, $token, $button_id, $currency, $currency, $email_address, $description, $user, $post_id );
        } elseif( $subscription ) {
            $email = \ds_process_functions::process_emails( $subscription, $token, $button_id, $currency, $currency, $email_address, $description, $user, $post_id );
        } else {
            $email = \ds_process_functions::process_emails( $e, $token, $button_id, $currency, $currency, $email_address, $description, $user, $post_id );
        }

        //Process answer
        if( $charge ) {
            $answer = \ds_process_functions::process_answer( $button_id, $charge, $token, $params, $d_stripe_general, $post_id, $user_id );
        } elseif( $subscription ) {
            $answer = \ds_process_functions::process_answer( $button_id, $subscription, $token, $params, $d_stripe_general, $post_id, $user_id);
        } else {
            $answer = \ds_process_functions::process_answer( $button_id, $e, $token, $params, $d_stripe_general, $post_id , $user_id);
        }

    }


}
$dsProcess = new ds_process_transactions;