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

    $stmt = $item->getFilteredReservations($data->start_date, $data->end_date, $data->room_id);
    $itemCount = $stmt->rowCount();

    if($itemCount > 0) {
        echo 'Room is not available!';
    } else {
        $item->room_id = $data->room_id;
        $item->customer_id = $data->customer_id;
        $item->created = date('Y-m-d H:i:s');
        $item->start_date = $data->start_date;
        $item->end_date = $data->end_date;
        
        if($item->book()){
            echo 'Room booked successfully.';
        } else{
            echo 'Error';
        }
    }
?>