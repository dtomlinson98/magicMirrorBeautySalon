<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// include database and customer model
include_once '../../config/Database.php';
include_once '../../models/Customer.php';

// instantiate DB and connect
$database = new Database();
$db = $database->connect();

// instantiate DB and customer object
$customer = new Customer($db);

// get raw posted data
$data = json_decode(file_get_contents('php://input'));

// assign data
$customer->name = isset($data->name) ? $data->name : null;
$customer->email = isset($data->email) ? $data->email : null;

// create customer and get the response
$response = $customer->create();

// Check if the response is successful and return it
if ($response) {
    echo json_encode($response);
} else {
    echo json_encode(array('message' => 'customer_id Not Found'));
}
?>
