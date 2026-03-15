<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WOOCR_LITE' ) ? WOOCR_LITE : WOOCR_FILE, 'woocr_activate' );
register_deactivation_hook( defined( 'WOOCR_LITE' ) ? WOOCR_LITE : WOOCR_FILE, 'woocr_deactivate' );
add_action( 'admin_init', 'woocr_check_version' );

function woocr_check_version() {
	if ( ! empty( get_option( 'woocr_version' ) ) && ( get_option( 'woocr_version' ) < WOOCR_VERSION ) ) {
		wpc_log( 'woocr', 'upgraded' );
		update_option( 'woocr_version', WOOCR_VERSION, false );
	}
}

function woocr_activate() {
	wpc_log( 'woocr', 'installed' );
	update_option( 'woocr_version', WOOCR_VERSION, false );
}

function woocr_deactivate() {
	wpc_log( 'woocr', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}