<?php
// Include the config file

use function PHPSTORM_META\type;

require_once "Config.php";

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true){
    header("location: ./Login.php");
    exit;
}

// Check if user is "admin"
if(!isset($_SESSION["role"]) || $_SESSION["role"] !== "jobseeker"){
    header("location: ../../static/error/Error_Permission.html");
    exit;
}

// Define variables and initialize with empty values
// === USER (jobseeker) VARIABLES
$currUser = "";
$currUserFname = $newUserFname = $userFname_err = "";
$currUserLname = $newUserLname = $userLname_err = "";
$currUserEmail = $newUserEmail = $userEmail_err = "";
$currUserPhone = $newUserPhone = $userPhone_err = "";
$currUserDob = $newUserDob = $userDob_err = "";
$currUserStreet = $newUserStreet = $userStreet_err = "";
$currUserCity = $newUserCity = $userCity_err = "";
$currUserState = $newUserState = $userState_err = "";
$currUserPostal = $newUserPostal = $userPostal_err = "";
$currUserCountry = $newUserCountry = $userCountry_err = "";
// === JOB HIST VARIABLES
$jobHistCount = 0;
$currJobHistory = $newJobHistory = [];
$jobHistCompany = $newJobHistCompany = $jobHistCompany_err = "";
$jobHistStart = $newJobHistStart = $jobHistStart_err = "";
$jobHistEnd = $newJobHistEnd = $jobHistEnd_err = "";
$jobHistPos = $newJobHistPos = $jobHistPos_err = "";
$jobHistSupFname = $newJobHistSupFname = $jobHistSupFname_err = "";
$jobHistSupLname = $newJobHistSupLname = $jobHistSupLname_err = "";
$jobHistSupEmail = $newJobHistSupEmail = $jobHistSupEmail_err = "";
$jobHistSupPhone = $newJobHistSupPhone = $jobHistSupPhone_err = "";
$jobHistMarkup = "";
// === EDU HISTORY & EDU FACILITIES VARIABLES
$eduHistCount = 0;
$currEduHistory = $newEduHistory = [];
$eduHistAreaOfStudy = $eduHistAreaOfStudy_err = "";
$eduHistDegree = $eduHistDegree_err = "";
$eduHistStart = $eduHistStart_err = "";
$eduHistEnd = $eduHistEnd_err = "";
$eduHistGpa = $eduHistGpa_err = "";
$currEduHistFacility = $newEduHistFacility = [];
$eduHistFacilityName = $eduHistFacilityName_err = "";
$eduHistFacilityCity = $eduHistFacilityCity_err = "";
$eduHistFacilityState = $eduHistFacilityState_err = "";
$eduHistFacilityPostal = $eduHistFacilityPostal_err = "";
$eduHistFacilityType = $eduHistFacilityType_err = "";

$currUser = $_SESSION["username"];
// Fetch jobseeker personal details
$sql = "SELECT fname, lname, email, phone, dob, street, city, state, postal, country FROM people WHERE username=? limit 1";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $currUser);
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        $userDetails = mysqli_fetch_assoc($res);
        if ($userDetails) {
            $currUserFname = $userDetails["fname"];
            $currUserLname = $userDetails["lname"];
            $currUserEmail = $userDetails["email"];
            $currUserPhone = $userDetails["phone"];
            $currUserDob = $userDetails["dob"];
            $currUserStreet = $userDetails["street"];
            $currUserCity = $userDetails["city"];
            $currUserState = $userDetails["state"];
            $currUserPostal = $userDetails["postal"];
            $currUserCountry = $userDetails["country"];
        }
    }
    mysqli_stmt_close($stmt);
}

// Fetch current user's job history
$sql = "SELECT * FROM `job_history` WHERE jobseeker=?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $currUser);
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        $numJobs = 0;
        while ($jobAssocArray = mysqli_fetch_assoc($res)) {
            $currJobHistory[$numJobs++] = $jobAssocArray;
        }
    }
    mysqli_stmt_close($stmt);
}

// Fetch current user's education history
$sql = "SELECT jobseeker, areaofstudy, degree, start_date, end_date, gpa, name, city, state, postal, type
    FROM education
    INNER JOIN education_facilities
    ON education.ed_facility_name=education_facilities.name AND education.ed_facility_city=education_facilities.city
    WHERE education.jobseeker=?
";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $currUser);
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        $numEdu = 0;
        while ($eduAssocArray = mysqli_fetch_assoc($res)) {
            $currEduHistory[$numEdu++] = $eduAssocArray;
        }
    }
    mysqli_stmt_close($stmt);
}

// Processing form data when form is submitted and contains data
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
    
    // PERSONAL DETAILS VALIDATION
    // Validate First name
    if (empty(trim($_POST["userFnameEntry"]))) {
        $userFname_err = "Please enter first name.";
    } else {
        $newUserFname = trim($_POST["userFnameEntry"]);
    }
    // Validate Last name
    if (empty(trim($_POST["userLnameEntry"]))) {
        $userLname_err = "Please enter last name.";
    } else {
        $newUserLname = trim($_POST["userLnameEntry"]);
    }
    // Validate Email
    if (empty(trim($_POST["userEmailEntry"]))) {
        $userEmail_err = "Please enter email.";
    } else {
        $newUserEmail = trim($_POST["userEmailEntry"]);
    }
    // Validate Phone
    if (empty(trim($_POST["userPhoneEntry"]))) {
        $userPhone_err = "Please enter phone number.";
    } else {
        $newUserPhone = trim($_POST["userPhoneEntry"]);
    }
    // Validate Dob
    if (empty(trim($_POST["userDobEntry"]))) {
        $userDob_err = "Please enter date of birth.";
    } else {
        $newUserDob = trim($_POST["userDobEntry"]);
    }
    // Validate Street
    if (empty(trim($_POST["userStreetEntry"]))) {
        $userStreet_err = "Please enter street address.";
    } else {
        $newUserStreet = trim($_POST["userStreetEntry"]);
    }
    // Validate City
    if (empty(trim($_POST["userCityEntry"]))) {
        $userCity_err = "Please enter city address.";
    } else {
        $newUserCity = trim($_POST["userCityEntry"]);
    }
    // Validate State
    if (empty(trim($_POST["userStateEntry"]))) {
        $userState_err = "Please enter state.";
    } else {
        $newUserState = trim($_POST["userStateEntry"]);
    }
    // Validate Postal
    if (empty(trim($_POST["userPostalEntry"]))) {
        $userPostal_err = "Please enter zipcode.";
    } else {
        $newUserPostal = trim($_POST["userPostalEntry"]);
    }
    // Validate Country
    if (empty(trim($_POST["userCountryEntry"]))) {
        $userCountry_err = "Please enter country.";
    } else {
        $newUserCountry = trim($_POST["userCountryEntry"]);
    }

    // UPDATE PERSONAL DETAILS
    if ($currUserFname != $newUserFname ||
    $currUserLname != $newUserLname ||
    $currUserEmail != $newUserEmail ||
    $currUserPhone != $newUserPhone ||
    $currUserDob != $newUserDob ||
    $currUserStreet != $newUserStreet ||
    $currUserCity != $newUserCity ||
    $currUserState != $newUserState ||
    $currUserPostal != $newUserPostal ||
    $currUserCountry != $newUserCountry) {
        $sql = "UPDATE people SET fname=?, lname=?, email=?, phone=?, dob=?, street=?, city=?, state=?, postal=?, country=? WHERE username=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssssssssss", $newUserFname, $newUserLname, $newUserEmail, $newUserPhone, $newUserDob, $newUserStreet, $newUserCity, $newUserState, $newUserPostal, $newUserCountry, $currUser);
            if (mysqli_stmt_execute($stmt)) {
                echo "SUCCESS!";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta char="UTF-8">
	<title> TeamCDA Website - Edit Profile </title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
	<!-- Style Scripts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat|Roboto">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
	<link rel="stylesheet" href="../../static/css/style.css" type="text/css">
	<!-- End of Style Scripts -->
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/ccfb95e64e.js" crossorigin="anonymous"></script>
	<script src="../../static/js/script.js"></script>
	<!-- Favicon Code - Include ALL Below -->
	<link rel="shortcut icon" href="../../static/icon/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" sizes="180x180" href="../../static/icon/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="../../static/icon/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="../../static/icon/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="../../static/icon/manifest.json">
	<link rel="mask-icon" href="../../static/icon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="msapplication-TileImage" content="../../static/icon/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<!-- END FAVICON CODE -->
</head>
<body>
    <!-- NAVIGATION HEADER START -->
	<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle Navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="#">TeamCDA</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="../index.php"><i class="fas fa-home"></i> Home </a>
				</li>
				<li class="nav-item disabled">
					<a class="nav-link" href="#"><i class="fas fa-exclamation-circle"></i> About </a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> Apps </a>
					<div class="dropdown-menu" aria-labelledby="Preview">
						<a class="dropdown-item" href="#"> APIHub - Pokemon GO </a>
						<a class="dropdown-item" href="#"> NecroEase - Pokemon GO </a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-gamepad"></i> Games </a>
					<div class="dropdown-menu" aria-labelledby="Preview">
						<a class="dropdown-item" href="#">Bandai Namco - Pac-Man</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-download"></i> Downloads </a>
					<div class="dropdown-menu" aria-labelledby="Preview">
						<a class="dropdown-item disabled" href="#"> All Downloads </a>
					</div>
				</li>
			</ul>
			<ul class="navbar-nav">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION["firstName"] . " " . $_SESSION["lastName"]); ?></a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
						<?php
							// Add appropriate links based on role
							if($_SESSION["role"] == "jobseeker"){
								echo "<a";
								echo " class=\"dropdown-item\"";
                                echo " href=\"../Pages/Project_Form.php\">Profile</a>";
                                echo "<a";
                                echo " class=\"dropdown-item\"";
                                echo " href=\"\">Edit Profile</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Resources/static/error/404.html\">Application history</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Resources/static/error/404.html\">Search jobs</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Resources/static/error/404.html\">View jobs by category</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Resources/static/error/404.html\">View jobs by company</a>";
							} else if($_SESSION["role"] == "employer"){
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"\">Company profile</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Resources/static/error/404.html\">Post new position</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Resources/static/error/404.html\">Edit positions</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Resources/static/error/404.html\">Review applicants</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Resources/static/error/404.html\">Search applicants</a>";
							}
						?>
						<a class="dropdown-item" href="./Reset_Password.php">Reset password</a>
						<a class="dropdown-item" href="./Logout.php">Sign out</a>
					</div>
				</li>
			</ul>
		</div>
	</nav>
	<!-- NAVIGATION HEADER END -->
    <div class="registerEmp-wrapper">
        <h2>Edit Profile</h2>
        <p>Edit your profile below.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- PERSONAL DETAILS -->
            <span><p class="form-subheader">PERSONAL DETAILS</p></span>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($userFname_err)) ? 'has-error' : ''; ?>">
                    <label for="userFnameEntry">First Name</label>
                    <input type="text" class="form-control" name="userFnameEntry" required="required" data-error="This field is required." placeholder="Tom" value="<?php echo $currUserFname; ?>">
                    <span class="help-block"><?php echo $userFname_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($userLname_err)) ? 'has-error' : ''; ?>">
                    <label for="userLnameEntry">Last Name</label>
                    <input type="text" class="form-control" name="userLnameEntry" required="required" data-error="This field is required." placeholder="Smith" value="<?php echo $currUserLname; ?>">
                    <span class="help-block"><?php echo $userLname_err; ?></span>
                </div>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($userEmail_err)) ? 'has-error' : ''; ?>">
                    <label for="userEmailEntry">Email Address</label>
                    <input type="email" class="form-control" name="userEmailEntry" required="required" data-error="This field is required." placeholder="example@yahoo.com" value="<?php echo $currUserEmail; ?>">
                    <span class="help-block"><?php echo $userEmail_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($userPhone_err)) ? 'has-error' : ''; ?>">
                    <label for="userPhoneEntry">Phone Number</label>
                    <input type="tel" class="form-control" name="userPhoneEntry" required="required" data-error="This field is required." placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}" value="<?php echo $currUserPhone; ?>">
                    <span class="help-block"><?php echo $userPhone_err; ?></span>
                </div>
            </div>
            <div class="form-group row mb-4 <?php echo (!empty($userDob_err)) ? 'has-error' : ''; ?>">
                <label for="userDobEntry">Date of Birth</label>
                <div class="input-group date" data-date-format="dd.mm.yyyy">
                    <input type="date" class="form-control" name="userDobEntry" required="required" data-error="This field is required." placeholder="dd.mm.yyyy" value="<?php echo $currUserDob; ?>">
                </div>
                <span class="help-block"><?php echo $userDob_err; ?></span>
            </div>
            <div class="form-group mb-2 <?php echo (!empty($userStreet_err)) ? 'has-error' : ''; ?>">
                <label for="userStreetEntry">Street</label>
                <input type="text" class="form-control" name="userStreetEntry" required="required" data-error="This field is required." placeholder="1234 Main St" value="<?php echo $currUserStreet; ?>">
                <span class="help-block"><?php echo $userStreet_err; ?></span>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($userCity_err)) ? 'has-error' : ''; ?>">
                    <label for="userCityEntry">City</label>
                    <input type="text" class="form-control" name="userCityEntry" required="required" data-error="This field is required." value="<?php echo $currUserCity; ?>">
                    <span class="help-block"><?php echo $userCity_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($userState_err)) ? 'has-error' : ''; ?>">
                    <label for="userStateEntry">State</label>
                    <input type="text" class="form-control" name="userStateEntry" required="required" data-error="This field is required." value="<?php echo $currUserState; ?>">
                    <span class="help-block"><?php echo $userState_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($userPostal_err)) ? 'has-error' : ''; ?>">
                    <label for="userPostalEntry">Zip</label>
                    <input type="text" class="form-control" name="userPostalEntry" required="required" data-error="This field is required." value="<?php echo $currUserPostal; ?>">
                    <span class="help-block"><?php echo $userPostal_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($userCountry_err)) ? 'has-error' : ''; ?>">
                    <label for="userCountryEntry">Country</label>
                    <input type="text" class="form-control" name="userCountryEntry" required="required" data-error="This field is required." value="<?php echo $currUserCountry; ?>">
                    <span class="help-block"><?php echo $userCountry_err; ?></span>
                </div>
            </div>
            <!-- JOB HISTORY -->
            <span><p class="form-subheader">JOB HISTORY</p></span>
            <?php
            foreach ($currJobHistory as $jobHistory) {
                $jobHistCount++;
                $jobHistCompany = $jobHistory['company'];
                $jobHistStart = $jobHistory['start_date'];
                $jobHistEnd = $jobHistory['end_date'];
                $jobHistPos = $jobHistory['position'];
                $jobHistSupFname = $jobHistory['supervisor_fname'];
                $jobHistSupLname = $jobHistory['supervisor_lname'];
                $jobHistSupEmail = $jobHistory['supervisor_email'];
                $jobHistSupPhone = $jobHistory['supervisor_phone'];

                $jobHistMarkup = 
                "
                <span><p class=\"form-subheader2\">[ JOB #$jobHistCount ]</p></span>
                <div class=\"form-group mb-2 <?php echo (!empty($jobHistCompany_err)) ? 'has-error' : ''; ?>\">
                    <label for=\"jobHistCompanyEntry$jobHistCount\">Company</label>
                    <input type=\"text\" class=\"form-control\" name=\"jobHistCompanyEntry$jobHistCount\" required=\"required\" data-error=\"This field is required.\" value=\"$jobHistCompany\">
                    <span class=\"help-block\">$jobHistCompany_err</span>
                </div>
                <div class=\"input-group mb-4\">
                    <div class=\"form-group mr-3 col-md-5 <?php echo (!empty($jobHistStart_err)) ? 'has-error' : ''; ?>\">
                        <label for=\"jobHistStartEntry$jobHistCount\">Start Date</label>
                        <div class=\"input-group date\" data-date-format=\"dd.mm.yyyy\">
                            <input type=\"date\" class=\"form-control\" name=\"jobHistStartEntry$jobHistCount\" required=\"required\" data-error=\"This field is required.\" placeholder=\"dd.mm.yyyy\" value=\"$jobHistStart\">
                        </div>
                        <span class=\"help-block\">$jobHistStart_err</span>
                    </div>
                    <div class=\"form-group mr-3 col-md-5 <?php echo (!empty($jobHistEnd_err)) ? 'has-error' : ''; ?>\">
                        <label for=\"jobHistEndEntry$jobHistCount\">End Date</label>
                        <div class=\"input-group date\" data-date-format=\"dd.mm.yyyy\">
                            <input type=\"date\" class=\"form-control\" name=\"jobHistEndEntry$jobHistCount\" required=\"required\" data-error=\"This field is required.\" placeholder=\"dd.mm.yyyy\" value=\"$jobHistEnd\">
                        </div>
                        <span class=\"help-block\">$jobHistEnd_err</span>
                    </div>
                </div>
                <div class=\"form-group mb-2 <?php echo (!empty($jobHistPos_err)) ? 'has-error' : ''; ?>\">
                    <label for=\"jobHistPosEntry$jobHistCount\">Position</label>
                    <input type=\"text\" class=\"form-control\" name=\"jobHistPosEntry$jobHistCount\" required=\"required\" data-error=\"This field is required.\" value=\"$jobHistPos\">
                    <span class=\"help-block\">$jobHistPos_err</span>
                </div>
                <div class=\"input-group mb-4\">
                    <div class=\"form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupFname_err)) ? 'has-error' : ''; ?>\">
                        <label for=\"jobHistSupFnameEntry$jobHistCount\">Supervisor's First Name</label>
                        <input type=\"text\" class=\"form-control\" name=\"jobHistSupFnameEntry$jobHistCount\" required=\"required\" data-error=\"This field is required.\" value=\"$jobHistSupFname\">
                        <span class=\"help-block\">$jobHistSupFname_err</span>
                    </div>
                    <div class=\"form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupLname_err)) ? 'has-error' : ''; ?>\">
                        <label for=\"jobHistSupLnameEntry$jobHistCount\">Supervisor's Last Name</label>
                        <input type=\"text\" class=\"form-control\" name=\"jobHistSupLnameEntry$jobHistCount\" required=\"required\" data-error=\"This field is required.\" value=\"$jobHistSupLname\">
                        <span class=\"help-block\">$jobHistSupLname_err</span>
                    </div>
                </div>
                <div class=\"input-group mb-4\">
                    <div class=\"form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupEmail_err)) ? 'has-error' : ''; ?>\">
                        <label for=\"jobHistSupEmailEntry$jobHistCount\">Supervisor's Email</label>
                        <input type=\"email\" class=\"form-control\" name=\"jobHistSupEmailEntry$jobHistCount\" placeholder=\"example@yahoo.com\" required=\"required\" data-error=\"This field is required.\" value=\"$jobHistSupEmail\">
                        <span class=\"help-block\">$jobHistSupEmail_err</span>
                    </div>
                    <div class=\"form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupPhone_err)) ? 'has-error' : ''; ?>\">
                        <label for=\"jobHistSupEmailEntry$jobHistCount\">Supervisor's Phone</label>
                        <input type=\"tel\" class=\"form-control\" name=\"jobHistSupPhoneEntry$jobHistCount\" placeholder=\"xxx-xxx-xxxx\" pattern=\"\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}\" required=\"required\" data-error=\"This field is required.\" value=\"$jobHistSupPhone\">
                        <span class=\"help-block\">$jobHistSupPhone_err</span>
                    </div>
                </div>
                ";

                echo $jobHistMarkup;
            }
            ?>
            <!-- EDUCATION HISTORY -->
            <span><p class="form-subheader">EDUCATION HISTORY</p></span>
            <?php
            foreach ($currEduHistory as $eduHistory) {
                $eduHistCount++;
                $eduHistAreaOfStudy = $eduHistory['areaofstudy'];
                $eduHistDegree = $eduHistory['degree'];
                $eduHistStart = $eduHistory['start_date'];
                $eduHistEnd = $eduHistory['end_date'];
                $eduHistGpa = $eduHistory['gpa'];
                $eduHistFacilityName = $eduHistory['name'];
                $eduHistFacilityCity = $eduHistory['city'];
                $eduHistFacilityState = $eduHistory['state'];
                $eduHistFacilityPostal = $eduHistory['postal'];
                $eduHistFacilityType = $eduHistory['type'];
            ?>
            <span><p class="form-subheader2">[ EDU #<?php echo $eduHistCount; ?> ]</p></span>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($eduHistAreaOfStudy_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistAreaOfStudyEntry">Area Of Study</label>
                    <input type="text" class="form-control" name="eduHistAreaOfStudyEntry" required data-error="This field is required." value="<?php echo $eduHistAreaOfStudy; ?>">
                    <span class="help-block"><?php echo $eduHistAreaOfStudy_err; ?></span>
                </div>
                <div class="form-group row mr-3 col-md-5 <?php echo (!empty($eduHistDegree_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistDegreeEntry">Degree</label>
                    <div class="input-group mb-2">
                        <select class="form-select" name="eduHistDegreeEntry" required data-error="This field is required.">
                            <option selected disabled value="">Please select degree</option>
                            <option value="High School" <?php if($eduHistDegree == "High School") { echo "selected"; } ?>>High School</option>
                            <option value="Bachelors" <?php if($eduHistDegree == "Bachelors") { echo "selected"; } ?>>Bachelors</option>
                            <option value="Associates" <?php if($eduHistDegree == "Associates") { echo "selected"; } ?>>Associates</option>
                            <option value="Masters" <?php if($eduHistDegree == "Masters") { echo "selected"; } ?>>Masters</option>
                            <option value="Doctorate" <?php if($eduHistDegree == "Doctorate") { echo "selected"; } ?>>Doctorate</option>
                        </select>
                    </div>
                    <span class="help-block"><?php echo $eduHistDegree_err; ?></span>
                </div>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($eduHistStart_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistStartEntry">Start Date</label>
                    <div class="input-group date" data-date-format="dd.mm.yyyy">
                        <input type="date" class="form-control" name="eduHistStartEntry" placeholder="dd.mm.yyyy" required data-error="This field is required." value="<?php echo $eduHistStart; ?>">
                    </div>
                    <span class="help-block"><?php echo $eduHistStart_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($eduHistEnd_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistEndEntry">End Date</label>
                    <div class="input-group date" data-date-format="dd.mm.yyyy">
                        <input type="date" class="form-control" name="eduHistEndEntry" placeholder="dd.mm.yyyy" required data-error="This field is required." value="<?php echo $eduHistEnd; ?>">
                    </div>
                    <span class="help-block"><?php echo $eduHistEnd_err; ?></span>
                </div>
            </div>
            <div class="input-group mb-4">
                <div class="form-group row mr-3 col-md-5 <?php echo (!empty($eduHistFacilityType_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistFacilityTypeEntry">Institution Type</label>
                    <div class="input-group mb-2">
                        <select class="form-select" name="eduHistFacilityTypeEntry" required data-error="This field is required.">
                            <option selected disabled value="">Please select institution type</option>
                            <option value="University" <?php if($eduHistFacilityType == "University") { echo "selected"; } ?>>University</option>
                            <option value="College" <?php if($eduHistFacilityType == "College") { echo "selected"; } ?>>College</option>
                            <option value="Technical School" <?php if($eduHistFacilityType == "Technical School") { echo "selected"; } ?>>Technical School</option>
                            <option value="High School" <?php if($eduHistFacilityType == "High School") { echo "selected"; } ?>>High School</option>
                            <option value="Grade School" <?php if($eduHistFacilityType == "Grade School") { echo "selected"; } ?>>Grade School</option>
                        </select>
                    </div>
                    <span class="help-block"><?php echo $eduHistFacilityType_err; ?></span>
                </div>
                <div class="form-group row mr-3 col-md-5 mb-2 <?php echo (!empty($eduHistGpa_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistGpaEntry">GPA</label>
                    <input type="number" class="form-control" name="eduHistGpaEntry" required data-error="This field is required." min="0" max="4" step=".001" value="<?php echo $eduHistGpa; ?>">
                    <span class="help-block"><?php echo $eduHistGpa_err; ?></span>
                </div>
            </div>
            <div class="form-group mb-4 <?php echo (!empty($eduHistFacilityName_err)) ? 'has-error' : ''; ?>">
                <label for="eduHistFacilityNameEntry">Institution Name</label>
                <input type="text" class="form-control" name="eduHistFacilityNameEntry" required data-error="This field is required." value="<?php echo $eduHistFacilityName; ?>">
                <span class="help-block"><?php echo $eduHistFacilityName_err; ?></span>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($eduHistFacilityCity_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistFacilityCityEntry">City</label>
                    <input type="text" class="form-control" name="eduHistFacilityCityEntry" required data-error="This field is required." value="<?php echo $eduHistFacilityCity; ?>">
                    <span class="help-block"><?php echo $eduHistFacilityCity_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($eduHistFacilityState_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistFacilityStateEntry">State</label>
                    <input type="text" class="form-control" name="eduHistFacilityStateEntry" required data-error="This field is required." value="<?php echo $eduHistFacilityState; ?>">
                    <span class="help-block"><?php echo $eduHistFacilityState_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($eduHistFacilityPostal_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistFacilityPostalEntry">Zip</label>
                    <input type="text" class="form-control" name="eduHistFacilityPostalEntry" required data-error="This field is required." value="<?php echo $eduHistFacilityPostal; ?>">
                    <span class="help-block"><?php echo $eduHistFacilityPostal_err; ?></span>
                </div>
            </div>
            <?php
            }
            ?>
            <!-- SUBMIT BUTTON -->
            <div class="bottom-padding form-group">
                <input type="submit" class="btn btn-primary float-right ml-2" value="Submit">
                <input type="reset" class="btn btn-secondary float-right" value="Reset">
            </div>
            <div class="btm-space"></div>
        </form>
        <!-- NAVIGATION FOOTER START -->
        <nav class="navbar fixed-bottom navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-btn btn-primary btn" href="https://twitter.com/CDA_Gamers"><i class="fab fa-twitter"></i> Twitter </a>
                <a class="navbar-btn btn-primary btn" href="https://t.co/gvolbJr5ng"><i class="fab fa-youtube"></i> YouTube </a>
            </div>
        </nav>
        <!-- NAVIGATION FOOTER END -->
    </div>    
</body>
</html>