<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="pusher"></div>
<footer class="navbar-fixed-bottom footer" style="display:none;">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<span class="copyright-footer"><?php echo date('Y'); ?> <?php echo _l('clients_copyright', get_option('companyname')); ?></span>
				<?php if(is_gdpr() && get_option('gdpr_show_terms_and_conditions_in_footer') == '1') { ?>
					- <a href="<?php echo terms_url(); ?>" class="terms-and-conditions-footer"><?php echo _l('terms_and_conditions'); ?></a>
				<?php } ?>
				<?php if(is_gdpr() && is_client_logged_in() && get_option('show_gdpr_link_in_footer') == '1') { ?>
					- <a href="<?php echo site_url('clients/gdpr'); ?>" class="gdpr-footer"><?php echo _l('gdpr_short'); ?></a>
				<?php } ?>
			</div>
		</div>
	</div>
</footer>

<footer>

  <div class="container-fluid footers">
    <div class="container">
      <div class="footerpart">
        <div class="row">
          <div class="col-sm-3 col-xs-12 col-lg-3 col-md-3 footerfirst">
            <div class="ffirst"> <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url("/assets/images/logo.png");?>"></a>
              <div class="ffirstcolcontet">
                <p>RentGO! Car Rental Template</p>
                <h2>Enjoy car rental!</h2>
                <ul>
                  <li><a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 footersecond">
            <div class="ffirst fsec">
              <div class="row">
                <div class="col-sm-3 col-xs-12 col-lg-3 col-md-3 footerthird">
                  <div class="fpages">
                    <div class="ffirsttitle">
                      <h1>Pages</h1>
                    </div>
                    <ul>
                      <li><a href="#">About Us</a></li>
                      <li><a href="#">Explore Vehicles</a></li>
                      <li><a href="#">Blog</a></li>
                      <li><a href="#">Contact</a></li>
                    </ul>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-12 col-lg-3 col-md-3 footerthird">
                  <div class="finfo">
                    <div class="ffirsttitle">
                      <h1>Information</h1>
                    </div>
                    <ul>
                      <li><a href="#">FAQ</a></li>
                      <li><a href="#">Rental Terms</a></li>
                      <li><a href="#">Privacy Policy</a></li>
                    </ul>
                  </div>
                </div>
                <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 footerthird">
                  <div class="fmobile">
                    <div class="ffirsttitle">
                      <h1>Mobile App Download</h1>
                    </div>
                    <p>Download now to get more discounts through
                      your mobile app!</p>
                    <ul>
                      <li><a href="#"><img src="<?php echo base_url("/assets/images/googleplay.jpg");?>"></a></li>
                      <li><a href="#"><img src="<?php echo base_url("/assets/images/appstore.jpg");?>"></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3 col-xs-12 col-lg-3 col-md-3 footerlast" style="background: url('<?php echo base_url("/assets/images/footer-chat.png");?>');">
            <div class="flast"> <a href="#"><img src="<?php echo base_url("/assets/images/footer-chat-user.png");?>"></a>
              <h2>Online Chat</h2>
              <p>Hello, I’m Sara.<br>
                How can I help you?</p>
              <div class="fbtns"><a href="#">Ask Question</a></div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <div class="container-fluid footerscopy">
    <div class="container">
  <div class="footercopyright">
      <div class="row">
      <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 copyrightleft">
      <div class="fcopyleft">
      <p>Copyright ® <?php echo date('Y'); ?> All rights reserved. </p>
      </div>
      </div>
      <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 copyrightright">
      <div class="fcopyright">
      <p>Made by<span><img src="<?php echo base_url("/assets/images/footer-name.png");?>"></span></p>
      </div>
      </div>
      </div>
      </div>
      </div></div>
</footer>
