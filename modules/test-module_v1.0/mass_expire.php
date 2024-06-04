<?php
require_once "../../redcap_connect.php";

$username = $_REQUEST["username"];

echo "<h2 style='color: #800000;'> Mass Expire </h2>";

$today = date("Y-m-d");
$query = "select * from redcap_user_rights where username = '$username' and (expiration is null or expiration > '$today')";

$result = mysqli_query($conn, $query);

// echo "<h5>$result->num_rows project(s) need(s) expiring for user <i>$username</i></h5>";

while ($row = mysqli_fetch_assoc($result)) {
    $project_id = $row['project_id'];
    $temp_query = "update redcap_user_rights set expiration = current_date() where username = '$username' and project_id = $project_id;"; 

    // echo "Expiring from from project <b>" . $project_id . "</b> on <b>" . $today . "</b></br>\n";
    
    if(db_query($temp_query)){
        \REDCap::logEvent("Updated User Expiration " . $username, "user = '" . $username. "'", $temp_query, NULL, NULL, $project_id);
        // echo "Updated project log.</br></br>\n";
    }
}

// num of expired project
echo $result->num_rows;
