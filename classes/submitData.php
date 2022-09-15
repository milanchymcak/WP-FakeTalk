<?php
namespace FakeTalk;

// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!'); 

/**
 * submitData
 */
class submitData {
    
    /**
     * __construct
     *
     * @param  array $postData
     * @return void
     */
    function __construct(array $postData=array()) {

        // save postData
        $this->postData = $postData;
    }

    /**
     * prepareSubmit
     * Return message with confirmation of the submission
     *
     * @return string
     */
    public function prepareSubmit(): string {

        // Print Message
        $message = '<div class="notice notice-warning inline facetalk_notice">';

        // Add text message based on the form we submitting
        if($this->postData['faketalk_hidden_submit'] === 'addnew') $message .= $this->finalSubmit();
        if($this->postData['faketalk_hidden_submit'] === 'batchdelete') $message .= $this->deleteBatch();
        if($this->postData['faketalk_hidden_submit'] === 'settings') $message .= $this->changeSettings();
        if($this->postData['faketalk_hidden_submit'] === 'force_delete_all') $message .= $this->deleteAllComments();
        if($this->postData['faketalk_hidden_submit'] === 'reset-settings') {
            if(function_exists('faketalk_delete_settings')) {
                faketalk_delete_settings();
                $message .= $this->formMessage(8); 
            } else {
                $message .= $this->formMessage(0); 
            }
        }

        $message .= '</div>';

        return $message;

    }
  
    /**
     * Delete All Comments Previously Posted by The Plugin
     * Find the comments using meta_key = '_faketalk
     *
     * @return string
     */
    private function deleteAllComments(): ?string {
        
        // Query comments by FakeTalk
        $comments = get_comments(
            array(
                'meta_key' => '_faketalk'
            )
        );

        // Delete them
        foreach($comments as $i => $commentData) {
            wp_delete_comment($comments[$i]->comment_ID, true);
        }

        // Notify the user about deletion
        return $this->formMessage(8); 
    }
 
    /**
     * Update User Settings
     *
     * @return string
     */
    private function changeSettings(): string {

        // Changing TLD
        if(isset($this->postData['faketalk_tld']) && !empty($this->postData['faketalk_tld'])) {
            update_option('_faketalk_option_tld', $this->postData['faketalk_tld']);
        }

        // Changing Max Posts
        if(isset($this->postData['faketalk_max_amount']) && !empty($this->postData['faketalk_max_amount'])) {
            update_option('_faketalk_option_max_amount', intval($this->postData['faketalk_max_amount']));
        }

        // Changing Max Posts - Query
        if(isset($this->postData['faketalk_max_amount_query']) && !empty($this->postData['faketalk_max_amount_query'])) {
            update_option('_faketalk_option_max_amount_query', intval($this->postData['faketalk_max_amount_query']));
        }

        // Changing Approved By Default
        // 0 is valid value here, so cant use empty by itself
        if(isset($this->postData['faketalk_approved']) && ($this->postData['faketalk_approved'] === '0' || !empty($this->postData['faketalk_approved']))) {
            update_option('_faketalk_option_approved', intval($this->postData['faketalk_approved']));
        }

        return $this->formMessage(7); 
    }

    /**
     * Delete (Specific) Batch
     *
     * @return string
     */
    private function deleteBatch(): string {

        // Cannot be empty
        if(!isset($this->postData['faketalk_batch_delete']) || empty($this->postData['faketalk_batch_delete'])) return $this->formMessage(6); 

        // Query comments by identifier
        $comments = get_comments(
            array(
                'meta_key' => sanitize_text_field('_faketalk_'.$this->postData['faketalk_batch_delete']),
            )
        );

        // Delete them
        $delete_comments = 0;
        foreach($comments as $i => $commentData) {
            if(wp_delete_comment($comments[$i]->comment_ID)) {
                $delete_comments++;
            }
        }
        
        // Success
        if($delete_comments > 0) return $this->formMessage(5); 

        // Not deleted
        return $this->formMessage(6); 
    }
 
    /**
     * finalSubmit
     *
     * @return string
     */
    private function finalSubmit(): string {

        // Get Target Posts
        $post_IDs = $this->getTargetPosts();
        if(empty($post_IDs) || !is_array($post_IDs)) return $this->formMessage(1); 

        // Get Submitted Comments
        $comments = $this->getComments();
        if(empty($comments) || !is_array($comments)) return $this->formMessage(2); 

        // Save Posted Comment IDs
        $comment_IDs = array();

        // Generate Random Batch Identifier
        // If the user decide to rollback the comments, we can easily delete them without affecting the comments 
        // that were posted previously
        // There is a small chance that there will be two identifier that are totally the same
        // In that case just buy sazka lottery tickets I guess
        $batch_identifier = date('Y-m-d') . '-' . mt_rand(1, 9999);

        // Submit
        foreach($comments as $comment) {

            // Get Date Range For Comments
            $date = $this->getDateRange();
            if(empty($date) || !is_numeric($date)) return $this->formMessage(3); // Must be timestamp numeric format

            // Spin the comment if avaiilable
            $comment = $this->textSpintax($comment);

            // Make array with random data
            $commentData = array(
                'comment_post_ID'		 =>	intval(sanitize_text_field($post_IDs[mt_rand(0, count($post_IDs)-1)])),
                'comment_author'	 	 =>	sanitize_text_field(trim($this->generateName())),
                'comment_author_email'	 =>	sanitize_text_field(trim($this->generateEmail())),
                'comment_date'			 => date('Y-m-d H:i:s', sanitize_text_field($date)),
                'comment_date_gmt'		 => date('Y-m-d H:i:s', sanitize_text_field($date)),
                'comment_content'		 => sanitize_text_field(trim($comment)),
                'comment_approved'		 =>	get_option('_faketalk_option_approved', '1'),
                'comment_author_IP'      => mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255)
            );

            // Post comment, it should returns comment ID
            $comment_ID = wp_insert_comment($commentData);

            // If successful
            if(!empty($comment_ID) && is_numeric($comment_ID)) {


                // Add comment meta
                $this->addCommentMeta($comment_ID, $batch_identifier);

                // Add to posted IDs
                array_push($comment_IDs, $comment_ID);

            }

        }

        // Successful Message
        if(!empty($comment_IDs)) {

            // Add success message
            $success = $this->formMessage(4);

            $success .= '<ul class="successful-comments">';

            // Show posted comments with edit links
            foreach($comment_IDs as $comment_posted_ID) {

                $success .= '<li><a href="'.get_edit_comment_link($comment_posted_ID).'" target="_blank">#'.$comment_posted_ID.'</a> &raquo; '.get_comment_text($comment_posted_ID).'</li>';
            }

            $success .= '</ul>';

            // Batch identifier
            $success .= '<form method="post" action="" class="batch-identifier">';
            $success .= '<input type="hidden" name="faketalk_batch_delete" value="'.$batch_identifier.'" />';
            $success .= '<input type="hidden" name="faketalk_hidden_submit" value="batchdelete">';
            $success .= '<input type="submit" class="button button-primary" value="Delete comments" />';
            $success .= '</form>';

            return $success;

        }

        // There is some issue as we didn't get comment ID
        return $this->formMessage(0);

    }
  
    /**
     * Add Comment Meta To Created Comments
     * Add:
     * _faketalk - generic key for the created comments
     * faketalk_custom_meta_key - user created custom meta key
     * faketalk_custom_meta_value - user created custom meta value
     * faketalk_custom_rating_value_min - user created custom meta value
     * faketalk_custom_rating_value_max - user created custom meta value
     * 
     * @param  int $comment_ID
     * @param  string $identifier
     * @return void
     */
    private function addCommentMeta(int $comment_ID=0, string $identifier=''): void {

        // Add default plugin comment meta
        add_comment_meta(
            $comment_ID, 
            '_faketalk', 
            date('Y/m/d at h:i:sa')
        );

        // Add default plugin comment meta with the work identifier
        add_comment_meta(
            $comment_ID, 
            '_faketalk_' . $identifier, 
            date('Y/m/d at h:i:sa')
        );

        // Custom Comment Meta Key By User
        if(
            isset($this->postData['faketalk_custom_meta_key']) && 
            !empty($this->postData['faketalk_custom_meta_key']) && 
            isset($this->postData['faketalk_custom_meta_value']) && 
            !empty($this->postData['faketalk_custom_meta_value'])
        ) {
            add_comment_meta(
                $comment_ID, 
                sanitize_text_field($this->postData['faketalk_custom_meta_key']),
                sanitize_text_field($this->postData['faketalk_custom_meta_value'])
            );
        }

        // Custom Rating Meta Key By User
        if(
            isset($this->postData['faketalk_custom_rating_key']) && 
            !empty($this->postData['faketalk_custom_rating_key']) && 
            isset($this->postData['faketalk_custom_rating_value_min']) && 
            !empty($this->postData['faketalk_custom_rating_value_min']) && 
            is_numeric($this->postData['faketalk_custom_rating_value_min']) &&
            isset($this->postData['faketalk_custom_rating_value_max']) && 
            !empty($this->postData['faketalk_custom_rating_value_max']) &&
            is_numeric($this->postData['faketalk_custom_rating_value_max'])
        ) {
            add_comment_meta(
                $comment_ID, 
                sanitize_text_field($this->postData['faketalk_custom_rating_key']),
                sanitize_text_field(mt_rand($this->postData['faketalk_custom_rating_value_min'], $this->postData['faketalk_custom_rating_value_max'])) // Randomized rating by user with min-max values
            );
        }

    }
 
    /**
     * Get All Targeted Posts 
     * Return all post_IDs to comment on
     * Can be specific by the user 
     * Or user can post on all post Ids
     *
     * @return array
     */
    private function getTargetPosts(): ?array {
        
        // Store IDs
        $post_IDs = array();

        // If user selected to post to all posts
        if(isset($this->postData['faketalk_all']) && $this->postData['faketalk_all'] === 'on') {

            // Query to get all post IDs
            $query =  get_posts(
                array(
                    'posts_per_page'   =>  get_option('_faketalk_option_max_amount_query', '9999'), // Max Amount 
                    'post_type'        => 'post',
                    'post_status'      => 'publish',
                    'fields'           => 'ids'
                ) 
            );

            foreach($query as $ID) {
                array_push($post_IDs, $ID);  
            }
        }

        // If user selected specific posts
        if(isset($this->postData['faketalk_post_ids']) && !empty($this->postData['faketalk_post_ids']) && is_array($this->postData['faketalk_post_ids'])) {

            foreach($this->postData['faketalk_post_ids'] as $ID) {
                if(is_numeric($ID)) array_push($post_IDs, $ID);  
            }
    
        }

        return $post_IDs;
    }

    /**
     * Get All Comments Posted By The User
     *
     * @return array
     */
    private function getComments(): ?array {

        // Comment Text
        if(isset($this->postData['faketalk_comments_text']) && !empty($this->postData['faketalk_comments_text'])) {
            return array_values(
                array_filter(
                    preg_split('/\n|\r/', $this->postData['faketalk_comments_text'])
                )
            );
        }

        return false;
    }
  
    /**
     * textSpintax
     * Spin the text with the spintax 
     * Example: {Never|Never ever|Do not|Don't} 
     * 
     * @param  mixed $text
     * @return string
     */
    private function textSpintax(string $text=''): ?string {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            function ($text) {
                $text = $this->textSpintax($text[1]);
                $parts = explode('|', $text);
                return $parts[array_rand($parts)];
            },
            $text
        );
    }
  
    /**
     * Get Random Time Specific By The User 
     * Return timestamp format
     * Fallback return false
     *
     * @return int
     */
    private function getDateRange(): ?int {

        // Date Ranges in timestamp
        if(
            isset($this->postData['faketalk_date_from']) && 
            !empty($this->postData['faketalk_date_from']) && 
            isset($this->postData['faketalk_date_to']) &&
            !empty($this->postData['faketalk_date_to'])
        ) {
            return mt_rand(strtotime($this->postData['faketalk_date_from']), strtotime($this->postData['faketalk_date_to']));
        }

        return false;
    }

    /**
     * Generate Random Fake Email
     * Each comment in the WP database must have an email associated to the comment
     * Also add "honesty" to the comments
     *
     * @return string
     */
    private function generateEmail(): string {

        // Generate Random Length Of Email
        $email_length = mt_rand(4,7);

        // Generate Random Length of Domain
        $domain_length = mt_rand(4,7);

        // Storing Email
        $email = '';

        // Generate email - Part 1
        for ($i = 0; $i < $email_length; $i++) {
            // Generate Random Letter
            $email .= chr(mt_rand(97,122));
        }

        // Add @
        $email .= '@';

        // Generate email - Part 2
        for ($i = 0; $i < $domain_length; $i++) {
            $email .= chr(mt_rand(97,122));
        }

        // Add .com
        $email .= get_option('_faketalk_option_tld', '.com');;

        return $email;

    }

    /**
     * Generate Random Name    
     *
     * @return string
     */
    private function generateName(): string {

        // If the file with names got lost
        if(!file_exists(FAKETALK_PATH . 'resources/txt/names.txt')) return 'Unknown';

        // Get name list
        $nameList = file(FAKETALK_PATH . 'resources/txt/names.txt', FILE_IGNORE_NEW_LINES);

        // Shuffle it for little bit spicyness
        shuffle($nameList);

        // Pick random name
        return $nameList[array_rand($nameList)];;
    }

    /**
     * Form Messages   
     *
     * @param  int $status_code
     * @return string
     */
    private function formMessage(int $status_code=0): string {

        // 1 - Problem with targeted blog posts
        if($status_code === 1) return 'We couldn\'t find any blog posts to comment on. Please try again!';

        // 2 - Problem with submitted comments
        if($status_code === 2) return 'There was an issue with the submitted comments. Please try again!';

        // 3 - Problem with date range
        if($status_code === 3) return 'There was an issue with the date range. Please try again!';

        // 4 - Success
        if($status_code === 4) return 'Yay! Your comments were submitted successfully.';

        // 5 - Batch Deleted Successfully
        if($status_code === 5) return 'Wow! Your comments were deleted!. You should post new ones!';

        // 6 - Batch Not Deleted
        if($status_code === 6) return 'Wow! Your comments were not deleted!. There is nothing we can do :(';

        // 7 - Settings Saved
        if($status_code === 7) return 'Good. Settings saved.';

        // 8 - Delete Comments
        if($status_code === 8) return 'It\'s done! You can start again.';

        // 0 - Unknown Error - Should be submitted
        return 'Error - Yeah, it happens. But we don\'t know why. Please try again!';

    }
}
