<?php

add_action( 'admin_head', 'back_end_head_my_hawk' );
add_action( 'admin_init', 'back_end_jquery' );

//writing in backend head
function back_end_head_my_hawk(){
	echo '<link rel="stylesheet" type="text/css" media="all" href="'.WP_PLUGIN_URL.'/my-hawk/css/style.css" />';
	echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/my-hawk/js/functions.js"></script>';
}

//adding jquery to the admin part
function back_end_jquery(){
	wp_enqueue_script('jquery');
}

?>
