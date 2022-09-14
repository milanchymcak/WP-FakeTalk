<?php 
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!'); 
?>
<div id="postbox-container-2" class="postbox-container postbox faketalk_plugin_body">

    <div class="faketalk_tabbed_window show" id="faketalk_target">

        <span class="faketalk_title">1. Option - Spread comments randomly</span>
        <div class="faketalk_all_option">
            <input type="checkbox" name="faketalk_all">
            <p class="description" style="display: inline-block;">Spread comments equally across ALL your posts</p>
        </div>

        <span class="faketalk_title">2. Option - Select posts</span>
        <select name="faketalk_post_ids[]" id="faketalk_posts" multiple>
            <?php 
                $faketalk_i = 1;
                $faketalk_query = new WP_Query(
                    array(
                        'posts_per_page'   =>  get_option('_faketalk_option_max_amount', '100'), // Max Amount 
                        'post_type'        => 'post',
                        'post_status'      => 'publish',
                        'orderby'          => 'date',
                        'order'            => 'DESC'
                    ) 
                );
                while ($faketalk_query->have_posts()) : $faketalk_query->the_post();
                    echo '<option value="'.get_the_ID().'">'.$faketalk_i.'. '.get_the_title().'</option>';
                    $faketalk_i++;
                endwhile;
            ?>
        </select>


    </div>

    <div class="faketalk_tabbed_window" id="faketalk_comments">
        <tr>
            <th scope="row">
                <span class="faketalk_title">Add Comments</span>
                <p class="description">
                    Nested Spintax available. For more information visit <a
                        href="https://medium.com/@instarazzo/what-is-spintax-format-and-what-are-its-applications-on-instarazzo-6e1b812cc208"
                        target="_blank" rel="nofollow">@medium</a>
                    <br >
                    Example: {Never|Never ever|Do not|Don't} {trust|rely on} an {online|on-line}
                    {comments|reviews|forums|discussions}
                </p>
            </th>
            <td>
                <textarea name="faketalk_comments_text" rows="20" cols="40" id="comments_text" class="large-text code"
                    placeholder="1 comment per line"></textarea>
            </td>
        </tr>
    </div>

    <div class="faketalk_tabbed_window" id="faketalk_date">

        <span class="faketalk_title">Select Date Range</span>
        <p class="description">
            Select date range that will try to create more authentic comments with older dates. In order to publish all
            comments as a today, please pick the same date in both inputs.
        </p>
        <?php 
  echo '<input name="faketalk_date_from" type="text" id="date_from" class="datepicker" size="20" value="'.date("d-m-Y", strtotime("-1 year", time())).'">'; 
  ?>
        To
        <?php 
  echo '<input name="faketalk_date_to" type="text" id="date_to" class="datepicker" size="20" value="'.date("d-m-Y").'">'; 
  ?>
        </tr>



    </div>

    <div class="faketalk_tabbed_window" id="faketalk_settings">

        <span class="faketalk_title">Custom Meta Field</span>
        <p class="description">Would you like to add some special comments meta fields to this batch? Feel free to do so below.</p>

        <label>Custom Meta Field Name</label>
        <input name="faketalk_custom_meta_key" id="faketalk_custom_meta_key" value="" type="text" >
        <label>Custom Meta Field Value</label>
        <input name="faketalk_custom_meta_value" id="faketalk_custom_meta_value" value="" type="text" >

        <span class="faketalk_title">Custom Rating System</span>
        <p class="description">Are your comments using some kind of rating / review system ? Simply specify meta name of the rating system
            and add min/max rating. We will try to add randomized rating to each generated comment.</p>

        <label>Custom Rating Name</label>
        <input name="faketalk_custom_rating_key" id="faketalk_custom_rating_key" value="" type="text" >
        <label>Custom Rating Value (Min)</label>
        <input name="faketalk_custom_rating_value_min" id="faketalk_custom_rating_value_min" value="" type="text" >
        <label>Custom Rating Value (Max)</label>
        <input name="faketalk_custom_rating_value_max" id="faketalk_custom_rating_value_max" value="" type="text" >

    </div>

    <div class="faketalk_tabbed_window" id="faketalk_schedule">
        <p class="description">You will be able to schedule posts only in premium version in upcoming patch.</p>
    </div>

    <div class="faketalk_tabbed_window" id="faketalk_submit">
        <p class="description">The material and information contained on this website is for general information purposes only. You should not rely upon the material or information on the website as a basis for making any business, legal or any other decisions.</p>
        <p class="submit">
            <input type="hidden" name="faketalk_hidden_submit" value="addnew">
            <input type="submit" name="faketalk_final_submit" id="faketalk_final_submit" class="button button-primary" value="Publish Comments">
        </p>
    </div>
</div>

<div id="postbox-container-1" class="postbox-container">
    <ul class="faketalk_tabbed_list">
        <li class="postbox current" data-tab-target="faketalk_target">
            <h3>#1 Target</h3>
        </li>
        <li class="postbox" data-tab-target="faketalk_comments">
            <h3>#2 Comments</h3>
        </li>
        <li class="postbox" data-tab-target="faketalk_date">
            <h3>#3 Date Range</h3>
        </li>
        <li class="postbox" data-tab-target="faketalk_settings">
            <h3>#4 Settings</h3>
        </li>
        <li class="postbox" data-tab-target="faketalk_schedule">
            <h3>#5 Schedule</h3>
        </li>
        <li class="postbox" data-tab-target="faketalk_submit">
            <h3>#6 Submit</h3>
        </li>
        <li class="postbox faketalk-sidebar-prom">
            <img src="<?php echo plugins_url('resources/images/ad.jpg', __FILE__ ); ?>" alt="" >
        </li>
    </ul>
</div>