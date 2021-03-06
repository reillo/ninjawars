<?php
namespace app\environment;

use Symfony\Component\HttpFoundation\Request;

/**
 * Creates an API for using a repeatable request and other globals
**/
class RequestWrapper{
	static $request = null;
	public static function init(){
		if(!static::$request){
			// Create request object from global page request otherwise.
			static::$request = Request::createFromGlobals();
		}
		// Otherwise, the request will be pre-injected.
	}

	// Inject a request object if unavailable, e.g. on cli or 
	public static function inject(Request $request){
		static::$request = $request;
	}

	// Nullify the static request, generally for unit testing.
	public static function destroy(){
		static::$request = null;
	}

	// Get url parameter by key
	public static function get($val){
		static::init();
		return static::$request->query->get($val);
	}

	// Post parameter by key
	public static function getPost($val){
		static::init();
		return static::$request->request->get($val);
	}

	// Equivalent to $_REQUEST
	public static function getPostOrGet($val){
		return first_value(static::getPost($val), static::get($val));
	}

	// Request parameter by key

}