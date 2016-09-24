# tmi-server-monitor
Very Basic Client/Server based project to monitor a website. Client can updated to monitor more than Database (as of now)
### ### ### Uses:
It uses CURL & PHPMailer (embeded)https://github.com/PHPMailer/PHPMailer
#Server Setting
Update following variables with the respective values:
````
$server = "<server name>"; // Used in emails too
$file = "<link to the client file on server>"; // complete link like http://server.com/tmi-mon.php
// As per PHP Mailer 
$smtp = array(	"host" 			=> 	'<SMTP host>',
		"port"				=> 	'<SMTP PORT>',
		"secure"			=> 	'tls', // Or ssl
		"auth"			=>	true, /// False if not rehttps://github.com/PHPMailer/PHPMailerquired
		"user"			=>	'<smtp user>',
		"pass"			=> 	'<password>',
		"fromEmail"	=> "<From email>",
		"toEmail"		=>	"<notify to>")		;
````

#Client Settings
Update database setting
````
    $dbhost = "<host>";
    $dbuser = "<username>";
    $dbpasswd = "<password>";
    $dbdatabase = "<database name>" ;`
````

# Setup Corn on the server with command 
````
<path to PHP>/php <dir path of file>/tmi-mon-cli.php
````
