<?php
// Include the config file

use function PHPSTORM_META\type;

require_once "Config.php";

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true){
    header("location: ../Resources/php/server/Login.php");
    exit;
}

// Check if user is "admin"
if(!isset($_SESSION["role"]) || $_SESSION["role"] !== "jobseeker"){
    header("location: ../Resources/static/error/Error_Permission.html");
    exit;
}

$currUser = $_SESSION["username"];

// Handle POST from Search.php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
    
    if (!empty(trim($_POST["jobidEntry"]))) {
        $jobidParam = $_POST["jobidEntry"];
    }
    if (!empty(trim($_POST["jobseekerEntry"]))) {
        $jobseekerParam = $_POST["jobseekerEntry"];
    }
    if (!empty(trim($_POST["desiredSalaryEntry"]))) {
        $desiredSalaryParam = $_POST["desiredSalaryEntry"];
    }
    if (!empty(trim($_POST["desiredStartEntry"]))) {
        $desiredStartParam = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["desiredStartEntry"])));
    }

    $sql = "INSERT INTO applied_for (jobid, jobseeker, desired_salary, desired_start_date) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $jobidParam, $jobseekerParam, $desiredSalaryParam, $desiredStartParam);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../../../Pages/Search.php");
        }
    }

}

?>