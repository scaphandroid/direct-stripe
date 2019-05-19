<?php
/*
 * Check if the shortcode is given a value argument
 *
 * If not, values still can be retrieved the old way with shortcode arguments
 *
 * @since 2.1.0
 */
if( ! $directStripeAttrValues['value'] || $directStripeAttrValues['value'] === '0' ) {
    $ds_button = (object) $directStripeAttrValues;
}

//Button ID
if ( !empty( $ds_button->button_id ) ) {
    $button_id = $ds_button->button_id;
} else {
    $button_id = $instance;
}
if( !empty( $ds_button->label ) ){
    $label = $ds_button->label;
} else {
    $label = __( 'Undefined button value, check settings', 'direct-stripe' );
}

// Opening actions
do_action( 'direct_stripe_before_form' );
do_action( 'direct_stripe_before_button', $button_id);

//Button Alignment
$ds_class = 'direct-stripe';
if( isset( $atts['alignment'] ) ) {
    if( $atts['alignment'] === 'left' ){
        $ds_class .= ' ds-left';
    } elseif( $atts['alignment'] === 'center' ) {
        $ds_class .= ' ds-center';
    } elseif( $atts['alignment'] === 'right' ) {
        $ds_class .= ' ds-right';
    }
}

    //Opening Div
    $str_before = '<div class="' . $ds_class . '">';
    $str_before = apply_filters( 'direct_stripe_div_before', $str_before, $button_id, $ds_class );
    echo $str_before;

    //Donation condition and input
    if(  isset( $ds_button->type ) && $ds_button->type === 'donation' ) {
        $direct_stripe_donation_input = '<input lang="en" type="number" step="0.01" min="1" name="donationvalue" id="donationvalue-' . $instance . '" class="donationvalue" data-donation-input-id="' . $instance . '" />';
       echo apply_filters('direct_stripe_donation_input', $direct_stripe_donation_input, $instance, $button_id );
     }
     ?>

    <?php //Custom styles button conditions
    if( isset($d_stripe_styles['direct_stripe_use_custom_styles']) && $d_stripe_styles['direct_stripe_use_custom_styles'] === '1' ) {
	    $ds_button_class = 'direct-stripe-button direct-stripe-button-id ';
    } elseif( isset($d_stripe_styles['direct_stripe_use_custom_styles']) && $d_stripe_styles['direct_stripe_use_custom_styles'] === '2' ) {
	    $ds_button_class = 'original-stripe-button direct-stripe-button-id ';
     } else {
        $ds_button_class = 'stripe-button-ds direct-stripe-button-id ';
     }
    //T&C Check box condition
    if(isset($ds_button->tc) && !empty($ds_button->tc) && $ds_button->tc !== false && $ds_button->tc !== "false" && $ds_button->tc !== "0") {
        $ds_button_class .= ' ds-check-tc';
    }
    if(  isset( $ds_button->type ) && $ds_button->type === 'donation' ) {
        $ds_button_class .= ' ds-check-donation';
    }
    //Button Class
    $ds_button_class = apply_filters('direct_stripe_button_class', $ds_button_class, $button_id, $instance );

    //Button
    $button = '<button id="' . $button_id . '" data-id="' . $instance . '" class="' . $ds_button_class . ' ' . $instance . '">' . esc_attr( $label ) . '</button>';
    $button = apply_filters( 'direct_stripe_button', $button, $instance, $button_id, $ds_button_class);
    echo $button;

    //T&C Check box condition
    if(isset($ds_button->tc) && !empty($ds_button->tc) && $ds_button->tc !== false && $ds_button->tc !== "false" && $ds_button->tc !== "0") {
        $tc_cond = '<br/><input type="checkbox" class="ds-conditions ' . $instance . '" id="ds-conditions-' . $instance . '" required/>
        <label for="ds-conditions-' . $instance . '">
        ' . esc_attr($d_stripe_styles['direct_stripe_tc_text']) . '
            <a target="_blank" href="' . esc_url($d_stripe_styles['direct_stripe_tc_link']) . '">' . $d_stripe_styles['direct_stripe_tc_link_text'] . '</a>
        </label><br />';
        $tc_cond= apply_filters( 'direct_stripe_tc_conditions', $tc_cond, $button_id, $instance, $d_stripe_styles['direct_stripe_tc_text'], $d_stripe_styles['direct_stripe_tc_link'], $d_stripe_styles['direct_stripe_tc_link_text'] );
        echo $tc_cond;
    }

    //Closing Div
    $str_after = "</div>";
    $str_after = apply_filters( 'direct_stripe_div_after', $str_after, $button_id );
    echo $str_after;


?>
<!-- The Modal -->
<div id="modal-<?php echo $instance ?>" class="ds-modal">

  <!-- Modal content -->
  <div class="ds-modal-content">
        <span class="ds-close">&times;</span>

        <div class="ds-form">

            <div class="cell example example5" id="example-5">
                <form data-id="<?php echo $instance; ?>">
                    <div id="example5-paymentRequest">
                        <!--Stripe paymentRequestButton Element inserted here-->
                    </div>
                    <fieldset>
                        <legend class="card-only" data-tid="elements_examples.form.pay_with_card">Pay with card</legend>
                        <legend class="payment-request-available" data-tid="elements_examples.form.enter_card_manually">Or enter card details</legend>
                        <div class="row">
                            <div class="field">
                                <label for="example5-name" data-tid="elements_examples.form.name_label">Name</label>
                                <input id="example5-name" data-tid="elements_examples.form.name_placeholder" class="input" type="text" placeholder="Jane Doe" required="" autocomplete="name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <label for="example5-email" data-tid="elements_examples.form.email_label">Email</label>
                                <input id="example5-email" data-tid="elements_examples.form.email_placeholder" class="input" type="text" placeholder="janedoe@gmail.com" required="" autocomplete="email">
                            </div>
                            <div class="field">
                                <label for="example5-phone" data-tid="elements_examples.form.phone_label">Phone</label>
                                <input id="example5-phone" data-tid="elements_examples.form.phone_placeholder" class="input" type="text" placeholder="(941) 555-0123" required="" autocomplete="tel">
                            </div>
                            </div>
                            <div data-locale-reversible="">
                            <div class="row">
                                <div class="field">
                                    <label for="example5-address" data-tid="elements_examples.form.address_label">Address</label>
                                    <input id="example5-address" data-tid="elements_examples.form.address_placeholder" class="input" type="text" placeholder="185 Berry St" required="" autocomplete="address-line1">
                                </div>
                            </div>
                            <div class="row" data-locale-reversible="">
                                <div class="field">
                                    <label for="example5-city" data-tid="elements_examples.form.city_label">City</label>
                                    <input id="example5-city" data-tid="elements_examples.form.city_placeholder" class="input" type="text" placeholder="San Francisco" required="" autocomplete="address-level2">
                                </div>
                                <div class="field">
                                    <label for="example5-state" data-tid="elements_examples.form.state_label">State</label>
                                    <input id="example5-state" data-tid="elements_examples.form.state_placeholder" class="input empty" type="text" placeholder="CA" required="" autocomplete="address-level1">
                                </div>
                                <div class="field">
                                    <label for="example5-zip" data-tid="elements_examples.form.postal_code_label">ZIP</label>
                                    <input id="example5-zip" data-tid="elements_examples.form.postal_code_placeholder" class="input empty" type="text" placeholder="94107" required="" autocomplete="postal-code">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="field">
                                <label for="example5-card" data-tid="elements_examples.form.card_label">Card</label>
                                <div id="example5-card" class="input StripeElement StripeElement--empty"><div class="__PrivateStripeElement" style="margin: 0px !important; padding: 0px !important; border: medium none !important; display: block !important; background: transparent none repeat scroll 0% 0% !important; position: relative !important; opacity: 1 !important;"><iframe allowtransparency="true" scrolling="no" name="__privateStripeFrame22" allowpaymentrequest="true" src="https://js.stripe.com/v3/elements-inner-card-526df1d2f610b7ed4891cd03d8cb5720.html#style[base][iconColor]=%23fff&amp;style[base][color]=%23fff&amp;style[base][fontWeight]=400&amp;style[base][fontFamily]=Helvetica+Neue%2C+Helvetica%2C+Arial%2C+sans-serif&amp;style[base][fontSize]=16px&amp;style[base][fontSmoothing]=antialiased&amp;style[base][::placeholder][color]=%23BFAEF6&amp;style[base][:-webkit-autofill][color]=%23fce883&amp;style[invalid][iconColor]=%23FFC7EE&amp;style[invalid][color]=%23FFC7EE&amp;iconStyle=solid&amp;locale=en&amp;componentName=card&amp;wait=false&amp;rtl=false&amp;keyMode=test&amp;origin=https%3A%2F%2Fstripe.dev&amp;referrer=https%3A%2F%2Fstripe.dev%2Felements-examples%2F&amp;controllerId=__privateStripeController1" title="Secure payment input frame" style="border: medium none !important; margin: 0px !important; padding: 0px !important; width: 1px !important; min-width: 100% !important; overflow: hidden !important; display: block !important; height: 19.2px;" frameborder="0"></iframe><input class="__PrivateStripeElement-input" aria-hidden="true" aria-label=" " autocomplete="false" maxlength="1" style="border: medium none !important; display: block !important; position: absolute !important; height: 1px !important; top: 0px !important; left: 0px !important; padding: 0px !important; margin: 0px !important; width: 100% !important; opacity: 0 !important; background: transparent none repeat scroll 0% 0% !important; pointer-events: none !important; font-size: 16px !important;"></div></div>
                            </div>
                        </div>
                        <button type="submit" data-tid="elements_examples.form.pay_button">Pay $25</button>
                    </fieldset>
                    <div class="error" role="alert"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                        <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                        <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                        </svg>
                        <span class="message"></span>
                    </div>
                    <div class="success">
                        <div class="icon">
                            <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink">
                            <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                            <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
                            </svg>
                        </div>
                        <h3 class="title" data-tid="elements_examples.success.title">Payment successful</h3>
                        <p class="message"><span data-tid="elements_examples.success.message">Thanks for trying Stripe Elements. No money was charged, but we generated a token:</span><span class="token">tok_189gMN2eZvKYlo2CwTBv9KKh</span></p>
                        <a class="reset" href="#">
                            <svg width="32px" height="32px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink">
                            <path fill="#000000" d="M15,7.05492878 C10.5000495,7.55237307 7,11.3674463 7,16 C7,20.9705627 11.0294373,25 16,25 C20.9705627,25 25,20.9705627 25,16 C25,15.3627484 24.4834055,14.8461538 23.8461538,14.8461538 C23.2089022,14.8461538 22.6923077,15.3627484 22.6923077,16 C22.6923077,19.6960595 19.6960595,22.6923077 16,22.6923077 C12.3039405,22.6923077 9.30769231,19.6960595 9.30769231,16 C9.30769231,12.3039405 12.3039405,9.30769231 16,9.30769231 L16,12.0841673 C16,12.1800431 16.0275652,12.2738974 16.0794108,12.354546 C16.2287368,12.5868311 16.5380938,12.6540826 16.7703788,12.5047565 L22.3457501,8.92058924 L22.3457501,8.92058924 C22.4060014,8.88185624 22.4572275,8.83063012 22.4959605,8.7703788 C22.6452866,8.53809377 22.5780351,8.22873685 22.3457501,8.07941076 L22.3457501,8.07941076 L16.7703788,4.49524351 C16.6897301,4.44339794 16.5958758,4.41583275 16.5,4.41583275 C16.2238576,4.41583275 16,4.63969037 16,4.91583275 L16,7 L15,7 L15,7.05492878 Z M16,32 C7.163444,32 0,24.836556 0,16 C0,7.163444 7.163444,0 16,0 C24.836556,0 32,7.163444 32,16 C32,24.836556 24.836556,32 16,32 Z"></path>
                            </svg>
                        </a>
                    </div>
                </form>
                
            </div>
        </div><!-- .ds-form -->

  </div>

</div> 
<?php
do_action( 'direct_stripe_after_button', $button_id  );