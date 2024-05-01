<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// include database and appointment models
include_once '../../config/Database.php';
include_once '../../models/Appointment.php';

//instantiate db and connect
$database = new Database();
$db = $database->connect();

//instantiate object
$appointment = new Appointment($db);

//get raw data
$data = json_decode(file_get_contents('php://input'));

//assign data
$appointment->service = isset($data->service) ? $data->service : null;
$appointment->date = isset($data->date) ? $data->date : null;
$appointment->time = isset($data->time) ? $data->time : null;

// Debugging
/* echo "Raw Data: " . json_encode($data); // Output the raw data received from the form
echo "Appointment Object: " . json_encode($appointment); // Output the Appointment object after assigning data */

//create and get the response
$response = $appointment->create();

//return response
if ($response && isset($response['id'])) {
    echo json_encode($response);
} else {
    echo json_encode(array('message' => 'Appointment Not Created'));
}
?>
