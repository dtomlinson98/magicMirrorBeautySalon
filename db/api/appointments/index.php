<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//get http method
$method = $_SERVER['REQUEST_METHOD'];

//OPTIONS request
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

//conditional requring relevant file
switch ($method) {
    case 'GET':
        require_once 'read.php';
        break;
    case 'POST':
        require_once 'create.php';
        break;
    case 'PUT':
        require_once 'update.php';
        break;
    case 'DELETE':
        require_once 'delete.php';
        break;
    default:
        http_response_code(405);
        echo json_encode(array('message' => 'Method Not Allowed'));
        break;
}
?>
