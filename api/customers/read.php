<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../../config/database.php';
    include_once '../../class/customers.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Customer($db);

    $stmt = $items->getCustomers();
    $itemCount = $stmt->rowCount();

    if($itemCount > 0){
        $customerArr = array();
        $customerArr["body"] = array();
        $customerArr["itemCount"] = $itemCount;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "id" => $id,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
                "phone" => $phone,
                "created" => $created
            );
            array_push($customerArr["body"], $e);
        }
        echo json_encode($customerArr);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>