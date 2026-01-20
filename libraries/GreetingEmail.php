<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GreetingEmail {

    public function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->library('Email');
        $this->module_name = 'minical-extension-boilerplate';

    }   

    function send_greeting_email()
    {
        $company_id = $this->ci->session->userdata('current_company_id');
        $company = $this->ci->Company_model->get_company($company_id);

        $content =  '<br/>I hope this email finds you.';
        $content1 = "<br/>".'Thank you for your business'.",<br/><br/>"
            .$company['name']."
                <br/>".$company['email']. "<br/>".$company['phone']."<br/>";


        $config['mailtype'] = 'html';

        $this->ci->email->initialize($config);

        $email_data = array (
            'company_name' => $company['name'],
            'company_email' => $company['email'],
            'content' => $content,
            'content1' => $content1
        );

        $customer_email = $company['email'];

        $from_email = 'donotreply@minical.io';

        $email_from = $company['email'];

        $this->ci->email->from($email_from, $company['name']);
        $this->ci->email->to($customer_email);
        $this->ci->email->reply_to($email_data['company_email']);

        $this->ci->email->subject('Hello From miniCal');

        $this->ci->email->message($this->ci->load->view('../extensions/'.$this->module_name.'/views/gretting-mail-html', $email_data, true));

        $this->ci->email->send();
        $msg = l('booking_list/Email successfully sent to ', true);
        return $msg.$customer_email;
    }

}
?>