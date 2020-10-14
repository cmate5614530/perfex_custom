<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>rent GO!</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url("/assets/css/style.css") ?>" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php $this->load->view('includes/mobile-menu.php');?>
<header class="d_header">
  <div class="container-fluid headerpart">
    <div class="container">
      <div class="headercommon">
        <div class="row">
          <div class="col-sm-7 col-xs-12 col-lg-7 col-md-7 topheadleft">
            <div class="headerlogo"> <a href="<?php echo site_url();?>"><img src="<?php echo base_url("/assets/images/logo.png") ?>" /></a> </div>
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
                      <li> <a href="#"><img src="<?php echo base_url("/assets/images/green-icon.png")?>" /></a></li>
                      <li>
                        <p>How can<br />
                          I help you?</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlinechatpartrighr drightt">
                <div class="loginbtn"> <a href="#"><img src="<?php echo base_url("/assets/images/user-icon.png")?>" />LOGIN or REGISTER</a> </div>
                <div class="row">
                  <div class="langagemenu">
                    <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlineflagicon">
                      <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> <img src="<?php echo base_url("/assets/images/us-flag.png")?>" /> </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png")?>" /></a> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png")?>" /></a> <a class="dropdown-item" href="#"><img src="<?php echo base_url("/assets/images/us-flag.png")?>" /></a> </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlinemenu">
                      <div class="menuicon"> <a href="javascript:void(0)" onclick="openNav()"><img src="<?php echo base_url("/assets/images/menuicon.jpg")?>" /></a> </div>
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
</body>
</html>