<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/..database/ticket.class.php');

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/main_page.php'));
}
$db = getDatabaseConnection();

$title = $_POST['title'];
$description = $_POST['description'];
$hashtags = $_POST['hashtags'];
$departmentName = $_POST['department'];
$assignedAgent = $_POST['assignedAgent'];
$status = $_POST['status'];
$priority = $_POST['priority'];
$submitDate = $_POST['submitDate'];
$userID = $_POST['userID'];     /* session userID */
$username = $_POST['username'];  /* session username */

if (Ticket::existsTicket($db, $title, $userID)) {
    $session->addMessage('error', "Ticket with the same title already exists");
    die(header('Location: ../pages/create_ticket.php'));
}
Ticket::createTicket($db, $title, $username, $status, $submitDate, $priority, $hashtags, $description, $assignedAgent, $deparmentName);
/*
comentário no ticket é chamada AJAX (pedido) no servidor para acrescentar, dá resposta a dizer que acrescentou
assim, não é necessário dar refresh à pagina e não se perde o contexto
servidor vai à BD e responde a dizer que acrescentou
*/

?>
