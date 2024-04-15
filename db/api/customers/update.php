<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT'); //PUT updates
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    

    include_once '../../config/Database.php';
    include_once '../../models/Customer.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog post object
    $customer = new Customer($db);

    //get raw posted data
    $data = json_decode(file_get_contents('php://input'));

    //checkign all parameters are given
    if(isset($data->customer_id) && isset($data->name) && isset($data->email)) {
        //set id
        $customer->customer_id = $data->customer_id;
        //set name
        $customer->name = $data->name;
        //set email
        $customer->email = $data->email;
    
        // if parameters are given and update returns true.. 
        if($customer->update()) {
            //response
            $response = array(
                'message' => 'Customer Updated',
                'customer_id' => $customer->customer_id,
                'customer' => $customer->name,   
                'email' => $customer->email 
            );
            echo json_encode($response);
            }
        } else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        }
    
?>