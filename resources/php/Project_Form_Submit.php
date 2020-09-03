<!DOCTYPE HTML>
<html>
    <head>
        <title>Test Page</title>
    </head>
    <body>

		<table>
		<?php


			foreach ($_POST as $key => $value) {
				echo "<tr>";
				echo "<td>";
				echo $key;
				echo "</td>";
				echo "<td>";
				echo $value;
				echo "</td>";
				echo "</tr>";
			}


		?>
		</table>
    </body>
</html>