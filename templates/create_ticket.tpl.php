<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/department.class.php');

?>

<?php function output_create_ticket_form(PDO $db)
{
    $hashtags = Hashtag::getHashtags($db);
    $departments = Department::getDepartments($db);
    ?>
    <form class="createticket">
        <label>
            Ticket title*
        </label>
        <input type='text' name='title'>

        <label>
            Hashtags*
        </label>
        <div class="hashtag-select">
            <?php foreach ($hashtags as $hashtag) { ?>
                <input type="checkbox" name="hashtags[]" value="<?= $hashtag->hashtagid ?>">
                <label>
                    <?= $hashtag->hashtagname ?>
                </label>
            <?php } ?>
        </div>
        <label>Ticket description*
        </label>
        <textarea name="description"></textarea>
        <label>Department</label>
        <select name='department' id='deps'>
            <?php foreach ($departments as $department) { ?>
                <option value=<?= $department->departmentId ?>><?= $department->departmentName ?>
                </option>
            <?php } ?>
        </select>
        <button formaction="../actions/action_create_ticket.php" formmethod="post" type="submit" class="submit">
            Create ticket
        </button>
    </form>
<?php } ?>