<?php 
session_start();
require_once '../config/config.php';

if($_SESSION['admin_type']!='super'){
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit("401 Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$ip = filter_input(INPUT_POST, 'cidr');
	$reason = filter_input(INPUT_POST, 'reason');

	$db = getDbInstance();

    $new_cidr = array(
        'cidr' => $ip,
        'reason' => $reason
    );

    $last_id = $db->insert('banned_cidr', $new_cidr);

    if($last_id){
        header('location: ../blocking.php');
        exit;
    }
}

?>