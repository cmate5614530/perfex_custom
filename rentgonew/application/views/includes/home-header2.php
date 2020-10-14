<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>rent GO!</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRELIrSWwZTXpwxUZESejkVWtQjtaQTWE&libraries=places"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body class="overfloehidden">
<?php include 'include/mobile-menu.php' ?>
<header class="Secondheader">
  <div class="container-fluid headerpart">
    <div class="container">
      <div class="headercommons">
        <div class="row">
          <div class="col-sm-3 col-xs-12 col-lg-3 col-md-3 leftmenu">
     <a href="javascript:void(0)" onclick="openNav()">  <img src="images/whitemenu.png" alt="">  <span> MENU </span> </a>
            
          </div>
          <div class="col-sm-6 col-xs-12 col-lg-6 col-md-6 logos"><div class="headerlogo"> <a href="#"><img src="images/whitelogo.png" /></a> </div> </div>
         <div class="col-sm-3 col-xs-12 col-lg-3 col-md-6 onlinechatpartrighr">
                <div class="loginbtn"> <a href="#"><img src="images/userwhite.png" />LOGIN or REGISTER</a> </div> 
                <div class="dropdown whitecolor">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> <img src="images/us-flag.png" /> </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#"><img src="images/us-flag.png" /></a> <a class="dropdown-item" href="#"><img src="images/us-flag.png" /></a> <a class="dropdown-item" href="#"><img src="images/us-flag.png" /></a> </div>
                      </div>
                
              </div>
        </div>
      </div>
    </div>
  </div>
</header>
