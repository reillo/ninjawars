<?php
require_once(__DIR__.'/../resources.php');
require_once(LIB_ROOT.'base.inc.php');

class TestAccountCreateAndDestroy{
	public static $test_email = 'testphpunit@example.com';
	public static $test_password = 'password';
	public static $test_ninja_name = 'phpunit_ninja_name';

// Library for creating and destroying test-only accounts, for use in their various ways in testing.
public static function purge_test_accounts($test=null){
    $test_ninja_name = $test? $test : TestAccountCreateAndDestroy::$test_ninja_name;
    $active_email = 'testphpunit@example.com';
    $aid = get_char_id(TestAccountCreateAndDestroy::$test_ninja_name);
    query('delete from players where player_id in 
        (select player_id from players join account_players on _player_id = player_id 
        	join accounts on _account_id = account_id 
            where active_email = :active_email or account_identity= :ae2 or players.uname = :uname)', 
        array(':active_email'=>$active_email, ':ae2'=>$active_email, ':uname'=>$test_ninja_name)); // Delete the players
    query('delete from account_players where _account_id in (select account_id from accounts 
            where active_email = :active_email or account_identity= :ae2)', // Delete the account_players linkage.
        array(':active_email'=>$active_email, ':ae2'=>$active_email));
    $query = query('delete from accounts where active_email = :active_email or account_identity= :ae2', 
    	array(':active_email'=>$active_email, ':ae2'=>$active_email)); // Finally, delete the test account.
    return ($query->rowCount() > 0);
    
    /*
    For manual deletion:
delete from players where player_id in (select player_id from players left join account_players on _player_id = player_id left join accounts on _account_id = account_id where active_email = 'testphpunit@example.com' or account_identity='testphpunit@example.com');	
delete from account_players where _account_id in (select account_id from accounts where active_email = 'testphpunit@example.com' or account_identity='testphpunit@example.com');
delete from accounts where active_email = 'testphpunit@example.com' or account_identity='testphpunit@example.com';
    */
}

// Create a testing account
public static function create_testing_account(){
	@session_start();
	$previous_server = @$_SERVER['REMOTE_ADDR'];
	$_SERVER['REMOTE_ADDR']='127.0.0.1';
	TestAccountCreateAndDestroy::purge_test_accounts();
	$found = get_char_id(TestAccountCreateAndDestroy::$test_ninja_name);
    if($found){
		throw new Exception('Test user already exists');
	}
	// Create test user, unconfirmed, whatever the default is for activity.
	$preconfirm = true;
	$confirm = rand(1000,9999); //generate confirmation code

	// Use the function from lib_player
	$player_params = array(
		'send_email'    => TestAccountCreateAndDestroy::$test_email
		, 'send_pass'   => TestAccountCreateAndDestroy::$test_password
		, 'send_class'  => 'dragon'
		, 'preconfirm'  => true
		, 'confirm'     => $confirm
		, 'referred_by' => 'ninjawars.net'
	);
	ob_start(); // Skip extra output
	$error = create_account_and_ninja(TestAccountCreateAndDestroy::$test_ninja_name, $player_params);
	ob_end_clean();
	$_SERVER['REMOTE_ADDR']=$previous_server; // Reset remote addr to whatever it was before.
	$char_id = get_char_id(TestAccountCreateAndDestroy::$test_ninja_name);
	return $char_id;
}

}