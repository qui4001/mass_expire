<?php

require_once "../../redcap_connect.php";

header('Content-type: application/json');
http_response_code(200);

$username = $_REQUEST["username"];

$today = date("Y-m-d");

$already_expired = $module->query(
    "select * from redcap_user_rights where username = ? and not (expiration is null or expiration > ?)", 
    [$username, $today]
);

$unexpired_query = $module->query(
    "select * from redcap_user_rights where username = ? and (expiration is null or expiration > ?)",
    [$username, $today]
);

while ($row = $unexpired_query->fetch_assoc()) {
    $project_id = $row['project_id'];
    $temp_query = "update redcap_user_rights set expiration = current_date() - INTERVAL 1 DAY where username = '$username' and project_id = $project_id;"; 
    $yesterday = date("Y-m-d", strtotime("-1 day"));
    
    if(db_query($temp_query)){
        \REDCap::logEvent("Updated User Expiration " . $username, "user = '" . $username. "'", $temp_query, NULL, NULL, $project_id);
        $module->log('Expired user \''.$username.'\' from project ID '.$project_id.' on '.$yesterday);
    }
}

$return_json = json_encode(['unexpired' => $unexpired_query->num_rows, 'already' => $already_expired->num_rows]); 

echo $return_json;
?>
