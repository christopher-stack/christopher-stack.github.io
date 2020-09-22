<?php
/* Input Data Credentials relative to the site */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'job_portal');
 
/* Attempt to connect to MySQL database with supplied info */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection, and throw error if unable to connect
if($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>