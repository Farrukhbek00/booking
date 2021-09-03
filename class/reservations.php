<?php
    class Reservation {

        // Connection
        private $conn;

        // Table
        private $db_table = "reservation";

        // Columns
        public $room_id;
        public $customer_id;
        public $created;
        public $start_date;
        public $end_date;

        // DB connection
        public function __construct($db){
            $this->conn = $db;
        }

        // Get filtered reservations
        public function getFilteredReservations($start_date, $end_date, $room_id){
            $sqlQuery = "SELECT no FROM " . $this->db_table . " 
                WHERE ((end_date BETWEEN '$start_date' AND '$end_date')
                OR (start_date BETWEEN '$start_date' AND '$end_date'))
                AND (room_id = '$room_id')
                ";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // Booking the room
        public function book() {
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        room_id = :room_id, 
                        customer_id = :customer_id, 
                        created = :created,
                        start_date = :start_date, 
                        end_date = :end_date";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->room_id = htmlspecialchars(strip_tags($this->room_id));
            $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));
            $this->created = htmlspecialchars(strip_tags($this->created));
            $this->start_date = htmlspecialchars(strip_tags($this->start_date));
            $this->end_date = htmlspecialchars(strip_tags($this->end_date));
        
            // bind data
            $stmt->bindParam(":room_id", $this->room_id);
            $stmt->bindParam(":customer_id", $this->customer_id);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":start_date", $this->start_date);
            $stmt->bindParam(":end_date", $this->end_date);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }  
        
        // Find the reservation
        public function findReservation(){
            $sqlQuery = "SELECT
                        no, 
                        room_id,
                        customer_id, 
                        start_date, 
                        end_date,
                        created
                      FROM
                        ". $this->db_table ."
                    WHERE 
                       room_id = ? AND start_date = ? AND end_date = ?
                    LIMIT 0,1";

            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->bindParam(1, $this->room_id);
            $stmt->bindParam(2, $this->start_date);
            $stmt->bindParam(3, $this->end_date);

            $stmt->execute();

            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->no = $dataRow['no'];
            $this->room_id = $dataRow['room_id'];
            $this->customer_id = $dataRow['customer_id'];
            $this->start_date = $dataRow['start_date'];
            $this->end_date = $dataRow['end_date'];
            $this->created = $dataRow['created'];
        }  
    }
?>