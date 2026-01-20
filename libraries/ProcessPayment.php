<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * TDMS Payment Gateway Library
 * Manages TD Merchant Solutions (Bambora/Worldline) payment operations
 * @property  Currency_model Currency_model
 */
class ProcessPayment
{

    const DEFAULT_CURRENCY = 'CAD';
    const TDMS_API_BASE_URL = 'https://api.na.bambora.com';
    const TDMS_API_VERSION = 'v1';

    /**
     * @var CI_Controller
     */
    private $ci;

    private $selected_gateway;

    /**
     * @var array Company gateway settings
     */
    private $company_gateway_settings;

    /**
     * @var array Customer
     */
    private $customer;

    /**
     * @var string Error message
     */
    private $error_message;

    /**
     * @var string
     */
    private $currency = self::DEFAULT_CURRENCY;

    /**
     * @var string External Id, can only be one per gateway
     */
    private $customer_external_entity_id;

    public function __construct($params = null)
    {
        $this->ci = &get_instance();
        $this->ci->load->model('Payment_gateway_model');
        $this->ci->load->model('Customer_model');
        $this->ci->load->library('session');
        $this->ci->load->model("Card_model");
        $this->ci->load->library('encrypt');
        $this->ci->load->model('Booking_model');

        $company_id = $this->ci->session->userdata('current_company_id');

        if (isset($params['company_id'])) {
            $company_id = $params['company_id'];
        }

        if(!$company_id){
            $company_id = $this->ci->session->userdata('anonymous_company_id');
        }

        $gateway_settings = $this->ci->Payment_gateway_model->get_payment_gateway_settings($company_id);

        if ($gateway_settings) {
            $this->setCompanyGatewaySettings($gateway_settings);
            $this->setSelectedGateway($this->company_gateway_settings['selected_payment_gateway']);
            $this->populateGatewaySettings();
            $this->setCurrency();
        }
    }

    private function populateGatewaySettings()
    {
        $this->tdms_merchant_id = $this->company_gateway_settings['tdms_merchant_id'];
        $this->tdms_api_passcode = $this->company_gateway_settings['tdms_api_passcode'];
        $this->tdms_profile_passcode = $this->company_gateway_settings['tdms_profile_passcode'];
        $this->tdms_test_mode = isset($this->company_gateway_settings['tdms_test_mode']) ? $this->company_gateway_settings['tdms_test_mode'] : 0;
        $this->tdms_enable_tokenization = isset($this->company_gateway_settings['tdms_enable_tokenization']) ? $this->company_gateway_settings['tdms_enable_tokenization'] : 0;
    }

    private function setCurrency()
    {
        $this->ci->load->model('Currency_model');
        $currency = $this->ci->Currency_model->get_default_currency($this->company_gateway_settings['company_id']);
        $this->currency = strtoupper($currency['currency_code']);
    }

    public static function getGatewayNames()
    {
        return array(
            'tdms' => 'TDMS (TD Merchant Solutions)',
        );
    }

    /**
     * @return string
     */
    public function getSelectedGateway()
    {
        return $this->selected_gateway;
    }

    /**
     * @param string $selected_gateway
     */
    public function setSelectedGateway($selected_gateway)
    {
        $this->selected_gateway = $selected_gateway;
    }

    /**
     * Test TDMS API connection
     */
    public function testConnection($merchant_id, $api_passcode)
    {
        try {
            $auth_header = base64_encode($merchant_id . ':' . $api_passcode);
            
            $headers = array(
                'Authorization: Passcode ' . $auth_header,
                'Content-Type: application/json'
            );

            // Test with a simple profile lookup that should return 404 but validates credentials
            $url = self::TDMS_API_BASE_URL . '/' . self::TDMS_API_VERSION . '/profiles/test_connection_check';
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // 404 is expected for test connection, 401 means bad credentials
            if ($http_code == 404 || $http_code == 200) {
                return array('success' => true, 'message' => 'Connection successful');
            } elseif ($http_code == 401) {
                return array('success' => false, 'message' => 'Invalid credentials');
            } else {
                return array('success' => false, 'message' => 'Connection failed: HTTP ' . $http_code);
            }

        } catch (Exception $e) {
            return array('success' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Make TDMS API Request
     */
    private function makeTDMSRequest($endpoint, $method = 'POST', $data = null, $use_profile_passcode = false)
    {
        $merchant_id = $this->tdms_merchant_id;
        $passcode = $use_profile_passcode ? $this->tdms_profile_passcode : $this->tdms_api_passcode;
        
        $auth_header = base64_encode($merchant_id . ':' . $passcode);
        
        $headers = array(
            'Authorization: Passcode ' . $auth_header,
            'Content-Type: application/json'
        );

        $url = self::TDMS_API_BASE_URL . '/' . self::TDMS_API_VERSION . $endpoint;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            return array('success' => false, 'message' => 'CURL Error: ' . $curl_error);
        }

        $response_data = json_decode($response, true);
        
        if ($http_code >= 200 && $http_code < 300) {
            return array('success' => true, 'data' => $response_data, 'http_code' => $http_code);
        } else {
            $error_message = isset($response_data['message']) ? $response_data['message'] : 'API Error: HTTP ' . $http_code;
            return array('success' => false, 'message' => $error_message, 'http_code' => $http_code, 'data' => $response_data);
        }
    }

    /**
     * Create Payment Profile
     */
    public function createPaymentProfile($card_data)
    {
        if (!$this->tdms_enable_tokenization) {
            return array('success' => false, 'message' => 'Tokenization is not enabled');
        }

        $profile_data = array(
            'card' => array(
                'name' => $card_data['name'],
                'number' => $card_data['number'],
                'expiry_month' => $card_data['expiry_month'],
                'expiry_year' => $card_data['expiry_year'],
                'cvd' => $card_data['cvd']
            )
        );

        if (isset($card_data['billing'])) {
            $profile_data['billing'] = $card_data['billing'];
        }

        $result = $this->makeTDMSRequest('/profiles', 'POST', $profile_data, true);
        
        return $result;
    }

    /**
     * Get Payment Profile
     */
    public function getPaymentProfile($profile_id)
    {
        $result = $this->makeTDMSRequest('/profiles/' . $profile_id, 'GET', null, true);
        return $result;
    }

    /**
     * Update Payment Profile
     */
    public function updatePaymentProfile($profile_id, $card_data)
    {
        $profile_data = array(
            'card' => array(
                'name' => $card_data['name'],
                'number' => $card_data['number'],
                'expiry_month' => $card_data['expiry_month'],
                'expiry_year' => $card_data['expiry_year']
            )
        );

        $result = $this->makeTDMSRequest('/profiles/' . $profile_id, 'PUT', $profile_data, true);
        return $result;
    }

    /**
     * Delete Payment Profile
     */
    public function deletePaymentProfile($profile_id)
    {
        $result = $this->makeTDMSRequest('/profiles/' . $profile_id, 'DELETE', null, true);
        return $result;
    }

    /**
     * Create Payment/Charge
     */
    public function createPayment($amount, $payment_method, $order_number = null, $customer_data = null)
    {
        $payment_data = array(
            'order_number' => $order_number ? $order_number : 'ORDER-' . time(),
            'amount' => $amount,
            'payment_method' => $payment_method
        );

        if ($customer_data) {
            $payment_data['customer_ip'] = isset($customer_data['ip']) ? $customer_data['ip'] : $_SERVER['REMOTE_ADDR'];
            
            if (isset($customer_data['billing'])) {
                $payment_data['billing'] = $customer_data['billing'];
            }
        }

        $result = $this->makeTDMSRequest('/payments', 'POST', $payment_data);
        
        return $result;
    }

    /**
     * Get Payment
     */
    public function getPayment($transaction_id)
    {
        $result = $this->makeTDMSRequest('/payments/' . $transaction_id, 'GET');
        return $result;
    }

    /**
     * Create Refund
     */
    public function createRefund($transaction_id, $amount)
    {
        $refund_data = array(
            'amount' => $amount
        );

        $result = $this->makeTDMSRequest('/payments/' . $transaction_id . '/returns', 'POST', $refund_data);
        
        return $result;
    }

    /**
     * Void Transaction
     */
    public function voidTransaction($transaction_id, $amount)
    {
        $void_data = array(
            'amount' => $amount
        );

        $result = $this->makeTDMSRequest('/payments/' . $transaction_id . '/void', 'POST', $void_data);
        
        return $result;
    }

    /**
     * Capture Pre-Authorization
     */
    public function capturePayment($transaction_id, $amount = null)
    {
        $capture_data = array();
        
        if ($amount !== null) {
            $capture_data['amount'] = $amount;
        }

        $result = $this->makeTDMSRequest('/payments/' . $transaction_id . '/completions', 'POST', $capture_data);
        
        return $result;
    }

    /**
     * Create booking charge
     */
    public function createBookingCharge($booking_id, $amount, $customer_id = null, $cvc = null, $is_capture = true)
    {
        $charge_id = null;

        if ($this->isGatewayPaymentAvailableForBooking($booking_id, $customer_id)) {
            try {
                $this->ci->load->model('Booking_model');
                $this->ci->load->model('Customer_model');
                $this->ci->load->model('Card_model');

                $booking = $this->ci->Booking_model->get_booking($booking_id);

                $customer_id = $customer_id ? $customer_id : $booking['booking_customer_id'];

                $customer_info = $this->ci->Card_model->get_customer_cards($customer_id);
                $customer = "";
                if (isset($customer_info) && $customer_info) {

                    foreach ($customer_info as $customer_data) {
                        if (($customer_data['is_primary']) && !$customer_data['is_card_deleted']) {
                            $customer = $customer_data;
                        }
                    }
                }

                $customer = json_decode(json_encode($customer), 1);
                $customer['customer_data'] = $customer_data;
                $customer_meta_data = json_decode($customer['customer_meta_data'], true);

                if (isset($customer_meta_data['profile_id']) && $customer_meta_data['profile_id']) {
                    
                    $payment_method = 'payment_profile';
                    $payment_method_data = array(
                        'customer_code' => $customer_meta_data['profile_id'],
                        'complete' => $is_capture ? true : false
                    );

                    $order_number = 'BOOKING-' . $booking_id . '-' . time();
                    
                    $charge = $this->createPayment($amount, $payment_method_data, $order_number);

                    $charge_id = null;
                    if ($charge['success']) {
                        if (isset($charge['data']['id']) && $charge['data']['id']) {
                            $charge_id = $charge['data']['id'];
                        }
                    } else {
                        $this->setErrorMessage($charge['message']);
                    }
                }

            } catch (Exception $e) {
                $error = $e->getMessage();
                $this->setErrorMessage($error);
            }
        } else {
            $error_message = $this->tdms_error_message ?? 'Payment gateway not available.';
            $this->setErrorMessage($error_message);
            return false;
        }

        return $charge_id;
    }

    /**
     * Check if gateway payment is available for booking
     */
    public function isGatewayPaymentAvailableForBooking($booking_id, $customer_id = null)
    {
        $result = false;

        $this->ci->load->model('Booking_model');
        $this->ci->load->model('Customer_model');

        $booking = $this->ci->Booking_model->get_booking($booking_id);

        $customer_id = $customer_id ? $customer_id : $booking['booking_customer_id'];

        $customer = $this->ci->Customer_model->get_customer($customer_id);

        unset($customer['cc_number']);
        unset($customer['cc_expiry_month']);
        unset($customer['cc_expiry_year']);
        unset($customer['cc_tokenex_token']);
        unset($customer['cc_cvc_encrypted']);

        $card_data = $this->ci->Card_model->get_active_card($customer_id, $this->ci->company_id);

        if (isset($card_data) && $card_data) {
            $customer['cc_number'] = $card_data['cc_number'];
            $customer['cc_expiry_month'] = $card_data['cc_expiry_month'];
            $customer['cc_expiry_year'] = $card_data['cc_expiry_year'];
            $customer['cc_tokenex_token'] = $card_data['cc_tokenex_token'];
            $customer['cc_cvc_encrypted'] = $card_data['cc_cvc_encrypted'];
            $customer['meta_data'] = $card_data['customer_meta_data'];
        }
        
        $tdms_profile_id = "";

        if(isset(json_decode($customer['meta_data'])->profile_id) &&
            json_decode($customer['meta_data'])->profile_id
        ) {
            $tdms_profile_id = json_decode($customer['meta_data'])->profile_id;
        }
        
        $customer = json_decode(json_encode($customer), 1);
        $hasExternalId = (isset($customer[$this->getExternalEntityField()]) and $customer[$this->getExternalEntityField()]);
        $hasProfileToken = (isset($tdms_profile_id) and $tdms_profile_id);

        if (!$hasProfileToken && $this->tdms_enable_tokenization) {
            // Create payment profile if tokenization is enabled
            $token = "";

            if(isset(json_decode($customer['meta_data'])->token) &&
                json_decode($customer['meta_data'])->token
            ) {
                $token = json_decode($customer['meta_data'])->token;
            }

            if ($token) {
                $profile_resp = $this->createPaymentProfile(array(
                    'token' => array(
                        'name' => $customer['customer_name'],
                        'code' => $token
                    )
                ));

                if(
                    isset($profile_resp['data']['customer_code']) &&
                    $profile_resp['success']
                ){
                    $meta['profile_id'] = $profile_resp['data']['customer_code'];
                    $meta['token'] = $token;
                    $meta['source'] = 'tdms';
                    $card_details['customer_meta_data'] = json_encode($meta);

                    if($card_details && count($card_details) > 0) {
                        $this->ci->Card_model->update_customer_primary_card($customer_id, $card_details);
                    }
                } else {
                    $this->tdms_error_message = $profile_resp['message'];
                    return false;
                }
            }

            $customer = $this->ci->Customer_model->get_customer($customer_id);
        
            unset($customer['cc_number']);
            unset($customer['cc_expiry_month']);
            unset($customer['cc_expiry_year']);
            unset($customer['cc_tokenex_token']);
            unset($customer['cc_cvc_encrypted']);
            
            $card_data = $this->ci->Card_model->get_active_card($customer_id, $this->ci->company_id);
                
            if(isset($card_data) && $card_data) {
                $customer['cc_number'] = $card_data['cc_number'];
                $customer['cc_expiry_month'] = $card_data['cc_expiry_month'];
                $customer['cc_expiry_year'] = $card_data['cc_expiry_year'];
                $customer['cc_tokenex_token'] = $card_data['cc_tokenex_token'];
                $customer['cc_cvc_encrypted'] = $card_data['cc_cvc_encrypted'];
                $customer['customer_meta_data'] = $card_data['customer_meta_data'];
            }
                
            $customer      = json_decode(json_encode($customer), 1);
            $customer_meta_data = json_decode($customer['customer_meta_data'], true);
            $hasProfileToken = (isset($customer_meta_data['profile_id']) and $customer_meta_data['profile_id']);
        }

        if (
            $this->areGatewayCredentialsFilled()
            and $customer
            and ($hasExternalId or $hasProfileToken)
        ) {
            $result = true;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getExternalEntityField()
    {
        $name = 'tdms_profile_id';
        return $name;
    }

    /**
     * Checks if gateway settings are filled
     */
    public function areGatewayCredentialsFilled()
    {
        $filled = true;
        $selected_gateway_credentials = $this->getSelectedGatewayCredentials();

        foreach ($selected_gateway_credentials as $credential) {
            if (empty($credential)) {
                $filled = false;
            }
        }

        return $filled;
    }

    /**
     * @param bool $publicOnly
     * @return array
     */
    public function getSelectedGatewayCredentials($publicOnly = false)
    {
        $credentials = $this->getGatewayCredentials($this->selected_gateway, $publicOnly);

        return $credentials;
    }

    /**
     * @param null $filter
     * @param bool $publicOnly
     * @return array
     */
    public function getGatewayCredentials($filter = null, $publicOnly = false)
    {
        $credentials = array();
        $credentials['selected_payment_gateway'] = $this->selected_gateway;
        $credentials['tdms']['tdms_merchant_id'] = $this->company_gateway_settings['tdms_merchant_id'];

        if (!$publicOnly) {
            $credentials['tdms']['tdms_api_passcode'] = $this->company_gateway_settings['tdms_api_passcode'];
            $credentials['tdms']['tdms_profile_passcode'] = $this->company_gateway_settings['tdms_profile_passcode'];
        }

        $credentials['tdms']['tdms_test_mode'] = $this->company_gateway_settings['tdms_test_mode'];
        $credentials['tdms']['tdms_enable_tokenization'] = $this->company_gateway_settings['tdms_enable_tokenization'];

        $result = $credentials;

        if ($filter) {
            $result = isset($result[$filter]) ? $result[$filter] : $result['payment_gateway'];
            $result['selected_payment_gateway'] = $this->selected_gateway;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param string $error_message
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

    /**
     * Refund booking payment
     */
    public function refundBookingPayment($payment_id, $amount, $payment_type, $booking_id = null)
    {
        $result = array("success" => true, "refund_id" => true);
        $this->ci->load->model('Payment_model');
        $this->ci->load->model('Customer_model');

        $payment = $this->ci->Payment_model->get_payment($payment_id);

        try {
            if ($payment['payment_gateway_used'] and $payment['gateway_charge_id']) {
                
                if ($payment_type == 'full') {
                    $amount = abs($payment['amount']);
                }

                $result = $this->createRefund($payment['gateway_charge_id'], $amount);
                
                if ($result['success'] && isset($result['data']['id'])) {
                    $result['refund_id'] = $result['data']['id'];
                }
            }
        } catch (Exception $e) {
            $result = array("success" => false, "message" => $e->getMessage());
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getPaymentGateways()
    {
        return array(
            'tdms' => array(
                'name' => 'TDMS (TD Merchant Solutions)',
                'customer_token_field' => 'tdms_profile_id',
            ),
        );
    }

    /**
     * @param $payment_type
     * @param $company_id
     * @return array
     */
    public function getPaymentGatewayPaymentType($payment_type, $company_id = null)
    {
        $payment_type = 'TDMS';
        $settings   = $this->getCompanyGatewaySettings();
        $company_id = $company_id ?: $settings['company_id'];

        $row = $this->query("select * from payment_type WHERE payment_type = '$payment_type' and company_id = '$company_id'");

        if (empty($row)) {
            $this->createPaymentGatewayPaymentType($payment_type, $company_id);
            $result = $this->getPaymentGatewayPaymentType($payment_type, $company_id);
        } else {
            $result = reset($row);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCompanyGatewaySettings()
    {
        return $this->company_gateway_settings;
    }

    /**
     * @param array $company_gateway_settings
     */
    public function setCompanyGatewaySettings($company_gateway_settings)
    {
        $this->company_gateway_settings = $company_gateway_settings;
    }

    private function query($sql)
    {
        return $this->ci->db->query($sql)->result_array();
    }

    /**
     * @param $company_id
     */
    public function createPaymentGatewayPaymentType($payment_type, $company_id)
    {
        $this->ci->db->insert(
            'payment_type',
            array(
                'payment_type' => $payment_type,
                'company_id' => $company_id,
                'is_read_only' => '1',
            )
        );

        return $this->ci->db->insert_id();
    }
}