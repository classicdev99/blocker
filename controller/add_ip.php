<?php 
session_start();
require_once '../config/config.php';

if($_SESSION['admin_type']!='super'){
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit("401 Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$ip = filter_input(INPUT_POST, 'ip');
	$reason = filter_input(INPUT_POST, 'reason');

	$db = getDbInstance();

    $new_ip = array(
        'ip' => $ip,
        'reason' => $reason
    );

    $last_id = $db->insert('banned_ip', $new_ip);

    if($last_id){
        header('location: ../blocking.php');
        exit;
    }
}

?>