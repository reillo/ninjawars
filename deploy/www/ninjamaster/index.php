<?php
require_once(LIB_ROOT."control/lib_inventory.php");
require_once(LIB_ROOT."data/NpcFactory.php");
require_once(LIB_ROOT."data/Npc.php");

class AdminViews{

	public static function high_rollers(){
		// Select first few max kills from players.
		// Max turns.
		// Max gold.
		// Max kills
		// etc.
		$res = array();
		$res['gold'] = query_array('select player_id, uname, gold from players order by gold desc limit 10');
		$res['turns'] = query_array('select player_id, uname, turns from players order by turns desc limit 10');
		$res['kills'] = query_array('select player_id, uname, kills from players order by kills desc limit 10');
		$res['health'] = query_array('select player_id, uname, health from players order by health desc limit 10');
		$res['ki'] = query_array('select player_id, uname, ki from players order by ki desc limit 10');
		return $res;
	}

	public static function duped_ips(){
		$host= gethostname();
		$server_ip = gethostbyname($host);
		return query_array('select uname, player_id, ip from players where ip in (SELECT ip FROM players 
				WHERE active = 1 and ip != \'\' and ip != \'127.0.0.1\' and ip != \''.$server_ip.'\' GROUP  BY ip HAVING count(*) > 1 ORDER BY count(*) ASC limit 30) order by ip');
	}


	// Reformat the character info sets.
	public static function split_char_infos($ids){
		if(is_numeric($ids)){ // Single id, so return a single data set
			return array(char_info($ids, $admin_info=true));
		} else { // Get the info for multiple ninjas
			$res = array();
			$ids = explode(',', $ids);
			foreach($ids as $id){
				$res[$id] = char_info($id, $admin_info=true);
			}
			return $res;
		}
	}

	public static function char_inventory($char_id){
		return inventory_counts($char_id);
	}
}

// Redirect for any non-admins.
$char_id = self_char_id();
$self = null;
if(positive_int($char_id)){
	$self = new Player($char_id);
}
if($self instanceof Player && $self->isAdmin()){
	// Admin possibilities start here.

	$view_char = null;

	$dupes = AdminViews::duped_ips();
	$stats = AdminViews::high_rollers();

	$npcs = NpcFactory::allNonTrivialNpcs();
	$trivial_npcs = NpcFactory::allTrivialNpcs();

	$char_name = in('char_name');

	if(is_string($char_name) && trim($char_name)){
		$view_char = get_char_id($char_name);
	}

	// If a request is made to view a character's info, show it.
	$view_char = $view_char? $view_char : in('view');
	$char_infos = $char_inventory = $message = null;
	if($view_char){
		$char_infos = AdminViews::split_char_infos($view_char);
		$char_inventory = AdminViews::char_inventory($view_char);
		$message = $char_infos[0]['messages']; // Split the message out as a separate var for space reasons
		unset($char_infos[0]['messages']);
	}


	display_page(
		'ninjamaster.tpl'	// *** Main Template ***
		, 'Admin Actions' // *** Page Title ***
		, ['stats'=>$stats, 'char_infos'=>$char_infos, 'dupes'=>$dupes, 'char_inventory'=>$char_inventory,
			'char_name'=>$char_name, 'npcs'=>$npcs, 'trivial_npcs'=>$trivial_npcs, 'message'=>$message] // *** Page Variables ***
	);


} else {
	// Redirect to the root site.
	redirect('/');
}




