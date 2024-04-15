<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE'); // DELETE deletes
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    
    // Include database and appointment model
    include_once '../../config/Database.php';
    include_once '../../models/Appointment.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate appointment object
    $appointment = new Appointment($db);

    // Get appointment id
    $data = json_decode(file_get_contents("php://input"));

    // Check if appointment ID is provided
    if (!empty($data->appointment_id)) {
        // Set appointment ID
        $appointment->appointment_id = $data->appointment_id;

        // Delete appointment
        if ($appointment->delete()) {
            echo json_encode(array(
                'message' => 'Appointment Deleted',
                'appointment_id' => $data->appointment_id
            ));
        } else {
            echo json_encode(array('message' => 'No Appointment Found'));
        }
    } else {
        echo json_encode(array('error' => 'Appointment ID not provided'));
    }
?>
