<?php
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!');

return '
<p class="description">Please, backup your database before you continue.</p>
<label for="faketalk_hidden_submit">Choose an action:</label>
<select id="faketalk_hidden_submit">
    <option value="">--Please choose an action--</option>
    <option value="reset-settings">Reset Settings</option>
    <option value="force_delete_all">Delete All Comments</option>
</select>
<input type="submit" name="submit" id="submit" class="button button-primary" value="Submit">
';