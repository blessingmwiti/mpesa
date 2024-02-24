<?php
//include "conn.php";

$callbackJSONData=file_get_contents('php://input');

$logFile = "stkPush.json";
$log = fopen($logFile, "a");
fwrite($log, $callbackJSONData);
fclose($log);

$callbackData=json_decode($callbackJSONData);

$resultCode=$callbackData->Body->stkCallback->ResultCode;
$resultDesc=$callbackData->Body->stkCallback->ResultDesc;
$merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
$checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;
$pesa=$callbackData->stkCallback->Body->CallbackMetadata->Item[0]->Name;
$amount=$callbackData->Body->stkCallback->CallbackMetadata->Item[0]->Value;
$mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;
$balance=$callbackData->stkCallback->Body->CallbackMetadata->Item[2]->Value;
$b2CUtilityAccountAvailableFunds=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
$transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
$phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;

$amount = strval($amount);
if($resultCode == 0){
$insert = $conn->query("INSERT INTO `stkpush`(`merchantRequestID`, `checkoutRequestID`,`resultCode`, `resultDesc`, `amount`, `mpesaReceiptNumber`, `transactionDate`, `phoneNumber`)
VALUES ('$merchantRequestID', '$checkoutRequestID','$resultCode', '$resultDesc', '$amount','$mpesaReceiptNumber','$transactionDate','$phoneNumber')");

$sql = $conn->query("UPDATE invoice SET status = 'Paid' WHERE phone = '$phoneNumber' order by id desc limit 1");
}

$conn = null;

echo "
<script>alert('payment has been successfully!')</script>
<script>window.location = 'index.php'</script>
";
