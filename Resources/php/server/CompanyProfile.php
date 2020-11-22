<?php
// Include the config file
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
if(!isset($_SESSION["role"]) || $_SESSION["role"] !== "employer"){
    header("location: ../../static/error/Error_Permission.html");
    exit;
}

// Define variables and initialize with empty values
$name = $name_err = "";
$location = $location_err = "";
$contFname = $contFname_err = "";
$contLname = $contLname_err = "";
$contStreet = $contStreet_err = "";
$contCity = $contCity_err = "";
$contState = $contState_err = "";
$contPostal = $contPostal_err = "";
$contCountry = $contCountry_err = "";
$contEmail = $contEmail_err = "";
$contPhone = $contPhone_err = "";

// Fetch company info from current user
$currentUser = $_SESSION["username"];

// Processing form data when form is submitted and contains data
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT username FROM people WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate role data
    // if(empty(trim($_POST["roleEntry"]))){
    //     $role_err = "Please enter/select a valid role.";
    // } else{
    //     $role = trim($_POST["roleEntry"]);
    // }
    // Validate company data
    if(empty(trim($_POST["companyEntry"]))){
        $company_err = "Please enter/select a valid company.";
    } else{
        $company = trim($_POST["companyEntry"]);
    }
    // Validate first name
    if(empty(trim($_POST["firstNameEntry"]))){
        $fname_err = "Please enter a first name.";
    } else{
        $fname = trim($_POST["firstNameEntry"]);
    }
    // Validate last name
    if(empty(trim($_POST["lastNameEntry"]))){
        $lname_err = "Please enter a last name.";
    } else{
        $lname = trim($_POST["lastNameEntry"]);
    }

    // Validate email address
    if(empty(trim($_POST["emailEntry"]))){
        $email_err = "Please enter a valid email address.";
    } else{
        $email = trim($_POST["emailEntry"]);
    }

    // Validate company selection
    if(empty(trim($_POST["companyEntry"]))){
        $company_err = "Please select a company from list.";
    } else{
        $company = trim($_POST["companyEntry"]);
    }

    // Validate phone number (not-required)
    if(empty(trim($_POST["phoneEntry"]))){
        //$phone_err = "Please enter a valid phone number.";
    } else{
        $phone = trim($_POST["phoneEntry"]);
    }
    // Validate date of birth (not-required)
    if(empty(trim($_POST["dateOfBirthEntry"]))){
        //$dob_err = "Please enter a valid date of birth.";
    } else{
        $dob = trim($_POST["dateOfBirthEntry"]);
    }

    // Validate address (not-required)
    if(empty(trim($_POST["inputAddress"]))){
        //$address_err = "Please enter a valid address.";
    } else{
        $address = trim($_POST["inputAddress"]);
    }
    // Validate city (not-required)
    if(empty(trim($_POST["inputCity"]))){
        //$city_err = "Please enter a valid city.";
    } else{
        $city = trim($_POST["inputCity"]);
    }
    // Validate state (not-required)
    if(empty(trim($_POST["inputState"]))){
        //$state_err = "Please enter a valid state.";
    } else{
        $state = trim($_POST["inputState"]);
    }
    // Validate zip (not-required)
    if(empty(trim($_POST["inputZip"]))){
        //$postal_err = "Please enter a valid zip.";
    } else{
        $postal = trim($_POST["inputZip"]);
    }
    // Validate country (not-required)
    if(empty(trim($_POST["inputCountry"]))){
        //$country_err = "Please enter a valid country.";
    } else{
        $country = trim($_POST["inputCountry"]);
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err) && empty($fname_err) && empty($lname_err) && empty($email_err) && empty($phone_err) && empty($dob_err) && empty($address_err) && empty($city_err) && empty($state_err) && empty($postal_err) && empty($country_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO people (username, phash, usertype, fname, lname, email, phone, dob, street, city, state, postal, country, employing_company) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssss", $param_username, $param_password, $param_role, $param_fname, $param_lname, $param_email, $param_phone, $param_dob, $param_address, $param_city, $param_state, $param_postal, $param_country, $param_company);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_role = $role;
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_phone = $phone;
            $param_dob = $dob;
            $param_address = $address;
            $param_city = $city;
            $param_state = $state;
            $param_postal = $postal;
            $param_country = $country;
            $param_company = $company;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: Login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta char="UTF-8">
	<title> TeamCDA Website - Company Profile </title>
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
								echo " href=\"../Resources/static/error/404.html\">Company profile</a>";
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
        <h2>Update Company Profile</h2>
        <p>Update company profile below.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="bottom-padding form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Company Name</label>
                <input type="text" name="nameEntry" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="bottom-padding form-group <?php echo (!empty($location_err)) ? 'has-error' : ''; ?>">
                <label>Location</label>
                <input type="text" name="locationEntry" class="form-control" value="<?php echo $location; ?>">
                <span class="help-block"><?php echo $location_err; ?></span>
            </div>
            <span><p class="form-subheader">COMPANY CONTACT PERSON INFORMATION</p></span>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($contFname_err)) ? 'has-error' : ''; ?>">
                    <label for="contFnameEntry">First Name</label>
                    <input type="text" class="form-control" name="contFnameEntry" required="required" data-error="This field is required." placeholder="Tom" value="<?php echo $contFname; ?>">
                    <span class="help-block"><?php echo $contFname_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($contLname_err)) ? 'has-error' : ''; ?>">
                    <label for="contLnameEntry">Last Name</label>
                    <input type="text" class="form-control" name="contLnameEntry" required="required" data-error="This field is required." placeholder="Smith" value="<?php echo $contLname; ?>">
                    <span class="help-block"><?php echo $contLname_err; ?></span>
                </div>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($contEmail_err)) ? 'has-error' : ''; ?>">
                    <label for="contEmailEntry">Email Address</label>
                    <input type="email" class="form-control" name="contEmailEntry" required="required" data-error="This field is required." placeholder="example@yahoo.com" value="<?php echo $contEmail; ?>">
                    <span class="help-block"><?php echo $contEmail_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($contPhone_err)) ? 'has-error' : ''; ?>">
                    <label for="contPhoneEntry">Phone Number</label>
                    <input type="tel" class="form-control" name="contPhoneEntry" data-error="This field is optional but must be valid." placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}" value="<?php echo $contPhone; ?>">
                    <span class="help-block"><?php echo $contPhone_err; ?></span>
                </div>
            </div>
            <div class="form-group mb-2 <?php echo (!empty($contStreet_err)) ? 'has-error' : ''; ?>">
                <label for="contStreetEntry">Street</label>
                <input type="text" class="form-control" name="contStreetEntry" data-error="This field is optional but must be valid." placeholder="1234 Main St" value="<?php echo $contStreet; ?>">
                <span class="help-block"><?php echo $contStreet_err; ?></span>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($contCity_err)) ? 'has-error' : ''; ?>">
                    <label for="contCityEntry">City</label>
                    <input type="text" class="form-control" name="contCityEntry" data-error="This field is optional but must be valid." value="<?php echo $contCity; ?>">
                    <span class="help-block"><?php echo $contCity_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($contState_err)) ? 'has-error' : ''; ?>">
                    <label for="contStateEntry">State</label>
                    <input type="text" class="form-control" name="contStateEntry" data-error="This field is optional but must be valid." value="<?php echo $contState; ?>">
                    <span class="help-block"><?php echo $contState_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($contPostal_err)) ? 'has-error' : ''; ?>">
                    <label for="contPostalEntry">Zip</label>
                    <input type="text" class="form-control" name="contPostalEntry" data-error="This field is optional but must be valid." value="<?php echo $contPostal; ?>">
                    <span class="help-block"><?php echo $contPostal_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($contCountry_err)) ? 'has-error' : ''; ?>">
                    <label for="contCountryEntry">Country</label>
                    <input type="text" class="form-control" name="contCountryEntry" data-error="This field is optional but must be valid." value="<?php echo $contCountry; ?>">
                    <span class="help-block"><?php echo $contCountry_err; ?></span>
                </div>
            </div>
            <div class="bottom-padding form-group">
                <input type="submit" class="btn btn-primary float-right ml-2" value="Submit">
                <input type="reset" class="btn btn-secondary float-right" value="Reset">
            </div>
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