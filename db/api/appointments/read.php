<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Appointment.php';

    //instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate blog customer object
    $appointment = new Appointment($db);
    
    if(isset($_GET['appointment_id'])) {
        include_once 'read_single.php';
    } else {
        //fetch all appointments
        $result = $appointment->read();
    
        if ($result) {
            // Output JSON directly
            echo json_encode($result);
        } else {
            // No appointments found
            echo json_encode(array('message' => 'No Appointments Found'));
        }
    }
    ?>