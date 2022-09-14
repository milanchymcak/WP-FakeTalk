<?php
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!');
?>
<table class="form-table" id="faketalk_statistics" role="presentation">
    <tbody>
        <tr>
            <th scope="row">
                <label for="faketalk_tld">Created Comments</label>
            </th>
            <td>
                <p>
                    <code>
                        <?php echo count(get_comments(array(
                                'meta_key' => '_faketalk'
                        ))); ?>
                    </code>
                </p>
                <p class="description">
                    Amount of comments created by FakeTalk. Thank you!
                </p>
            </td>
        </tr>

    </tbody>
</table>
<p class="submit">
<p class="description">Please, backup your database before you continue.</p>
    <input type="hidden" name="faketalk_hidden_submit" value="force_delete_all">
    <input type="submit" name="submit" id="submit" class="button button-primary" value="Delete All Comments">
</p>