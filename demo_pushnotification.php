
<?php

/*$deviceToken = 'c45493549a4d7e091f966d3812f595b6eaa273ca4a37fd8d9421bc8edf1a8434';

// My private key's passphrase here:
$passphrase = 'Lbim2201';

// My alert message here:
$message = 'New Push Notification!';

//badge
$badge = 1;

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushcert.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
    'ssl://gateway.sandbox.push.apple.com:2195', $err,
    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
    'alert' => $message,
    'badge' => $badge,
    'sound' => 'newMessage.wav'
);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
    echo 'Error, notification not sent' . PHP_EOL;
else
    echo 'notification sent!' . PHP_EOL;

// Close the connection to the server
fclose($fp);
*/
?>


 <?php
        
        $apnsServer = 'ssl://gateway.sandbox.push.apple.com:2195';
        
        $privateKeyPassword = 'Lbim2201';
        
        $message = 'Push Notifications';
        
        $deviceToken = 'c45493549a4d7e091f966d3812f595b6eaa273ca4a37fd8d9421bc8edf1a8434';
        
        $pushCertAndKeyPemFile = 'pushcert.pem';
        $stream = stream_context_create();
        stream_context_set_option($stream,'ssl','passphrase', $privateKeyPassword);
        stream_context_set_option($stream,'ssl','local_cert', $pushCertAndKeyPemFile);

        $connectionTimeout = 20;
        $connectionType = STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT;
        $connection = stream_socket_client($apnsServer, $errorNumber, $errorString, $connectionTimeout, $connectionType, $stream);
        if (!$connection){
        	echo "Failed to connect to the APNS server. Error no = $errorNumber<br/>";
        	exit;
        } else {
        	echo "Successfully connected to the APNS. Processing...</br>";
        }
        $messageBody['aps'] = array(
        	'alert' => $message,
        	'sound' => 'default',
        	'badge' => 2,
        );
        $payload = json_encode($messageBody);
        $notification = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) .$payload;
        $wroteSuccessfully = fwrite($connection, $notification, strlen($notification));
        if (!$wroteSuccessfully){
        	echo "Could not send the message<br/>";
        }
        else {
	        echo "Successfully sent the message.b<br/>";
        }
        fclose($connection);

  ?>
