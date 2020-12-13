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
$appHist =[];
$currUser = $_SESSION["username"];
$sql = "SELECT jobs.jobid as jobid, position, DATE_FORMAT(start_date, '%b %d, %Y') as start_date, salary, required_education, required_skills, required_job_specific, required_prior_experience, city, state, employing_company FROM `applied_for` INNER JOIN `jobs` ON applied_for.jobid = jobs.jobid INNER JOIN `people` ON jobs.employer = people.username WHERE applied_for.jobseeker = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $currUser);
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($res)) {
            array_push($appHist, $row);
        }
    }
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
                                echo " href=\"../Pages/EditProfile.php\">Profile</a>";
                                echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Pages/EditProfile.php\">Edit Profile</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Pages/History.php\">Application history</a>";
								echo "<a";
								echo " class=\"dropdown-item\"";
								echo " href=\"../Pages/Search.php\">Search jobs</a>";
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
						<a class="dropdown-item" href="../Resources/php/server/Reset_Password.php">Reset password</a>
						<a class="dropdown-item" href="../Resources/php/server/Logout.php">Sign out</a>
					</div>
				</li>
			</ul>
		</div>
	</nav>
	<!-- NAVIGATION HEADER END -->
    <div class="registerEmp-wrapper">
        <h2>Application History</h2>
        <p>Click on the headers to toggle between ascending/descending sorts by title, start date, or salary.</p>
        <p>Click on each job summary for full description.</p>
        <div class="histTable">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th id="titleHead" onclick="sortByCol(document.querySelector('table'), 1, this.id)">Job Title</th>
                        <th id="startHead" onclick="sortByCol(document.querySelector('table'), 2, this.id)">Start Date</th>
                        <th id="salaryHead" onclick="sortByCol(document.querySelector('table'), 3, this.id)">Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($appHist as $app) {
                    ?>
                    <tr data-toggle="collapse" data-target="#accordion_<?php echo $app["jobid"]; ?>" class="clickable">
                        <td><?php echo $app["position"] ?></td>
                        <td><?php echo $app["start_date"] ?></td>
                        <td>$<?php echo $app["salary"] ?></td>
                    </tr>
                    <tr id="accordion_<?php echo $app["jobid"]; ?>" class="collapse">
                        <td colspan="3">
                            <div class="mx-2 row">
                                <div class="col-md-4">
                                    <p>Company:</p>
                                </div>
                                <div class="col-md-8">
                                    <p><?php echo $app["employing_company"]?></p>
                                </div>
                            </div>
                            <div class="mx-2 row">
                                <div class="col-md-4">
                                    <p>Location:</p>
                                </div>
                                <div class="col-md-8">
                                    <p><?php echo $app["city"]?>, <?php echo $app["state"]?></p>
                                </div>
                            </div>
                            <div class="mx-2 row">
                                <div class="col-md-4">
                                    <p>Required Education:</p>
                                </div>
                                <div class="col-md-8">
                                    <p><?php echo $app["required_education"]?></p>
                                </div>
                            </div>
                            <div class="mx-2 row">
                                <div class="col-md-4">
                                    <p>Required Skils:</p>
                                </div>
                                <div class="col-md-8">
                                    <p><?php echo $app["required_skills"]?></p>
                                </div>
                            </div>
                            <div class="mx-2 row">
                                <div class="col-md-4">
                                    <p>Job Specific Requirements:</p>
                                </div>
                                <div class="col-md-8">
                                    <p><?php echo $app["required_job_specific"]?></p>
                                </div>
                            </div>
                            <div class="mx-2 row">
                                <div class="col-md-4">
                                    <p>Required Prior Experience:</p>
                                </div>
                                <div class="col-md-8">
                                    <p><?php echo $app["required_prior_experience"]?></p>
                                </div>
					        </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
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
    <script src="../Resources/static/js/script.js" type="text/javascript"></script>
</body>
</html>