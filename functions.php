<?php

// Theme foundation
include_once 'includes/utilities.php';
include_once 'includes/config.php';
include_once 'includes/meta.php';
include_once 'includes/media-backgrounds.php';
include_once 'includes/nav-functions.php';
include_once 'includes/header-functions.php';
include_once 'includes/footer-functions.php';

include_once 'includes/degree-functions.php';
include_once 'includes/vertical-functions.php';
include_once 'includes/resources-functions.php';


// Plugin extras/overrides

if ( class_exists( 'UCF_Post_List_Common' ) ) {
	include_once 'includes/post-list-functions.php';
}

if ( class_exists( 'UCF_Degree_Search_Common' ) ) {
	include_once 'includes/degree-search-functions.php';
}

if ( class_exists( 'UCF_People_PostType' ) ) {
	include_once 'includes/person-functions.php';
}

if ( class_exists( 'UCF_News_Common' ) ) {
	include_once 'includes/news-functions.php';
}
