<?php
//include necessary files
include_once '../../config/Database.php';
include_once '../../models/Appointment.php';

//instantiate the database connection
$database = new Database();
$db = $database->connect();

//instantiate the object
$appointment = new Appointment($db);

//get the appointment_id from the request parameters
$appointment->appointment_id = isset($_GET['id']) ? $_GET['id'] : die();

//attempt to fetch the single appointment
$appointmentData = $appointment->read_single();

//check if appointment was found
if ($appointmentData) {
    // Format appointment data
    $appointmentItem = array(
        'appointment_id' => $appointmentData['appointment_id'],
        'customer_id'    => $appointmentData['customer_id'],
        'service'        => $appointmentData['service'],
        'date'           => $appointmentData['date'],
        'time'           => $appointmentData['time']
    );

    // Output the appointment data as JSON
    echo json_encode($appointmentItem);
} else {
    // No appointment found
    echo json_encode(array('message' => 'Appointment Not Found'));
}
?>
