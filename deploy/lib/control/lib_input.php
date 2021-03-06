<?php
require_once(CORE.'environment/RequestWrapper.php');
/*
 * Deals with getting and filtering user input (just from request).
 *
 * @package input
 */

use app\environment\RequestWrapper;

// Input function that by default LEAVES INPUT COMPLETELY UNFILTERED
// To not filter some input, you have to explicitly pass in null for the third parameter,
// e.g. in('some_url_parameter', null, null)
function in($var_name, $default_val=null, $filter_callback=null) {
	$req = RequestWrapper::getPostOrGet($var_name);
	$result = isset($req)? $req: $default_val;
	// Check that the filter function sent in exists.
	if ($filter_callback && function_exists($filter_callback)) {
		$result = $filter_callback($result);
	}

    return $result;
}

/**
 *  Wrapper around the post variables as a clean way to get input.
 **/
function post($key, $default_val=null){
	$post = RequestWrapper::getPost($key);
	return isset($post)? $post: $default_val;
}

// Return a casting with a result of a positive int, or else zero.
function non_negative_int($num){
	return ((int)$num == $num && (int)$num > 0? (int)$num : 0);
}



function toInt($dirty) {
	return sanitize_to_int($dirty);
}

// Casts to an integer anything that can be cast that way non-destructively, otherwise null.
function sanitize_to_int($dirty) {
	if ($dirty == (int) $dirty) { // Cast anything that can be non-destructively cast.
		$res = (int) $dirty;
	} else {
		$res = null;
	}

	return $res;
}

// Return a casting with a result of a positive int, or else zero.
function positive_int($num){
	// Note that this function will cast strings with leading integers to those integers.  E.g. 555'sql-injection becomes 555
	return ((int)$num == $num && (int)$num > 0? (int)$num : 0);
}

// Strip everything except alphanumeric, underscore, and dash
function sanitize_to_word($dirty) {
	return preg_replace("[^A-Za-z0-9_\-]", "", (string) $dirty);
}

// Strip everything except words, digits, spaces, _, -, ., @, :, and slash for urls /
function sanitize_to_text($dirty) {
	// Allows words, digits, spaces, _, -, ., @, :, and slash for urls /
	return preg_replace("/[^\w\d\s_\-\.\@\:\/]/", "", (string) $dirty);
}

function sanitize_to_email($dirty) {
	return filter_var($dirty, FILTER_SANITIZE_EMAIL);
}

// Restrict an option to certain possibilities, e.g. for an orderby string, the possibilities would be an array of column names.
function restrict_to($original, $possibilities=array(), $default=null) {
	foreach ($possibilities as $possibility) {
		if ($original == $possibility) {
			return $possibility;
		}
	}

	return $default;  // If the original doesn't match, just return the default.
}
