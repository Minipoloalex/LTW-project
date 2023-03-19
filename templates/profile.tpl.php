<?php
declare(strict_types = 1);

?>

<?php function drawProfileForm(Client $client) { ?>
<h2>Profile</h2>
<form action="../actions/action_edit_profile.php" method="post" class="profile">

  <label for="first_name">First Name:</label>
  <input id="first_name" type="text" name="first_name" value="<?=$client->firstName?>">
  
  <label for="last_name">Last Name:</label>
  <input id="last_name" type="text" name="last_name" value="<?=$client->lastName?>">  
  
  <button type="submit">Save</button>
</form>
<?php } ?>

<?php function drawProfile() { ?>

<?php } ?>
