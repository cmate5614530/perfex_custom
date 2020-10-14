<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

    public function __construct()
    {
            parent::__construct();
            // Your own constructor code
    }
    public function index()
    {
        die('welcome to home page');
    }
    public function vehiclesearchlist_fullpage_details_v2()
    {
        $this->load->view('layout' , array('template' => 'pages/vehiclesearchlist-fullpage-details-v2'));
    }
	public function vehiclesearchlist_extraoption()
{
        $this->load->view('layout' , array('template' => 'pages/vehiclesearchlist-extraoption'));
}
public function vehicle_rent_personal_information_pay()
{
        $this->load->view('layout' , array('template' => 'pages/vehicle-rent-personal-information-pay'));
}
public function vehicle_pay_result()
{
        $this->load->view('layout' , array('template' => 'pages/vehicle-pay-result'));
}
public function explore_vehiclelist()
{
        $this->load->view('layout' , array('template' => 'pages/explore-vehiclelist'));
}
public function explore_vehiclelist_v2()
{
        $this->load->view('layout' , array('template' => 'pages/explore-vehiclelist-v2'));
}
public function login_forgot_password()
{
        $this->load->view('layout' , array('template' => 'pages/login-forgot-password'));
}
public function user_profile_personal_information()
{
        $this->load->view('layout' , array('template' => 'pages/user-profile-personal-information'));
}

public function user_profile_my_reservation()
{
        $this->load->view('layout' , array('template' => 'pages/user-profile-my-reservation'));
}

public function user_profile_my_messages()
{
        $this->load->view('layout' , array('template' => 'pages/user-profile-my-messages'));
}
public function aboutus()
{
        $this->load->view('layout' , array('template' => 'pages/aboutus'));
}

public function faq()
{
        $this->load->view('layout' , array('template' => 'pages/faq'));
}

public function privacypolicy()
{
        $this->load->view('layout' , array('template' => 'pages/privacypolicy'));
}

public function contact()
{
        $this->load->view('layout' , array('template' => 'pages/contact'));
}
}


