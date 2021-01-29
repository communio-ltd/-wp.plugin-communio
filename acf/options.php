<?php

// ADD OPTIONS PAGE 
$ACFCommunioOptions = array(
	'page_title' => 'App Options',
	'menu_title' => '',
	'menu_slug' => '',
	'capability' => 'edit_posts',
	'position' => false,
	'parent_slug' => '',
	'icon_url' => false,
	'redirect' => true,
	'post_id' => 'options',
	'autoload' => false,
);

// ensure acf is running
if( function_exists( 'the_field' ) ) {
	acf_add_options_page( $ACFCommunioOptions );
}else{
	 add_action( 'admin_notices', 'my_acf_notice_new' );
}
