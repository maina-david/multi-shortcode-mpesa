<?php

namespace MainaDavid\MultiShortcodeMpesa;

use Exception;
use Illuminate\Support\Carbon;
use MainaDavid\MultiShortcodeMpesa\Models\ShortCode;
use MainaDavid\MultiShortcodeMpesa\traits\MpesaHelper;
use Illuminate\Support\Facades\Http;

class Mpesa extends Service
{
    /* Importing the `MpesaHelper` trait. */
    use MpesaHelper;

    /* A constant that is used to define the domain name of the safaricom api. */
    const BASE_DOMAIN         = "safaricom.co.ke";

    /* Concatenating the string `sandbox` to the constant `BASE_DOMAIN` */
    const BASE_SANDBOX_DOMAIN = "sandbox." . self::BASE_DOMAIN;

    /* Used to store the shortcode that is passed to the constructor. */
    public $shortcode;

    /* Used to store the environment of the shortcode. */
    public $environment;

    /* Used to store the direction of the shortcode. */
    public $direction;

    /* Used to store the consumer key that is passed to the constructor. */
    public $consumer_key;

    /* Used to store the consumer secret that is passed to the constructor. */
    public $consumer_secret;

    /* Used to store the pass key that is passed to the constructor. */
    public $pass_key;

    /* Used to store the initiator name that is passed to the constructor. */
    public $initiator_name;

    /* Used to store the initiator password that is passed to the constructor. */
    public $initiator_password;

    /* A variable that is used to store the base url of the safaricom api. */
    public $baseUrl;

    /* A variable that is used to store the url of the safaricom api that is used to generate the access
    token. */
    public $authUrl;

    /* A variable that is used to store the url of the safaricom api that is used to register the callback
    url. */
    public $registerUrl;

    /* A variable that is used to store the url of the safaricom api that is used to check the balance of
    the account. */
    public $balanceUrl;

    /* A variable that is used to store the url of the safaricom api that is used to initiate the
    stk push. */
    public $stkPushUrl;

    /* A variable that is used to store the url of the safaricom api that is used to query the status of
    the stk push. */
    public $queryStkUrl;

    /* Used to store the url of the safaricom api that is used to query the status of the transaction. */
    public $statusqueryUrl;

    /* Used to store the url of the safaricom api that is used to reverse the transaction. */
    public $reversalUrl;

    /* Used to store the url of the safaricom api that is used to initiate the b2c transaction. */
    public $b2cUrl;

    public function __construct($shortcode)
    {
        $Orgshortcode = $shortcode;
        /* Getting the shortcode from the database. */
        $short_code = ShortCode::where('shortcode', $Orgshortcode)->first();

        /* Checking if the shortcode exists in the database. */
        if (!$short_code) {
            throw new Exception("Shortcode not found!", 1);
        }
        /* Checking if the environment is sandbox or not. If it is sandbox, it sets the base url to the sandbox
            url. If it is not sandbox, it sets the base url to the production url. */
        if ($short_code->environment === 'sandbox') {
            $this->baseUrl = "https://" . self::BASE_SANDBOX_DOMAIN;
        } else {
            $this->baseUrl = "https://api." . self::BASE_DOMAIN;
        }

        /* Assigning the value of the `environment` column of the `shortcodes` table to the `environment`
        variable. */
        $this->environment = $short_code->environment;

        /* Assigning the value of the `` variable to the `->shortcode` variable. */
        $this->shortcode = $Orgshortcode;

        /* Assigning the value of the `direction` column of the `shortcodes` table to the `direction` variable. */
        $this->direction = $short_code->direction;

        /* Decrypting the consumer key that is stored in the database. */
        $this->consumer_key = $short_code->consumer_key;

        /* Decrypting the consumer secret that is stored in the database. */
        $this->consumer_secret = $short_code->consumer_secret;

        $this->pass_key = $short_code->pass_key;

        /* Decrypting the initiator name that is stored in the database. */
        $this->initiator_name = $short_code->initiator_name;

        /* Decrypting the initiator password that is stored in the database. */
        $this->initiator_password = $short_code->initiator_password;

        /* Concatenating the base url with the url of the safaricom api that is used to generate the access
        token. */
        $this->authUrl = $this->baseUrl . "/oauth/v1/generate?grant_type=client_credentials";

        /* Concatenating the base url with the url of the safaricom api that is used to register the callback
        url. */
        $this->registerUrl = $this->baseUrl . "/mpesa/c2b/v1/registerurl";

        /* Concatenating the base url with the url of the safaricom api that is used to check the balance of
        the account. */
        $this->balanceUrl = $this->baseUrl . "/mpesa/accountbalance/v1/query";

        /* Concatenating the base url with the url of the safaricom api that is used to initiate the
        stk push. */
        $this->stkPushUrl = $this->baseUrl . "/mpesa/stkpush/v1/processrequest";

        /* Concatenating the base url with the url of the safaricom api that is used to query the status of the
        stk push. */
        $this->queryStkUrl = $this->baseUrl . "/mpesa/stkpushquery/v1/query";

        /* Concatenating the base url with the url of the safaricom api that is used to query the status of the
        transaction. */
        $this->statusqueryUrl = $this->baseUrl . "/mpesa/transactionstatus/v1/query";

        /* Concatenating the base url with the url of the safaricom api that is used to initiate the b2c
        transaction. */
        $this->b2cUrl = $this->baseUrl . "/mpesa/b2c/v1/paymentrequest";

        /* Concatenating the base url with the url of the safaricom api that is used to reverse the
        transaction. */
        $this->reversalUrl = $this->baseUrl . "/mpesa/reversal/v1/request";
    }

    /**
     * It uses the consumer key and secret to get an access token from the API
     * 
     * @return The access token is being returned.
     */
    public function generateAccessToken()
    {
        $response = Http::withBasicAuth(decrypt($this->consumer_key), decrypt($this->consumer_secret))
            ->get($this->authUrl);

        if ($response->successful()) {
            return $response['access_token'];
        }
        if ($response->failed()) {
            throw new Exception("Invalid credentials!", 1);
        }
    }

    /* Used to register the callback urls. */
    public function registerUrls()
    {
        $data = [
            'ShortCode' => $this->shortcode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => config('multi-shortcode-mpesa.c2b_confirmation_url'),
            'ValidationURL' => config('multi-shortcode-mpesa.c2b_validation_url')
        ];

        return $this->MpesaRequest($this->balanceUrl, $data);
    }

    /**
     * It returns the balance of the account that is linked to the shortcode
     * 
     * @return The response from the API.
     */
    public function accountBalance()
    {
        $data = [
            'Initiator' => decrypt($this->initiator_name),
            'SecurityCredential' => $this->generate_security_credentials($this->environment, decrypt($this->initiator_password)),
            'CommandID' => 'AccountBalance',
            'PartyA' => $this->shortcode,
            'IdentifierType' => '4',
            'Remarks' => '',
            'QueueTimeOutURL' => config('multi-shortcode-mpesa.balance_timeout_url'),
            'ResultURL' => config('multi-shortcode-mpesa.balance_result_url')
        ];

        return $this->MpesaRequest($this->balanceUrl, $data);
    }

    /**
     * It initiates an STK push transaction to the phone number provided
     * 
     * @param phonenumber The phone number of the customer.
     * @param amount The amount to be charged to the customer.
     * @param reference This is the unique transaction identifier that you will receive in the callback
     * url.
     * @param description A description of the transaction.
     * 
     * @return The response from the API.
     */
    public function stkPush($phonenumber, $amount, $reference = '', $description = '')
    {
        if ($this->direction !== 'c2b') {
            return $this->error('Shortcode does not support Customer to Business!');
        }
        $data = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $this->LipaNaMpesaPassword($this->shortcode, decrypt($this->pass_key)),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int)$amount,
            'PartyA' => $this->phoneNumberValidator($phonenumber),
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $this->phoneNumberValidator($phonenumber),
            'CallBackURL' => config('multi-shortcode-mpesa.stk_callback_url'),
            'AccountReference' => $reference,
            'TransactionDesc' => $description
        ];
        return $this->MpesaRequest($this->stkPushUrl, $data);
    }

    /**
     * It takes the CheckoutRequestID as a parameter and returns the status of the transaction
     * 
     * @param CheckoutRequestID This is the unique identifier for the transaction.
     * 
     * @return The response is a JSON object with the following parameters:
     */
    public function stkPushQuery($CheckoutRequestID)
    {
        if ($this->direction !== 'c2b') {
            return $this->error('Shortcode does not support Customer to Business!');
        }
        $data = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $this->LipaNaMpesaPassword($this->shortcode, decrypt($this->pass_key)),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'CheckoutRequestID' => $CheckoutRequestID
        ];

        return $this->MpesaRequest($this->queryStkUrl, $data);
    }

    /**
     * It sends money from your business to a customer
     * 
     * @param amount The amount to be transacted.
     * @param phonenumber The phone number of the person you are sending money to.
     * @param remarks This is a comment that will be sent to the receiver.
     * 
     * @return The response from the API.
     */
    public function b2c($commandID, $amount, $phonenumber, $remarks)
    {
        /* Checking if the direction is not equal to b2c, if it is not, it will return an error message. */
        if ($this->direction !== 'b2c') {
            return $this->error('Shortcode does not support Business to Customer!');
        }
        /* Checking if the commandID is in the array of BusinessPayment, SalaryPayment, PromotionPayment. If it
        is not, it will return an error. */
        if (!in_array($commandID, ['BusinessPayment', 'SalaryPayment', 'PromotionPayment'])) {
            return $this->error('Invalid commandID!');
        }
        $data = [
            'InitiatorName' => decrypt($this->initiator_name),
            'SecurityCredential' => $this->generate_security_credentials($this->environment, decrypt($this->initiator_password)),
            'CommandID' => $commandID,
            'Amount' => (int)$amount,
            'PartyA' => $this->shortcode,
            'PartyB' => $this->phoneNumberValidator($phonenumber),
            'Remarks' => $remarks,
            'QueueTimeOutURL' => config('multi-shortcode-mpesa.b2c_timeout_url'),
            'ResultURL' => config('multi-shortcode-mpesa.b2c_result_url'),
            'Occasion' => ''
        ];

        return $this->MpesaRequest($this->b2cUrl, $data);
    }

    /**
     * It returns the status of a transaction
     * 
     * @param TransactionID The transaction ID you get from the B2C response.
     * 
     * @return The response is a JSON object with the following properties:
     */
    public function transactionStatus($TransactionID)
    {
        if ($this->direction !== 'b2c') {
            return $this->error('Shortcode does not support Business to Customer!');
        }
        $data = [
            'Initiator' => decrypt($this->initiator_name),
            'SecurityCredential' => $this->generate_security_credentials($this->environment, decrypt($this->initiator_password)),
            'CommandID' => 'TransactionStatusQuery',
            'TransactionID' => $TransactionID,
            'PartyA' => $this->shortcode,
            'IdentifierType' => '1',
            'ResultURL' => config('multi-shortcode-mpesa.status_result_url'),
            'QueueTimeOutURL' => config('multi-shortcode-mpesa.status_timeout_url'),
            'Remarks' => '',
            'Occasion' => ''
        ];

        return $this->MpesaRequest($this->statusqueryUrl, $data);
    }

    /**
     * It reverses a transaction
     * 
     * @param TransactionID This is the transaction ID of the transaction you want to reverse.
     * @param amount The amount to be reversed.
     * 
     * @return The response from the API.
     */
    public function reverseTransaction($TransactionID)
    {
        if ($this->direction !== 'b2c') {
            return $this->error('Shortcode does not support Business to Customer!');
        }
        $data = [
            'Initiator' => decrypt($this->initiator_name),
            'SecurityCredential' => $this->generate_security_credentials($this->environment, decrypt($this->initiator_password)),
            'CommandID' => 'TransactionReversal',
            'TransactionID' => $TransactionID,
            'ReceiverParty' => $this->shortcode,
            'RecieverIdentifierType' => '4',
            'ResultURL' => config('multi-shortcode-mpesa.reversal_result_url'),
            'QueueTimeOutURL' => config('multi-shortcode-mpesa.reversal_timeout_url'),
            'Remarks' => '',
            'Occasion' => ''
        ];

        return $this->MpesaRequest($this->reversalUrl, $data);
    }

    /**
     * It takes in a url and data, then uses the access token to make a post request to the url with the
     * data
     * 
     * @param url The endpoint you want to hit.
     * @param data This is the data you want to send to the API.
     * 
     * @return The response is a json object
     */
    public function MpesaRequest($url, $data)
    {
        $response = Http::withToken($this->generateAccessToken())->post($url, $data);

        return $response->successful() ? $this->success($response) : $this->error($response->json());
    }
}