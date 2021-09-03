<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../config/database.php';
    include_once '../../class/customers.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Customer($db);

    $data = json_decode(file_get_contents("php://input"));

    $item->first_name = $data->first_name;
    $item->last_name = $data->last_name;
    $item->email = $data->email;
    $item->phone = $data->phone;
    $item->created = date('Y-m-d H:i:s');
    
    if($item->createCustomer()){
        echo 'Customer created successfully.';
    } else{
        echo 'Customer could not be created.';
    }
?>