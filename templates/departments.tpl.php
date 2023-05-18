<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');

?>

<?php function drawDepartment(Department $department, PDO $db) { ?>
    <?php $nr_tickets = count(Ticket::getByDepartment($db, $department->departmentId))?>
    <?php $nr_agents = count(Agent::getByDepartment($db, $department->departmentId)) ?>
    <tr>
        <td><?=$department->departmentName?></td>
        <td><?=$department->departmentId?></td>
        <td><?=$nr_tickets?></td>
        <td><?=$nr_agents?></td>
<?php } ?>

<?php function drawDepartmentsTable(array $departments, PDO $db) { ?>
    <section id="departments">
    <table>
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Department ID</th>
                <th>Nr of agents</th>
                <th>Nr of tickets</th>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach($departments as $department) {
                drawDepartment($department, $db);
            }
            ?>
        </tbody>
    </table>
    </section>

<?php } ?>

<?php function drawAddDepartmentForm(){ ?>
    <div class="add-department-form">
    <p>Want to add a new department?</p>
        <form id="addDepartmentForm">
            <label for="department_name">Department Name (limit 24 characters):</label>
            <input type="text" id="department_name" name="department_name" maxlength="24" required>
            <button type="submit">Add</button>
            <?php
            output_empty_feedback_message("add-department-feedback");
            ?>
        </form>
    </div>
<?php } ?>