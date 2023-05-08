<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');

require_once(__DIR__ . '/create_ticket.tpl.php');
?>
<?php function output_single_ticket_agent_view(Ticket $ticket, Session $session, array $all_hashtags, array $all_agents, array $all_departments) { 
    if (!$ticket->isClosed()) {
        output_change_ticket_info_form($ticket, $all_agents, $all_departments, $all_hashtags);
        output_close_ticket_button("agent");
    }
    else {
        output_single_ticket_info($ticket);
        output_reopen_ticket_form($ticket, $session);
    }
}
function output_single_ticket_client_view(Ticket $ticket, Session $session) {
    output_single_ticket_info($ticket);
    if ($ticket->isClosed()) output_reopen_ticket_form($ticket, $session);
    else output_close_ticket_button("client");
}
function output_single_ticket_info(Ticket $ticket) { ?>
    <span id="agent">Agent: <?=$ticket->assignedagent ?? "None"?></span>
    <span id="department">Department: <?=$ticket->departmentName ?? "None"?></span>
    <span id="priority">Priority: <?=$ticket->priority?></span>
    
    <?php output_hashtag_list($ticket->hashtags); ?>
<?php }
function output_single_ticket(Ticket $ticket, array $messages, array $actions,
array $all_hashtags, array $all_agents, array $all_departments, Session $session, bool $isAgentView) { ?>
    <article id="individual-ticket">
        <header><h1 id="ticket-title"><?=$ticket->title?></h1></header>
        <h3>Ticket description:</h3>
        <p id="ticket-description"><?=$ticket->description?></p>
        
        <div id="container-ticket-info">
            <section id="ticket-info">
                <header>
                    <h3 class="ticket-info-label">Ticket info</h3>
                    <?php output_ticket_status($ticket->status); ?>
                </header>
                <?php 
                output_ticket_id_hidden($ticket->ticketid);
                output_csrf_input($session);
                ?>
                <div id="ticket-created">
                    <span id="ticket-user"><?=$ticket->username?></span>
                    <span id="ticket-date"><?=date('F j', $ticket->submitdate)?></span>
                </div>
                <?php
                if ($isAgentView) {
                    output_single_ticket_agent_view($ticket, $session, $all_hashtags, $all_agents, $all_departments);
                }
                else {
                    output_single_ticket_client_view($ticket, $session);
                }
                ?>
            </section>
        </div>
        <section id="messages-list">
            <?php
            foreach($messages as $message) {
                output_message($message, $session->getId(), $ticket->username);
            }
            ?>
        </section>
        <section id="actions-list">
            <?php
            foreach($actions as $action) {
                output_action($action);
            }
            ?>
        </section>
        
    </article>
<?php } ?>


<?php function output_message(Message $message, int $sessionID, string $createdBy) { ?>
    <?php
    if ($sessionID === $message->userID) $class = 'self';
    else if ($createdBy === $message->username) $class = 'original-poster';
    else $class = 'other';
    ?>
    <article class="message <?=$class?>">
        <header>
            <span class="user"><?=$message->username?></span>
            <span class="date"><?=date('F j', $message->date)?></span>
        </header>
        <p class="text"><?=htmlentities($message->text)?></p>
    </article>
<?php }?>

<?php function output_action(Action $action) { ?>
    
<?php } ?>

<?php function output_message_form(int $ticketID) { ?>
    <form id="message-form">
        <label>Add a message:
            <textarea rows="1" data-id="<?=$ticketID?>" name="message" id="message-input"></textarea>
        </label>
        <button id="add-message" type="submit">Submit</button>
    </form>
<?php } ?>

<?php function output_change_ticket_info_form(Ticket $ticket, array $agents, array $departments, array $hashtags) { ?>
        <?php
        output_priority_form($ticket->priority);

        output_department_form($departments, $ticket->departmentName);

        output_agent_form($agents, $ticket->assignedagent);
        $checked_hashtags = $ticket->hashtags;
        $not_checked_hashtags = array_filter($hashtags, function($hashtag) use ($checked_hashtags) {
            return array_search($hashtag->hashtagid, array_column($checked_hashtags, 'hashtagid'), true) === false;
        });
        output_hashtag_form($not_checked_hashtags, $ticket->hashtags);
        ?>

        <button id="update-ticket" type="submit">Save</button>

<?php } ?>

<?php function output_agent_form(array $agents, ?string $assignedagent) { ?>
    <label id="agent">
        Agent
        <select name="agent">
            <option></option>
            <?php foreach($agents as $agent) { ?> 
                <?php if ($agent->username === $assignedagent) { ?>
                    <option value=<?=$agent->id?> selected><?=$agent->username?></option>
                <?php } else { ?>
                    <option value=<?=$agent->id?>><?=$agent->username?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </label>
<?php } ?>


<?php function output_ticket_status(string $status) {
    if ($status === 'closed') { ?>
        <span id="ticket-status" class="closed"><?=$status?></span>
    <?php } else if ($status === 'open') { ?>
        <span id="ticket-status" class="open"><?=$status?></span>
    <?php } 
    else { ?>
        <span id="ticket-status" class="in-progress"><?=$status?></span>
<?php } ?>
<?php } ?>

<?php function output_reopen_ticket_form(Ticket $ticket, Session $session) { ?>
    <form id="reopen-ticket-form" action="../actions/action_reopen_ticket.php" method="post">
        <?php
        output_ticket_id_hidden($ticket->ticketid);
        output_csrf_input($session);
        ?>
        <button id="reopen-ticket" type="submit">Reopen ticket</button>
    </form>
<?php } ?>

<?php function output_close_ticket_button(string $view_type) {
    if ($view_type !== 'agent' && $view_type !== 'client') throw new Exception('Invalid type inside output_close_ticket_button');
    ?>
    <button id="close-ticket" class="close-<?=$view_type?>">Close ticket</button>
<?php } ?>

<?php function output_ticket_id_hidden(int $ticketID) { ?>
    <input id="ticket-id" name="ticketID" type="hidden" value='<?=$ticketID?>'>
<?php } ?>

<?php function output_hashtag_list(array $ticketHashtags) { ?>
    <div id="hashtags">
        <legend>Hashtags</legend>
        <ul id="hashtag-list">
            <?php foreach ($ticketHashtags as $hashtag) { ?>
                <li class="hashtag"><?=$hashtag->hashtagname?></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>


<?php function output_answer_with_faq_form(array $displayed_faqs) { ?>
    <button id="answer-with-faq-button">Answer with FAQ</button>
    <form id="answer-with-faq-form">
        <label for="faq-search">Type the frequently asked question:</label>
        <input autocomplete="off" type="text" name="faq" id="faq-search" value="" list="faq-datalist">
        <datalist id="faq-datalist">
            <?php foreach ($displayed_faqs as $faq) { ?>
                <option value="<?= htmlentities($faq->question)?>">
            <?php } ?>
        </datalist>
        <button id="faq-message" type="submit">Add message by FAQ</button>
    </form>
<?php } ?>
