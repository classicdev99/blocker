<?php 
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$id = filter_input(INPUT_GET, 'unban_id');

	$db = getDbInstance();

    $db->where('id',$id);
    $status = $db->delete('banned_cidr');

    if($status){
        header('location: ../blocking.php');
        exit;
    }
}
?>