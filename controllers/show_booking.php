<?php
class Show_booking extends MY_Controller
{
    public $module_name;
	function __construct()
	{
        parent::__construct();

        $this->module_name = $this->router->fetch_module();
        $this->load->model('../extensions/'.$this->module_name.'/models/Bookings_model');
        $this->load->library('../extensions/'.$this->module_name.'/libraries/GreetingEmail');
     
	}
	
    function show_latest_bookings(){

        $data['bookings'] = $this->Bookings_model->get_bookings();
        $files = get_asstes_files($this->module_assets_files, $this->module_name, $this->controller_name, $this->function_name);

        $data['menu_on'] = TRUE;
        $data['selected_menu'] = 'bookings';
        $data['main_content'] = '../extensions/'.$this->module_name.'/views/booking';

        // library function call
        // $this->greetingemail->send_greeting_email();

        // action call
        // do_action('action name', variable);
        // do_action('add.booking.created', $data);  // this is a core action
        // do_action('my-custom-action', $data);   //this is a custom action 

        // filter call
        // apply_filter('filter name', variable);
        // apply_filter('my-custom-action', $data);

        // helpers function call
        // check if funtion exists or not
        // if(function_exists('custom_helper')){
        //   custom_helper($data);
        // }

        $this->template->load('bootstrapped_template', null , $data['main_content'], $data);
    }


    function show_customer_list()
    {
        $data['customers'] = $this->Bookings_model->get_customer_list();
        $files = get_asstes_files($this->module_assets_files, $this->module_name, $this->controller_name, $this->function_name);
        $data['menu_on'] = TRUE;
        $data['main_content'] = '../extensions/'.$this->module_name.'/views/customer_list';
        $this->template->load('bootstrapped_template', null , $data['main_content'], $data);
    }
   
 
  
}