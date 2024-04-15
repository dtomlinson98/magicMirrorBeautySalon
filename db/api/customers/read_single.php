<?php
    include_once '../../config/Database.php';
    include_once '../../models/Customer.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog customer object
    $customer = new Customer($db);
    
    $customer->id = isset($_GET['id']) ? $_GET['id'] : die();

    //single customer
    if ($customer->read_single()) {
        //customer id found
        $customerItem = array(
            'customer_id' => $customer->id,
            'name'        => $customer->name,
            'email'       => $customer->email
        );
        echo json_encode($customerItem);
        //customer id not found
    } else {
        //customer not found
        echo json_encode(array('message' => 'Customer Not Found'));
    }
    ?>
