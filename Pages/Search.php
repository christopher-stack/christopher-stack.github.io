<?php
// Include the config file

use function PHPSTORM_META\type;

require_once "../Resources/php/server/Config.php";

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

// Define variables and initialize with empty values
$currUser = $_SESSION["username"];

// Processing form data when form is submitted and contains data
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {

	$search = $searchParam = "";
	$jobResults = [];
	$appliedResults = [];

    // fetch jobids that currUser has applied for and store in $appliedResults array
    $sql = "SELECT jobid from applied_for WHERE jobseeker = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $currUser);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            while ($resArray = mysqli_fetch_array($res)) {
                array_push($appliedResults, $resArray);
            }
        }
    }

    // fetch search and store in $jobResults array
    if (!empty(trim($_POST["searchEntry"]))) {
        $search = trim($_POST["searchEntry"]);
		$searchParam = "%$search%";
		$sql = "SELECT jobid, position, description, salary, DATE_FORMAT(start_date, '%b %d, %Y') as start_date, DATE_FORMAT(posted_date, '%b %d, %Y') as posted_date, required_education, required_skills, required_job_specific, required_prior_experience, street, city, state, postal, employing_company FROM `jobs` LEFT JOIN `people` ON jobs.employer = people.username WHERE (jobs.position LIKE ? OR jobs.description LIKE ?) AND jobs.start_date > CURDATE()";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);
            if (mysqli_stmt_execute($stmt)) {
                $res = mysqli_stmt_get_result($stmt);
                while ($resAssocArray = mysqli_fetch_assoc($res)) {
                    array_push($jobResults, $resAssocArray);
                }
            }
        } 
	}
	
	$_SESSION["search"] = $search;
	$_SESSION["jobResults"] = $jobResults;
	$_SESSION["appliedResults"] = $appliedResults;
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta char="UTF-8">
	<title> TeamCDA Website - Search Jobs </title>
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
								echo " href=\"../server/Search.php\">Search jobs</a>";
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
        <h2>Search Jobs</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="searchForm">
            <div class="form-group <?php echo (!empty($search_err)) ? 'has-error' : ''; ?>">
                <div class="input-icons">
                    <input type="text" id="searchField" class="form-control search-field" name="searchEntry" autocomplete="off" value="" onkeyup="search(this.value)">
                    <i class="fa fa-search icon"></i>
                </div>
			</div>
			<div id="suggestionsList"></div>
			<div class="form-group text-center">
				<button type="submit" class="btn btn-dark w-75">Search</button>
            </div>
		</form>
		
		<?php
		if (isset($_SESSION["jobResults"]) && isset($_SESSION["appliedResults"])) {
			$search = $_SESSION["search"];
			$jobResults = $_SESSION["jobResults"];
			$appliedResults = $_SESSION["appliedResults"];
		?>
		<div class="results-container mt-5">
			<h2><?php echo "Search results for \"$search\""?></h2>
			<?php
			foreach($jobResults as $jobDetails) {
				$appliedBefore = false;
				foreach($appliedResults as $applied) {
					if ($applied["jobid"] == $jobDetails["jobid"]) {
						$appliedBefore = true;
					}
				}
			?>
			<div class="container my-5">
				<div class="row my-3">
					<h3><?php echo $jobDetails["position"]?></h3>
				</div>
				<div class="mx-2 row">
					<div class="col-md-8">
						<p><?php echo $jobDetails["employing_company"]?> [<?php echo $jobDetails["city"]?>, <?php echo $jobDetails["state"]?>]</p>
					</div>
					<div class="col-md-4">
						<p></p>
					</div>
				</div>
				<div class="mx-2 row">
					<div class="col-md-8">
						<p>Start Date: <?php echo $jobDetails["start_date"]?></p>
					</div>
					<div class="col-md-4">
						<p>Salary: $<?php echo $jobDetails["salary"]?></p>
					</div>
				</div>
				<div class="hidden" id="details<?php echo $jobDetails["jobid"] ?>">
					<div class="mx-2 row">
						<div class="col-md-4">
							<p>Required Education:</p>
						</div>
						<div class="col-md-8">
							<p><?php echo $jobDetails["required_education"]?></p>
						</div>
					</div>
					<div class="mx-2 row">
						<div class="col-md-4">
							<p>Required Skils:</p>
						</div>
						<div class="col-md-8">
							<p><?php echo $jobDetails["required_skills"]?></p>
						</div>
					</div>
					<div class="mx-2 row">
						<div class="col-md-4">
							<p>Job Specific Requirements:</p>
						</div>
						<div class="col-md-8">
							<p><?php echo $jobDetails["required_job_specific"]?></p>
						</div>
					</div>
					<div class="mx-2 row">
						<div class="col-md-4">
							<p>Required Prior Experience:</p>
						</div>
						<div class="col-md-8">
							<p><?php echo $jobDetails["required_prior_experience"]?></p>
						</div>
					</div>
					<div class="mx-2 row">
						<p>Posted on: <?php echo $jobDetails["posted_date"]?></p>
					</div>
				</div>
				<div class="row toggleBtn">
					<p onclick="showMore(<?php echo $jobDetails['jobid'] ?>)" style="font-size: .8rem" class="showMore offset-md-9" id="more<?php echo $jobDetails["jobid"] ?>">+ Show More</p>
					<p onclick="showLess(<?php echo $jobDetails['jobid'] ?>)" style="font-size: .8rem" class="hidden showLess offset-md-9" id="less<?php echo $jobDetails["jobid"] ?>">- Show Less</p>
				</div>
				<form action="../Resources/php/server/Apply.php" method="post" id="applyForm">
					<input type="hidden" name="jobidEntry" value="<?php echo $jobDetails["jobid"]; ?>">
					<input type="hidden" name="jobseekerEntry" value="<?php echo $currUser; ?>">
					<input type="hidden" name="desiredSalaryEntry" value="<?php echo $jobDetails["salary"]; ?>">
					<input type="hidden" name="desiredStartEntry" value="<?php echo $jobDetails["start_date"]; ?>">
					<?php
					if (!$appliedBefore) {
					?>
					<button type="submit" class="btn btn-dark w-20 mt-3">Apply Now</button>
					<?php
					}
					else {
					?>
					<button type="submit" disabled class="btn btn-dark w-20 mt-3">Application Completed</button>
					<?php	
					}
					?>
				</form>
			</div>
			<hr>
			<?php
			}
			?>
		</div>
		<?php
		unset($_SESSION["search"]);
		unset($_SESSION["jobResults"]);
		unset($_SESSION["appliedResults"]);
		}
		?>

        <!-- NAVIGATION FOOTER START -->
        <nav class="navbar fixed-bottom navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-btn btn-primary btn" href="https://twitter.com/CDA_Gamers"><i class="fab fa-twitter"></i> Twitter </a>
                <a class="navbar-btn btn-primary btn" href="https://t.co/gvolbJr5ng"><i class="fab fa-youtube"></i> YouTube </a>
            </div>
        </nav>
        <!-- NAVIGATION FOOTER END -->
    </div>
    <script src="../Resources/static/js/script.js" type="text/javascript"></script>
</body>
</html>