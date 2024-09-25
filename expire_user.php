<?php
namespace ExternalModules;

require_once "../../redcap_connect.php";

header('Content-type: application/json');
http_response_code(200);

if(ExternalModules::isSuperUser()){
    $username = $_REQUEST["username"];
    if(!isset($username)){
        $error_json = json_encode(['status'=>'failure', 'description'=>'Critical error: External module endpoint was called without an username.']);
        echo $error_json;
        exit();
    }

    $num_projects = $module->query(
        "select count(*) as proj_count from redcap_user_rights where username = ?",
        [$username]
    );

    $num_projects_result = $num_projects->fetch_assoc();

    if(!$num_projects_result['proj_count']){
        $error_json = json_encode(['status'=>'failure', 'description'=>'User <b>'. $module->escape($username) . '</b> is not associated with any project.']);
        echo $error_json;
        exit();
    }

    $today = date("Y-m-d");

    $already_expired = $module->query(
        "select * from redcap_user_rights where username = ? and not (expiration is null or expiration > ?)", 
        [$username, $today]
    );

    $unexpired_query = $module->query(
        "select * from redcap_user_rights where username = ? and (expiration is null or expiration > ?)",
        [$username, $today]
    );

    // if there are projects to expire
    while ($row = $unexpired_query->fetch_assoc()) {
        $project_id = $row['project_id'];
        $update_query = $module->query(
            "update redcap_user_rights set expiration = current_date() - INTERVAL 1 DAY where username = ? and project_id = ?",
            [$username, $project_id]
        );

        if($update_query){
            $temp_query = "update redcap_user_rights set expiration = current_date() - INTERVAL 1 DAY where username = '$username' and project_id = $project_id;";
            \REDCap::logEvent("Updated User Expiration " . $username, "user = '" . $username. "'", $temp_query, NULL, NULL, $project_id);
            $yesterday = date("Y-m-d", strtotime("-1 day"));
            $module->log('Expired user \''.$username.'\' from project ID '.$project_id.' on '.$yesterday);
        }
    }

    $return_json = json_encode(['status'=>'success','unexpired' => $unexpired_query->num_rows, 'already' => $already_expired->num_rows]); 
    // for ($x = 0; $x <= 100000000; $x++) {}
    echo $return_json;
}
else{
    $error_json = json_encode(['status'=>'failure', 'description'=>'User is not a superuser.']);
    echo $error_json;
}
?>
