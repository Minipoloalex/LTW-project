<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}
if (!isset($_POST['message'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing message parameter'));
    exit();
}

$message = $_POST['message'];
$userID = $session->getId();

$db = getDatabaseConnection();

$message = Message::addMessage($db, $userID, $message);

if (!$message) {
    http_response_code(500); // Internal server error
    echo json_encode(array('error' => 'Failed to add message to database'));
    exit();
}
// message has 4 fields: id, text, userID, date
echo json_encode(array(
    'id' => $message->id,
    'text' => $message->text,
    'userID' => $message->userID,
    'date' => date('F j', $message->date),
));
?>
