<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../../config/database.php';
    include_once '../../class/rooms.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new Room($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->id = $data->id;
    
    // room values
    $item->name = $data->name;
    $item->number = $data->number;
    $item->created = date('Y-m-d H:i:s');
    
    if($item->updateRoom()){
        echo json_encode("Room data updated.");
    } else{
        echo json_encode("Data could not be updated");
    }
?>