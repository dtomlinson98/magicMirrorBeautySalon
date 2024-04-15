<?php
    class Appointment {
        // DB stuff
        private $conn;
        private $table = 'appointments';

        // appointment properties
        public $appointment_id;
        public $customer_id;
        public $service;
        public $time;
        public $date;
        public $created_at;

        // setter
        public function __construct($db) {
            $this->conn = $db;
        }

        // getter
        public function read() {
            // Define query
            $query = "SELECT * FROM {$this->table}";
        
            // Prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // Execute query
            $stmt->execute();
        
            // Fetch all appointments data
            $appointmentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Cast the 'appointment_id' field to string in each appointment data
            foreach ($appointmentsData as &$appointment) {
                $appointment['appointment_id'] = (string) $appointment['appointment_id'];
            }
        
            // Return appointments data
            return $appointmentsData;
        }

        //get single customer
        public function read_single() {
            // Define query
            $query = 
            "SELECT * FROM {$this->table} 
            WHERE appointment_id = ? 
            LIMIT 1";
    
            // Prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // Bind appointment_id
            $stmt->bindParam(1, $this->appointment_id);
    
            // Execute query
            $stmt->execute();
    
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return single appointment
            return $appointment;
        }

        //create appointment
        public function create($customer_id) {
            // Define query
            $query = 
            "INSERT INTO {$this->table} 
            (service, date, time, customer_id) 
            VALUES (:service, :date, :time, :customer_id)";
            
            //prepare statement
            $stmt = $this->conn->prepare($query);
            
            //clean data
            $this->service = htmlspecialchars(strip_tags($this->service));
            
            //date and tiem format
            $this->date = isset($this->date) ? date("Y-m-d", strtotime($this->date)) : null;
            $this->time = isset($this->time) ? date("H:i:s", strtotime($this->time)) : null;
            
            //bind data
            $stmt->bindParam(':service', $this->service);
            $stmt->bindParam(':date', $this->date);
            $stmt->bindParam(':time', $this->time);
            $stmt->bindParam(':customer_id', $customer_id);
            
            //execute query
            try {
                if ($stmt->execute()) {
                    //appointment created 
                    $appointment_id = $this->conn->lastInsertId();
                    $response = array(
                        'id' => $appointment_id,
                        'service' => $this->service,
                        'date' => $this->date,
                        'time' => $this->time
                    );
                    return $response;
                } else {
                    //failed to create 
                    return array("message" => "Failed to create appointment");
                }
            } catch (PDOException $e) {
                // Error occurred
                return array("message" => "Error: " . $e->getMessage());
            }
        }
        //update customer
        public function update() {
            // Define the query
            $query = "UPDATE {$this->table} 
                      SET service = :service, date = :date, time = :time 
                      WHERE appointment_id = :appointment_id";
        
            // Prepare the statement
            $stmt = $this->conn->prepare($query);
        
            // Clean data
            $this->service = htmlspecialchars(strip_tags($this->service));
            $this->date = htmlspecialchars(strip_tags($this->date));
            $this->time = htmlspecialchars(strip_tags($this->time));
            $this->appointment_id = htmlspecialchars(strip_tags($this->appointment_id));
        
            // Bind data
            $stmt->bindParam(':service', $this->service);
            $stmt->bindParam(':date', $this->date);
            $stmt->bindParam(':time', $this->time);
            $stmt->bindParam(':appointment_id', $this->appointment_id);
        
            // Execute query and return result
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        //delete customer
        public function delete() {
            // Check if appointment exists
            if (!$this->appointmentExists()) {
                return false;
            }
        
            // Define query
            $query = "DELETE FROM {$this->table} WHERE appointment_id = :appointment_id";
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
        
            // Clean data
            $this->appointment_id = htmlspecialchars(strip_tags($this->appointment_id));
        
            // Bind data
            $stmt->bindParam(':appointment_id', $this->appointment_id);
        
            // Execute query
            $stmt->execute();
        
            return true;
        }
        
        // Function checking if appointment exists
        public function appointmentExists() {
            // Define query
            $query = "SELECT appointment_id FROM {$this->table} WHERE appointment_id = :appointment_id";
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
        
            // Clean data
            $this->appointment_id = htmlspecialchars(strip_tags($this->appointment_id));
        
            // Bind data
            $stmt->bindParam(':appointment_id', $this->appointment_id);
        
            // Execute query
            $stmt->execute();
        
            if ($stmt->rowCount() > 0) {
                return true; 
            } else {
                return false;
            }
        }
    }
?>
