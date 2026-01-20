<?php

// Add TDMS gateway to available payment gateways
add_filter('payment_gateways', function($gateways) {
    $gateways['tdms'] = 'TDMS (TD Merchant Solutions)';
    return $gateways;
});