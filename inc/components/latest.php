<?php
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!');

// Query comments by FakeTalk
$comments = get_comments(
    array(
        'meta_key' => '_faketalk'
    )
);

if(empty($comments)) return '<span class="no-faketalk_comments">No comments are published, yet!</span>';

// Delete them
$latestComments = '<ul>';
foreach($comments as $i => $commentData) {
    $latestComments .= '
    <li>
        <a href="' . get_edit_comment_link( $commentData->comment_ID) . '" class="faketalk_comment_ID">#' . $commentData->comment_ID . '</a>
        <div class="faketalk_comment_meta">
            <span class="faketalk_date">Posted: ' . $commentData->comment_date. '</span>
            by ' . $commentData->comment_author . '
        </div>
        <div class="faketalk_comment_text">
        ' . $commentData->comment_content . '

        </div>
        <div class="faketalk_footer_links">
            <a href="' . get_edit_comment_link( $commentData->comment_ID ) . '">Edit Comment</a>
            <a href="' . get_permalink( $commentData->comment_post_ID ) . '">View Comment</a>
        </div>
    </li>
    ';
}
$latestComments .= '</ul>';

return $latestComments;