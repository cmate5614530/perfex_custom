<?php get_template_part_dispo('home-header'); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<link rel="stylesheet" href="assets/css/daterangepicker.min.css">
<script src="assets/js/jquery.daterangepicker.min.js"></script>

</div>
</div>
<div class="container-fluid bannersection">
    <?php get_template_part_dispo('main-slider'); ?>
    <div class="container">
        <div class="hoc">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 home_left">

                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 home_right">

                    <div class="coomr">
                        <h1> Find Now </h1>
                        <div class="Search from">
                            <h2>Find your car </h2>
                            <div class="searchfommm">


                                <form method="post" action="">
                                    <img class="imagesbg" src="assets/images/bacground.png" alt=""  />

                                    <div class="fcom">
                                        <div class="locationboth"><input type="text" id="autocomplete" placeholder="Pick up"/> </div>
                                        <div class="locationboth1"><input type="text" id="autocomplete1" placeholder="Drop-off"/>  </div>
                                        <div class="dates"><input type="text" id="datepicker" placeholder="MM.DD.YY"> </div>
                                        <div class="psaasenger"> <input type="text" id="datepicker" placeholder="Number of passengers">  </div>
                                             <h3> Vehicle types </h3>
                                        <div class="checkbox">
  <div class="cboc"><input type="checkbox" name="points">  <label for="points"> <span>Taxi </span> </label>
<img src="assets/images/taxi.png">   </div>
                                       <div class="cboc"> <input type="checkbox" name="points">  <label for="points"> <span data-toggle="Bus">Bus  </span></label>   <img src="assets//images/bus.png"></div>
                                       <div class="cboc"> <input type="checkbox" name="points">  <label for="points"><span>Business Sedan </span> </label> <img src="assets/images/business.png">  </div>
                                       <div class="cboc"> <input type="checkbox" name="points">  <label for="points"> <span>Luxury Sedan </span> </label> <img src="assets/images/Luxury.png">   </div>
                                       <div class="cboc"> <input type="checkbox" name="points">  <label for="points"><span>Economy Minivan </span> </label>  <img src="assets/images/Economy.png"> </div>
                                       <div class="cboc"> <input type="checkbox" name="points">  <label for="points"> <span>Minibus  </span></label> <img src="assets/images/minibus.png">  </div>
                                       <div class="cboc"> <input type="checkbox" name="points">  <label for="points"> <span>Economy Sedan </span> </label>  <img src="assets/images/suv.png"> </div>
                                       <div class="cboc"> <input type="checkbox" name="points">  <label for="points"> <span>Business Minivan </span></label>  <img src="assets/images/business.png">  </div>
                                       </div>
                                        <div class="submit"><input type="submit" value="search" />  </div>
                                   
                                    </div>

                                </form>
                                <script>
                                    var input = document.getElementById('autocomplete');
                                    var autocomplete = new google.maps.places.Autocomplete(input);
                                </script>
                                <script>
                                    var input = document.getElementById('autocomplete1');
                                    var autocomplete = new google.maps.places.Autocomplete(input);
                                </script>
                            </div>
                        </div>
                        <div class="brbottom">
                            <div class="beleft">
                                <p>Start From </p>
                                <div class="price"><sup>$ </sup><strong>64</strong><sup style="left: -10px;">,99 </sup>  <span>/Daily </span> </div>
                            </div>
                            <div class="brrlrught">
                                <h2> Volvo XC90 Excellence </h2>
                                <ul>
                                    <li> <img src="assets/images/desel.png" alt=""> Diesel </li>
                                    <li> <img src="assets/images/automatci.png" alt=""> Automatic </li>
                                </ul>

                            </div>
                            <div class="brrright">

                                <img src="assets/images/mottor.png" alt="">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    $('#datepicker').dateRangePicker({
        time: {
            enabled: true
        },
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'), 10)
    });


</script>
