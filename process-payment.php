<?php

require './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_POST['submit'])) {
    $consumer_key = $_ENV['CONSUMER_KEY'];
    $consumer_secret = $_ENV['CONSUMER_SECRET'];
    $headers = ['Content-Type:application/json; charset=utf8'];

    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumer_key . ":" . $consumer_secret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $access_token = $result->access_token;
    curl_close($curl);
    $phone = $_POST['phone']; // Collect from form
    function formatPhoneNumber($phoneNumber) {
        // Check if the phone number starts with '0'
        if (strpos($phoneNumber, '0') === 0) {
            // Replace the leading '0' with '254'
            $formattedNumber = '254' . substr($phoneNumber, 1);
        } else {
            // If the number does not start with '0', return it as is
            $formattedNumber = $phoneNumber;
        }
        return $formattedNumber;
    }
    $phone_number = formatPhoneNumber($phone);
    $amount = $_POST['amount']; // Collect from form
    $lipa_na_mpesa_online_shortcode = $_ENV['LIPA_NA_MPESA_ONLINE_SHORTCODE'];
    $lipa_na_mpesa_online_passkey = $_ENV['LIPA_NA_MPESA_ONLINE_PASSKEY'];
    $transaction_type = 'CustomerPayBillOnline';
    $timestamp = date('YmdHis');
    $password = base64_encode($lipa_na_mpesa_online_shortcode . $lipa_na_mpesa_online_passkey . $timestamp);
    $transaction_desc = 'Test';
    $account_reference = 'Test';
    $callback_url = $_ENV['CALLBACK_URL'];

    $stk_push_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $stk_push_headers = ['Authorization: Bearer ' . $access_token, 'Content-Type:application/json'];
    $stk_push_body = [
        'BusinessShortCode' => $lipa_na_mpesa_online_shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => $transaction_type,
        'Amount' => $amount,
        'PartyA' => $phone_number,
        'PartyB' => $lipa_na_mpesa_online_shortcode,
        'PhoneNumber' => $phone_number,
        'CallBackURL' => $callback_url,
        'AccountReference' => $account_reference,
        'TransactionDesc' => $transaction_desc
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $stk_push_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stk_push_headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($stk_push_body));
    $response = curl_exec($curl);
    curl_close($curl);

    echo $response;

    function logTransaction($message)
    {
        $logFile = 'transaction_log.txt';
        $current = file_exists($logFile) ? file_get_contents($logFile) : '';
        $current .= $message . "\n";
        file_put_contents($logFile, $current);
    }

    if ($response) {
        $responseData = json_decode($response, true); // Decoding the JSON response

        // Prepare a log message
        $logMessage = sprintf(
            "Transaction Date: %s, Phone Number: %s, Amount: KES %s, Status: %s, Transaction ID: %s",
            date('Y-m-d H:i:s'),
            $phone_number,
            $amount,
            isset($responseData['ResponseDescription']) ? $responseData['ResponseDescription'] : 'N/A',
            isset($responseData['CheckoutRequestID']) ? $responseData['CheckoutRequestID'] : 'N/A'
        );

        // Call the log function
        logTransaction($logMessage);
    }
}
