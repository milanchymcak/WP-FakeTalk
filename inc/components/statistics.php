<?php
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!');
?>
<table class="form-table" id="faketalk_statistics" role="presentation">
    <tbody>
        <tr>
            <th scope="row">
                Created Comment
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
                    Amount of comments created by FakeTalk. 
                </p>
            </td>
        </tr>
    </tbody>
</table>