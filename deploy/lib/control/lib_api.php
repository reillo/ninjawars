<?php
/* The functions used by the json api.
Test URLs:
http://nw.local/api.php?type=char_search&jsoncallback=alert&term=tchalvak&limit=10
http://nw.local/api.php?type=facebook_login_sync&jsoncallback=alert

 */

/**
 * Determine which function to call to get the json for.
**/
function nw_json($type, $dirty_jsoncallback) {
	$jsoncallback = $dirty_jsoncallback;
	$jsoncallback = !preg_match('/[^a-z_0-9]/i', $dirty_jsoncallback)? $dirty_jsoncallback : null; // Reject if non alphanumeric and _ chars
	if(!$jsoncallback){
		header('Content-Type: application/json; charset=utf8');
		return json_encode(false);
	}
	$res = false;
	//  Whitelist of valid callbacks.
	$valid_type_map = array('player'=>'json_player','latest_event'=>'json_latest_event', 'chats'=>'json_chats', 
		'latest_message'=>'json_latest_message', 'index'=>'json_index', 'latest_chat_id'=>'json_latest_chat_id', 
		'inventory'=>'json_inventory', 'new_chats'=>'json_new_chats', 'send_chat'=>'json_send_chat', 
		'char_search'=>'json_char_search');
	$res = null;
	$data = in('data');

	if (isset($valid_type_map[$type])) {
		if ($type == 'send_chat') {
			$res = $jsoncallback.'('.json_send_chat(in('msg')).')';
		} else if ($type == 'new_chats') {
			$chat_since = in('since', null);
			$chat_limit = in('chat_limit', 100);
			$res = $jsoncallback.'('.json_new_chats($chat_since, $chat_limit).')';
		} elseif ($type == 'chats') {
			$chat_limit = in('chat_limit', 20);
			$res = $jsoncallback.'('.json_chats($chat_limit).')';
		} elseif ($type == 'char_search') {
			$res = $jsoncallback.'('.json_char_search(in('term'), in('limit')).')';
		} elseif (!empty($data)){ // If data param is present, pass data to the function
			$res = $jsoncallback.'('.$valid_type_map[$type]($data).')';
		} else { // No data present, just call the function with no arguments.
			$res = $jsoncallback.'('.$valid_type_map[$type]().')';
		}
	}
	return $res;
}

// Search through characters by text, returning multiple matches.
function json_char_search($term, $limit) {
	if (!is_numeric($limit)) {
		$limit = 10;
	}
	// Should be fine for this to allow regex characters here if it happens.
	$res = query("select player_id, uname from players 
			where uname ilike :term || '%' and active=1 
			order by level desc limit :limit", 
			array(':term'=>$term, ':limit'=>array($limit, PDO::PARAM_INT)));
	return '{"char_matches":'.json_encode($res->fetchAll(PDO::FETCH_ASSOC)).'}';
}

function json_latest_message() {
	DatabaseConnection::getInstance();
	$user_id = (int) self_char_id();

	$statement = DatabaseConnection::$pdo->prepare("SELECT message_id, message, date, send_to, send_from, unread, uname AS sender FROM messages JOIN players ON player_id = send_from WHERE send_to = :userID1 AND send_from != :userID2 and unread = 1 ORDER BY date DESC LIMIT 1");
	$statement->bindValue(':userID1', $user_id);
	$statement->bindValue(':userID2', $user_id);
	$statement->execute();

	// Skips message sent by self, i.e. clan send messages.
	return '{"message":'.json_encode($statement->fetch()).'}';
}

function json_latest_event() {
	DatabaseConnection::getInstance();
	$user_id = (int) self_char_id();

	$statement = DatabaseConnection::$pdo->prepare("SELECT event_id, message AS event, date, send_to, send_from, unread, uname AS sender FROM events JOIN players ON player_id = send_from WHERE send_to = :userID and unread = 1 ORDER BY date DESC LIMIT 1");
	$statement->bindValue(':userID', $user_id);
	$statement->execute();

	return '{"event":'.json_encode($statement->fetch()).'}';
}

function json_player() {
	$player = self_char_info();
	return '{"player":'.json_encode($player).'}';
}

function json_chats($limit = 20) {
	$limit = (int)$limit;
	DatabaseConnection::getInstance();
	$statement = DatabaseConnection::$pdo->prepare("SELECT * FROM chat ORDER BY date DESC LIMIT :limit");
	$statement->bindValue(':limit', $limit);
	$statement->execute();
	$chats = $statement->fetchAll();

	return '{"chats":'.json_encode($chats).'}';
}

function json_latest_chat_id() {
	DatabaseConnection::getInstance();
	$statement = DatabaseConnection::$pdo->query("SELECT chat_id FROM chat ORDER BY date DESC LIMIT 1");

	return '{"latest_chat_id":'.json_encode($statement->fetch()).'}';
}

function json_send_chat($msg) {
	if (is_logged_in()) {
		require_once(LIB_ROOT."control/lib_chat.php");
		$msg = trim($msg);
		$user_id = (int) self_char_id();
		$info = self_char_info();
		$success = send_chat($user_id, $msg);
		if(!$success){
			return false;
		} else {
			return '{"message":"'.$msg.'","sender_id":"'.$user_id.'","uname":"'.$info['uname'].'"}';
		}
	}
}

// Get the newest chats for the mini-chat area.
function json_new_chats($since) {
	$since = ($since ? (float)$since : null); // Since is a float?  Weird
	$now = microtime(true);
	DatabaseConnection::getInstance();
	if ($since) {
		$statement = DatabaseConnection::$pdo->prepare("SELECT chat.*, uname FROM chat 
			LEFT JOIN players ON player_id = sender_id 
			WHERE EXTRACT(EPOCH FROM date) > :since ORDER BY date ASC"
		  );
		$statement->bindValue(':since', $since);
	} else {
		$statement = DatabaseConnection::$pdo->prepare("SELECT chat.*, uname FROM chat 
			LEFT JOIN players ON player_id = sender_id ORDER BY date ASC");
	}
	$statement->execute();
	$chats = $statement->fetchAll();

	return '{"new_chats":{"datetime":'.json_encode($now).',"new_count":'.count($chats).',"chats":'.json_encode($chats).'}}';
}

function json_member_count() {
	$members = member_counts(); // From lib_player_list.
	return json_encode($members);
}

function json_inventory() {
	$char_id = (int) self_id();
	return '{"inventory":'.json_encode(
		query_array("SELECT item.item_display_name as item, amount FROM inventory join item on inventory.item_type = item.item_id WHERE owner = :char_id ORDER BY item_display_name", array(':char_id'=>$char_id))
	).'}';
}

function json_index() {
	DatabaseConnection::getInstance();
	$player   = public_self_info();
	$events   = array();
	$messages = array();
	$user_id  = $player['player_id'];
	$unread_messages = null;
	$unread_events = null;

	if ($user_id) {
		$events = DatabaseConnection::$pdo->prepare("SELECT event_id, message AS event, date, send_to, send_from, unread, uname AS sender FROM events JOIN players ON player_id = send_from WHERE send_to = :userID and unread = 1 ORDER BY date DESC");
		$events->bindValue(':userID', $user_id);

		$events->execute();
		
		$unread_events = $events->rowCount();

		$messages = DatabaseConnection::$pdo->prepare("SELECT message_id, message, date, send_to, send_from, unread, uname AS sender FROM messages JOIN players ON player_id = send_from WHERE send_to = :userID1 AND send_from != :userID2 and unread = 1 ORDER BY date DESC");
		$messages->bindValue(':userID1', $user_id);
		$messages->bindValue(':userID2', $user_id);

		$messages->execute();
		
		$unread_messages = $messages->rowCount();
	}

	return '{"player":'.json_encode($player).',
				"member_counts":'.json_member_count().',
	            "unread_messages_count":'.json_encode($unread_messages).',
				"message":'.json_encode(!empty($messages) ? $messages->fetch() : null).',
				"inventory":{"inv":1,"items":'.json_encode(query_array("SELECT item.item_display_name as item, amount FROM inventory join item on inventory.item_type = item.item_id WHERE owner = :user_id ORDER BY item_display_name", array(':user_id'=>$user_id))).',"hash":"'.md5(strtotime("now")).'"},
				"unread_events_count":'.json_encode($unread_events).',
				"event":'.json_encode(!empty($events) ? $events->fetch() : null).'}';
}