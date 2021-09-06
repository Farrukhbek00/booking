<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../config/database.php';
    include_once '../../class/reservations.php';
    include_once '../../class/customers.php';
    include_once '../../class/rooms.php';
    require_once "../../vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    $database = new Database();
    $db = $database->getConnection();

    $reservation = new Reservation($db);

    $data = json_decode(file_get_contents("php://input"));
    $reservation->room_id = $data->room_id;
    $reservation->start_date = $data->start_date;
    $reservation->end_date = $data->end_date;
  
    $reservation->findReservation();

    if ($reservation->no != null) {
        $first_customer = new Customer($db);
        $first_customer->id = $data->customer_id;
        $first_customer->getSingleCustomer();

        $second_customer = new Customer($db);
        $second_customer->id = $reservation->customer_id;
        $second_customer->getSingleCustomer();

        $room = new Room($db);
        $room->id = $data->room_id;
        $room->getSingleRoom();

        $mail = new PHPMailer(true); 
        try {
            $mail->SMTPDebug = 3; 
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'a60435026f501e'; 
            $mail->Password = '83052277fb62e6';
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587;

            $mail->setFrom('farruhzokirov00@gmail.com', 'Farrukh Zokirov');
            $mail->addReplyTo('farruhzokirov00@gmail.com', 'Mailtrap');
            $mail->addAddress($first_customer->email, $first_customer->first_name . ' ' . $first_customer->last_name);
            $mail->addCC('cc1@example.com', 'Elena');
            $mail->addBCC('bcc1@example.com', 'Alex');


            $mail->Subject = "Booking the room";
            $mail->isHTML(true);

            if ($data->customer_id == $reservation->customer_id) {
                $mail->Body = "<i>Hello $first_customer->first_name $first_customer->last_name 
                    You booked the room No $room->number from $reservation->start_date to $reservation->end_date</i>";
            } else {
                $mail->Body = "<i>Hello $first_customer->first_name $first_customer->last_name 
                    Sorry, the room No $room->number bokked by $second_customer->first_name  $second_customer->last_name 
                    from $reservation->start_date to $reservation->end_date</i>";
            }
            $mail->send();
            echo "Message has been sent successfully";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } 
      
        http_response_code(200);
        echo json_encode("Sent to email!");
    } else{
        http_response_code(404);
        echo json_encode("Reservation not found.");
    }
?>