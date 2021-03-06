<?php
require_once(LIB_ROOT.'base.inc.php');
function test_player_obj() {
	// in: player_id, out: valid db save
	DatabaseConnection::getInstance();
	$player_id_sel = DatabaseConnection::$pdo->query("SELECT player_id FROM players WHERE uname = 'glassbox'");
	$player_id = $player_id_sel->fetchColumn();
	$player = new Player($player_id);
	assert($player->vo->player_id == $player_id);
	$orig_clan = $player->vo->clan_long_name;
	$player->vo->clan_long_name = 'Testingz';
	$player->save();
	$changed_player = new Player($player_id);
	$changed_clan = $changed_player->vo->clan_long_name;
	$changed_player->vo->clan_long_name = $orig_clan;
	assert($changed_clan == 'Testingz');

	// in: player uname, out: valid db save
	$player = new Player('glassbox');
	assert($player->vo->player_id == $player_id);
	$orig_clan = $player->vo->clan_long_name;
	$player->vo->clan_long_name = 'Testingz';
	$player->save();
	$changed_player = new Player($player_id);
	$changed_clan = $changed_player->vo->clan_long_name;
	$changed_player->vo->clan_long_name = $orig_clan;
	assert($changed_clan == 'Testingz');

	// in: player status check, out: no errors
	$player = new Player('glassbox');
	assert($player->vo->player_id == $player_id);
	$orig_clan = $player->vo->clan_long_name;
	$player->vo->clan_long_name = 'Testingz';
	$player->save();
	$changed_player = new Player($player_id);
	$changed_clan = $changed_player->vo->clan_long_name;
	$changed_player->vo->clan_long_name = $orig_clan;
	assert($changed_clan == 'Testingz');

	// in: player, out: vo of player data
	$player = new Player('glassbox');
	$vo = $player->as_vo();
	assert($vo instanceof PlayerVO);

	// in: player, out: array of player data
	$player = new Player('glassbox');
	$arr = $player->as_array();
	assert(count($arr)>0);
	var_dump($arr);
}
?>
