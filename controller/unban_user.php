<?php 
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$id = filter_input(INPUT_GET, 'unban_id');

	$db = getDbInstance();

    $update_data = array(
        'blocked' => 0
    );

    $db->where('id',$id);
     $stat = $db->update('users',$update_data);
     
        header('location: ../users.php');
        exit;
}
?>