<?php
require('Twilio/src/Twilio/autoload.php');

use Twilio\Rest\Client;

function sendsms($number,$name,$otp){
    
$sid = "AC70c2a7d7b28196a45d548c78eb51c91f";
$token = "0accab90974385fd6d7c72805c5df7cd";
$client = new Client($sid, $token);

// Specify the phone numbers in [E.164 format](https://www.twilio.com/docs/glossary/what-e164) (e.g., +16175551212)
// This parameter determines the destination phone number for your SMS message. Format this number with a '+' and a country code
$phoneNumber = "+91$number";

// This must be a Twilio phone number that you own, formatted with a '+' and country code
$twilioPurchasedNumber = "+13612669207";

// Send a text message
$message = $client->messages->create(
    $phoneNumber,
    [
        'from' => $twilioPurchasedNumber,
        'body' => "Hey $name! OTP for your request of forget password is : $otp"
    ]
);
// print("Message sent successfully with sid = " . $message->sid ."\n\n");

// Print the last 10 messages
// $messageList = $client->messages->read([],10);
// foreach ($messageList as $msg) {
//     print("ID:: ". $msg->sid . " | " . "From:: " . $msg->from . " | " . "TO:: " . $msg->to . " | "  .  " Status:: " . $msg->status . " | " . " Body:: ". $msg->body ."\n");
// }
}