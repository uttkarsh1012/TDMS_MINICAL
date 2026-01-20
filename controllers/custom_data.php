<?php

class Custom_data extends MY_Controller
{
    public $module_name;
    function __construct()
    {
        parent::__construct();
        $this->module_name = $this->router->fetch_module();
  
    }


    /**
    * This function is an example of how to get post custom data (POST)
    */
    function get_post_data(){
        $data['menu_on'] = TRUE;
        $post = array('user_id' => $this->user_id, "company_id" => $this->company_id);
        $files = get_asstes_files($this->module_assets_files, $this->module_name, $this->controller_name, $this->function_name);
        
        $post_data = get_post($post);
        if(isset($post_data) && $post_data != null){
            $data['posts'] = $post_data;
        }
        $data['main_content'] = '../extensions/'.$this->module_name.'/views/post_custom_data';
        $this->template->load('bootstrapped_template', null, $data['main_content'], $data); 
    }

    
    /**
     * This function is an example of how to add custom data (POST)
     */
    function add_custom_data()
    {
        if(!empty($_POST['meta']) && $_POST['meta']){
            $meta = $_POST['meta'];
        }
        if(isset($_POST['post_title']) && $_POST['post_title'] !== '' && $_POST['post_type'] !== '' ){
            $custom_data = array(
                'company_id' => $this->company_id, // require
                'user_id' => $this->user_id, // require
                'post_title' => $_POST['post_title'], // require
                'post_type' => $_POST['post_type'] ?? '', // require
                'post_content' => $_POST['post_content'] ?? '',
                'post_mime_type' => $_POST['post_mime_type'] ?? '',
                'post_date' => date('Y-m-d H:i:s'),

                /**
                 * If you want to add post-meta data with post data then
                 * just pass array of postmeta data on meta key 
                 * No need to call an additional method for 
                 * metadata if the above array contains 
                 * a meta key then metadata will automatically be added
                 */
                'meta' =>  $meta ?? ''
            );

            /**
             * This method add post data
             */
            add_post($custom_data);
            
            echo json_encode(array('success'=> true));
        }else{
            echo json_encode(array('success'=> false,'error'=> 'Please enter require data.' ));
        }    
        
    }


    /**
     * This function is an example of how to get data of a post to update custom data (POST)
     */
    function edit_custom_data($post_id){
       
        $post = array("post_id" => $post_id);
        $post_data = get_post($post);
        if(isset($post_data) && $post_data != null){
         echo json_encode(array('success'=> true, "post"=> $post_data));

        }  else{
        echo json_encode(array('success'=> false, "error"=> "Try again!"));

        } 
    }
    

    
    /**
     * This function is an example of how to update custom data (POST)
     */
    function update_custom_data()
    {
        if(isset($_POST['post_id']) && $_POST['post_id'] !== ''){
            $custom_data = array(
                'post_id' => $_POST['post_id'],
                'company_id' => $this->company_id, // require
                'user_id' => $this->user_id, // require
                'post_title' => $_POST['post_title'], // require
                'post_type' => $_POST['post_type'] ?? '', // require
                'post_content' => $_POST['post_content'] ?? '',
                'post_mime_type' => $_POST['post_mime_type'] ?? '',
                'post_modified' => date('Y-m-d H:i:s'),
            );

            /**
             * This method add post data
             */
            edit_post($custom_data);
            
            echo json_encode(array('success'=> true));
        }else{
            echo json_encode(array('success'=> false,'error'=> 'Try Again.' ));
        }    
        
    }


    /**
     * This function is an example of how to delete custom data (POST)
     */
    function delete_custom_data($post_id)
    {
        if(isset($post_id) && $post_id != null){

           $force_delete = false;
           $result = delete_post($post_id, $force_delete);
           if(isset($result)){
            echo json_encode(array('success'=> true, "msg"=> 'Data is delete!'));
           }
        }else{
            echo json_encode(array('success'=> true, "error"=> 'Try again!'));
        }
    }
}