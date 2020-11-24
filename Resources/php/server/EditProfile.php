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
$eduHistAreaOfStudy = $newEduHistAreaOfStudy = $eduHistAreaOfStudy_err = "";
$eduHistDegree = $newEduHistDegree = $eduHistDegree_err = "";
$eduHistStart = $newEduHistStart =  $eduHistStart_err = "";
$eduHistEnd = $newEduHistEnd = $eduHistEnd_err = "";
$eduHistGpa = $newEduHistGpa = $eduHistGpa_err = "";
$currEduHistFacility = $newEduHistFacility = [];
$eduHistFacilityName = $newEduHistFacilityName = $eduHistFacilityName_err = "";
$eduHistFacilityCity = $newEduHistFacilityCity = $eduHistFacilityCity_err = "";
$eduHistFacilityState = $newEduHistFacilityState = $eduHistFacilityState_err = "";
$eduHistFacilityPostal = $newEduHistFacilityPostal = $eduHistFacilityPostal_err = "";
$eduHistFacilityType = $newEduHistFacilityType = $eduHistFacilityType_err = "";

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
$sql = "SELECT * FROM `job_history` WHERE jobseeker=? ORDER BY start_date DESC LIMIT 3";
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
                header("Location: ./EditProfile.php");
            }
            mysqli_stmt_close($stmt);
        }
    }

    // JOB HISTORY VALIDATION
    for ($i = 0; $i < 3; $i++) {
        // Validate company
        if (empty(trim($_POST["jobHistCompanyEntry".($i + 1)]))) {
            $jobHistCompany_err = "Please enter company.";
        } else {
            $newJobHistCompany = trim($_POST["jobHistCompanyEntry".($i + 1)]);
        }
        // Validate start date
        if (empty(trim($_POST["jobHistStartEntry".($i + 1)]))) {
            $jobHistStart_err = "Please enter start date.";
        } else {
            $newJobHistStart = trim($_POST["jobHistStartEntry".($i + 1)]);
        }
        // Validate end date
        if (empty(trim($_POST["jobHistEndEntry".($i + 1)]))) {
            $jobHistEnd_err = "Please enter end date.";
        } else {
            $newJobHistEnd = trim($_POST["jobHistEndEntry".($i + 1)]);
        }
        // Validate position
        if (empty(trim($_POST["jobHistPosEntry".($i + 1)]))) {
            $jobHistPos_err = "Please enter position.";
        } else {
            $newJobHistPos = trim($_POST["jobHistPosEntry".($i + 1)]);
        }
        // Validate sup fname
        if (empty(trim($_POST["jobHistSupFnameEntry".($i + 1)]))) {
            $jobHistSupFname_err = "Please enter supervisor's first name.";
        } else {
            $newJobHistSupFname = trim($_POST["jobHistSupFnameEntry".($i + 1)]);
        }
        // Validate sup lname
        if (empty(trim($_POST["jobHistSupLnameEntry".($i + 1)]))) {
            $jobHistSupLname_err = "Please enter supervisor's last name.";
        } else {
            $newJobHistSupLname = trim($_POST["jobHistSupLnameEntry".($i + 1)]);
        }
        // Validate sup email
        if (empty(trim($_POST["jobHistSupEmailEntry".($i + 1)]))) {
            $jobHistSupEmail_err = "Please enter supervisor's email.";
        } else {
            $newJobHistSupEmail = trim($_POST["jobHistSupEmailEntry".($i + 1)]);
        }
        // Validate sup phone
        if (empty(trim($_POST["jobHistSupPhoneEntry".($i + 1)]))) {
            $jobHistSupPhone_err = "Please enter supervisor's phone number.";
        } else {
            $newJobHistSupPhone = trim($_POST["jobHistSupPhoneEntry".($i + 1)]);
        }

        if (empty($currJobHistory)) {
            $sql = "INSERT INTO job_history (jobseeker, company, start_date, end_date, position, supervisor_fname, supervisor_lname, supervisor_email, supervisor_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssssssss", $currUser, $newJobHistCompany, $newJobHistStart, $newJobHistEnd, $newJobHistPos, $newJobHistSupFname, $newJobHistSupLname, $newJobHistSupEmail, $newJobHistSupPhone);
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ./EditProfile.php");
                }
                else {
                    echo "Something went wrong. Please try again.";
                }
            }
        } else {
            $currCompany = $currJobHistory[$i]["company"];
            $currStart = $currJobHistory[$i]["start_date"];
            $sql = "UPDATE job_history 
                SET company=?, start_date=?, end_date=?, position=?, supervisor_fname=?, supervisor_lname=?, supervisor_email=?, supervisor_phone=?
                WHERE jobseeker=? AND company=? AND start_date=?
            ";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssssssssss", $newJobHistCompany, $newJobHistStart, $newJobHistEnd, $newJobHistPos, $newJobHistSupFname, $newJobHistSupLname, $newJobHistSupEmail, $newJobHistSupPhone, $currUser, $currCompany, $currStart);
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ./EditProfile.php");
                }
            }
        }
    }

    // EDUCATION HISTORY VALIDATION
    if ($currEduHistory) { // if already have data (update)
        $numEdu = 0;
        foreach ($currEduHistory as $edu) {
            $numEdu++;
        }
        for ($i = 0; $i < $numEdu; $i++) {
            // Validate area of study
            if (empty(trim($_POST["eduHistAreaOfStudyEntry".($i + 1)]))) {
                $eduHistAreaOfStudy_err = "Please enter area of study.";
            } else {
                $newEduHistAreaOfStudy = trim($_POST["eduHistAreaOfStudyEntry".($i + 1)]);
            }
            // Validate degree
            if (empty(trim($_POST["eduHistDegreeEntry".($i + 1)]))) {
                $eduHistDegree_err = "Please select degree.";
            } else {
                $newEduHistDegree = trim($_POST["eduHistDegreeEntry".($i + 1)]);
            }
            // Validate start date
            if (empty(trim($_POST["eduHistStartEntry".($i + 1)]))) {
                $eduHistStart_err = "Please enter start date.";
            } else {
                $newEduHistStart = trim($_POST["eduHistStartEntry".($i + 1)]);
            }
            // Validate end date
            if (empty(trim($_POST["jobHistEndEntry".($i + 1)]))) {
                $eduHistEnd_err = "Please enter end date.";
            } else {
                $newEduHistEnd = trim($_POST["eduHistEndEntry".($i + 1)]);
            }
            // Validate gpa
            if (empty(trim($_POST["eduHistGpaEntry".($i + 1)]))) {
                $eduHistGpa_err = "Please enter GPA.";
            } else {
                $newEduHistGpa = trim($_POST["eduHistGpaEntry".($i + 1)]);
            }
            // Validate facility name
            if (empty(trim($_POST["eduHistFacilityNameEntry".($i + 1)]))) {
                $eduHistFacilityName_err = "Please enter name of institution.";
            } else {
                $newEduHistFacilityName = trim($_POST["eduHistFacilityNameEntry".($i + 1)]);
            }
            // Validate facility city
            if (empty(trim($_POST["eduHistFacilityCityEntry".($i + 1)]))) {
                $eduHistFacilityCity_err = "Please enter city.";
            } else {
                $newEduHistFacilityCity = trim($_POST["eduHistFacilityCityEntry".($i + 1)]);
            }
            // Validate facility state
            if (empty(trim($_POST["eduHistFacilityStateEntry".($i + 1)]))) {
                $eduHistFacilityState_err = "Please enter state.";
            } else {
                $newEduHistFacilityState = trim($_POST["eduHistFacilityStateEntry".($i + 1)]);
            }
            // Validate facility postal
            if (empty(trim($_POST["eduHistFacilityPostalEntry".($i + 1)]))) {
                $eduHistFacilityPostal_err = "Please enter zipcode.";
            } else {
                $newEduHistFacilityPostal = trim($_POST["eduHistFacilityPostalEntry".($i + 1)]);
            }
            // Validate facility type
            if (empty(trim($_POST["eduHistFacilityTypeEntry".($i + 1)]))) {
                $eduHistFacilityType_err = "Please select type of institution.";
            } else {
                $newEduHistFacilityType = trim($_POST["eduHistFacilityTypeEntry".($i + 1)]);
            }

            if (empty($currEduHistory)) {
                // insert
                
            } else {
                // Update
                $currAreaOfStudy = $currEduHistory[$i]["areaofstudy"];
                $currDegree = $currEduHistory[$i]["degree"];
                $sql = "UPDATE education
                    INNER JOIN education_facilities
                    ON education.ed_facility_name=education_facilities.name AND education.ed_facility_city=education_facilities.city
                    SET areaofstudy=?, degree=?, start_date=?, end_date=?, gpa=?, name=?, city=?, state=?, postal=?, type=?
                    WHERE jobseeker=? AND areaofstudy=? AND degree=?
                ";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sssssssssssss", $newEduHistAreaOfStudy, $newEduHistDegree, $newEduHistStart, $newEduHistEnd, $newEduHistGpa, $newEduHistFacilityName, $newEduHistFacilityCity, $newEduHistFacilityState, $newEduHistFacilityPostal, $newEduHistFacilityType, $currUser, $currAreaOfStudy, $currDegree);
                    if (mysqli_stmt_execute($stmt)) {
                        header("Location: ./EditProfile.php");
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
    else { // no data yet / new user (insert)

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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="profileForm">
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
            if ($currJobHistory) {
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
            ?>
                <span><p class="form-subheader2">[ JOB #<?php echo $jobHistCount; ?> ]</p></span>
                <div class="form-group mb-2 <?php echo (!empty($jobHistCompany_err)) ? 'has-error' : ''; ?>">
                    <label for="jobHistCompanyEntry<?php echo $jobHistCount; ?>">Company</label>
                    <input type="text" class="form-control" name="jobHistCompanyEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." value="<?php echo $jobHistCompany; ?>">
                    <span class="help-block"><?php echo $jobHistCompany_err; ?></span>
                </div>
                <div class="input-group mb-4">
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistStart_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistStartEntry<?php echo $jobHistCount; ?>">Start Date</label>
                        <div class="input-group date" data-date-format="dd.mm.yyyy">
                            <input type="date" class="form-control" name="jobHistStartEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." placeholder="dd.mm.yyyy" value="<?php echo $jobHistStart; ?>">
                        </div>
                        <span class="help-block"><?php echo $jobHistStart_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistEnd_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistEndEntry<?php echo $jobHistCount; ?>">End Date</label>
                        <div class="input-group date" data-date-format="dd.mm.yyyy">
                            <input type="date" class="form-control" name="jobHistEndEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." placeholder="dd.mm.yyyy" value="<?php echo $jobHistEnd; ?>">
                        </div>
                        <span class="help-block"><?php echo $jobHistEnd_err; ?></span>
                    </div>
                </div>
                <div class="form-group mb-2 <?php echo (!empty($jobHistPos_err)) ? 'has-error' : ''; ?>">
                    <label for="jobHistPosEntry<?php echo $jobHistCount; ?>">Position</label>
                    <input type="text" class="form-control" name="jobHistPosEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." value="<?php echo $jobHistPos; ?>">
                    <span class="help-block"><?php echo $jobHistPos_err; ?></span>
                </div>
                <div class="input-group mb-4">
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupFname_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistSupFnameEntry<?php echo $jobHistCount; ?>">Supervisor's First Name</label>
                        <input type="text" class="form-control" name="jobHistSupFnameEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." value="<?php echo $jobHistSupFname; ?>">
                        <span class="help-block"><?php echo $jobHistSupFname_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupLname_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistSupLnameEntry<?php echo $jobHistCount; ?>">Supervisor's Last Name</label>
                        <input type="text" class="form-control" name="jobHistSupLnameEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." value="<?php echo $jobHistSupLname; ?>">
                        <span class="help-block"><?php echo $jobHistSupLname_err; ?></span>
                    </div>
                </div>
                <div class="input-group mb-4">
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupEmail_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistSupEmailEntry<?php echo $jobHistCount; ?>">Supervisor's Email</label>
                        <input type="email" class="form-control" name="jobHistSupEmailEntry<?php echo $jobHistCount; ?>" placeholder="example@yahoo.com" required data-error="This field is required." value="<?php echo $jobHistSupEmail; ?>">
                        <span class="help-block"><?php echo $jobHistSupEmail_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupPhone_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistSupEmailEntry<?php echo $jobHistCount; ?>">Supervisor's Phone</label>
                        <input type="tel" class="form-control" name="jobHistSupPhoneEntry<?php echo $jobHistCount; ?>" placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}" required data-error="This field is required." value="<?php echo $jobHistSupPhone; ?>">
                        <span class="help-block"><?php echo $jobHistSupPhone_err; ?></span>
                    </div>
                </div>
            <?php
            }} else {
                for ($jobHistCount = 1; $jobHistCount <= 3; $jobHistCount++) {
            ?>  
                <span><p class="form-subheader2">[ JOB #<?php echo $jobHistCount; ?> ]</p></span>
                <div class="form-group mb-2 <?php echo (!empty($jobHistCompany_err)) ? 'has-error' : ''; ?>">
                    <label for="jobHistCompanyEntry<?php echo $jobHistCount; ?>">Company</label>
                    <input type="text" class="form-control" name="jobHistCompanyEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." value="<?php echo $jobHistCompany; ?>">
                    <span class="help-block"><?php echo $jobHistCompany_err; ?></span>
                </div>
                <div class="input-group mb-4">
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistStart_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistStartEntry<?php echo $jobHistCount; ?>">Start Date</label>
                        <div class="input-group date" data-date-format="dd.mm.yyyy">
                            <input type="date" class="form-control" name="jobHistStartEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." placeholder="dd.mm.yyyy" value="<?php echo $jobHistStart; ?>">
                        </div>
                        <span class="help-block"><?php echo $jobHistStart_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistEnd_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistEndEntry<?php echo $jobHistCount; ?>">End Date</label>
                        <div class="input-group date" data-date-format="dd.mm.yyyy">
                            <input type="date" class="form-control" name="jobHistEndEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." placeholder="dd.mm.yyyy" value="<?php echo $jobHistEnd; ?>">
                        </div>
                        <span class="help-block"><?php echo $jobHistEnd_err; ?></span>
                    </div>
                </div>
                <div class="form-group mb-2 <?php echo (!empty($jobHistPos_err)) ? 'has-error' : ''; ?>">
                    <label for="jobHistPosEntry<?php echo $jobHistCount; ?>">Position</label>
                    <input type="text" class="form-control" name="jobHistPosEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." value="<?php echo $jobHistPos; ?>">
                    <span class="help-block"><?php echo $jobHistPos_err; ?></span>
                </div>
                <div class="input-group mb-4">
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupFname_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistSupFnameEntry<?php echo $jobHistCount; ?>">Supervisor's First Name</label>
                        <input type="text" class="form-control" name="jobHistSupFnameEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." value="<?php echo $jobHistSupFname; ?>">
                        <span class="help-block"><?php echo $jobHistSupFname_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupLname_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistSupLnameEntry<?php echo $jobHistCount; ?>">Supervisor's Last Name</label>
                        <input type="text" class="form-control" name="jobHistSupLnameEntry<?php echo $jobHistCount; ?>" required data-error="This field is required." value="<?php echo $jobHistSupLname; ?>">
                        <span class="help-block"><?php echo $jobHistSupLname_err; ?></span>
                    </div>
                </div>
                <div class="input-group mb-4">
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupEmail_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistSupEmailEntry<?php echo $jobHistCount; ?>">Supervisor's Email</label>
                        <input type="email" class="form-control" name="jobHistSupEmailEntry<?php echo $jobHistCount; ?>" placeholder="example@yahoo.com" required data-error="This field is required." value="<?php echo $jobHistSupEmail; ?>">
                        <span class="help-block"><?php echo $jobHistSupEmail_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($jobHistSupPhone_err)) ? 'has-error' : ''; ?>">
                        <label for="jobHistSupEmailEntry<?php echo $jobHistCount; ?>">Supervisor's Phone</label>
                        <input type="tel" class="form-control" name="jobHistSupPhoneEntry<?php echo $jobHistCount; ?>" placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}" required data-error="This field is required." value="<?php echo $jobHistSupPhone; ?>">
                        <span class="help-block"><?php echo $jobHistSupPhone_err; ?></span>
                    </div>
                </div>
            <?php
            }}
            ?>
            <!-- EDUCATION HISTORY -->
            <span><p class="form-subheader">EDUCATION HISTORY</p></span>
            <?php
            if ($currEduHistory) {
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
                        <label for="eduHistAreaOfStudyEntry<?php echo $eduHistCount; ?>">Area Of Study</label>
                        <input type="text" class="form-control" name="eduHistAreaOfStudyEntry<?php echo $eduHistCount; ?>" required data-error="This field is required." value="<?php echo $eduHistAreaOfStudy; ?>">
                        <span class="help-block"><?php echo $eduHistAreaOfStudy_err; ?></span>
                    </div>
                    <div class="form-group row mr-3 col-md-5 <?php echo (!empty($eduHistDegree_err)) ? 'has-error' : ''; ?>">
                        <label for="eduHistDegreeEntry<?php echo $eduHistCount; ?>">Degree</label>
                        <div class="input-group mb-2">
                            <select class="form-select" name="eduHistDegreeEntry<?php echo $eduHistCount; ?>" required data-error="This field is required.">
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
                        <label for="eduHistStartEntry<?php echo $eduHistCount; ?>">Start Date</label>
                        <div class="input-group date" data-date-format="dd.mm.yyyy">
                            <input type="date" class="form-control" name="eduHistStartEntry<?php echo $eduHistCount; ?>" placeholder="dd.mm.yyyy" required data-error="This field is required." value="<?php echo $eduHistStart; ?>">
                        </div>
                        <span class="help-block"><?php echo $eduHistStart_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-5 <?php echo (!empty($eduHistEnd_err)) ? 'has-error' : ''; ?>">
                        <label for="eduHistEndEntry<?php echo $eduHistCount; ?>">End Date</label>
                        <div class="input-group date" data-date-format="dd.mm.yyyy">
                            <input type="date" class="form-control" name="eduHistEndEntry<?php echo $eduHistCount; ?>" placeholder="dd.mm.yyyy" required data-error="This field is required." value="<?php echo $eduHistEnd; ?>">
                        </div>
                        <span class="help-block"><?php echo $eduHistEnd_err; ?></span>
                    </div>
                </div>
                <div class="input-group mb-4">
                    <div class="form-group row mr-3 col-md-5 <?php echo (!empty($eduHistFacilityType_err)) ? 'has-error' : ''; ?>">
                        <label for="eduHistFacilityTypeEntry<?php echo $eduHistCount; ?>">Institution Type</label>
                        <div class="input-group mb-2">
                            <select class="form-select" name="eduHistFacilityTypeEntry<?php echo $eduHistCount; ?>" required data-error="This field is required.">
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
                        <label for="eduHistGpaEntry<?php echo $eduHistCount; ?>">GPA</label>
                        <input type="number" class="form-control" name="eduHistGpaEntry<?php echo $eduHistCount; ?>" required data-error="This field is required." min="0" max="4" step=".001" value="<?php echo $eduHistGpa; ?>">
                        <span class="help-block"><?php echo $eduHistGpa_err; ?></span>
                    </div>
                </div>
                <div class="form-group mb-4 <?php echo (!empty($eduHistFacilityName_err)) ? 'has-error' : ''; ?>">
                    <label for="eduHistFacilityNameEntry<?php echo $eduHistCount; ?>">Institution Name</label>
                    <input type="text" class="form-control" name="eduHistFacilityNameEntry<?php echo $eduHistCount; ?>" required data-error="This field is required." value="<?php echo $eduHistFacilityName; ?>">
                    <span class="help-block"><?php echo $eduHistFacilityName_err; ?></span>
                </div>
                <div class="input-group mb-4">
                    <div class="form-group mr-3 col-md-3 <?php echo (!empty($eduHistFacilityCity_err)) ? 'has-error' : ''; ?>">
                        <label for="eduHistFacilityCityEntry<?php echo $eduHistCount; ?>">City</label>
                        <input type="text" class="form-control" name="eduHistFacilityCityEntry<?php echo $eduHistCount; ?>" required data-error="This field is required." value="<?php echo $eduHistFacilityCity; ?>">
                        <span class="help-block"><?php echo $eduHistFacilityCity_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-3 <?php echo (!empty($eduHistFacilityState_err)) ? 'has-error' : ''; ?>">
                        <label for="eduHistFacilityStateEntry<?php echo $eduHistCount; ?>">State</label>
                        <input type="text" class="form-control" name="eduHistFacilityStateEntry<?php echo $eduHistCount; ?>" required data-error="This field is required." value="<?php echo $eduHistFacilityState; ?>">
                        <span class="help-block"><?php echo $eduHistFacilityState_err; ?></span>
                    </div>
                    <div class="form-group mr-3 col-md-3 <?php echo (!empty($eduHistFacilityPostal_err)) ? 'has-error' : ''; ?>">
                        <label for="eduHistFacilityPostalEntry<?php echo $eduHistCount; ?>">Zip</label>
                        <input type="text" class="form-control" name="eduHistFacilityPostalEntry<?php echo $eduHistCount; ?>" required data-error="This field is required." value="<?php echo $eduHistFacilityPostal; ?>">
                        <span class="help-block"><?php echo $eduHistFacilityPostal_err; ?></span>
                    </div>
                </div>
            <?php
            }}
            ?>
            <div id="addEduBtn" class="input-group mb-4">
                <a href="javascript:addEdu(<?php echo $eduHistCount; ?>)">ADD EDUCATION</a>
            </div>
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
    <script src="../../static/js/script.js" type="text/javascript"></script>
</body>
</html>