<?php

class Option_data extends MY_Controller
{
    public $module_name;
    function __construct()
    {
        parent::__construct();
        $this->module_name = $this->router->fetch_module();
  
    }


    /**
    * This function is an example of how to get option custom data (OPTION)
    */
    function get_option_data(){

        $data['menu_on'] = TRUE;
        $option = array("company_id" => $this->company_id);
        $files = get_asstes_files($this->module_assets_files, $this->module_name, $this->controller_name, $this->function_name);
        $option_data = get_options();
        if(isset($option_data) && $option_data != null){
            $data['options'] = $option_data;
        }
        
        $data['main_content'] = '../extensions/'.$this->module_name.'/views/option_data';
        $this->template->load('bootstrapped_template', null, $data['main_content'], $data); 
    }

    
    /**
     * This function is an example of how to add custom data (Options)
     */
    function add_option_data()
    {
        if(isset($_POST['option_name']) && $_POST['option_name'] !== ''){
            
            $autoload = $_POST['autoload'];
                    
            /**
            * This method add option data
            * it takes 3 arg in parameter
            * 
            */
            $data = add_option($_POST['option_name'], $_POST['option_value'],  $autoload);

            if(isset($data) && $data != null){
                echo json_encode(array('success'=> true));
            }else{
                echo json_encode(array('success'=> false,'error'=> 'Somthing went wrong.' ));
            }

        }else{
            echo json_encode(array('success'=> false,'error'=> 'Please enter require data.' ));
        }    
                 
    }


    /**
     * This function is an example of how to get data of a options to update custom data (OPTION)
     */
    function edit_option_data(){
       
        $option = $_POST['option'];
        $option_data = get_option($option);
      
        if(isset($option_data) && $option_data != null){
         echo json_encode(array('success'=> true, "option"=> $option_data));

        }  else{
        echo json_encode(array('success'=> false, "error"=> "Try again!"));

        } 
    }
    

    
    /**
     * This function is an example of how to update custom data (OPTION)
     */
    function update_option_data()
    {
        if(isset($_POST['option_name']) && $_POST['option_name'] !== ''){
            update_option($_POST['option_name'], $_POST['option_value'], $_POST['autoload']);
            
            echo json_encode(array('success'=> true));
        }else{
            echo json_encode(array('success'=> false,'error'=> 'Try Again.' ));
        }    
        
    }


    /**
     * This function is an example of how to delete custom data (OPTION)
     */
    function delete_option_data()
    {
        if(isset($_POST['option_name']) && $_POST['option_name'] != null){
               $result = delete_option($_POST['option_name']);
            if(isset($result)){
                echo json_encode(array('success'=> true, "msg"=> 'Data is delete!'));
            }
        }else{
            echo json_encode(array('success'=> true, "error"=> 'Try again!'));
        }
    }
}