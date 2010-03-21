<?php
// Now only a wrapper for the send_event function. 
function sendMessage($from, $to, $msg, $filter=false) {
	// Filter argument is deprecated now.
	$from_id = (int) get_user_id($from);
	$to_id = get_user_id($to);
	send_event($from_id, $to_id, $msg);
}

// For true user-to-user or user-to-clan messages as opposed to events.
function send_event($from_id, $to_id, $msg) {
	if (!$to_id) {
		$to_id = get_user_id();
	}

	if (!is_numeric($from_id) || !is_numeric($to_id)) {
		throw new Exception('A player id wasn\'t sent in to the send_event function.');
	}

	DatabaseConnection::getInstance();
	$statement = DatabaseConnection::$pdo->prepare("INSERT INTO events (event_id, send_from, send_to, message, date) 
    VALUES (default, :from, :to, :message, now())");
	$statement->bindValue(':from', $from_id);
	$statement->bindValue(':to', $to_id);
	$statement->bindValue(':message', $msg);
	$statement->execute();
}

function get_events($user_id, $limit=null) {
	DatabaseConnection::getInstance();
	$statement = DatabaseConnection::$pdo->prepare("SELECT send_from, message, unread, uname AS from FROM events 
        JOIN players ON send_from = player_id WHERE send_to = :to ORDER BY date DESC ".($limit ? "LIMIT :limit" : '')."");
	$statement->bindValue(':to', $user_id);

	if ($limit) {
		$statement->bindValue(':limit', $limit);
	}

	$statement->execute();

	return $statement->fetchAll();
}

function read_events($user_id) {
	DatabaseConnection::getInstance();
	$statement = DatabaseConnection::$pdo->prepare("UPDATE events SET unread = 0 WHERE send_to = :to");
	$statement->bindValue(':to', $user_id);
	$statement->execute();
}
?>
