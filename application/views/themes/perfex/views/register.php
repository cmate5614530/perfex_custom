<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12 loginpage-left">

<div class="loginpage-left-common">
   <div class="login-left-content text-center">
   <div class="login-title-details">
      <div class="loginpg-title">
              <h2> <?php
         echo _l(get_option('allow_registration') == 1 ? 'clients_login_heading_register' : 'clients_login_heading_no_register');
         ?></h2>
              <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur.</p>
            </div>
            <ul>
              <li> <a href="#"> <img src="<?php echo base_url("/assets/images/fb-lcon-login.png");?>" alt="">
                <p>Signup with Facebook</p>
                </a> </li>
              <li> <a href="#"> <img src="<?php echo base_url("/assets/images/google-icon-login.png");?>" alt="">
                <p>Signup with Google</p>
                </a> </li>
            </ul>
            <div class="form-seperate-text">
            <p>or</p>
          </div>
   </div>
   
    <div class="login-form personal-info-form">
      <?php echo form_open($this->uri->uri_string(), array('class' => 'login-form')); ?>
      <?php hooks()->do_action('clients_login_form_start'); ?>
   
      <div class="panel_s1">
         <div class="panel-body1">
          
            <div class="form-group">
               <label for="email"><?php echo _l('clients_login_email'); ?></label>
               <input type="text" autofocus="true" class="form-control" name="email" id="email"> <i class="fa fa-check" aria-hidden="true"></i>
               <?php echo form_error('email'); ?>
            </div>
            <div class="form-group">
               <label for="password"><?php echo _l('clients_login_password'); ?></label>
               <input type="password" class="form-control" name="password" id="password"> <i class="fa fa-check" aria-hidden="true"></i>
               <?php echo form_error('password'); ?>
            </div>
            <?php if (show_recaptcha_in_customers_area()) { ?>
               <div class="g-recaptcha mbot15" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
               <?php echo form_error('g-recaptcha-response'); ?>
            <?php } ?>
            <div class="checkbox">
               <input type="checkbox" name="remember" id="remember">
               <label for="remember">
                  <?php echo _l('clients_login_remember'); ?>
               </label>
            </div>
            <div class="col-sm-4 col-md-4 col-lg-4 col-xs-12 login-forgot">
            <a href="<?php echo site_url('authentication/forgot_password'); ?>"><?php echo _l('customer_forgot_password'); ?></a>
            </div>
          <div class="col-sm-8 col-md-8 col-lg-8 col-xs-12 login-submit">
          <div class="form-group">
           <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 bright">
               <button type="submit" class="mysubmit"><?php echo _l('clients_login_login_string'); ?></button>
               </div>
                
            </div>
            </div>
            
            
            <?php hooks()->do_action('clients_login_form_end'); ?>
            <?php echo form_close(); ?>
         </div>
      </div>
     
   </div>
   </div>
    
   </div>

</div>


<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12 loginpage-right">

<div class="loginpage-right-common">
<div class="pwderror">
            <p>Passwords do not match! Please check.</p>
          </div>
  
<div class="registernow-part">
  <div class="login-title-details">
   <div class="loginpg-title"> <h2 class="register-heading"><?php echo _l('clients_register_heading'); ?></h2> 
   <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
   </div>
   </div>

 <div class="register-form personal-info-form">
    <?php echo form_open('authentication/register', ['id'=>'register-form']); ?>
    <div class="panel_s1">
        <div class="panel-body1">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="bold register-contact-info-heading"><?php echo _l('client_register_contact_info'); ?></h4>
                    <div class="form-group mtop15 register-firstname-group">
                        <label class="control-label" for="firstname"><?php echo _l('clients_firstname'); ?></label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo set_value('firstname'); ?>"> <i class="fa fa-check" aria-hidden="true"></i>
                        <?php echo form_error('firstname'); ?>
                    </div>
                    <div class="form-group register-lastname-group">
                        <label class="control-label" for="lastname"><?php echo _l('clients_lastname'); ?></label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo set_value('lastname'); ?>"><i class="fa fa-check" aria-hidden="true"></i>
                        <?php echo form_error('lastname'); ?>
                    </div>
                    <div class="form-group register-email-group">
                        <label class="control-label" for="email"><?php echo _l('clients_email'); ?></label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>"><i class="fa fa-check" aria-hidden="true"></i>
                        <?php echo form_error('email'); ?>
                    </div>
                    <div class="form-group register-contact-phone-group">
                        <label class="control-label" for="contact_phonenumber"><?php echo _l('clients_phone'); ?></label>
                        <input type="text" class="form-control" name="contact_phonenumber" id="contact_phonenumber" value="<?php echo set_value('contact_phonenumber'); ?>">
                    </div>
                    <div class="form-group register-website-group">
                        <label class="control-label" for="website"><?php echo _l('client_website'); ?></label>
                        <input type="text" class="form-control" name="website" id="website" value="<?php echo set_value('website'); ?>"><i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                    <div class="form-group register-position-group">
                        <label class="control-label" for="title"><?php echo _l('contact_position'); ?></label>
                        <input type="text" class="form-control" name="title" id="title" value="<?php echo set_value('title'); ?>"><i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                    <div class="form-group register-password-group">
                        <label class="control-label" for="password"><?php echo _l('clients_register_password'); ?></label>
                        <input type="password" class="form-control" name="password" id="password"> <i class="fa fa-check" aria-hidden="true"></i>
                        <?php echo form_error('password'); ?>
                    </div>
                    <div class="form-group register-password-repeat-group">
                        <label class="control-label" for="passwordr"><?php echo _l('clients_register_password_repeat'); ?></label>
                        <input type="password" class="form-control" name="passwordr" id="passwordr">
                        <?php echo form_error('passwordr'); ?>
                    </div>
                    <div class="register-contact-custom-fields">
                        <?php echo render_custom_fields( 'contacts','',array('show_on_client_portal'=>1)); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="bold register-company-info-heading"><?php echo _l('client_register_company_info'); ?></h4>
                    <div class="form-group mtop15 register-company-group">
                        <label class="control-label" for="company"><?php echo _l('clients_company'); ?></label>
                        <input type="text" class="form-control" name="company" id="company" value="<?php echo set_value('company'); ?>"> <i class="fa fa-check" aria-hidden="true"></i>
                        <?php echo form_error('company'); ?> 
                    </div>
                    <?php if(get_option('company_requires_vat_number_field') == 1){ ?>
                    <div class="form-group register-vat-group">
                        <label class="control-label" for="vat"><?php echo _l('clients_vat'); ?></label>
                        <input type="text" class="form-control" name="vat" id="vat" value="<?php echo set_value('vat'); ?>"> <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                    <?php } ?>
                    <div class="form-group register-company-phone-group">
                        <label class="control-label" for="phonenumber"><?php echo _l('clients_phone'); ?></label>
                        <input type="text" class="form-control" name="phonenumber" id="phonenumber" value="<?php echo set_value('phonenumber'); ?>"> <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                    <div class="form-group register-country-group">
                        <label class="control-label" for="lastname"><?php echo _l('clients_country'); ?></label>
                        <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" name="country" class="form-control" id="country"> 
                            <option value=""></option>
                            <?php foreach(get_all_countries() as $country){ ?>
                            <option value="<?php echo $country['country_id']; ?>"<?php if(get_option('customer_default_country') == $country['country_id']){echo ' selected';} ?> <?php echo set_select('country', $country['country_id']); ?>><?php echo $country['short_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group register-city-group">
                        <label class="control-label" for="city"><?php echo _l('clients_city'); ?></label>
                        <input type="text" class="form-control" name="city" id="city" value="<?php echo set_value('city'); ?>"> <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                    <div class="form-group register-address-group">
                        <label class="control-label" for="address"><?php echo _l('clients_address'); ?></label>
                        <input type="text" class="form-control" name="address" id="address" value="<?php echo set_value('address'); ?>"> <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                    <div class="form-group register-zip-group">
                        <label class="control-label" for="zip"><?php echo _l('clients_zip'); ?></label>
                        <input type="text" class="form-control" name="zip" id="zip" value="<?php echo set_value('zip'); ?>"> <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                    <div class="form-group register-state-group">
                        <label class="control-label" for="state"><?php echo _l('clients_state'); ?></label>
                        <input type="text" class="form-control" name="state" id="state" value="<?php echo set_value('state'); ?>"> <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                    <div class="register-company-custom-fields">
                        <?php echo render_custom_fields( 'customers','',array('show_on_client_portal'=>1)); ?>
                    </div>
                </div>
                <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions') == 1) { ?>
                <div class="col-md-12 register-terms-and-conditions-wrapper">
                    <div class="text-center">
                       <div class="checkbox">
                        <input type="checkbox" name="accept_terms_and_conditions" id="accept_terms_and_conditions" <?php echo set_checkbox('accept_terms_and_conditions', 'on'); ?>>
                        <label for="accept_terms_and_conditions">
                            <?php echo _l('gdpr_terms_agree', terms_url()); ?>
                        </label>
                    </div>
                    <?php echo form_error('accept_terms_and_conditions'); ?>
                </div>
            </div>
            <?php } ?>
            <?php if(show_recaptcha_in_customers_area()){ ?>
            <div class="col-md-12 register-recaptcha">
               <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
               <?php echo form_error('g-recaptcha-response'); ?>
           </div>
           <?php } ?>
       </div>
   </div>
</div>

<div class="row">
    <div class="col-md-12 text-center">
        <div class="form-group">
            <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="mysubmit"><?php echo _l('clients_register_string'); ?></button>
        </div>
    </div>
</div>

<?php echo form_close(); ?>
<script src="<?php echo base_url("/assets/js/intlTelInput.js");?>"></script>
 <link rel="stylesheet" href="<?php echo base_url("/assets/css/intlTelInput.css");?>" />
              <script>
        $("#contact_phonenumber").intlTelInput({
          onlyCountries: ["al", "ad", "at", "by", "be", "ba", "bg", "hr", "cz", "dk", 
          "ee", "fo", "fi", "fr", "de", "gi", "gr", "va", "hu", "is", "ie", "it", "lv", 
          "li", "lt", "lu", "mk", "mt", "md", "mc", "me", "nl", "no", "pl", "pt", "ro", 
          "ru", "sm", "rs", "sk", "si", "es", "se", "ch", "ua", "gb"]
         });
  </script>
</div>
</div>
</div>


</div>




