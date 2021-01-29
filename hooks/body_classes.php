<?php

function my_body_classes( $classes ) {
 
		$url = $_SERVER["REQUEST_URI"];
		$isItNative = strpos($url, 'nav=0');

		/* hack */
		if( $isItNative ){
			 $classes[] = 'native';
		}else{
			$classes[] = 'website';
		}

    return $classes;
}

add_filter( 'body_class','my_body_classes' );