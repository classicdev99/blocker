<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$data_to_store = filter_input_array(INPUT_POST);
    $db = getDbInstance();
    //Check whether the user name already exists ; 
    $db->where('user_name',$data_to_store['user_name']);
    $db->get('admin_accounts');
    
    if($db->count >=1){
        $_SESSION['failure'] = "User name already exists";
        header('location: ../signup.php');
        exit();
    }

    //Encrypt password
    $data_to_store['password'] = password_hash($data_to_store['password'],PASSWORD_DEFAULT);
    //reset db instance
    $db = getDbInstance();
    $last_id = $db->insert ('admin_accounts', $data_to_store);
    if($last_id)
    {

    	$_SESSION['success'] = "User registered successfully!";
    	header('location: ../login.php');
    	exit();
    }  
    
}
?>