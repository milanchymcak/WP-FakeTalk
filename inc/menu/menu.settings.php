<?php 
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!'); 
?>
<table class="form-table" id="faketalk_settings" role="presentation">
    <tbody>
        <tr>
            <th scope="row">
                <label for="faketalk_tld">TLD Emails</label>
            </th>
            <td>
                <input name="faketalk_tld" type="text" id="faketalk_tld" value="<?php echo get_option('_faketalk_option_tld', '.com'); ?>" class="regular-text">
                <p class="description">
                    Default Top-level domain for the generated emails. By default we use ".com".
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="faketalk_max_amount">Max Amount Of Posts</label>
            </th>
            <td>
                <input name="faketalk_max_amount" type="text" id="faketalk_max_amount" value="<?php echo get_option('_faketalk_option_max_amount', '100'); ?>" class="regular-text">
                <p class="description">
                    Max amount of blog posts shown on the "Add New" Page.
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="faketalk_max_amount_query">Add Comments To All Posts</label>
            </th>
            <td>
                <input name="faketalk_max_amount_query" type="text" id="faketalk_max_amount_query" value="<?php echo get_option('_faketalk_option_max_amount_query', '9999'); ?>" class="regular-text">
                <p class="description">
                    Max amount of blog posts that we fetch when using "Spread comments randomly" option. Default is 9999.
                </p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="faketalk_approved">Approved by Default</label>
            </th>
            <td>
                <select name="faketalk_approved" id="faketalk_approved">
                <?php 


                // Option is not created, yet
                if(NULL === get_option( '_faketalk_option_approved', NULL ) ) {
                    echo '<option value="1" selected="selected">Approved</option>';
                    echo '<option value="0">Pending</option>';
                } else {
                    if(intval(get_option('_faketalk_option_approved')) === 0) {
                        echo '<option value="1">Approved</option>';
                        echo '<option value="0" selected="selected">Pending</option>';
                    } else {
                        echo '<option value="1" selected="selected">Approved</option>';
                        echo '<option value="0">Pending</option>';
                    }
                }
                ?>
                </select>
                <p class="description">
                    By default all comments generated are approved. You can change it to "Pending" status if you want more control.
                </p>
            </td>
        </tr>

    </tbody>
</table>
<p class="submit">
    <input type="hidden" name="faketalk_hidden_submit" value="settings">
    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
</p>