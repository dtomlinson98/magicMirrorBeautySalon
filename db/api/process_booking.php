<?php
header('Content-Type: application/json');

//include database and appointment models
include_once '../config/Database.php';
include_once '../models/Appointment.php';
include_once '../models/Customer.php';

//instantiate database connection
$database = new Database();
$db = $database->connect();

//instantiate appointment and customer
$appointment = new Appointment($db);
$customer = new Customer($db);

//extract data
$name = isset($_POST['name']) ? $_POST['name'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$service = isset($_POST['service']) ? $_POST['service'] : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;
$time = isset($_POST['time']) ? $_POST['time'] : null;


//create customer
$customer->name = $name;
$customer->email = $email;
$customerResult = $customer->create();

if ($customerResult && isset($customerResult['id'])) {
    //customer created 
    $customer_id = $customerResult['id'];

    //create appointment
    $appointment->service = $service;
    $appointment->date = $date;
    $appointment->time = $time;
    $appointment->customer_id = $customer_id;
    $appointmentResult = $appointment->create($customer_id);

    if ($appointmentResult && isset($appointmentResult['id'])) {
        //appointment created 
        header("Location: confirmation.php?appointment_id=" . $appointmentResult['id']);
        exit();
    } else {
        //failed to create appointment
        echo json_encode(array('message' => 'Failed to create appointment'));
    }
} else {
    //failed to create customer
    echo json_encode(array('message' => 'Failed to create customer'));
}
?>
