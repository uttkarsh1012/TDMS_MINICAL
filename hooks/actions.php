<?php
// This is a core action that will be executed when a new reservation is created in miniCal
add_action('add.booking.created', 'your_callback_function_1', 10, 1);
function your_callback_function_1($data) {
    // code here
}


// This is a custom action and can be executed from this extension controllers.
add_action('my-custom-action', 'your_custom_callback_function', 10, 1);
function your_custom_callback_function($data) {
    // code here
}