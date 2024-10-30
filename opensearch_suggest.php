<?php

	require_once ('../../../wp-config.php');
	
	$results = array();
	
	$choices = 10;
		
	
	
	function filter_where($where = '') {
		global $wpdb;
		
		$where .= ' AND post_title LIKE "%'.$wpdb->escape($_REQUEST['q']).'%"';
		return $where;
	}
	add_filter('posts_where', 'filter_where');

	
	$wp_query = new WP_Query();
	$wp_query->query(array(
		'showposts' => $choices,
		'post_status' => 'publish'
	));
	$posts = $wp_query->posts;
	
	foreach($posts as $post){
		setup_postdata($post);
		$title = strip_tags(get_the_title());
		$results[] = $title;
	}
	
	header('Content-Length:'.count($results));
	header('Content-Type: application/json; charset=utf-8');
	echo '["'.$_REQUEST['q'].'",'.json_encode($results).']';
?>