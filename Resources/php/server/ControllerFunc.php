<?php
function getData($link, $query) {
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_assoc($result)) {
        $resultArr[] = $row;
    }
    if (!empty($resultArr)) {
        return $resultArr;
    }
}
?>