<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE'); //DELETE deletes
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    

    include_once '../../config/Database.php';
    include_once '../../models/Customer.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

     //customer object
     $customer = new Customer($db);

     //get customer id
     $data = json_decode(file_get_contents("php://input"));
 
     // check if customer ID is provided
     if (!empty($data->customer_id)) {
         // Set customer ID
         $customer->customer_id = $data->customer_id;
 
         // delete customer
         if ($customer->delete() === true) {
            echo json_encode(array(
                'message' => 'Customer Deleted',
                'customer_id' => $data->customer_id));
        } else {
            echo json_encode(array('message' => 'No Customer Found'));
        }
    } else {
        echo json_encode(array('error' => 'Customer ID not provided'));
    }
 ?>