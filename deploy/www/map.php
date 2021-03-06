<?php
require_once(LIB_ROOT."data/lib_npc.php");

$private = false;
$alive   = false;


if ($error = init($private, $alive)) {
	display_error($error);
} else {

// Here is where the node locations are defined, and their order is allocated.
// 

$nodes = array(

	array( // Row
	
		array('name'=>'Shrine', 'type'=>'shrine building', 'url'=>'shrine.php', 'image'=>'shrine.png', 'tile_image'=>'concentric_shrine.png', 'xcoord'=>0, 'ycoord'=>0, 'id'=>1)
		, array('name'=>'', 'type'=>'rice-field', 'url'=>'', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>12)
		, array('name'=>'Road', 'type'=>'north-south-road', 'url'=>'', 'tile_image'=>'north-south-road.png', 'xcoord'=>2, 'ycoord'=>0, 'id'=>3)
		, array('name'=>'', 'type'=>'rice-field', 'url'=>'', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>12)
		, array('name'=>'Doshin', 'type'=>'doshin building', 'url'=>'doshin_office.php', 'image'=>'doshin.png', 'tile_image'=>'doshin_building.png', 'xcoord'=>1, 'ycoord'=>0, 'id'=>2)
		
	),

	array( // Row
	
		array('name'=>'', 'type'=>'wheat-field', 'url'=>'', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>15)
		, array('name'=>'Dojo', 'type'=>'dojo building',  'url'=>'dojo.php', 'tile_image'=>'concentric_leaf.png', 'xcoord'=>1, 'ycoord'=>1, 'id'=>7)
		, array('name'=>'Road', 'type'=>'north-south-road', 'url'=>'', 'tile_image'=>'north-south-road.png', 'xcoord'=>2, 'ycoord'=>0, 'id'=>3)
		, array('name'=>'Shop', 'type'=>'weapons-shop building',  'url'=>'shop.php', 'tile_image'=>'concentric_star.png', 'xcoord'=>0, 'ycoord'=>1, 'id'=>6)
		, array('name'=>'', 'type'=>'rice-field', 'url'=>'', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>12)
		
	),
	array(// Row
	
		array('name'=>'Rice Paddy', 'type'=>'wheat-field', 'url'=>'', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>10)
		, array('name'=>'Road', 'type'=>'east-west-road', 'url'=>'', 'xcoord'=>2, 'ycoord'=>1, 'id'=>8)
		, array('name'=>'Village Square', 'type'=>'village-square', 'url'=>'village.php', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>12)
		, array('name'=>'Road', 'type'=>'east-west-road', 'url'=>'', 'xcoord'=>2, 'ycoord'=>1, 'id'=>8)
		, array('name'=>'Fields', 'type'=>'rice-field', 'url'=>'work.php', 'tile_image'=>'concentric_field.png', 'xcoord'=>2, 'ycoord'=>14, 'id'=>14)
		// Unnamed node.
	),

	array(// Row
		array('name'=>'', 'type'=>'wheat-field', 'url'=>'', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>15)
		, array('name'=>'Casino', 'type'=>'casino building', 'url'=>'casino.php', 'tile_image'=>'elemental_coin.png', 'xcoord'=>0, 'ycoord'=>2, 'id'=>11)
		, array('name'=>'Road', 'type'=>'north-south-road', 'url'=>'', 'tile_image'=>'north-south-road.png', 'xcoord'=>2, 'ycoord'=>1, 'id'=>17)
		, array('name'=>'Bath House', 'type'=>'bath-house building', 'url'=>'duel.php', 'tile_image'=>'concentric_star.png', 'xcoord'=>2, 'ycoord'=>13, 'id'=>19)
		, array('name'=>'Fields', 'type'=>'rice-field', 'url'=>'work.php', 'tile_image'=>'concentric_field.png', 'xcoord'=>2, 'ycoord'=>14, 'id'=>14)
	),
	
	array(// Row
		array('name'=>'Rice Paddy', 'type'=>'wheat-field', 'url'=>'', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>10)
		, array('name'=>'Grassy Knoll', 'type'=>'grass', 'url'=>'', 'tile_image'=>null, 'xcoord'=>0, 'ycoord'=>2, 'id'=>11)
		, array('name'=>'Road', 'type'=>'north-south-road', 'url'=>'', 'tile_image'=>'north-south-road.png', 'xcoord'=>2, 'ycoord'=>1, 'id'=>17)
		, array('name'=>'Fields', 'type'=>'rice-field', 'url'=>'work.php', 'tile_image'=>'concentric_field.png', 'xcoord'=>2, 'ycoord'=>13, 'id'=>13)
		, array('name'=>'Fields', 'type'=>'rice-field', 'url'=>'work.php', 'tile_image'=>'concentric_field.png', 'xcoord'=>2, 'ycoord'=>14, 'id'=>14)
	)
	);
	/*
	
// Old locations array.
$locations = array(
	array('name'=>'Shrine', 'url'=>'shrine.php', 'image'=>'shrine.png', 'tile_image'=>'concentric_shrine.png')
	, array('name'=>'Doshin', 'url'=>'doshin_office.php', 'image'=>'doshin.png', 'tile_image'=>'doshin_building.png')
	, array('name'=>'Fields', 'url'=>'work.php', 'tile_image'=>'concentric_field.png')
	, array('name'=>'Shop',   'url'=>'shop.php', 'tile_image'=>'concentric_star.png')
	, array('name'=>'Dojo',   'url'=>'dojo.php', 'tile_image'=>'concentric_leaf.png')
	, array('name'=>'Casino', 'url'=>'casino.php', 'tile_image'=>'elemental_coin.png')
);
*/

display_page(
	'map.tpl'
	, 'Map'
	, array(
		'nodes'		  => $nodes
//		'locations'   => $locations
		, 'show_ad'   => rand(1, 20) // Only show the ad in the village 1/10th of the time, enough to make it use appropriate data for the ads.
	)
	, array(
		'quickstat' => 'player'
	)
);
}
