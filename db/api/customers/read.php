<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Customer.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog customer object
    $customer = new Customer($db);
    
    if(isset($_GET['id'])) {
        include_once 'read_single.php';
    //if id not given
    } else {
        //fetch all customers
        $result = $customer->read();
    
        //check if any customers
        $num = count($result);
        if($num > 0) {
            //create an array to hold the customer data
            echo json_encode($result);
        } else {
            //if no customers in datbase
            echo json_encode(array('message' => 'No Customers Found'));
        }
    }
    ?>