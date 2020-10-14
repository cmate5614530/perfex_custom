<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<nav class="navbar navbar-default header" style="display:none;">
   <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#theme-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <?php get_company_logo('','navbar-brand logo'); ?>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="theme-navbar-collapse">
         <ul class="nav navbar-nav navbar-right">
            <?php hooks()->do_action('customers_navigation_start'); ?>
            <?php foreach($menu as $item_id => $item) { ?>
               <li class="customers-nav-item-<?php echo $item_id; ?>"
                  <?php echo _attributes_to_string(isset($item['li_attributes']) ? $item['li_attributes'] : []); ?>>
                  <a href="<?php echo $item['href']; ?>"
                     <?php echo _attributes_to_string(isset($item['href_attributes']) ? $item['href_attributes'] : []); ?>>
                     <?php
                     if(!empty($item['icon'])){
                        echo '<i class="'.$item['icon'].'"></i> ';
                     }
                     echo $item['name'];
                     ?>
                  </a>
               </li>
            <?php } ?>
            <?php hooks()->do_action('customers_navigation_end'); ?>
            <?php if(is_client_logged_in()) { ?>
               <li class="dropdown customers-nav-item-profile">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                     <img src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>" data-toggle="tooltip" data-title="<?php echo html_escape($contact->firstname . ' ' .$contact->lastname); ?>" data-placement="bottom" class="client-profile-image-small mright5">
                     <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu animated fadeIn">
                     <li class="customers-nav-item-edit-profile">
                        <a href="<?php echo site_url('clients/profile'); ?>">
                           <?php echo _l('clients_nav_profile'); ?>
                        </a>
                     </li>
                     <?php if($contact->is_primary == 1){ ?>
                        <?php if(can_loggged_in_user_manage_contacts()) { ?>
                           <li class="customers-nav-item-edit-profile">
                              <a href="<?php echo site_url('contacts'); ?>">
                                 <?php echo _l('clients_nav_contacts'); ?>
                              </a>
                           </li>
                        <?php } ?>
                        <li class="customers-nav-item-company-info">
                           <a href="<?php echo site_url('clients/company'); ?>">
                              <?php echo _l('client_company_info'); ?>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if(can_logged_in_contact_update_credit_card()){ ?>
                        <li class="customers-nav-item-stripe-card">
                           <a href="<?php echo site_url('clients/credit_card'); ?>">
                              <?php echo _l('credit_card'); ?>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (is_gdpr() && get_option('show_gdpr_in_customers_menu') == '1') { ?>
                        <li class="customers-nav-item-announcements">
                           <a href="<?php echo site_url('clients/gdpr'); ?>">
                              <?php echo _l('gdpr_short'); ?>
                           </a>
                        </li>
                     <?php } ?>
                     <li class="customers-nav-item-announcements">
                        <a href="<?php echo site_url('clients/announcements'); ?>">
                           <?php echo _l('announcements'); ?>
                           <?php if($total_undismissed_announcements != 0){ ?>
                              <span class="badge"><?php echo $total_undismissed_announcements; ?></span>
                           <?php } ?>
                        </a>
                     </li>
                     <?php if(!is_language_disabled()) {
                        ?>
                        <li class="dropdown-submenu pull-left customers-nav-item-languages">
                           <a href="#" tabindex="-1">
                              <?php echo _l('language'); ?>
                           </a>
                           <ul class="dropdown-menu dropdown-menu-left">
                              <li class="<?php if(get_contact_language() == ""){echo 'active';} ?>">
                                 <a href="<?php echo site_url('clients/change_language'); ?>">
                                    <?php echo _l('system_default_string'); ?>
                                 </a>
                              </li>
                              <?php foreach($this->app->get_available_languages() as $user_lang) { ?>
                                 <li <?php if(get_contact_language() == $user_lang){echo 'class="active"';} ?>>
                                    <a href="<?php echo site_url('clients/change_language/'.$user_lang); ?>">
                                       <?php echo ucfirst($user_lang); ?>
                                    </a>
                                 </li>
                              <?php } ?>
                           </ul>
                        </li>
                     <?php } ?>
                     <li class="customers-nav-item-logout">
                        <a href="<?php echo site_url('authentication/logout'); ?>">
                           <?php echo _l('clients_nav_logout'); ?>
                        </a>
                     </li>
                  </ul>
               </li>
            <?php } ?>
            <?php hooks()->do_action('customers_navigation_after_profile'); ?>
         </ul>
      </div>
      <!-- /.navbar-collapse -->
   </div>
   <!-- /.container-fluid -->
</nav>

<div id="myNav" class="overlay" >
  
   <div class="container">
   <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <header>
  <div class="container-fluid headerpart">
    <div class="container">
      <div class="headercommon">
        <div class="row">
          <div class="col-sm-7 col-xs-12 col-lg-7 col-md-7 topheadleft">
            <div class="headerlogo"> <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url("/assets/images/whitelogo.png");?>"></a> </div>
            
          </div>
         
          <div class="col-sm-5 col-xs-12 col-lg-5 col-md-5 topheadright">
          
            <div class="row">
             <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 dropdownmenu"> 
            <ul> <li>
                  <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> USD ($) </button>
                    <div class="dropdown-menu"> <a class="dropdown-item" href="#">
                      <p>USD ($)</p>
                      </a> <a class="dropdown-item" href="#">
                      <p>USD ($)</p>
                      </a> <a class="dropdown-item" href="#">
                      <p>USD ($)</p>
                      </a> </div>
                  </div>
                </li>
             </ul>
             
             </div>
              <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlinechatpartrighr">
                <div class="loginbtn"> <a href="#"><img src="<?php echo base_url("/assets/images/userwhite.png");?>">MY ACCOUNT</a> </div>
                <div class="row">
                  <div class="langagemenu">
                  <div class="onlineflagicon">
                      <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> <img src="<?php echo base_url("/assets/images/us-flag.png");?>"> </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png");?>"></a> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png");?>"></a> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png");?>"></a> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<div class="mcomm">
<div class="middle_section">

<div class="menu_left">
<div class="flast"> <a href="#"><img src="<?php echo base_url("/assets/images/footer-chat-user.png");?>"></a>
           <h2> Online Chat</h2>
              <p>Hello, I’m Sara.<br>
                How can I help you?</p>
                <div class="fbtns"><a href="#">Ask Question</a></div>
              
            </div>
</div>
<div class="menu_right">
<ul>
<li class="active"> <a href="#">Homepage </a></li>
<li> <a href="#">Explore Vehicles </a></li>
<li> <a href="#">About Us </a></li>
<li> <a href="#">FAQ </a></li>
<li> <a href="#">Rental Terms </a></li>
<li> <a href="#">Privacy Policy </a></li>
<li> <a href="#">Contact</a></li>
</ul>
</div>
</div>
<div class="mf_left ffirstcolcontet">
<div class="mileft">
<ul>
                  <li><a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                </ul>
                <p>Copyright ® 2019 All rights reserved. </p>
                </div>
                <div class="miright">
                <div class="mirifghtf1"> <ul>
                      <li><a href="#"><img src="<?php echo base_url("/assets/images/googlepay.png");?>"></a></li>
                      <li><a href="#"><img src="<?php echo base_url("/assets/images/appstore.png");?>"></a></li>
                    </ul></div>
                <div class="mrrightee"><div class="fmobile">
                    <div class="ffirsttitle">
                      <h1>Mobile App Download</h1>
                    </div>
                    <p>Download now to get more discounts through
                      your mobile app!</p>
                    
                  </div> </div>
                </div>
</div>

</div>
  </div>
</div>
<script>
function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}
</script>

<header class="d_header">
  <div class="container-fluid headerpart">
    <div class="container">
      <div class="headercommon">
        <div class="row">
          <div class="col-sm-7 col-xs-12 col-lg-7 col-md-7 topheadleft">
            <div class="headerlogo"> <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url("/assets/images/logo.png");?>"></a> </div>
            <div class="headermenu">
              <ul>
                <li><a href="#">Homepage</a></li>
                <li><a href="#">Explore Vehicles</a></li>
                <li><a href="#">Contact</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-5 col-xs-12 col-lg-5 col-md-5 topheadright">
            <div class="row">
              <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlinechatpart ">
                <div class="onleft">
                  <h2>Online Chat</h2>
                  <div class="onlinechatimg">
                    <ul>
                      <li> <a href="#"><img src="<?php echo base_url("/assets/images/green-icon.png");?>"></a></li>
                      <li>
                        <p>How can<br>
                          I help you?</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlinechatpartrighr drightt">
                <div class="loginbtn"> <a href="#"><img src="<?php echo base_url("/assets/images/user-icon.png");?>">LOGIN or REGISTER</a> </div>
                <div class="row">
                  <div class="langagemenu">
                  <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlineflagicon">
                      <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> <img src="<?php echo base_url("/assets/images/us-flag.png");?>"> </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png");?>"></a> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png");?>"></a> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png");?>"></a> </div>
                      </div>
                    </div>
                   
                    <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlinemenu">
                      <div class="menuicon"> <a href="javascript:void(0)" onclick="openNav()"><img src="<?php echo base_url("/assets/images/menuicon.jpg");?>"></a> </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

