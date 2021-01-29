<?php
/*
 * Template Name: get-my-bp-pages
 * Description: enables an apps menu to redirecrt to the right bp page in the parent site - wip big time
 */
?>
<?php 

	$url = $_SERVER["REQUEST_URI"];

	// match get-my-bp-pages?myactivity etc in url and redierct appropraitly - hack but fine for now

	// IE. https://bsv.org.uk/get-my-bp-pages?myprofile

	$isMyActivity = strpos($url, 'myactivity');
	$isMyProfile = strpos($url, 'myprofile');

	$userid = get_current_user_id();

	if($isMyActivity){
		echo '$isMyActivity';
		header("Location: " . bp_core_get_user_domain( $userid ));
		exit();
	}elseif($isMyProfile){
		echo '$isMyProfile';
		header("Location: " . bp_core_get_user_domain( $userid ) . "profile/edit/group/1/");
		exit();
	}else{
		echo 'THIS SECTION IS CURRENTLY UNAVAILABLE';
	}
	
?>