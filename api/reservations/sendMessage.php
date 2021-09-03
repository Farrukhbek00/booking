<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../config/database.php';
    include_once '../../class/reservations.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Reservation($db);

    $data = json_decode(file_get_contents("php://input"));
    $item->room_id = $data->room_id;
    $item->start_date = $data->start_date;
    $item->end_date = $data->end_date;
  
    $item->findReservation();

    if($item->no != null){
        
        if ($data->customer_id == $item->customer_id) {
            $customer = new Customer($db);
            $customer->id = $data->customer_id;
            $customer->getSingleCustomer();

            if ($customer->email != null) {
                $emp_arr = array(
                    "id" =>  $customer->id,
                    "first_name" => $customer->first_name,
                    "last_name" => $customer->last_name,
                    "email" => $customer->email,
                    "phone" => $customer->phone,
                    "created" => $customer->created
                );
              
                http_response_code(200);
                echo json_encode($emp_arr);
            }
            print_r("Teng");
        } else {
            
        }
      
        http_response_code(200);
        echo json_encode("Sent to email!");
    } else{
        http_response_code(404);
        echo json_encode("Reservation not found.");
    }
?>