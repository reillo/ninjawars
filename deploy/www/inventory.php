<?php
$private    = true;
$alive      = false;
$quickstat  = "viewinv";
$page_title = "Your Inventory";

include SERVER_ROOT."interface/header.php";
?>

<h1>Your Inventory</h1>

<div class='item-list'>

<?php
$user_id = get_user_id();
DatabaseConnection::getInstance();
$statement = DatabaseConnection::$pdo->prepare("SELECT amount AS c, item FROM inventory WHERE owner = :owner GROUP BY item, amount");
$statement->bindValue(':owner', $user_id);
$statement->execute();

if ($data = $statement->fetch()) {
	$items['Speed Scroll']   = 0;
	$items['Stealth Scroll'] = 0;
	$items['Shuriken']       = 0;
	$items['Fire Scroll']    = 0;
	$items['Ice Scroll']     = 0;
	$items['Dim Mak']        = 0;

	$itemData = array(
		'Speed Scroll' => array(
			'codename'   => 'Speed Scroll'
			, 'display'  => 'Speed Scrolls'
		)
		, 'Stealth Scroll' => array(
			'codename'   => 'Stealth Scroll'
			, 'display'  => 'Stealth Scrolls'
		)
		, 'Shuriken' => array(
			'display'  => 'Shuriken'
		)
		, 'Fire Scroll' => array(
			'display'  => 'Fire Scrolls'
		)
		, 'Ice Scroll' => array(
			'display'  => 'Ice Scrolls'
		)
		, 'Dim Mak' => array(
			'display'  => 'Dim Mak'
		)
	);

	do {
		$items[$data['item']] = $data['c'];
	} while ($data = $statement->fetch());

	echo "<div style='margin-bottom: 10px;'>Click a linked item to use it on yourself.</div>\n";

	echo "<table style=\"width: 150;\">\n";

	foreach ($items AS $itemName=>$amount) {
		if ($amount > 0 && is_array($itemData[$itemName])) {
			echo "<tr>\n";
			echo "  <td>\n    ";

			if (array_key_exists('codename', $itemData[$itemName])) {
				echo "<a href=\"inventory_mod.php?item=".urlencode($itemData[$itemName]['codename'])."&amp;selfTarget=1&amp;target=$username&amp;link_back=inventory\">";
			}

			echo $itemData[$itemName]['display'];

			if (array_key_exists('codename', $itemData[$itemName])) {
				echo "</a>";
			}

			echo ":\n  </td>\n";

			echo "  <td>\n";
			echo    $amount."\n";
			echo "  </td>\n";
			echo "</tr>\n";
		}
	}

	echo "</table>\n";
} else {
	echo "You have no items, to buy some, visit the <a href=\"shop.php\">shop</a>.\n";
}


?>
</div>
  <form id="player_search" action="list_all_players.php" method="get" name="player_search">
    <div>
      <a href="list_all_players.php?hide=dead">Use an Item on a ninja?</a>
      <input id="searched" type="text" maxlength="50" name="searched" class="textField">
      <input id="hide" type="hidden" name="hide" value="dead">
      <input type="submit" value="Search for Ninja" class="formButton">
    </div>
  </form>

  <p>
  Current gold: <?php echo getGold($username);?>
  <p>


<?php
include SERVER_ROOT."interface/footer.php";
?>
