<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT'); // PUT updates
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//include database and appointment model
include_once '../../config/Database.php';
include_once '../../models/Appointment.php';

//instantiate DB and connect
$database = new Database();
$db = $database->connect();

//instantiate appointment object
$appointment = new Appointment($db);

//get raw posted data
$data = json_decode(file_get_contents('php://input'));

//check if all parameters are given
if(isset($data->appointment_id) && isset($data->service) && isset($data->date) && isset($data->time)) {
    //set appointment data
    $appointment->appointment_id = $data->appointment_id;
    $appointment->service = $data->service;
    $appointment->date = $data->date;
    $appointment->time = $data->time;

    //ff parameters are given and update returns true
    if($appointment->update()) {
        $response = array(
            'message' => 'Appointment Updated',
            'appointment_id' => $appointment->appointment_id,
            'service' => $appointment->service,
            'date' => $appointment->date,
            'time' => $appointment->time
        );
        echo json_encode($response);
    } else {
        echo json_encode(array('message' => 'Failed to update appointment'));
    }
} else {
    //ff any required parameter is missing
    echo json_encode(array('message' => 'Missing Required Parameters'));
}
?>
