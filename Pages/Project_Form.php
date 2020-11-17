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
  if ($_SESSION["role"] !== "jobseeker") {
    header("location: ../Resources/static/error/Error_Permission.html");
    exit;
  }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta char="UTF-8">
	<title> TeamCDA Website - Job Applicant Form! </title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
	<!-- Style Scripts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat|Roboto">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
	<link rel="stylesheet" href="../Resources/static/css/style.css" type="text/css">
	<!-- End of Style Scripts -->
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/ccfb95e64e.js" crossorigin="anonymous"></script>
	<script src="../Resources/static/js/script.js"></script>
	<!-- Favicon Code - Include ALL Below -->
	<link rel="shortcut icon" href="../Resources/static/icon/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" sizes="180x180" href="../Resources/static/icon/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="../Resources/static/icon/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="../Resources/static/icon/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="../Resources/static/icon/manifest.json">
	<link rel="mask-icon" href="../Resources/static/icon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="msapplication-TileImage" content="../Resources/static/icon/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<!-- END FAVICON CODE -->
</head>
<body onload="calcTime()" style="margin:0;">
	<div id="loader"></div>
	<div style="display:none;" id="webDiv" class="animate-bottom">
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
              <a class="dropdown-item" href="../Resources/php/server/Reset_Password.php">Reset password</a>
              <a class="dropdown-item" href="../Resources/php/server/Logout.php">Sign out</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- NAVIGATION HEADER END -->
    <div class="container" style="overflow-x:auto;">
        <h1>Job Applicant - Submission Form</h1>
        <br />
        <h2 style="text-align:left">Instructions</h2>
        <p style="text-align:left; white-space: pre-wrap;">
Please fill out the below form with as much details as possible.

Further details about your selected fields will be shown after you've filled out an application.
        </p>
        <hr />
        <!--Form Data-->
        <form method="post" action="../Resources/php/Project_Form_Submit.php">
          <div class="form-group row mb-4">
            <label for="positionSelect">Select Position</label>
            <div class="input-group mb-2">
              <select class="form-select" id="positionSelect" name="Position">
                <option>QA Tester</option>
                <option>UX Designer</option>
                <option>PR Management</option>
                <option>Concept Artist</option>
                <option>Other...</option>
              </select>
            </div>
            <label for="desiredPay">Desired Pay</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              <input type="text" class="form-control" id="desiredPay" name="Desired Pay" aria-label="Amount (to the nearest dollar)">
              <div class="input-group-append">
                <span class="input-group-text">.00</span>
              </div>
            </div>
            <label for="startDate">Start Date</label>
            <div class="input-group date" data-date-format="dd.mm.yyyy">
              <input type="date" class="form-control" id="startDate" name="Start Date" placeholder="dd.mm.yyyy">
            </div>
          </div>
          <div class="input-group mb-4">
            <div class="form-group mr-3 col-md-5">
              <label for="firstNameEntry">First Name</label>
              <input type="text" class="form-control" id="firstNameEntry" name="First Name" required="required" data-error="This field is required." placeholder="Tom">
            </div>
            <div class="form-group col-md-5">
              <label for="lastNameEntry">Last Name</label>
              <input type="text" class="form-control" id="lastNameEntry" name="Last Name" required="required" data-error="This field is required." placeholder="Smith">
            </div>
          </div>
          <div class="input-group mb-4">
            <div class="form-group mr-3 col-md-5">
              <label for="emailEntry">Email Address</label>
              <input type="email" class="form-control" id="emailEntry" name="Email Address" required="required" data-error="This field is required." placeholder="example@yahoo.com">
            </div>
            <div class="form-group col-md-5">
              <label for="phoneEntry">Phone Number</label>
              <input type="tel" class="form-control" id="phoneEntry" name="Phone Number" required="required" data-error="This field is required." placeholder="xxx-xxx-xxxx" pattern="\(?\d{3}\)?\s?\-?\s?\d{3}\s?\-?\s?\d{4}">
            </div>
          </div>
          <div class="form-group mb-2">
            <label for="inputAddress">Address</label>
            <input type="text" class="form-control" id="inputAddress" name="Address 1" required="required" data-error="This field is required." placeholder="1234 Main St">
          </div>
          <div class="form-group mb-2">
            <label for="inputAddress2">Address 2</label>
            <input type="text" class="form-control" id="inputAddress2" name="Address 2" placeholder="Apartment, studio, or floor">
          </div>
          <div class="input-group mb-4">
            <div class="form-group mr-3 col-md-3">
              <label for="inputCity">City</label>
              <input type="text" class="form-control" id="inputCity" name="City" required="required" data-error="This field is required.">
            </div>
            <div class="form-group mr-3 col-md-3">
              <label for="inputState">State</label>
              <input type="text" class="form-control" id="inputState" name="State" required="required" data-error="This field is required.">
            </div>
            <div class="form-group col-md-2">
              <label for="inputZip">Zip</label>
              <input type="text" class="form-control" id="inputZip" name="Zip Code" required="required" data-error="This field is required.">
            </div>
          </div>
          <div class="form-group row mb-4">
            <div class="form-group mb-2 col-md-4">
              <label for="expertiseSelect">Select Highest Education/Expertise</label>
              <select class="form-select" id="expertiseSelect" name="Expertise Selection">
                <option>College</option>
                <option>High School</option>
                <option>Specialized Training/Experience</option>
                <option>Other...</option>
              </select>
            </div>
            <div class="form-group mb-2 col-md-6">
              <label for="referenceEntry">Institution/Reference Name</label>
              <input type="text" class="form-control" id="referenceEntry" name="Reference Name" required="required" data-error="This field is required.">
            </div>
            <div class="form-group mb-2">
              <label for="inputReferenceAddress">Reference Address</label>
              <input type="text" class="form-control" id="inputReferenceAddress" name="Reference Address" required="required" data-error="This field is required." placeholder="1234 Main St">
            </div>
            <div class="form-group mb-2 col-md-6">
              <label for="referenceLevelEntry">Institution/Reference Level</label>
              <input type="text" class="form-control" id="referenceLevelEntry" name="Reference Level" required="required" data-error="This field is required.">
            </div>
          </div>
          <div class="form-group mb-4">
            <div class="form-group mb-2">
              <label for="employmentHistory">Employment History</label>
              <textarea class="form-control" id="employmentHistory" name="Employment History" required="required" rows="10" data-error="This field is required." placeholder="Please describe your last three positions, including your position, business name, and a reference contact."></textarea>
            </div>
            <div class="form-group">
              <label for="additionalInfo">Additional Info</label>
              <textarea class="form-control" id="additionalInfo" name="Additional Info" rows="10" data-error="This field is required." placeholder="Let us know of any additional info about yourself or related to this form, which may/may not help this process."></textarea>
            </div>
          </div>
          
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <hr />
    </div>
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