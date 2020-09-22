<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta char="UTF-8">
	<title> TeamCDA Website - Form Results! </title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
	<!-- Style Scripts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat|Roboto">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
	<link rel="stylesheet" href="../static/css/style.css" type="text/css">
	<!-- End of Style Scripts -->
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/ccfb95e64e.js" crossorigin="anonymous"></script>
	<script src="../static/js/script.js"></script>
	<!-- Favicon Code - Include ALL Below -->
	<link rel="shortcut icon" href="../static/icon/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" sizes="180x180" href="../static/icon/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="../static/icon/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="../static/icon/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="../static/icon/manifest.json">
	<link rel="mask-icon" href="../static/icon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="msapplication-TileImage" content="../static/icon/mstile-144x144.png">
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
            <a class="nav-link" href="../../index.php"><i class="fas fa-home"></i> Home </a>
            </li>
            <li class="nav-item disabled">
            <a class="nav-link" href="#"><i class="fas fa-exclamation-circle"></i> About </a>
            </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> Apps </a>
            <div class="dropdown-menu" aria-labelledby="Preview">
                <a class="dropdown-item" href="../../Pages/App_APIHub.html"> APIHub - Pokemon GO </a>
                <a class="dropdown-item" href="../../Pages/NecroEase/index.html"> NecroEase - Pokemon GO </a>
            </div>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-gamepad"></i> Games </a>
            <div class="dropdown-menu" aria-labelledby="Preview">
                <a class="dropdown-item" href="../../Pages/Game_Pacman.html">Bandai Namco - Pac-Man</a>
            </div>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-download"></i> Downloads </a>
            <div class="dropdown-menu" aria-labelledby="Preview">
                <a class="dropdown-item disabled" href="#"> All Downloads </a>
            </div>
        </li>
        </ul>
        </div>
    </nav>
	<!-- NAVIGATION HEADER END -->
	<div class="container" style="overflow-x:auto;">
        <h1>Job Applicant - Submission Form Results</h1>
        <br />
		
		<table>
        <?php
            // Print Position Info and Details
            // TODO: Make the Details dynamic based upon position
            echo "<h2 style=\"text-align:left\">";
            echo "Position: ";
            echo $_POST["Position"];
            echo "</h2>";
            echo "<br />";
            echo "<h3 style=\"text-align:left\">";
            echo "Details:";
            echo "</h3>";
            echo "<p style=\"text-align:left\">";
            echo "<script>document.write(demoString);</script>";
            echo "</p>";
            echo "<hr />";

			foreach ($_POST as $key => $value) {
				echo "<tr>";
				echo "<td>";
				echo str_replace('_', ' ', htmlspecialchars($key));
				echo ":</td>";
                echo "<td>";
                if (is_array($value)) {
					echo htmlspecialchars(implode(",", $value));
				}
				else {
					echo htmlspecialchars((empty($value) ? "N/A" : $value));
				}
				echo "</td>";
				echo "</tr>";
			}
		?>
		</table>
		<hr />
        <p style="text-align:center;white-space: pre-wrap;font-size: x-large;">
Thank you for your Submission!
You will be contacted at a later date, pending further information!
        </p>
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