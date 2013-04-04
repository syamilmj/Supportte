<?php
/** 
 * Custom functions to override BBPress default functionalities
 * ============================================================= */

/** 
 * Modify bbp_get_time_since() output 
 * -------------------------------------------------------------------------------------------*/
add_filter('bbp_get_time_since', 'aq_get_time_since', 10, 3);
function aq_get_time_since( $output, $older_date, $newer_date ) {
	
	// Setup the strings
	$unknown_text   = apply_filters( 'bbp_core_time_since_unknown_text',   __( 'sometime',  'bbpress' ) );
	$right_now_text = apply_filters( 'bbp_core_time_since_right_now_text', __( 'right now', 'bbpress' ) );
	$ago_text       = apply_filters( 'bbp_core_time_since_ago_text',       __( '%s ago',    'bbpress' ) );

	// array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365 , __( 'year',   'bbpress' ), __( 'years',   'bbpress' ) ),
		array( 60 * 60 * 24 * 30 ,  __( 'month',  'bbpress' ), __( 'months',  'bbpress' ) ),
		array( 60 * 60 * 24 * 7,    __( 'week',   'bbpress' ), __( 'weeks',   'bbpress' ) ),
		array( 60 * 60 * 24 ,       __( 'day',    'bbpress' ), __( 'days',    'bbpress' ) ),
		array( 60 * 60 ,            __( 'hour',   'bbpress' ), __( 'hours',   'bbpress' ) ),
		array( 60 ,                 __( 'minute', 'bbpress' ), __( 'minutes', 'bbpress' ) ),
		array( 1,                   __( 'second', 'bbpress' ), __( 'seconds', 'bbpress' ) )
	);

	if ( !empty( $older_date ) && !is_numeric( $older_date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $older_date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $older_date ) );
		$older_date  = gmmktime( (int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0] );
	}

	// $newer_date will equal false if we want to know the time elapsed
	// between a date and the current time. $newer_date will have a value if
	// we want to work out time elapsed between two known dates.
	$newer_date = ( !$newer_date ) ? strtotime( current_time( 'mysql' ) ) : $newer_date;

	// Difference in seconds
	$since = $newer_date - $older_date;

	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since ) {
		$output = $unknown_text;

	// We only want to output two chunks of time here, eg:
	//     x years, xx months
	//     x days, xx hours
	// so there's only two bits of calculation below:
	} else {

		// Step one: the first chunk
		for ( $i = 0, $j = count( $chunks ); $i < $j; ++$i ) {
			$seconds = $chunks[$i][0];

			// Finding the biggest chunk (if the chunk fits, break)
			$count = floor( $since / $seconds );
			if ( 0 != $count ) {
				break;
			}
		}

		// If $i iterates all the way to $j, then the event happened 0 seconds ago
		if ( !isset( $chunks[$i] ) ) {
			$output = $right_now_text;

		} else {

			// Set output var
			$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];

			// No output, so happened right now
			if ( ! (int) trim( $output ) ) {
				$output = $right_now_text;
			}
		}
	}

	return $output;
}

/** 
 * Modify status display in single topic
 * -------------------------------------------------------------------------------------------*/
remove_action('bbp_template_before_single_topic', 'bbps_add_support_forum_features');
add_action('bbp_template_before_single_topic', 'aq_add_support_forum_features');

function aq_add_support_forum_features(){	
	//only display all this stuff if the support forum option has been selected.
	if (bbps_is_support_forum(bbp_get_forum_id())){
		$can_edit = bbps_get_update_capabilities();
		$topic_id = bbp_get_topic_id();
		$status = bbps_get_topic_status($topic_id);
		$forum_id = bbp_get_forum_id();
		$user_id = get_current_user_id();

		//get out the option to tell us who is allowed to view and update the drop down list.
		if ( $can_edit == true ) { ?>

		<div id="bbps_support_forum_options">
			<?php bbps_generate_status_options($topic_id,$status); ?>
		</div>

		<?php 
		}

		//has the user enabled the move topic feature?
		if( (get_option('_bbps_enable_topic_move') == 1) && (current_user_can('administrator') || current_user_can('bbp_moderator')) ) { 
		?>

		<div id ="bbps_support_forum_move">
			<form id="bbps-topic-move" name="bbps_support_topic_move" action="" method="post">
				<label for="bbp_forum_id">Move topic to: </label><?php bbp_dropdown(); ?>
				<input type="submit" value="Move" name="bbps_topic_move_submit" />
				<input type="hidden" value="bbps_move_topic" name="bbps_action"/>
				<input type="hidden" value="<?php echo $topic_id ?>" name="bbps_topic_id" />
				<input type="hidden" value="<?php echo $forum_id ?>" name="bbp_old_forum_id" />
			</form>
		</div>

		<?php
			
		}
	}
}

/** 
 * Adds Status to topic title
 * -------------------------------------------------------------------------------------------*/
remove_action('bbp_theme_before_topic_title', 'bbps_modify_title');
add_action('bbp_theme_before_topic_title', 'aq_modify_before_title', 10, 2);
function aq_modify_before_title($title, $topic_id = 0) {
	$topic_id = bbp_get_topic_id( $topic_id );

	$replies = bbp_get_topic_reply_count($topic_id);
	$statuses = array (1,2,3);
	$status_id = get_post_meta( $topic_id, '_bbps_topic_status', true );

	// Let's not override default closed/sticky status
	if(bbp_is_topic_sticky()) {
		echo '<span class="topic-sticky"> [Sticky] </span>';
	} 
	// Let's not override the default statuses
	elseif(!in_array($status_id, $statuses)) {

		if($replies >= 1) {

			echo '<span class="in-progress"> [In Progress] </span>';

		} else {

			echo '<span class="not-resolved"> [Not Resolved] </span>';

		}
	// Default Statuses
	} else {

		if ($status_id == 1) // Not Resolved
			echo '<span class="not-resolved"> [Not Resolved] </span>';

		if ($status_id == 2) // Not Resolved
			echo '<span class="resolved"> [Resolved] </span>';

		if ($status_id == 3) { // Not Support Question (mark as resolved)
			add_post_meta($topic_id, '_bbps_topic_status', 2);
			echo '<span class="resolved"> [Resolved] </span>';
		}
	}
		
}


/** 
 * Display Topic Status
 * -------------------------------------------------------------------------------------------*/
function aq_display_topic_status($topic_id = 0) {

	$topic_id = $topic_id ? $topic_id : bbp_get_topic_id();

	$statuses = array (1,2,3);
	$status_id = get_post_meta( $topic_id, '_bbps_topic_status', true );

	echo '<div class="aq-topic-status">';

	if(bbp_is_topic_sticky()) {

		echo '<span class="sticky">Sticky</span>';

	} elseif(in_array($status_id, $statuses)) {

		if ($status_id == 1) {
			echo '<span class="not-resolved">Not Resolved</span>';
		}
			
		if ($status_id == 2) {
			echo '<span class="resolved">Resolved</span>';
		}

		if ($status_id == 3) {
			echo '<span class="in-progress">In Progress</span>';
		}

	} elseif(bbp_is_topic_closed()) {

		echo '<span class="sticky">Sticky</span>';
		
	} else {

		echo '<span class="in-progress">In Progress</span>';
		
	}

	echo '</div>';

}

/** Disable admin bar completely for non-admin */
if (!function_exists('disableAdminBar')) {
	function disableAdminBar(){
  	remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 ); // for the admin page
    remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 ); // for the front end
    function remove_admin_bar_style_backend() {  // css override for the admin page
      echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
    }
    add_filter('admin_head','remove_admin_bar_style_backend');
    function remove_admin_bar_style_frontend() { // css override for the frontend
      echo '<style type="text/css" media="screen">
      html { margin-top: 0px !important; }
      * html body { margin-top: 0px !important; }
      </style>';
    }
    add_filter('wp_head','remove_admin_bar_style_frontend', 99);
  }
}

if(!current_user_can('manage_options')) {
	add_action('init','disableAdminBar'); // New version
}

/**
 * Remove topic & reply revision log
 * -------------------------------------------------------------------------------------------*/
remove_filter( 'bbp_get_reply_content', 'bbp_reply_content_append_revisions',  1,  2 );
remove_filter( 'bbp_get_topic_content', 'bbp_topic_content_append_revisions',  1,  2 );

/**
 * Custom function from bbp_get_author_link(), returns only author name
 * -------------------------------------------------------------------------------------------*/
function aq_get_author( $post_id = 0 ) {

	// Confirmed topic
	if ( bbp_is_topic( $post_id ) ) {
		return bbp_get_topic_author( $post_id );

	// Confirmed reply
	} elseif ( bbp_is_reply( $post_id ) ) {
		return bbp_get_reply_author( $post_id );

	// Get the post author and proceed
	} else {
		$user_id = get_post_field( 'post_author', $post_id );
	}

	// Neither a reply nor a topic, so could be a revision
	if ( !empty( $post_id ) ) {

		// Assemble some link bits
		$anonymous  = bbp_is_reply_anonymous( $post_id );

		// Add links if not anonymous
		if ( empty( $anonymous ) && bbp_user_has_profile( $user_id ) ) {

			$author_link = get_the_author_meta( 'display_name', $user_id );

		// No links if anonymous
		} else {
			$author_link = join( '&nbsp;', $author_links );
		}

	// No post so link is empty
	} else {
		$author_link = '';
	}

	return $author_link;
}

/**
 * Adds search query to topic pagination
 * -------------------------------------------------------------------------------------------*/
add_filter( 'bbp_topic_pagination', 'aq_topic_pagination_query');
function aq_topic_pagination_query( $bbp_topic_pagination = array() ) {
	
	$http_get = ('GET' == $_SERVER['REQUEST_METHOD']); 
	$search = $http_get ? $_GET['q'] : '';
	
	if($search) {
		$bbp_topic_pagination['add_args'] = array( 'q' => $search );
	}
	
	return $bbp_topic_pagination;
	
}

/** change "search-posts" to other base (optional) */
add_action( 'init', 'wpse21549_init' );
function wpse21549_init() {
    $GLOBALS['wp_rewrite']->search_base = 'search-posts';
}

/** 
 * Change user roles naming
 * -------------------------------------------------------------------------------------------*/
function aq_custom_bbp_roles( $role, $user_id ) {
	if( $role == 'Key Master' )
		return 'Admin';
 	
 	if( $role == 'Participant' )
 		return 'Member';
 	
	return $role;
}
 
add_filter( 'bbp_get_user_display_role', 'aq_custom_bbp_roles', 10, 2 );






