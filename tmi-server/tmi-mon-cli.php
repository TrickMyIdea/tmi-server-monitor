<?php

/***
 * @author Hardeep Singh (info@trickmyidea.com) Sep -2016
 * Script for monitoring the server using client server model.
 * This is server which gets the status from the client and incase of error, sends an email (Line:65 onwards)
 */

error_reporting(0);
ini_set('display_errors', 0);

$server = "<server name>"; // Used in emails too
$file = "<link to the client file on server>"; // complete link like http://server.com/tmi-mon.php
$serviceDown = false;

// As per PHP Mailer
$smtp = array(
			"host" 			=> 	'<SMTP host>',
			"port"				=> 	'<SMTP PORT>',
			"secure"			=> 	'tls', // Or ssl
			"auth"			=>	true, /// False if not required
			"user"			=>	'<smtp user>',
			"pass"			=> 	'<password>',
			"fromEmail"	=> "<From email>",
			"toEmail"		=>	"<notify to>")		;

$emailText = "<h2>$server status for services:</h2><br/>";

$options[CURLOPT_FRESH_CONNECT] = true;
$options[CURLOPT_FOLLOWLOCATION] = false;
$options[CURLOPT_FAILONERROR] = true;
$options[CURLOPT_RETURNTRANSFER] = true; // curl_exec will not return true if you use this, it will instead return the request body
$options[CURLOPT_TIMEOUT] = 10;

$curl = curl_init($file);

curl_setopt_array($curl, $options);

$return = curl_exec($curl);
if(curl_errno($curl)){
    $emailText .= "Web server: <span style='color:red'>Down: Not Reachable</span> <br>";
    curl_close($curl);
    $serviceDown = true;
}else{
    $services = json_decode($return);
    $emailText .= "Web server: <span style='color:green'>Running</span> <br>";
    foreach($services as $serviceName => $service){
        if($service->status == 0 ){
            $emailText .= $serviceName . ": <span style='color:green'>Running</span> <br>";
        }else{
            $serviceDown = true;
            $emailText .= $serviceName . ": <span style='color:red'>ERROR:: " .  $service->error . "</span><br>";
        }
    }
    curl_close($curl);
}

if($serviceDown){
// 	echo  $emailText . PHP_EOL . PHP_EOL ;

	require "class.smtp.php";
	require "class.phpmailer.php";

	$mail = new PHPMailer;

	$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->SMTPOptions = array(
	    'ssl' => array(
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	        'allow_self_signed' => true
	    )
	);

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = $smtp['host'];  // Specify main and backup SMTP servers
	$mail->Port = $smtp['port'];                                    // TCP port to connect to
	$mail->SMTPSecure = $smtp['secure'];                            // Enable TLS encryption, `ssl` also accepted
	$mail->SMTPAuth = $smtp['auth'];                               // Enable SMTP authentication
	$mail->Username = $smtp['user'];                 // SMTP username
	$mail->Password = $smtp['pass'];                           // SMTP password

	$mail->setFrom($smtp['fromEmail'], 'Server Monitor');
	$mail->addAddress($smtp['toEmail'], 'Server Administrator');     // Add a recipient
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = "Server Monitor:: $server";
	$mail->Body    = $emailText;

	if(!$mail->send()) {
	    echo 'Message could not be sent:::' . $emailText;
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	}
}


