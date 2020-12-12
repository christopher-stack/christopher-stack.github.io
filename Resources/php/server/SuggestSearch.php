<?php

// Include the config file

use function PHPSTORM_META\type;

require_once "Config.php";

$suggestions = [];
$query = "%".$_GET["q"]."%";

$sql = "SELECT position FROM jobs WHERE position LIKE ? OR description LIKE ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "ss", $query, $query);
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        while ($row = $res->fetch_array()) {
            array_push($suggestions, $row["position"]);
        }
        
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(['positions' => $suggestions]);
    }
}

?>