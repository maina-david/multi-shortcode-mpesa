<?php

return [
    /*-----------------------------------------
        |Mpesa C2B Validation url
        |------------------------------------------
        */
    'c2b_validation_url' => env('MPESA_C2B_VALIDATION_URL', env('APP_URL' . '/api/c2b_validation')),

    /*-----------------------------------------
        |Mpesa C2B Confirmation url
        |------------------------------------------
        */
    'c2b_confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL', env('APP_URL' . '/api/c2b_confirmation')),

    /*-----------------------------------------
        |Mpesa B2C Result url
        |------------------------------------------
        */
    'b2c_result_url' => env('MPESA_B2C_RESULT_URL', env('APP_URL' . '/api/b2c_result')),

    /*-----------------------------------------
        |Mpesa B2C Timeout url
        |------------------------------------------
        */
    'b2c_timeout_url' => env('MPESA_B2C_TIMEOUT_URL', env('APP_URL' . '/api/b2c_timeout')),

    /*-----------------------------------------
        |Mpesa Lipa Na Mpesa callback url
        |------------------------------------------
        */
    'stk_callback_url' => env('MPESA_CALLBACK_URL', env('APP_URL' . '/api/stk_callback')),

    /*-----------------------------------------
        |Mpesa Transaction Status Result url
        |------------------------------------------
        */
    'status_result_url' => env('MPESA_STATUS_RESULT_URL', env('APP_URL' . '/api/status_result')),

    /*-----------------------------------------
        |Mpesa Transaction Status Timeout url
        |------------------------------------------
        */
    'status_timeout_url' => env('MPESA_STATUS_TIMEOUT_URL', env('APP_URL' . '/api/status_timeout')),

    /*-----------------------------------------
        |Mpesa Account Balance Result url
        |------------------------------------------
        */
    'balance_result_url' => env('MPESA_BALANCE_RESULT_URL', env('APP_URL' . '/api/balance_result')),

    /*-----------------------------------------
        |Mpesa Account Balance Timeout url
        |------------------------------------------
        */
    'balance_timeout_url' => env('MPESA_BALANCE_TIMEOUT_URL', env('APP_URL' . '/api/balance_timeout')),

    /*-----------------------------------------
        |Mpesa Reversal Result url
        |------------------------------------------
        */
    'reversal_result_url' => env('MPESA_REVERSAL_RESULT_URL', env('APP_URL' . '/api/reversal_result')),

    /*-----------------------------------------
        |Mpesa Reversal Timeout url
        |------------------------------------------
        */
    'reversal_timeout_url' => env('MPESA_REVERSAL_TIMEOUT_URL', env('APP_URL' . '/api/reversal_timeout')),
];