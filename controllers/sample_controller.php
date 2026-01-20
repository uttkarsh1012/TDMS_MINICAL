<?php
class Sample_controller extends MY_Controller
{
    public $module_name;
	function __construct()
	{
        parent::__construct();
        $this->module_name = $this->router->fetch_module();
    
	}

    function index()
    {
        $data['menu_on'] = TRUE;
        $files = get_asstes_files($this->module_assets_files, $this->module_name, $this->controller_name, $this->function_name);

        $data['main_content'] = '../extensions/'.$this->module_name.'/views/sample_view';
        $this->template->load('bootstrapped_template', null , $data['main_content'], $data);
    }

    function show_info_page()
    {
        $data['menu_on'] = TRUE;
        $files = get_asstes_files($this->module_assets_files, $this->module_name, $this->controller_name, $this->function_name);

        $data['main_content'] = '../extensions/'.$this->module_name.'/views/info_page';
        $this->template->load('bootstrapped_template', null , $data['main_content'], $data);
    }

}