<?php

// Why use two different functions fetch_assoc and db_query?

namespace WCM\TestModule;

echo "<h2> Mass Exipring :: test1</br></h2>\n";

$username = 'test1';
$result = $module->query("select * from redcap_user_rights where username = '$username'", []);

$today = date("Y-m-d");

while($row = $result->fetch_assoc()){
    $project_id = $row['project_id'];
    //$expiratoin = $row['expiration'];
    
    // $temp_query = "update redcap_user_rights set expiration = current_date() where username = '$username' and project_id = '$project_id' and expiration = '$expiration';"; 
    $temp_query = "update redcap_user_rights set expiration = current_date() where username = '$username' and project_id = $project_id;"; 
    echo "$temp_query</br>\n";
    if(db_query($temp_query)){
        echo "Todo: Update Project $project_id log.</br>\n";
        \REDCap::logEvent("Updated User Expiration " . $username, "user = '" . $username. "'", $temp_query, NULL, NULL, $project_id);
    }
}
