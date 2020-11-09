<?php
// Include the config file
require_once "Config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$fname = $lname = "";
$fname_err = $lname_err = "";
 
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
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($fname_err) && empty($lname_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO people (username, phash, fname, lname) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password, $param_fname, $param_lname);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_fname = $fname;
            $param_lname = $lname;
            
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
                <div class="form-group mr-3 col-md-5">
                    <label for="emailEntry">Email Address</label>
                    <input type="email" class="form-control" name="emailEntry" required="required" data-error="This field is required." placeholder="example@yahoo.com">
                </div>
                <div class="form-group col-md-5">
                    <label for="phoneEntry">Phone Number</label>
                    <input type="tel" class="form-control" name="phoneEntry" required="required" data-error="This field is required." placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}">
                </div>
            </div>
            <div class="form-group mb-2">
                <label for="inputAddress">Address</label>
                <input type="text" class="form-control" name="inputAddress" required="required" data-error="This field is required." placeholder="1234 Main St">
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-3">
                    <label for="inputCity">City</label>
                    <input type="text" class="form-control" name="inputCity" required="required" data-error="This field is required.">
                </div>
                <div class="form-group mr-3 col-md-3">
                    <label for="inputState">State</label>
                    <input type="text" class="form-control" name="inputState" required="required" data-error="This field is required.">
                </div>
                <div class="form-group mr-3 col-md-2">
                    <label for="inputZip">Zip</label>
                    <input type="text" class="form-control" name="inputZip" required="required" data-error="This field is required.">
                </div>
                <div class="form-group mr-3 col-md-2">
                    <label for="inputCountry">Country</label>
                    <input type="text" class="form-control" name="inputCountry" required="required" data-error="This field is required.">
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