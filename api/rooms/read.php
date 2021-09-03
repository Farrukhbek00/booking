<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../../config/database.php';
    include_once '../../class/rooms.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Room($db);

    $stmt = $items->getRooms();
    $itemCount = $stmt->rowCount();

    if($itemCount > 0){
        $roomArr = array();
        $roomArr["body"] = array();
        $roomArr["itemCount"] = $itemCount;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "id" => $id,
                "name" => $name,
                "number" => $number,
                "created" => $created
            );
            array_push($roomArr["body"], $e);
        }
        echo json_encode($roomArr);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>