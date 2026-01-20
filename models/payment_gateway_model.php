<?php

class Payment_gateway_model extends CI_Model {

	function __construct()
    {
        parent::__construct();
    }

	function update_payment_gateway_settings($data)
    {	
        $getwayData = $this->db->get_where('company_payment_gateway', array('company_id =' => $this->company_id))->result_array();
        
        if(empty($getwayData))
        {
            $this->db->insert('company_payment_gateway',$data);
        }
        else
        {
            $this->db->where('company_id', $this->company_id);
            $this->db->update('company_payment_gateway', $data);
        }
    }
	
	function get_payment_gateway_settings($company_id)
    {
        $result = null;
        
    	$this->db->select('c.*, cpg.selected_payment_gateway, cpg.tdms_merchant_id, cpg.tdms_api_passcode, cpg.tdms_profile_passcode, cpg.tdms_test_mode, cpg.tdms_enable_tokenization, cpg.store_cc_in_booking_engine');
    	$this->db->where('c.company_id', $company_id);
    	$this->db->from('company as c');
        $this->db->join('company_payment_gateway as cpg', 'cpg.company_id = c.company_id', 'left');
        
        $query = $this->db->get();
        $result = $query->result_array();

        if ($this->db->_error_message()) {
			show_error($this->db->_error_message());
		}

        if ($query->num_rows >= 1) {
            $result = $result[0];
		}

        return $result;
    }
}