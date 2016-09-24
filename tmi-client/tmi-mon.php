<?php

/***
 * @author Hardeep Singh (info@trickmyidea.com) Sep -2016
 * Script for monitoring the server using client server model.
 * This is client that tests the service on the server.
 * Add service as a function and add it to check array (line: 40)
 */

    /// Database
    $dbhost = "<host>";
    $dbuser = "<username>";
    $dbpasswd = "<password>";
    $dbdatabase = "<database name>" ;


function testDatabase(){

    global $dbhost, $dbuser, $dbpasswd ,$dbdatabase  ;

    $mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbdatabase);

    $return = array();

    /* check connection */
    if (mysqli_connect_errno()) {
        $return['status'] = "1";
        $return['error']  ="Unable to connect to database::" . $mysqli->connect_error;
    }

    $mysqli->close();

    if(!isset($return['status'] ))
        $return['status'] = "0";

    return $return;
}


$check = array("DB" => "testDatabase");
$return = array();
foreach ($check as $service => $func){
    $return[$service] = call_user_func($func);
}
echo json_encode($return);
