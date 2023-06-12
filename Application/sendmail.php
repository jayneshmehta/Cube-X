<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
// require 'vendor/autoload.php';

function genetateotp()
{
  $otp = null;
  for ($i = 1; $i <= 6; $i++) {
    $otp .= rand(0, 9);
  }
  return $otp;
}

//Create an instance; passing `true` enables exceptions
function sendmail($id,$otp,$name){

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    // $mail->debug(0);
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'mail.mailtest.radixweb.net';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'testphp@mailtest.radixweb.net';        //SMTP username
    $mail->Password   = 'Radix@web#8';                          //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('testphp@mailtest.radixweb.net', 'RadixWeb ');
    $mail->addAddress($id, 'jaynesh mehta');     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'OTP for Email verification';
    $mail->Body    = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
    <div style="margin:50px auto;width:70%;padding:20px 0">
      <div style="border-bottom:1px solid #eee">
        <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Cube-X</a>
      </div>
      <p style="font-size:1.1em">Hi,'.$name.'</p>
      <p>Thank you for choosing Cube-X. Use the following OTP to complete your Sign Up procedures. OTP is valid for 5 minutes</p>
      <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">'.$otp.'</h2>
      <p style="font-size:0.9em;">Regards,<br />Cube-X</p>
      <hr style="border:none;border-top:1px solid #eee" />
      <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
        <p>Cube-X Inc</p>
        <p>Ekyarth Behind Nirma University</p>
        <p>Ahmedabad</p>
      </div>
    </div>
  </div>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    $msg = 'Message has been sent';
} catch (Exception $e) {
    $msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
    return $msg;
}
?>