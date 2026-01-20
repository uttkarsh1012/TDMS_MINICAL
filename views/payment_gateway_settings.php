<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">TDMS Payment Gateway Settings</h3>
                </div>
                <div class="card-body">
                    <form id="tdms_settings_form">
                        <div class="alert alert-info">
                            <strong>Note:</strong> TD Merchant Solutions uses Bambora/Worldline as their payment gateway platform. 
                            You can find your credentials in your TD Merchant Services dashboard under Administration > Order Settings.
                        </div>

                        <div class="form-group">
                            <label for="tdms_merchant_id">Merchant ID *</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="tdms_merchant_id" 
                                   name="tdms_merchant_id" 
                                   value="<?php echo isset($tdmsData['tdms_merchant_id']) ? $tdmsData['tdms_merchant_id'] : ''; ?>"
                                   placeholder="Enter your TDMS Merchant ID">
                            <small class="form-text text-muted">
                                Found under Administration > Company Info in your TD Merchant Services dashboard
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="tdms_api_passcode">API Access Passcode *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="tdms_api_passcode" 
                                   name="tdms_api_passcode" 
                                   value="<?php echo isset($tdmsData['tdms_api_passcode']) ? $tdmsData['tdms_api_passcode'] : ''; ?>"
                                   placeholder="Enter your API Access Passcode">
                            <small class="form-text text-muted">
                                Found under Administration > Order Settings > Payment Gateway > Security/Authentication
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="tdms_profile_passcode">Profile API Passcode</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="tdms_profile_passcode" 
                                   name="tdms_profile_passcode" 
                                   value="<?php echo isset($tdmsData['tdms_profile_passcode']) ? $tdmsData['tdms_profile_passcode'] : ''; ?>"
                                   placeholder="Enter your Profile API Passcode">
                            <small class="form-text text-muted">
                                Found under Configuration > Payment Profile Configuration > Security Settings (required for tokenization)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="tdms_test_mode">Test Mode</label>
                            <select class="form-control" id="tdms_test_mode" name="tdms_test_mode">
                                <option value="0" <?php echo (isset($tdmsData['tdms_test_mode']) && $tdmsData['tdms_test_mode'] == '0') ? 'selected' : ''; ?>>Production</option>
                                <option value="1" <?php echo (isset($tdmsData['tdms_test_mode']) && $tdmsData['tdms_test_mode'] == '1') ? 'selected' : ''; ?>>Test Mode</option>
                            </select>
                            <small class="form-text text-muted">
                                Enable test mode to use sandbox credentials for testing
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="tdms_enable_tokenization" 
                                       name="tdms_enable_tokenization" 
                                       value="1"
                                       <?php echo (isset($tdmsData['tdms_enable_tokenization']) && $tdmsData['tdms_enable_tokenization'] == '1') ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="tdms_enable_tokenization">
                                    Enable Payment Profile Tokenization
                                </label>
                                <small class="form-text text-muted">
                                    Store customer payment information securely for future transactions (requires Profile API Passcode)
                                </small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="selected_payment_gateway">Payment Gateway</label>
                            <select class="form-control" id="selected_payment_gateway" name="selected_payment_gateway">
                                <option value="tdms" <?php echo (isset($tdmsData['selected_payment_gateway']) && $tdmsData['selected_payment_gateway'] == 'tdms') ? 'selected' : ''; ?>>TDMS (TD Merchant Solutions)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="save_tdms_settings">
                                <i class="fa fa-save"></i> Save Settings
                            </button>
                            <button type="button" class="btn btn-secondary" id="test_tdms_connection">
                                <i class="fa fa-plug"></i> Test Connection
                            </button>
                        </div>

                        <div id="tdms_message" class="mt-3"></div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Setup Instructions</h4>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Log in to your TD Merchant Services dashboard</li>
                        <li>Navigate to <strong>Administration > Company Info</strong> to find your Merchant ID</li>
                        <li>Go to <strong>Administration > Order Settings > Payment Gateway > Security/Authentication</strong> to generate or view your API Access Passcode</li>
                        <li>If using tokenization, go to <strong>Configuration > Payment Profile Configuration > Security Settings</strong> for Profile API Passcode</li>
                        <li>Enable hash validation and API access in your TD Merchant Services settings</li>
                        <li>Enter your credentials above and click "Test Connection" to verify</li>
                        <li>Click "Save Settings" to activate the gateway</li>
                    </ol>
                    <p class="mt-3">
                        <strong>Need Help?</strong> Contact TD Merchant Solutions at <strong>1-800-363-1163</strong> or visit 
                        <a href="https://www.tdmerchantsolutions.com" target="_blank">tdmerchantsolutions.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#save_tdms_settings').click(function() {
        var formData = $('#tdms_settings_form').serialize();
        
        $.ajax({
            url: '<?php echo base_url(); ?>tdms_payment_gateway/integrations/update_tdms_payment_gateway_settings',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success !== false) {
                    $('#tdms_message').html('<div class="alert alert-success">Settings saved successfully!</div>');
                } else {
                    $('#tdms_message').html('<div class="alert alert-danger">Error: ' + (response.message || 'Failed to save settings') + '</div>');
                }
            },
            error: function() {
                $('#tdms_message').html('<div class="alert alert-danger">Error saving settings. Please try again.</div>');
            }
        });
    });

    $('#test_tdms_connection').click(function() {
        var merchant_id = $('#tdms_merchant_id').val();
        var api_passcode = $('#tdms_api_passcode').val();
        
        if(!merchant_id || !api_passcode) {
            $('#tdms_message').html('<div class="alert alert-warning">Please enter Merchant ID and API Passcode to test connection.</div>');
            return;
        }

        $('#tdms_message').html('<div class="alert alert-info">Testing connection...</div>');
        
        $.ajax({
            url: '<?php echo base_url(); ?>tdms_payment_gateway/integrations/test_tdms_connection',
            type: 'POST',
            data: {
                merchant_id: merchant_id,
                api_passcode: api_passcode
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#tdms_message').html('<div class="alert alert-success"><i class="fa fa-check"></i> Connection successful! Your credentials are valid.</div>');
                } else {
                    $('#tdms_message').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Connection failed: ' + (response.message || 'Invalid credentials') + '</div>');
                }
            },
            error: function() {
                $('#tdms_message').html('<div class="alert alert-danger">Error testing connection. Please check your credentials and try again.</div>');
            }
        });
    });
});
</script>