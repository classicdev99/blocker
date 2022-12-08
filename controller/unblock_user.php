<?php 
session_start();
require_once '../includes/auth_validate.php';
require_once '../config/config.php';
$unblock_id = filter_input(INPUT_POST, 'unblock_id');
 $db = getDbInstance();

if($_SESSION['admin_type']!='super'){
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit("401 Unauthorized");
}

$update_data = array(
    'blocked' => 0
);
// Delete a user using user_id
if ($unblock_id && $_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db->where('id', $unblock_id);

    $stat = $db->update('users',$update_data);
    if ($stat) {
        header('location: ../index.php');
        exit;
    }
}