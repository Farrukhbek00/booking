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

    $item->id = isset($_GET['id']) ? $_GET['id'] : die();
  
    $item->getSingleCustomer();

    if($item){
        // create array
        $emp_arr = array(
            "id" =>  $item->id,
            "first_name" => $item->first_name,
            "last_name" => $item->last_name,
            "email" => $item->email,
            "phone" => $item->phone,
            "created" => $item->created
        );
      
        http_response_code(200);
        echo json_encode($emp_arr);
    } else{
        http_response_code(404);
        echo json_encode("Customer not found.");
    }
?>