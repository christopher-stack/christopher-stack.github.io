<?php
// Include the config file

use function PHPSTORM_META\type;

require_once "Config.php";
require_once "ControllerFunc.php";

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
$currCompany = "";
$currName = $newName = $name_err = "";
$currLocation = $newLocation = $location_err = "";
$currContFname = $newContFname = $contFname_err = "";
$currContLname = $newContLname = $contLname_err = "";
$currContStreet = $newContStreet = $contStreet_err = "";
$currContCity = $newContCity = $contCity_err = "";
$currContState = $newContState = $contState_err = "";
$currContPostal = $newContPostal = $contPostal_err = "";
$currContCountry = $newContCountry = $contCountry_err = "";
$currContEmail = $newContEmail = $contEmail_err = "";
$currContPhone = $newContPhone = $contPhone_err = "";

// Fetch company info from current user
$currUser = $_SESSION["username"];
$sql = "SELECT employing_company FROM people WHERE username=? limit 1";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $currUser);
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        $currCompany = mysqli_fetch_assoc($res)["employing_company"];
    }
    mysqli_stmt_close($stmt);
}
$currUserLocation = $_SESSION["userLocation"];
$sql = "SELECT * FROM companies WHERE name=? AND location=? limit 1";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "ss", $currCompany, $currUserLocation);
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        $companyDetails = mysqli_fetch_assoc($res);
        if ($companyDetails) {
            $currName = $companyDetails["name"];
            $currLocation = $companyDetails["location"];
            $currContFname = $companyDetails["contact_fname"];
            $currContLname = $companyDetails["contact_lname"];
            $currContEmail = $companyDetails["contact_email"];
            $currContPhone = $companyDetails["contact_phone"];
            $currContStreet = $companyDetails["contact_street"];
            $currContCity = $companyDetails["contact_city"];
            $currContState = $companyDetails["contact_state"];
            $currContPostal = $companyDetails["contact_postal"];
            $currContCountry = $companyDetails["contact_country"];
        }
    }
    mysqli_stmt_close($stmt);
}

// Processing form data when form is submitted and contains data
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {

    // Validate company name (nameEntry)
    if (empty(trim($_POST["nameEntry"]))) {
        $name_err = "Please enter company name.";
    } else {
        $newName = trim($_POST["nameEntry"]);
    }

    // Validate location (locationEntry)
    if (empty(trim($_POST["locationEntry"]))) {
        $location_err = "Please enter company location.";
    } else {
        $newLocation = trim($_POST["locationEntry"]);
    }

    // Validate contact first name (contFnameEntry)
    if (empty(trim($_POST["contFnameEntry"]))) {
        $contFname__err = "Please enter company contact's first name.";
    } else {
        $newContFname = trim($_POST["contFnameEntry"]);
    }

    // Validate contact last name (contLnameEntry)
    if (empty(trim($_POST["contLnameEntry"]))) {
        $contLname_err = "Please enter company contact's last name.";
    } else {
        $newContLname = trim($_POST["contLnameEntry"]);
    }

    // Validate contact email (contEmailEntry)
    if (empty(trim($_POST["contEmailEntry"]))) {
        $contEmail_err = "Please enter company contact's email.";
    } else {
        $newContEmail = trim($_POST["contEmailEntry"]);
    }

    // Validate contact phone (contPhoneEntry)
    if (empty(trim($_POST["contPhoneEntry"]))) {
        $contPhone_err = "Please enter company contact's phone number.";
    } else {
        $newContPhone = trim($_POST["contPhoneEntry"]);
    }

    // OPTIONAL INPUTS
    $newContStreet = trim($_POST["contStreetEntry"]);
    $newContCity = trim($_POST["contCityEntry"]);
    $newContState = trim($_POST["contStateEntry"]);
    $newContPostal = trim($_POST["contPostalEntry"]);
    $newContCountry = trim($_POST["contCountryEntry"]);

    // Check if current name is empty (Possible use case: company not registered yet); go ahead and register company
    if ($currName == '' && $newName != '') {
        $sql = "INSERT INTO companies (name, location, contact_fname, contact_lname, contact_street, contact_city, contact_state, contact_postal, contact_country, contact_email, contact_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssssssssss", $newName, $newLocation, $newContFname, $newContLname, $newContStreet, $newContCity, $newContState, $newContPostal, $newContCountry, $newContEmail, $newContPhone);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../../../index.php");
            }
        }
        mysqli_stmt_close($stmt);
    }
    // Check if current values are different from new values; update if different
    elseif ($currName != $newName ||
    $currLocation != $newLocation ||
    $currContFname != $newContFname ||
    $currContLname != $newContLname ||
    $currContEmail != $newContEmail ||
    $currContPhone != $newContPhone ||
    $currContStreet != $newContStreet ||
    $currContCity != $newContCity ||
    $currContState != $newContState ||
    $currContPostal != $newContPostal ||
    $currContCountry != $newContCountry) {
        // side note: another check could potentially be done here to see if a company of a the same name already exists in new location
        $sql = "UPDATE companies SET name=?, location=?, contact_fname=?, contact_lname=?, contact_street=?, contact_city=?, contact_state=?, contact_postal=?, contact_country=?, contact_email=?, contact_phone=? WHERE name=? AND location=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssssssssssss", $newName, $newLocation, $newContFname, $newContLname, $newContStreet, $newContCity, $newContState, $newContPostal, $newContCountry, $newContEmail, $newContPhone, $currName, $currLocation);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../../../index.php");
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
            <div class="bottom-padding form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Company Name</label>
                <?php
                if ($currName == '') {
                    echo "<input type=\"text\" name=\"nameEntry\" class=\"form-control\" value='$currName'>";
                } else {
                    echo "<input type=\"text\" name=\"nameEntry\" class=\"form-control\" value='$currName' disabled>";
                }
                ?>
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="bottom-padding form-group <?php echo (!empty($location_err)) ? 'has-error' : ''; ?>">
                <label>Location</label>
                <input type="text" name="locationEntry" class="form-control" value="<?php echo $currLocation; ?>">
                <span class="help-block"><?php echo $location_err; ?></span>
            </div>
            <span><p class="form-subheader">COMPANY CONTACT PERSON INFORMATION</p></span>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($contFname_err)) ? 'has-error' : ''; ?>">
                    <label for="contFnameEntry">First Name</label>
                    <input type="text" class="form-control" name="contFnameEntry" required="required" data-error="This field is required." placeholder="Tom" value="<?php echo $currContFname; ?>">
                    <span class="help-block"><?php echo $contFname_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($contLname_err)) ? 'has-error' : ''; ?>">
                    <label for="contLnameEntry">Last Name</label>
                    <input type="text" class="form-control" name="contLnameEntry" required="required" data-error="This field is required." placeholder="Smith" value="<?php echo $currContLname; ?>">
                    <span class="help-block"><?php echo $contLname_err; ?></span>
                </div>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($contEmail_err)) ? 'has-error' : ''; ?>">
                    <label for="contEmailEntry">Email Address</label>
                    <input type="email" class="form-control" name="contEmailEntry" required="required" data-error="This field is required." placeholder="example@yahoo.com" value="<?php echo $currContEmail; ?>">
                    <span class="help-block"><?php echo $contEmail_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($contPhone_err)) ? 'has-error' : ''; ?>">
                    <label for="contPhoneEntry">Phone Number</label>
                    <input type="tel" class="form-control" name="contPhoneEntry" data-error="This field is optional but must be valid." placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}" value="<?php echo $currContPhone; ?>">
                    <span class="help-block"><?php echo $contPhone_err; ?></span>
                </div>
            </div>
            <div class="form-group mb-2 <?php echo (!empty($contStreet_err)) ? 'has-error' : ''; ?>">
                <label for="contStreetEntry">Street</label>
                <input type="text" class="form-control" name="contStreetEntry" data-error="This field is optional but must be valid." placeholder="1234 Main St" value="<?php echo $currContStreet; ?>">
                <span class="help-block"><?php echo $contStreet_err; ?></span>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($contCity_err)) ? 'has-error' : ''; ?>">
                    <label for="contCityEntry">City</label>
                    <input type="text" class="form-control" name="contCityEntry" data-error="This field is optional but must be valid." value="<?php echo $currContCity; ?>">
                    <span class="help-block"><?php echo $contCity_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($contState_err)) ? 'has-error' : ''; ?>">
                    <label for="contStateEntry">State</label>
                    <input type="text" class="form-control" name="contStateEntry" data-error="This field is optional but must be valid." value="<?php echo $currContState; ?>">
                    <span class="help-block"><?php echo $contState_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($contPostal_err)) ? 'has-error' : ''; ?>">
                    <label for="contPostalEntry">Zip</label>
                    <input type="text" class="form-control" name="contPostalEntry" data-error="This field is optional but must be valid." value="<?php echo $currContPostal; ?>">
                    <span class="help-block"><?php echo $contPostal_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($contCountry_err)) ? 'has-error' : ''; ?>">
                    <label for="contCountryEntry">Country</label>
                    <input type="text" class="form-control" name="contCountryEntry" data-error="This field is optional but must be valid." value="<?php echo $currContCountry; ?>">
                    <span class="help-block"><?php echo $contCountry_err; ?></span>
                </div>
            </div>
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