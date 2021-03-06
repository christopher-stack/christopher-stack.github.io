<?php
// Include the config file
require_once "Config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$role = "jobseeker";
$role_err = "";
$fname = $lname = "";
$fname_err = $lname_err = "";
$email = $phone = "";
$email_err = $phone_err = "";
$dob = $dob_err = "";
$address = $address_err = "";
$city = $city_err = "";
$state = $state_err = "";
$postal = $postal_err = "";
$country = $country_err = "";
 
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
    /*if(empty(trim($_POST["roleEntry"]))){
        $role_err = "Please enter/select a valid role.";
    } else{
        $role = trim($_POST["roleEntry"]);
    }*/
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
        $sql = "INSERT INTO people (username, phash, usertype, fname, lname, email, phone, dob, street, city, state, postal, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssssss", $param_username, $param_password, $param_role, $param_fname, $param_lname, $param_email, $param_phone, $param_dob, $param_address, $param_city, $param_state, $param_postal, $param_country);
            
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
	<title> TeamCDA Website - Register </title>
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
    <div class="register-wrapper">
        <h2>Sign Up</h2>
        <p>Please fill out this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="bottom-padding form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="bottom-padding form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="bottom-padding form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <!-- <div class="form-group row mb-4 <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                <label for="roleEntry">Select Role</label>
                <div class="input-group mb-2">
                <select class="form-select" name="roleEntry">
                    <option>jobseeker</option>
                    <option>employer</option>
                    <option>admin</option>
                </select>
                <span class="help-block"><?php echo $role_err; ?></span>
            </div> -->
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                    <label for="firstNameEntry">First Name</label>
                    <input type="text" class="form-control" name="firstNameEntry" required="required" data-error="This field is required." placeholder="Tom">
                    <span class="help-block"><?php echo $fname_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
                    <label for="lastNameEntry">Last Name</label>
                    <input type="text" class="form-control" name="lastNameEntry" required="required" data-error="This field is required." placeholder="Smith">
                    <span class="help-block"><?php echo $lname_err; ?></span>
                </div>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label for="emailEntry">Email Address</label>
                    <input type="email" class="form-control" name="emailEntry" required="required" data-error="This field is required." placeholder="example@yahoo.com">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                    <label for="phoneEntry">Phone Number</label>
                    <input type="tel" class="form-control" name="phoneEntry" data-error="This field is optional but must be valid." placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}">
                    <span class="help-block"><?php echo $phone_err; ?></span>
                </div>
            </div>
            <div class="form-group row mb-4 <?php echo (!empty($dob_err)) ? 'has-error' : ''; ?>">
                <label for="dateOfBirthEntry">Date of Birth</label>
                <div class="input-group date" data-date-format="dd.mm.yyyy">
                    <input type="date" class="form-control" name="dateOfBirthEntry" placeholder="dd.mm.yyyy">
                </div>
                <span class="help-block"><?php echo $dob_err; ?></span>
            </div>
            <div class="form-group mb-2 <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                <label for="inputAddress">Address</label>
                <input type="text" class="form-control" name="inputAddress" data-error="This field is optional but must be valid." placeholder="1234 Main St">
                <span class="help-block"><?php echo $address_err; ?></span>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                    <label for="inputCity">City</label>
                    <input type="text" class="form-control" name="inputCity" data-error="This field is optional but must be valid.">
                    <span class="help-block"><?php echo $city_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($state_err)) ? 'has-error' : ''; ?>">
                    <label for="inputState">State</label>
                    <input type="text" class="form-control" name="inputState" data-error="This field is optional but must be valid.">
                    <span class="help-block"><?php echo $state_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($postal_err)) ? 'has-error' : ''; ?>">
                    <label for="inputZip">Zip</label>
                    <input type="text" class="form-control" name="inputZip" data-error="This field is optional but must be valid.">
                    <span class="help-block"><?php echo $postal_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($country_err)) ? 'has-error' : ''; ?>">
                    <label for="inputCountry">Country</label>
                    <input type="text" class="form-control" name="inputCountry" data-error="This field is optional but must be valid.">
                    <span class="help-block"><?php echo $country_err; ?></span>
                </div>
            </div>
            <div class="bottom-padding form-group">
                <span>Already have an account? <a href="Login.php">Login here</a>.</span>
                <input type="submit" class="btn btn-primary float-right ml-2" value="Submit">
                <input type="reset" class="btn btn-secondary float-right" value="Reset">
            </div>
        </form>
    </div>    
</body>
</html>