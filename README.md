# Multi-Shortcode-Mpesa-PHP

This package seeks to help php developers implement the various Mpesa APIs without much hustle.
It is based on the REST API whose documentation is available on https://developer.safaricom.co.ke.

## Installation

You can install the PHP SDK via composer or by downloading the source

#### Via Composer

The recommended way to install the SDK is with [Composer](http://getcomposer.org/).

```bash
composer require maina-david/multi-shortcode-mpesa
```

Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:

```
'providers' => [
    // ...
    MainaDavid\MultiShortcodeMpesa\MpesaServiceProvider::class,
];
```

You should publish the shortcode migration and the config/multi-shortcode-mpesa.php config file with:

```bash
php artisan vendor:publish --provider="MainaDavid\MultiShortcodeMpesa\MpesaServiceProvider"
```

You can overwrite the default urls in config/multi-shortcode-mpesa.php file.

Clear your config cache. This package requires access to the multi-shortcode-mpesa config. Generally it's bad practice to do config-caching in a development environment. If you've been caching configurations locally, clear your config cache with either of these commands:

```bash
 php artisan optimize:clear
 # or
 php artisan config:clear
```

Run the migrations: After the config and migration have been published and configured, you can create the tables for this package by running:

```bash
 php artisan migrate
```

## Usage

The SDK needs to be instantiated using one of your shortcode saved in the short_codes table and expects following values to be encrypted when storing them in the database for security reasons. It will decrypt the values, if unable it will throw an error.

- consumer_key.
- consumer_secret.
- pass_key.
- initiator_name.
- initiator_password.

### Example saving a shortcode to the database

```php
use MainaDavid\MultiShortcodeMpesa\Models\ShortCode;

    /**
     * It validates the request, creates a new shortcode object, encrypts the sensitive data and saves it
     * to the database
     *
     * @param Request request The request object.
     *
     * @return A JSON response with a success message.
     */
    public function storeShortCode(Request $request)
    {
        /* Validating the request. */
        $request->validate([
            'environment' => 'required|in:sandbox,production',
            'direction' => 'required|in:c2b,b2c',
            'shortcode' => 'required|integer',
            'consumer_key' => 'required',
            'consumer_secret' => 'required',
            'pass_key' => 'required_if:direction,c2b',
            'initiator_name' => 'required_if:direction,b2c',
            'initiator_password' => 'required_if:direction,b2c',
        ]);

        $shortcode = new ShortCode();
        $shortcode->environment = $request->environment; //sandbox or production
        $shortcode->direction = $request->direction; // c2b or b2c
        $shortcode->shortcode = $request->shortcode; // mpesa shortcode
        $shortcode->consumer_key = encrypt($request->consumer_key); // shortcode consumer key
        $shortcode->consumer_secret = encrypt($request->consumer_secret); // shortcode consumer secret
        /* Checking if the request has a pass_key and if it does, it encrypts it and saves it to the database. */
        if ($request->has('pass_key')) {
            $shortcode->pass_key = encrypt($request->pass_key); // not required for b2c
        }
        /* Checking if the request has an initiator name and if it does, it encrypts it and saves it to the
        database. */
        if ($request->has('initiator_name')) {
            $shortcode->initiator_name = encrypt($request->initiator_name);  // shortcode api initiator
        }
        /* Checking if the request has an initiator password and if it does, it encrypts it and saves it to the
        database. */
        if ($request->has('initiator_password')) {
            $shortcode->initiator_password = encrypt($request->initiator_password); // shortcode initiator password
        }
        $shortcode->save();

        return response()->json([
            'success' => true,
            'message' => 'Shortcode saved successfully!'
        ], 200);
    }
```

> You can use this SDK for either production or sandbox apps. For sandbox, the shortcode environment is **ALWAYS** `sandbox`

> Authorization is done by the SDK for both sandbox and production environments. You can use multiple shortcodes with different environments seamlessly.

### Content

- `stkPush($phonenumber, $amount, $reference = '', $description = '')`: Initiates an STK push transaction

  - `phonenumber`: The phone number sending money. `REQUIRED`
  - `amount`: This is the Amount to be transacted. Only whole numbers are supported. `REQUIRED`
  - `reference`: This is an Identifier of the transaction for CustomerPayBillOnline transaction type. `OPTIONAL`
  - `description`: This is any additional comment that can be sent along with the request. `OPTIONAL`

- `stkPushQuery($CheckoutRequestID)`: Returns the status of the transaction

  - `CheckoutRequestID`: The unique identifier of the processed checkout transaction request. `REQUIRED`

- `b2c($commandID, $amount, $phonenumber, $remarks)`: Sends money from your business to a customer

  - `commandID`: Unique command that specifies B2C transaction type [SalaryPayment,BusinessPayment,PromotionPayment]. `REQUIRED`
  - `phonenumber`: Customer mobile number to receive the amount. `REQUIRED`
  - `amount`: The amount of money being sent to the customer. Only whole numbers are supported. `REQUIRED`
  - `remarks`: Any additional information to be associated with the transaction. `REQUIRED`

- `transactionStatus($TransactionID)`: It returns the status of a transaction

  - `TransactionID`: Unique identifier to identify a transaction on Mpesa. `REQUIRED`

- `reverseTransaction($TransactionID, $amount)`: It reverses a transaction

  - `TransactionID`: Unique identifier to identify a transaction on Mpesa. `REQUIRED`

### Lipa na Mpesa Online

```php
use MainaDavid\MultiShortcodeMpesa\Mpesa;

// Mpesa shortcode
$shortcode = '12345';

// Phone numbers are transformed to the required format by the sdk so no worries about the formatting
$phonenumber = '0722123456';

// Instantiate the class
$mpesa  = new Mpesa($shortcode);

// Use stkpush service
$result = $mpesa->stkPush($phonenumber, $amount);

print_r($result);
```

### Check the status of a Lipa Na M-Pesa Online Payment

```php
use MainaDavid\MultiShortcodeMpesa\Mpesa;

// Mpesa shortcode
$shortcode = '12345';

$CheckoutRequestID = 'ws_CO_260520211133524545';

// Instantiate the class
$mpesa  = new Mpesa($shortcode);

$result = $mpesa->stkPushQuery($CheckoutRequestID);

print_r($result);
```

### Business to Customers (Pay Outs)

```php
use MainaDavid\MultiShortcodeMpesa\Mpesa;

// Mpesa shortcode
$shortcode = '12345';

$phonenumber = '0722123456';

// Instantiate the class
$mpesa  = new Mpesa($shortcode);

$result = $mpesa->b2c($amount, $phonenumber);

print_r($result);
```

### Check the status of a transaction

```php
use MainaDavid\MultiShortcodeMpesa\Mpesa;

// Mpesa shortcode
$shortcode = '12345';

$TransactionID = 'QWERTY123456789';

// Instantiate the class
$mpesa  = new Mpesa($shortcode);

$result = $mpesa->transactionStatus($TransactionID);

print_r($result);
```

### Reverse a transaction

```php
use MainaDavid\MultiShortcodeMpesa\Mpesa;

// Mpesa shortcode
$shortcode = '12345';

$TransactionID = 'QWERTY123456789';

// Instantiate the class
$mpesa  = new Mpesa($shortcode);

$result = $mpesa->reverseTransaction($TransactionID);

print_r($result);
```
