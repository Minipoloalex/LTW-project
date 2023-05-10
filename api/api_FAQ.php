<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/forum.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
$db = getDatabaseConnection();

/*add faq*/
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in'));
        exit();
    }

    if (!is_valid_string($_POST['question'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing question parameter.'));
        exit();
    }

    $question = $_POST['question'];

    if (Forum::alreadyExists($db, $question)) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'A similar FAQ was found.'));
        exit();
    }
    $faq = Forum::addFaq($db, $question);

    if (!$faq) {
        http_response_code(500); // Internal server error
        echo json_encode(array('error' => 'Failed to add FAQ.'));
        exit();
    }

    $type = Client::getType($db, $session->getId());

    echo json_encode(array(
        'success' => 'Your question was successfully sent.',
        'id' => $faq->forumId,
        'question' => $faq->question,
        'answer' => $faq->answer,
        'displayed' => $faq->displayed,
        'type' => $type
    ));

    exit(); /*após uma mensagem (sucesso ou failure) deve levar exit()*/

}

/*edit faq and answer faq*/
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'You are not logged in.'));
        exit();
    }

    if (Client::getType($db, $session->getId()) !== 'Admin') {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'You are not authorized.'));
        exit();
    }

    // verify if all parameters are set
    if (!isset($_GET['question']) || !isset($_GET['answer'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing parameters.'));
        exit();
    }

    // verify if parameters are valid
    if (!is_valid_string($_GET['question']) || !is_valid_string($_GET['answer'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid parameters.'));
        exit();
    }
    if (!is_valid_id($_GET['id'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid parameters.'));
        exit();
    }

    /* ==================== */
    // !NOTE: verify duplicate faqs
    // verify if question exists
    // $question = $_GET['question'];
    // $answer = $_GET['answer'];
    // $faq = Forum::getFaq($db, $question, $answer); 

    // if ($faq !== NULL) {
    //     http_response_code(500); // Internal server error
    //     echo json_encode(array('error' => 'Found similar FAQ on database'));
    //     exit();
    // }

    /* ==================== */
    $id = $_GET['id'];
    $question = $_GET['question'];
    $answer = $_GET['answer'];

    
    // if (Forum::alreadyExists($db, $question, $id)) {
    //     http_response_code(500); // Internal server error
    //     echo json_encode(array('error' => 'Found similar FAQ on database'));
    //     exit();
    // }

    $faq = Forum::updateFaq($db, $question, $answer, $id);

    echo json_encode(array('success' => 'FAQ was updated successfully.', 'id' => $faq->forumId, 'question' => $faq->question, 'answer' => $faq->answer, 'displayed' => $faq->displayed));
    exit();
}

/*delete faq*/
// verify if DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // TODO: receive id
    // verify if user is logged in
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in'));
        exit();
    }

    // verify if user is admin
    if (Client::getType($db, $session->getId()) === "Client") {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'User not authorized'));
        exit();
    } 
    
    // $question = $_GET['question'];
    // $answer = $_GET['answer'];

    // $faq = Forum::getFaq($db, $question, $answer);

    // if (!$faq) {
    //     http_response_code(500); // Internal server error
    //     echo json_encode(array('error' => 'Failed to find FAQ on database'));
    //     exit();
    // }

    // delete FAQ
    $id = $_GET['id'];
    $faq = Forum::deleteFaq($db, $id);
    echo json_encode(array('success' => 'FAQ deleted successfully.'));
    exit();
}

/*change display attribute*/
if ($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'You are not logged in.'));
        exit();
    }

    if (Client::getType($db, $session->getId()) === 'Client') {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'You are not authorized.'));
        exit();
    }

    // verify if all parameters are set
    if (!isset($_GET['displayed'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'There are missing parameters.'));
        exit();
    }

    // verify is displayed is valid (0 or 1)
    if (!($_GET['displayed'] === '0' || $_GET['displayed'] === '1')) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid parameters.'));
        exit();
    }
    
    $displayed = $_GET['displayed'];
    $id = $_GET['id'];

    if (!Forum::updateDisplayed($db, $displayed, $id)){
        http_response_code(500); // Internal server error
        echo json_encode(array('error' => 'Failed to update FAQ.'));
        exit();
    }

    echo json_encode(array('success' => 'FAQ updated successfully.'));
    exit();
}

echo json_encode(array('error' => 'Invalid request method'));
?>

