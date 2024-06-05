<?php

require_once "../../redcap_connect.php";

header('Content-type: application/json');
http_response_code(200);

$username = $_REQUEST["username"];
// $response = ['username' => $username];
// echo json_encode($response); // {"a":1,"b":2,"c":3,"d":4,"e":5}


$today = date("Y-m-d");
$query = "select * from redcap_user_rights where username = '$username' and (expiration is null or expiration > '$today')";

$result = mysqli_query($conn, $query);

// echo json_encode(['value' => $result->num_rows]);

while ($row = mysqli_fetch_assoc($result)) {
    $project_id = $row['project_id'];
    $temp_query = "update redcap_user_rights set expiration = current_date() where username = '$username' and project_id = $project_id;"; 

    // echo "Expiring from from project <b>" . $project_id . "</b> on <b>" . $today . "</b></br>\n";
    
    if(db_query($temp_query)){
        \REDCap::logEvent("Updated User Expiration " . $username, "user = '" . $username. "'", $temp_query, NULL, NULL, $project_id);
        // echo "Updated project log.</br></br>\n";
    }
}

