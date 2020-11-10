<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true){
    header("location: ../Resources/php/server/Login.php");
    exit;
} else {
  // If user satisfied the above condition, we'll also check their role.
  // If they lack permission, navigate them to the role error page
  if ($_SESSION["role"] !== "admin") {
    header("location: ../Resources/static/error/Error_Permission.html");
    exit;
  }
}

// Include the config file
require_once "Config.php";
 
// Define variables and initialize with empty values
$company_name = $company_name_err = "";
$company_location = $company_location_err = "";
$contact_fname = $contact_fname_err = "";
$contact_lname = $contact_lname_err = "";
$contact_street = $contact_street_err = "";
$contact_city = $contact_city_err = "";
$contact_state = $contact_state_err = "";
$contact_postal = $contact_postal_err = "";
$contact_country = $contact_country_err = "";
$contact_email = $contact_email_err = "";
$contact_phone = $contact_phone_err = "";
 
// Processing form data when form is submitted and contains data
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
 
    // Validate company name
    if(empty(trim($_POST["company_name"]))){
        $company_name_err = "Please enter a company name.";
    } else{
        // Prepare a select statement
        $sql = "SELECT name FROM companies WHERE name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_company_name);
            
            // Set parameters
            $param_company_name = trim($_POST["company_name"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $company_name_err = "This company name is already taken.";
                } else{
                    $company_name = trim($_POST["company_name"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate company location
    if(empty(trim($_POST["company_location"]))){
        $company_location_err = "Please enter valid location data.";
    } else{
        $company_location = trim($_POST["company_location"]);
    }
    // Validate contact first name (not-required)
    if(empty(trim($_POST["contact_fname"]))){
        //$contact_fname_err = "Please enter a first name.";
    } else{
        $contact_fname = trim($_POST["contact_fname"]);
    }
    // Validate contact last name (not-required)
    if(empty(trim($_POST["contact_lname"]))){
        //$contact_lname_err = "Please enter a last name.";
    } else{
        $contact_lname = trim($_POST["contact_lname"]);
    }

    // Validate contact email address (not-required)
    if(empty(trim($_POST["contact_email"]))){
        //$contact_email_err = "Please enter a valid email address.";
    } else{
        $contact_email = trim($_POST["contact_email"]);
    }
    // Validate contact phone number (not-required)
    if(empty(trim($_POST["contact_phone"]))){
        //$contact_phone_err = "Please enter a valid phone number.";
    } else{
        $contact_phone = trim($_POST["contact_phone"]);
    }

    // Validate contact address (not-required)
    if(empty(trim($_POST["contact_street"]))){
        //$contact_street_err = "Please enter a valid address.";
    } else{
        $contact_street = trim($_POST["contact_street"]);
    }
    // Validate contact city (not-required)
    if(empty(trim($_POST["contact_city"]))){
        //$contact_city_err = "Please enter a valid city.";
    } else{
        $contact_city = trim($_POST["contact_city"]);
    }
    // Validate contact state (not-required)
    if(empty(trim($_POST["contact_state"]))){
        //$contact_state_err = "Please enter a valid state.";
    } else{
        $contact_state = trim($_POST["contact_state"]);
    }
    // Validate contact zip (not-required)
    if(empty(trim($_POST["contact_postal"]))){
        //$contact_postal_err = "Please enter a valid zip.";
    } else{
        $contact_postal = trim($_POST["contact_postal"]);
    }
    // Validate country (not-required)
    if(empty(trim($_POST["contact_county"]))){
        //$contact_country_err = "Please enter a valid country.";
    } else{
        $contact_country = trim($_POST["contact_country"]);
    }
    
    // Check input errors before inserting in database
    if(empty($company_name_err) && empty($company_location_err) && empty($contact_fname_err) && empty($contact_lname_err) && empty($contact_email_err) && empty($contact_phone_err) && empty($contact_street_err) && empty($contact_city_err) && empty($contact_state_err) && empty($contact_postal_err) && empty($contact_country_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO companies (name, location, contact_fname, contact_lname, contact_street, contact_city, contact_state, contact_postal, contact_country, contact_email, contact_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssss", $param_company_name, $param_company_location, $param_contact_fname, $param_contact_lname, $param_contact_street, $param_contact_city, $param_contact_state, $param_contact_postal, $param_contact_country, $param_contact_email, $param_contact_phone);
            
            // Set parameters
            $param_company_name = $company_name;
            $param_company_location = $company_location;
            $param_contact_fname = $contact_fname;
            $param_contact_lname = $contact_lname;
            $param_contact_street = $contact_street;
            $param_contact_city = $contact_city;
            $param_contact_state = $contact_state;
            $param_contact_postal = $contact_postal;
            $param_contact_country = $contact_country;
            $param_contact_email = $contact_email;
            $param_contact_phone = $contact_phone;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect user to welcome page
                header("location: ../../../index.php");
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
	<title> TeamCDA Website - Register Company </title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
	<!-- Style Scripts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat|Roboto">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
	<link rel="stylesheet" href="../../static/css/style.css" type="text/css">
	<!-- End of Style Scripts -->
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.bundle.min.js" integrity="sha384-BOsAfwzjNJHrJ8cZidOg56tcQWfp6y72vEJ8xQ9w6Quywb24iOsW913URv1IS4GD" crossorigin="anonymous"></script>
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
        <h2>Register Company</h2>
        <p>Please fill out this form to register a new company.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="bottom-padding form-group <?php echo (!empty($company_name_err)) ? 'has-error' : ''; ?>">
                <label for="company_name">Company Name</label>
                <input type="text" name="company_name" required="required" class="form-control" value="<?php echo $company_name; ?>">
                <span class="help-block"><?php echo $company_name_err; ?></span>
            </div>
            <div class="form-group row mb-4 <?php echo (!empty($company_location_err)) ? 'has-error' : ''; ?>">
                <label for="company_location">Location Details</label>
                <textarea class="form-control" name="company_location" required="required" rows="10" data-error="This field is required." placeholder="Let us know of any additional location info to help customers."></textarea>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($contact_fname_err)) ? 'has-error' : ''; ?>">
                    <label for="contact_fname">Contact - First Name</label>
                    <input type="text" class="form-control" name="contact_fname" data-error="This field is optional but must be valid." placeholder="Tom">
                    <span class="help-block"><?php echo $contact_fname_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($contact_lname_err)) ? 'has-error' : ''; ?>">
                    <label for="contact_lname">Contact - Last Name</label>
                    <input type="text" class="form-control" name="contact_lname" data-error="This field is optional but must be valid." placeholder="Smith">
                    <span class="help-block"><?php echo $contact_lname_err; ?></span>
                </div>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($contact_email_err)) ? 'has-error' : ''; ?>">
                    <label for="contact_email">Contact - Email Address</label>
                    <input type="email" class="form-control" name="contact_email" data-error="This field is optional but must be valid." placeholder="example@yahoo.com">
                    <span class="help-block"><?php echo $contact_email_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($contact_phone_err)) ? 'has-error' : ''; ?>">
                    <label for="contact_phone">Contact - Phone Number</label>
                    <input type="tel" class="form-control" name="contact_phone" data-error="This field is optional but must be valid." placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}">
                    <span class="help-block"><?php echo $contact_phone_err; ?></span>
                </div>
            </div>
            <div class="form-group mb-2 <?php echo (!empty($contact_street_err)) ? 'has-error' : ''; ?>">
                <label for="contact_street">Contact - Address</label>
                <input type="text" class="form-control" name="contact_street" data-error="This field is optional but must be valid." placeholder="1234 Main St">
                <span class="help-block"><?php echo $contact_street_err; ?></span>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($contact_city_err)) ? 'has-error' : ''; ?>">
                    <label for="contact_city">Contact - City</label>
                    <input type="text" class="form-control" name="contact_city" data-error="This field is optional but must be valid.">
                    <span class="help-block"><?php echo $contact_city_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($contact_state_err)) ? 'has-error' : ''; ?>">
                    <label for="contact_state">Contact - State</label>
                    <input type="text" class="form-control" name="contact_state" data-error="This field is optional but must be valid.">
                    <span class="help-block"><?php echo $contact_state_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($contact_postal_err)) ? 'has-error' : ''; ?>">
                    <label for="contact_postal">Contact - Zip</label>
                    <input type="text" class="form-control" name="contact_postal" data-error="This field is optional but must be valid.">
                    <span class="help-block"><?php echo $contact_postal_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($contact_country_err)) ? 'has-error' : ''; ?>">
                    <label for="contact_country">Contact - Country</label>
                    <input type="text" class="form-control" name="contact_country" data-error="This field is optional but must be valid.">
                    <span class="help-block"><?php echo $contact_country_err; ?></span>
                </div>
            </div>
            <div class="bottom-padding form-group">
                <span>Please make sure to fill out all required data before submitting.</span>
                <input type="submit" class="btn btn-primary float-right ml-2" value="Submit">
                <input type="reset" class="btn btn-secondary float-right" value="Reset">
            </div>
        </form>
    </div>    
</body>
</html>