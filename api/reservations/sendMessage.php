<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../config/database.php';
    include_once '../../class/reservations.php';
    include_once '../../class/customers.php';

    use PHPMailer\PHPMailer\PHPMailer;

    

    $database = new Database();
    $db = $database->getConnection();

    $reservation = new Reservation($db);

    $data = json_decode(file_get_contents("php://input"));
    $reservation->room_id = $data->room_id;
    $reservation->start_date = $data->start_date;
    $reservation->end_date = $data->end_date;
  
    $reservation->findReservation();

    if ($reservation->no != null) {
        if ($data->customer_id == $reservation->customer_id) {
            $customer = new Customer($db);
            $customer->id = $data->customer_id;
            $customer->getSingleCustomer();

            //Load Composer's autoloader
            require '../../vendor/autoload.php';  
            $mail = new PHPMailer(); 
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'a60435026f501e'; 
            $mail->Password = '83052277fb62e6';
            $mail->SMTPSecure = 'tls';

            $mail->setFrom("dasas@example.com");
            $mail->addAddress("sdas@example.com");
            $mail->addReplyTo("1810132fz@example.com", "Reply");
            $mail->addCC("cc@example.com");
            $mail->addBCC("bcc@example.com");

            $mail->isHTML(true);

            $mail->Subject = "Subject Text";
            $mail->Body = "<i>Mail body in HTML</i>";
            $mail->AltBody = "This is the plain text version of the email content";

            try {
                $mail->send();
                // echo "Message has been sent successfully";
            } catch (Exception $e) {
                // echo "Mailer Error: " . $mail->ErrorInfo;
            } 
        } else {
            
        }
      
        http_response_code(200);
        echo json_encode("Sent to email!");
    } else{
        http_response_code(404);
        echo json_encode("Reservation not found.");
    }
?>