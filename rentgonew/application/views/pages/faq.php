<div class="container-fluid page-title">
  <div class="container">
    <h1>FAQ</h1>
  </div>
</div>
<div class="container-fluid pagebreadcrumb">
  <div class="container">
    <div class="page-bradcrumb-common">
      <ul>
        <li><a href="#">Homepage</a></li>
        <li class="active"><a href="#">FAQ</a></li>
      </ul>
    </div>
  </div>
</div>
<script>
    $(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
        	$(this).prev(".card-header").find(".fa").addClass("fa-angle-up").removeClass("fa-angle-down");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-angle-down").addClass("fa-angle-up");
        }).on('hide.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-angle-up").addClass("fa-angle-down");
        });
    });
	 
</script>
<script>
$(document).ready(function() {

    $('.collapse').on('shown.bs.collapse', function () {
        $(this).prev().addClass('active-accodian');
    });

    $('.collapse').on('hidden.bs.collapse', function () {
        $(this).prev().removeClass('active-accodian');
    });

});
</script>
<div class="container-fluid aboutuspage-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-9 col-lg-9 col-xs-12 aboutuspage-left">
        <div class="aboutus-left-common faqpage">
          <div class="accordion myfaq" id="accordionExample">
      <div class="card">
        <div class="card-header" id="headingOne">
          <h2>
            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne">Exercitation ullamco laboris nisi ut aliquip ex?<i class="fa fa-angle-down"></i></button>
          </h2>
        </div>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
          <div class="card-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia.</p>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingTwo">
          <h2>
            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"> Sed ut perspiciatis unde omnis iste natus error sit voluptatem?<i class="fa fa-angle-down"></i></button>
          </h2>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
          <div class="card-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia.</p>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingThree">
          <h2>
            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia?<i class="fa fa-angle-down"></i></button>
          </h2>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
          <div class="card-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia.</p>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingFour">
          <h2>
            <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour">Nemo enim ipsam voluptatem quia voluptas?<i class="fa fa-angle-down"></i></button>
          </h2>
        </div>
        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
          <div class="card-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia.</p>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
          </div>
        </div>
      </div>
      
    </div>
          
          
          
        </div>
      </div>
      <div class="col-sm-12 col-md-3 col-lg-3 col-xs-12 aboutuspage-right">
        <div class="aboutus-right-common">
          <div class="page-rightmenu">
            <ul>
              <li>
                <h3>Pages</h3>
              </li>
              <li><a href="#">About Us</a></li>
              <li class="active"><a href="#">FAQ</a></li>
              <li><a href="#">Rental Terms</a></li>
               <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="scrollto-up"><a href="#"><img src="<?php echo base_url("/assets/images/up-arrow.png");?>" alt="" /></a></div>
</div>
<div class="blankspace faqpageblank">
</div>
<style>
#accordionExample .card {
	border: none;
	border-bottom: 1px solid #e3e3e3;
}
#accordionExample .card-header {
	background: #fff;
	padding: 25px 0px;
}
#accordionExample .card-header button {
	padding-left: 0px;
	width: 100%;
	text-align: left;
	color: #5a5a5a;
	font-size: 24px;
}
#accordionExample .btn.collapsed {
	color: #5a5a5a;
}
#accordionExample .card-header i {
	position: absolute;
	right: 0;
	font-size: 36px;
	color: #5a5a5a;
	padding-right: 30px;
}
#accordionExample .active-accodian button {
	color: #1e81f6;
	font-family: 'Roboto';
}
#accordionExample .active-accodian button i {
	color: #1e81f6;
}
#accordionExample .card-header button:hover,#accordionExample .card-header button:focus {
	text-decoration: none;
}
#accordionExample .card-body {
	padding-left: 0px;
	text-align: justify;
	padding-top: 30px;
	border-bottom: 2px solid #1e81f6;
	padding-right: 0;
}
#accordionExample .card-body p {
	color: #5a5a5a;
	font-size: 16px;
	line-height: 34px;
}
#accordionExample .card-header.active-accodian {
	border-bottom: none !important;
}
.aboutus-left-common.faqpage {
	padding-left: 55px;
}
.blankspace.faqpageblank {
	margin-top: 88px;
}
</style>

