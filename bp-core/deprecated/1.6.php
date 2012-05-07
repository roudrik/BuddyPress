<?php

/**
 * Deprecated Functions
 *
 * @package BuddyPress
 * @subpackage Core
 * @deprecated Since 1.6
 */

/** Toolbar functions *********************************************************/

function bp_admin_bar_remove_wp_menus() {
	_deprecated_function( __FUNCTION__, '1.6' );
}

function bp_admin_bar_root_site() {
	_deprecated_function( __FUNCTION__, '1.6' );
}

function bp_admin_bar_my_sites_menu() {
	_deprecated_function( __FUNCTION__, '1.6' );
}

function bp_admin_bar_comments_menu( $wp_admin_bar ) {
	_deprecated_function( __FUNCTION__, '1.6' );
}

function bp_admin_bar_appearance_menu() {
	_deprecated_function( __FUNCTION__, '1.6' );
}

function bp_admin_bar_updates_menu() {
	_deprecated_function( __FUNCTION__, '1.6' );
}

function bp_members_admin_bar_my_account_logout() {
	_deprecated_function( __FUNCTION__, '1.6' );
}

function bp_core_is_user_deleted( $user_id = 0 ) {
	_deprecated_function( __FUNCTION__, '1.6' );
	bp_is_user_deleted( $user_id );
}

function bp_core_is_user_spammer( $user_id = 0 ) {
	_deprecated_function( __FUNCTION__, '1.6' );
	bp_is_user_spammer( $user_id );
}


/**
 * Blogs functions
 */

/**
 * @deprecated 1.6
 * @deprecated No longer used; see bp_blogs_transition_activity_status()
 */
function bp_blogs_manage_comment( $comment_id, $comment_status ) {
	_deprecated_function( __FUNCTION__, '1.6', 'No longer used' );
}

/**
 * Core functions
 */

/**
 * @deprecated 1.6
 * @deprecated No longer used; see BP_Admin::admin_menus()
 */
function bp_core_add_admin_menu() {
	_deprecated_function( __FUNCTION__, '1.6', 'No longer used' );
}

/**
 * @deprecated 1.6
 * @deprecated No longer used. We do ajax properly now.
 */
function bp_core_add_ajax_hook() {
	_deprecated_function( __FUNCTION__, '1.6', 'No longer used' );
}

/**
 * Members functions
 */

/**
 * @deprecated 1.6
 * @deprecated No longer used. Check for $bp->pages->activate->slug instead.
 */
function bp_has_custom_activation_page() {
	_deprecated_function( __FUNCTION__, '1.6' );
}

/**
 * Groups functions
 */

/**
 * @deprecated 1.6
 * @deprecated Renamed to groups_get_id() for greater consistency
 */
function groups_check_group_exists( $group_slug ) {
	_deprecated_function( __FUNCTION__, '1.6', 'groups_get_id()' );
	return groups_get_id( $group_slug );
}

/**
 * Activity functions
 */

/**
 * @deprecated 1.6
 * @deprecated No longer used. Renamed to bp_activity_register_activity_actions().
 */
function updates_register_activity_actions() {
	_deprecated_function( __FUNCTION__, '1.6' );
}

/**
 * Sets the "From" address in emails sent
 *
 * @deprecated 1.6
 * @deprecated No longer used.
 * @return noreply@sitedomain email address
 */
function bp_core_email_from_address_filter() {
	_deprecated_function( __FUNCTION__, '1.6' );

	$domain = (array) explode( '/', site_url() );
	return apply_filters( 'bp_core_email_from_address_filter', 'noreply@' . $domain[2] );
}

/**
 * Backward compatibility for AJAX callbacks that do not die() on their own
 *
 * In BuddyPress 1.6, BP was altered so that it uses admin-ajax.php (instead of wp-load.php) for
 * AJAX requests. admin-ajax.php dies with an output of '0' (to signify an error), so that if an
 * AJAX callback does not kill PHP execution, a '0' character will be erroneously appended to the
 * output. All bp-default AJAX callbacks (/bp-themes/bp-default/_inc/ajax.php) have been updated
 * for BP 1.6 so that they die() properly; any theme that dynamically includes this file will
 * inherit the fixes. However, any theme that contains a copy of BP's pre-1.5 ajax.php file will
 * continue to witness the 'trailing "0"' problem.
 *
 * This function provides a backward compatible workaround for these themes, by hooking to the
 * BP wp_ajax_ actions that were problematic prior to BP 1.6, and killing PHP execution with die().
 *
 * Note that this hack only runs if the function bp_dtheme_register_actions() is not found (this
 * function was introduced in BP 1.6 for related backward compatibility reasons).
 */
if ( !function_exists( 'bp_dtheme_register_actions' ) ) :
	function bp_die_legacy_ajax_callbacks() {

		// This is a list of the BP wp_ajax_ hook suffixes whose associated functions did
		// not die properly before BP 1.6
		$actions = array(
			// Directory template loaders
			'members_filter',
			'groups_filter',
			'blogs_filter',
			'forums_filter',
			'messages_filter',

			// Activity
			'activity_widget_filter',
			'activity_get_older_updates',
			'post_update',
			'new_activity_comment',
			'delete_activity',
			'delete_activity_comment',
			'spam_activity',
			'spam_activity_comment',
			'activity_mark_fav',
			'activity_mark_unfav',

			// Groups
			'groups_invite_user',
			'joinleave_group',

			// Members
			'addremove_friend',
			'accept_friendship',
			'reject_friendship',

			// Messages
			'messages_close_notice',
			'messages_send_reply',
			'messages_markunread',
			'messages_markread',
			'messages_delete',
			'messages_autocomplete_results'
		);

		// For each of the problematic hooks, exit at the very end of execution
		foreach( $actions as $action ) {
			add_action( 'wp_ajax_'        . $action, create_function( '', 'exit;' ), 9999 );
			add_action( 'wp_ajax_nopriv_' . $action, create_function( '', 'exit;' ), 9999 );
		}
	}
	add_action( 'after_setup_theme', 'bp_die_legacy_ajax_callbacks', 20 );
endif;
?>