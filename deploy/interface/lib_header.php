<?php
/**
 * Update the information of a viewing observer, or player.
**/
function update_activity_info()
{
	// ******************** Usage Information of the browser *********************
	$remoteAddress = (isset($_SERVER['REMOTE_ADDR'])     ? $_SERVER['REMOTE_ADDR']     : NULL);
	$userAgent     = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL);
	$referer       = (isset($_SERVER['HTTP_REFERER'])    ? $_SERVER['HTTP_REFERER']    : NULL);

	// ************** Setting anonymous and player usage information

	$dbconn = new DatabaseConnection();

	if (!SESSION::is_set('online'))
	{	// *** Completely new session, update latest activity log. ***
		if ($remoteAddress)
		{	// *** Delete prior to trying to re-insert into the people online. ***
			$statement = DatabaseConnection::$pdo->prepare('DELETE FROM ppl_online WHERE ip_address = :ip OR session_id = :sessionID');

			$statement->bindValue(':ip',        $remoteAddress);
			$statement->bindValue(':sessionID', session_id());

			$statement->execute();
		}

		// *** Update viewer data. ***
		$statement = DatabaseConnection::$pdo->prepare('INSERT INTO ppl_online (session_id, activity, ip_address, refurl, user_agent) VALUES (:sessionID, now(), :ip, :referer, :userAgent)');

		$statement->bindValue(':sessionID', session_id());
		$statement->bindValue(':ip',        $remoteAddress);
		$statement->bindValue(':referer',   $referer);
		$statement->bindValue(':userAgent', $userAgent);

		$statement->execute();

		SESSION::set('online', true);
	}
	else
	{	// *** An already existing session. ***
		$statement = DatabaseConnection::$pdo->prepare('UPDATE ppl_online SET activity = now(), member = :member WHERE session_id = :sessionID');
		$statement->bindValue(':sessionID', session_id());
		$statement->bindValue(':member', is_logged_in(), PDO::PARAM_BOOL);
		$statement->execute();
	}
}

/**
 * Writes out the header for all the pages.
 * Will need a "don't write header" option for jQuery iframes.
**/
function render_html_for_header($p_title = null, $p_bodyClasses = 'body-default', $p_isIndex=null)
{
	$parts = array(
		'title'          => ($p_title ? htmlentities($p_title) : '')
		, 'body_classes' => $p_bodyClasses
		, 'WEB_ROOT'     => WEB_ROOT
		, 'local_js'     => (OFFLINE || DEBUG)
		, 'DEBUG'        => DEBUG
		, 'is_index'     => $p_isIndex
		, 'logged_in'    => get_user_id()
	);

	return render_template('header.tpl', $parts);
}

function render_header($p_title='Ninjawars : Live by the Sword', $p_bodyClasses = 'body-default'){
    return render_html_for_header($p_title, $p_bodyClasses = 'body-default');
}

// Renders the error message when a section isn't viewable.
function render_viewable_error($p_error)
{
	return render_template("error.tpl", array('error'=>$p_error));
}

/**
 * Returns the state of the player from the database,
 * uses a user_id if one is present, otherwise
 * defaults to the currently logged in player, but can act on any player
 * if another username is passed in.
 * @param $user user_id or username
 * @param @password Unless true, wipe the password.
**/
function get_player_info($p_id = null, $p_password = false)
{
	$dbconn = new DatabaseConnection();
	$dao = new PlayerDAO($dbconn);
	$id = either($p_id, SESSION::get('player_id')); // *** Default to current. ***

	$playerVO = $dao->get($id);

	$player_data = array();

	if ($playerVO)
	{
		foreach ($playerVO as $fieldName=>$value)
		{
			$player_data[$fieldName] = $value;
		}

		if (!$p_password)
		{
			unset($player_data['pname']);
		}
	}

	///TODO: Migrate all calls of this function to a new function that returns a Player object. When all calls to this function are removed, remove this function
	return $player_data;
}

/* Potential solution for hash-based in-iframe navigation.
function hash_page_name($page_title=null){
	$page = basename(__FILE__, ".php");
	if ($page && file_exists($page)){
	$page = urlencode($page);
	var_dump($page);
	echo
	<<< EOT
	 <script type="text/javascript">
			if(document.location.hash){
				document.location.hash = '$page';
			}
			</script>
EOT;
	}
}

hash_page_name($page_title);
*/
?>
