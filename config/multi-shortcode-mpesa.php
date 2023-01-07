<?php

return [

    /* Setting the value of the `c2b_validation_url` key to the value of the `MPESA_C2B_VALIDATION_URL`
    environment variable. If the `MPESA_C2B_VALIDATION_URL` environment variable is not set, it will use
    the value of the `APP_URL` environment variable concatenated with `/api/c2b_validation`.
    */
    'c2b_validation_url' => env('MPESA_C2B_VALIDATION_URL', env('APP_URL') . '/api/c2b_validation'),

    /* Setting the value of the `c2b_confirmation_url` key to the value of the `MPESA_C2B_CONFIRMATION_URL`
    environment variable. If the `MPESA_C2B_CONFIRMATION_URL` environment variable is not set, it will
    use the value of the `APP_URL` environment variable concatenated with `/api/c2b_confirmation`. 
    */
    'c2b_confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL', env('APP_URL') . '/api/c2b_confirmation'),

    /* Setting the value of the `b2c_result_url` key to the value of the `MPESA_B2C_RESULT_URL`
    environment variable. If the `MPESA_B2C_RESULT_URL` environment variable is not set, it will use
    the value of the `APP_URL` environment variable concatenated with `/api/b2c_result`. 
    */
    'b2c_result_url' => env('MPESA_B2C_RESULT_URL', env('APP_URL') . '/api/b2c_result'),

    /* Setting the value of the `b2c_timeout_url` key to the value of the `MPESA_B2C_TIMEOUT_URL`
    environment variable. If the `MPESA_B2C_TIMEOUT_URL` environment variable is not set, it will use
    the value of the `APP_URL` environment variable concatenated with `/api/b2c_timeout`. 
    */
    'b2c_timeout_url' => env('MPESA_B2C_TIMEOUT_URL', env('APP_URL') . '/api/b2c_timeout'),

    /* Setting the value of the `stk_callback_url` key to the value of the `MPESA_CALLBACK_URL`
    environment variable. If the `MPESA_CALLBACK_URL` environment variable is not set, it will use
    the value of the `APP_URL` environment variable concatenated with `/api/stk_callback`. 
    */
    'stk_callback_url' => env('MPESA_CALLBACK_URL', env('APP_URL') . '/api/stk_callback'),

    /* Setting the value of the `status_result_url` key to the value of the `MPESA_STATUS_RESULT_URL`
    environment variable. If the `MPESA_STATUS_RESULT_URL` environment variable is not set, it will use
    the value of the `APP_URL` environment variable concatenated with `/api/status_result`. 
    */
    'status_result_url' => env('MPESA_STATUS_RESULT_URL', env('APP_URL') . '/api/status_result'),

    /* Setting the value of the `status_timeout_url` key to the value of the `MPESA_STATUS_TIMEOUT_URL`
    environment variable. If the `MPESA_STATUS_TIMEOUT_URL` environment variable is not set, it will use
    the value of the `APP_URL` environment variable concatenated with `/api/status_timeout`. 
    */
    'status_timeout_url' => env('MPESA_STATUS_TIMEOUT_URL', env('APP_URL') . '/api/status_timeout'),

    /* Setting the value of the `balance_result_url` key to the value of the `MPESA_BALANCE_RESULT_URL`
    environment variable. If the `MPESA_BALANCE_RESULT_URL` environment variable is not set, it will use
    the value of the `APP_URL` environment variable concatenated with `/api/balance_result`. 
    */
    'balance_result_url' => env('MPESA_BALANCE_RESULT_URL', env('APP_URL') . '/api/balance_result'),

    /* Setting the value of the `balance_timeout_url` key to the value of the `MPESA_BALANCE_TIMEOUT_URL`
    environment variable. If the `MPESA_BALANCE_TIMEOUT_URL` environment variable is not set, it will
    use the value of the `APP_URL` environment variable concatenated with `/api/balance_timeout`. 
    */
    'balance_timeout_url' => env('MPESA_BALANCE_TIMEOUT_URL', env('APP_URL') . '/api/balance_timeout'),

    /* Setting the value of the `reversal_result_url` key to the value of the `MPESA_REVERSAL_RESULT_URL`
    environment variable. If the `MPESA_REVERSAL_RESULT_URL` environment variable is not set, it will
    use the value of the `APP_URL` environment variable concatenated with `/api/reversal_result`. 
    */
    'reversal_result_url' => env('MPESA_REVERSAL_RESULT_URL', env('APP_URL') . '/api/reversal_result'),

    /* Setting the value of the `reversal_timeout_url` key to the value of the `MPESA_REVERSAL_TIMEOUT_URL`
    environment variable. If the `MPESA_REVERSAL_TIMEOUT_URL` environment variable is not set, it will
    use the value of the `APP_URL` environment variable concatenated with `/api/reversal_timeout`. 
    */
    'reversal_timeout_url' => env('MPESA_REVERSAL_TIMEOUT_URL', env('APP_URL') . '/api/reversal_timeout')
];