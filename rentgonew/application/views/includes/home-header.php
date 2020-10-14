<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>rent GO!</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else
$link = "http";

// Here append the common URL characters.
$link .= "://";
$link .= $_SERVER['SERVER_NAME']."/demo/html/rentgo/";


?>
<link rel="stylesheet" href="<?php echo $link;?>/include/css/style.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRELIrSWwZTXpwxUZESejkVWtQjtaQTWE&libraries=places"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
<?php include 'include/mobile-menu.php' ?>
<header class="transparent_header">
  <div class="container-fluid headerpart">
    <div class="container">
      <div class="headercommon">
        <div class="row">
          <div class="col-sm-7 col-xs-6 col-lg-7 col-md-7 topheadleft">
            <div class="headerlogo"> <a href="#"><img src="images/whitelogo.png" /></a> </div>
            <div class="headermenu">
              <ul>
                <li class="active"><a href="#">Homepage</a></li>
                <li><a href="#">Explore Vehicles</a></li>
                <li><a href="#">Contact</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-5 col-xs-6 col-lg-5 col-md-5 topheadright">
            <div class="row">
              <div class="col-sm-4 col-xs-12 col-lg-6 col-md-6 onlinechatpart">
                <div class="onleft">
                  <h2>Online Chat</h2>
                  <div class="onlinechatimg">
                    <ul>
                      <li> <a href="#"><img src="images/green-icon.png" /></a></li>
                      <li>
                        <p>How can<br />
                          I help you?</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-sm-8 col-xs-12 col-lg-6 col-md-6 onlinechatpartrighr">
                <div class="loginbtn"> <a href="#"><img class="desktop" src="images/user-icon.png" /> <img class="mobile" src="images/userwhite.png">LOGIN or REGISTER</a> </div>
                <div class="row">
                  <div class="langagemenu">
                    <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlineflagicon">
                      <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> <img src="images/us-flag.png" /> </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#"><img src="images/us-flag.png" /></a> <a class="dropdown-item" href="#"><img src="images/us-flag.png" /></a> <a class="dropdown-item" href="#"><img src="images/us-flag.png" /></a> </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 onlinemenu">
                      <div class="menuicon"> <a href="javascript:void(0)" onclick="openNav()"><img class="desktop" src="images/menuicons.png" /> <img  class="mobile"src="images/whitemenu.png" alt=""></a> </div>
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