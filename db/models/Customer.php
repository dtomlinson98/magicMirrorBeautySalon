<?php
    class Customer {
        // DB stuff
        private $conn;
        private $table = 'customers';

        // customer properties
        public $id;
        public $name;
        public $email;

        // setter
        public function __construct($db) {
            $this->conn = $db;
        }

        // getter
        public function read() {

            //define query
            $query = 
            "SELECT {$this->table}.customer_id, 
            {$this->table}.name, 
            {$this->table}.email 
            FROM {$this->table} 
            ORDER BY {$this->table}.customer_id";

            //prepare query
            $stmt = $this->conn->prepare($query);

            //execute query 
            $stmt->execute();

           //fetching all customers 
            $customersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Cast the 'id' field to string in each customer data
            foreach ($customersData as &$customer) {
                $customer['customer_id'] = (string) $customer['customer_id'];
            }

            return $customersData;
        }

        //get single customer
        public function read_single() {

            //define query
            $query = 
            "SELECT
            name, email 
            FROM {$this->table} 
            WHERE customer_id = ? 
            LIMIT 1";

            //prepare query
            $stmt = $this->conn->prepare($query);

            //bind 
            $stmt->bindParam(1, $this->id);
        
            $stmt->execute();
           
            //if customer found
            if ($stmt->rowCount() > 0) {
                //fetch customer
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->name = $row['name'];
                $this->email = $row['email'];
                return true;
            } else {
                return false; 
            }
        }

        //create customer
        public function create() {

            // get data from POST request
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;

            //define query
            $query = 
            "INSERT INTO 
            {$this->table} 
            (name, email) 
            VALUES 
            (:name, :email)";
                                             
            // prepare stmt
            $stmt = $this->conn->prepare($query);

            //clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));

            //bind data
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);

            //execute query
            $executeResult = $stmt->execute();

            //execute query
            if($executeResult) {
                //if stmt executes customer_id will be set to the last inserted id in the table
                $customer_id = $this->conn->lastInsertId();
                //then that value will be used in the response
                $response = array(
                    'message' => 'Customer Created',
                    'id' => $customer_id,
                    'name' => $name,
                    'email' => $email
                );
                return $response;
            } else { 
                //if execute fails then messsage will be returned
                $response = array("message" => "Statement Execute Failed");
                return $response;
                
            }
        }

        //update customer
        public function update() {

            //define query
            $query = 
            "UPDATE {$this->table} 
            SET name = :name, email = :email 
            WHERE customer_id = :customer_id";
                                              
            //prepare stmt
            $stmt = $this->conn->prepare($query);

            //clean data
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));

            //bind data
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':customer_id', $this->customer_id);

            //execute query
            //wrapping in conditional to set to true or false
            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        //delete customer
        public function delete() {
            //check if customer exists
            if (!$this->customerExists()) {
                return false;
            }

            //define query
            $query = 
            "DELETE 
            FROM {$this->table} 
            WHERE customer_id = :customer_id";
            
            //prepare stmt
            $stmt = $this->conn->prepare($query);

            //clean data
            $this->id = htmlspecialchars(strip_tags($this->customer_id));

            //bind data
            $stmt->bindParam(':customer_id', $this->customer_id);

            //execute query
            $stmt->execute();
            
            return true;
        }

             //function checking if customer id exists
             public function customerExists() {
                //define query
                $query = "SELECT customer_id FROM {$this->table} WHERE customer_id = :customer_id";
                
                //prepare stmt
                $stmt = $this->conn->prepare($query);
                
                //clean data
                $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));
                
                //bind data
                $stmt->bindParam(':customer_id', $this->customer_id);
                
                //execute query
                $stmt->execute();
    
                if ($stmt->rowCount() > 0) {
                    return true; 
                } else {
                    return false;
                }
            }
    }
?>
