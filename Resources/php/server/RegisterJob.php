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
$position = $position_err = "";
$description = $description_err = "";
$salary = $salary_err = "";
$start_date = $start_date_err = "";
// Optional values
$required_education = $required_education_err = "";
$required_skills = $required_skills_err = "";
$required_job_specific = $required_job_specific_err = "";
$required_prior_experience = $required_prior_experience_err = "";

// Processing form data when form is submitted and contains data
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
    // Validate position title
    if(empty(trim($_POST["positionEntry"]))){
        $position_err = "Please enter a position title.";
    } else{
        $position = trim($_POST["positionEntry"]);
    }
    // Validate position description
    if(empty(trim($_POST["descriptionEntry"]))){
        $description_err = "Please enter a description for this position.";
    } else{
        $description = trim($_POST["descriptionEntry"]);
    }
    // Validate position salary
    if(empty(trim($_POST["salaryEntry"]))){
        $salary_err = "Please enter a salary for this position.";
    } else{
        $salary = trim($_POST["salaryEntry"]);
    }
    // Validate position start date
    if(empty(trim($_POST["startDateEntry"]))){
        $start_date_err = "Please enter a valid starting date for this position.";
    } else{
        $start_date = trim($_POST["startDateEntry"]);
    }

    // Validate required education (not-required)
    if(empty(trim($_POST["requiredEducationEntry"]))){
        //$required_education_err = "Please enter required education for this position.";
    } else{
        $required_education = trim($_POST["requiredEducationEntry"]);
    }
    // Validate required skills (not-required)
    if(empty(trim($_POST["requiredSkillsEntry"]))){
        //$required_skills_err = "Please enter required skills for this position.";
    } else{
        $required_skills = trim($_POST["requiredSkillsEntry"]);
    }
    // Validate job-specific requirements (not-required)
    if(empty(trim($_POST["jobSpecificRequirementsEntry"]))){
        //$required_job_specific_err = "Please enter job-specific requirements for this position.";
    } else{
        $required_job_specific = trim($_POST["jobSpecificRequirementsEntry"]);
    }
    // Validate required prior experience (not-required)
    if(empty(trim($_POST["priorExperienceRequirementsEntry"]))){
        //$required_prior_experience_err = "Please enter required prior experience for this position.";
    } else{
        $required_prior_experience = trim($_POST["priorExperienceRequirementsEntry"]);
    }
    
    // Check input errors before inserting in database
    if(empty($position_err) && empty($description_err) && empty($salary_err) && empty($start_date_err) && empty($required_education_err) && empty($required_skills_err) && empty($required_job_specific_err) && empty($required_prior_experience_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO jobs (jobid, position, description, salary, start_date, posted_date, required_education, required_skills, required_job_specific, required_prior_experience, employer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssss", $param_jobId, $param_position, $param_description, $param_salary, $param_start_date, $param_posted_date, $param_required_education, $param_required_skills, $param_required_job_specific, $param_required_prior_experience, $param_employer);
            
            // Set parameters
            $param_jobId = NULL; // set in db
            $param_position = $position;
            $param_description = $description;
            $param_salary = $salary;
            $param_start_date = $start_date;
            $param_posted_date = date("Y-m-d"); // set upon submission
            $param_required_education = $required_education;
            $param_required_skills = $required_skills;
            $param_required_job_specific = $required_job_specific;
            $param_required_prior_experience = $required_prior_experience;
            $param_employer = $_SESSION["username"]; // set on login; reflected in db
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to main page
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
	<title> TeamCDA Website - Post new Job </title>
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
								echo " href=\"../Resources/php/server/EditProfile.php\">Edit Profile</a>";
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
								echo " href=\"../Resources/php/server/RegisterJob.php\">Post new position</a>";
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
        <h2>Register HR Employee</h2>
        <p>Please fill out this form to create an employee account.</p>
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
            <div class="form-group row mb-4 <?php echo (!empty($company_err)) ? 'has-error' : ''; ?>">
                <label for="companyEntry">Select Company</label>
                <div class="input-group mb-2">
                <select class="form-select" name="companyEntry">
                    <option selected="true" disabled>Choose one ...</option>
                    <?php
                    foreach($_SESSION["companiesList"] as $row) { ?>
                    <option>
                        <?php
                        echo $row['name'];
                        ?>
                    </option>
                    <?php
                    }
                    ?>
                </select>
                <span class="help-block"><?php echo $company_err; ?></span>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                    <label for="firstNameEntry">First Name</label>
                    <input type="text" class="form-control" name="firstNameEntry" required="required" data-error="This field is required." placeholder="Tom" value="<?php echo $fname; ?>">
                    <span class="help-block"><?php echo $fname_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
                    <label for="lastNameEntry">Last Name</label>
                    <input type="text" class="form-control" name="lastNameEntry" required="required" data-error="This field is required." placeholder="Smith" value="<?php echo $lname; ?>">
                    <span class="help-block"><?php echo $lname_err; ?></span>
                </div>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-5 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label for="emailEntry">Email Address</label>
                    <input type="email" class="form-control" name="emailEntry" required="required" data-error="This field is required." placeholder="example@yahoo.com" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group col-md-5 <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                    <label for="phoneEntry">Phone Number</label>
                    <input type="tel" class="form-control" name="phoneEntry" data-error="This field is optional but must be valid." placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}" value="<?php echo $phone; ?>">
                    <span class="help-block"><?php echo $phone_err; ?></span>
                </div>
            </div>
            <div class="form-group row mb-4 <?php echo (!empty($dob_err)) ? 'has-error' : ''; ?>">
                <label for="dateOfBirthEntry">Date of Birth</label>
                <div class="input-group date" data-date-format="dd.mm.yyyy">
                    <input type="date" class="form-control" name="dateOfBirthEntry" placeholder="dd.mm.yyyy" value="<?php echo $dob; ?>">
                </div>
                <span class="help-block"><?php echo $dob_err; ?></span>
            </div>
            <div class="form-group mb-2 <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                <label for="inputAddress">Address</label>
                <input type="text" class="form-control" name="inputAddress" data-error="This field is optional but must be valid." placeholder="1234 Main St" value="<?php echo $address; ?>">
                <span class="help-block"><?php echo $address_err; ?></span>
            </div>
            <div class="input-group mb-4">
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                    <label for="inputCity">City</label>
                    <input type="text" class="form-control" name="inputCity" data-error="This field is optional but must be valid." value="<?php echo $city; ?>">
                    <span class="help-block"><?php echo $city_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-3 <?php echo (!empty($state_err)) ? 'has-error' : ''; ?>">
                    <label for="inputState">State</label>
                    <input type="text" class="form-control" name="inputState" data-error="This field is optional but must be valid." value="<?php echo $state; ?>">
                    <span class="help-block"><?php echo $state_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($postal_err)) ? 'has-error' : ''; ?>">
                    <label for="inputZip">Zip</label>
                    <input type="text" class="form-control" name="inputZip" data-error="This field is optional but must be valid." value="<?php echo $postal; ?>">
                    <span class="help-block"><?php echo $postal_err; ?></span>
                </div>
                <div class="form-group mr-3 col-md-2 <?php echo (!empty($country_err)) ? 'has-error' : ''; ?>">
                    <label for="inputCountry">Country</label>
                    <input type="text" class="form-control" name="inputCountry" data-error="This field is optional but must be valid." value="<?php echo $country; ?>">
                    <span class="help-block"><?php echo $country_err; ?></span>
                </div>
            </div>
            <div class="bottom-padding form-group">
                <span>Already have an account? <a href="Login.php">Login here</a>.</span>
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