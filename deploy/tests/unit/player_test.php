<?php
// Core may be autoprepended in ninjawars
require_once(LIB_ROOT.'base.inc.php');

// Note that the file has to have a file ending of ...test.php to be run by phpunit


class TestCharacter extends PHPUnit_Framework_TestCase {
	private $previous_server_ip = '';
	private $char_id;
    private $mock_ip = '127.0.0.199';


	/**
	 * group char
	**/
	function setUp(){
		require_once(ROOT.'core/control/Player.class.php');
		$this->previous_server_ip = @$_SERVER['REMOTE_ADDR'];
		$_SERVER['REMOTE_ADDR']=$this->mock_ip;
		$this->test_email = TestAccountCreateAndDestroy::$test_email; // Something@example.com probably
		$this->test_password = TestAccountCreateAndDestroy::$test_password;
		$this->test_ninja_name = TestAccountCreateAndDestroy::$test_ninja_name;
		TestAccountCreateAndDestroy::purge_test_accounts($this->test_ninja_name);
		$char_id = TestAccountCreateAndDestroy::create_testing_account();
		$this->char_id = $char_id;
	}
	
	/**
	 * group char
	**/
	function tearDown(){
		// Delete test user.
		TestAccountCreateAndDestroy::purge_test_accounts($this->test_ninja_name);
		$_SERVER['REMOTE_ADDR']=$this->previous_server_ip; // Reset remote addr to whatever it was before, just in case.
    }

	/**
	 * group char
	**/
    function testCreatePlayerObject(){
    	$char = new Player($this->char_id);
    	$this->assertTrue((bool)positive_int($char->id()));
    }


	/**
	 * group char
	**/
    function testCreatePlayerObjectHasUsefulInfo(){
    	$char = new Player($this->char_id);
    	$this->assertTrue((bool)positive_int($char->health()));
		$this->assertTrue((bool)positive_int($char->speed()));
		$this->assertTrue((bool)positive_int($char->stamina()));
		$this->assertTrue((bool)positive_int($char->strength()));
		$this->assertTrue((bool)positive_int($char->level()));
		$this->assertNotEmpty($char->name());
		$this->assertTrue((bool)positive_int($char->damage()));
    }

    /**
     * group char
    **/
    function testCharacterHasADifficultyRating(){
    	$char = new Player($this->char_id);
    	$this->assertGreaterThan(0, $char->difficulty());
    }

    function testCharacterHasAVerificationNumber(){
        $char = new Player($this->char_id);
        $this->assertGreaterThan(0, $char->getVerificationNumber());
    }

	/**
	 * group char
	**/
    function testPlayerStatusesChangeStatCalcs(){
    	$char = new Player($this->char_id);
    	$str = $char->strength();
    	$speed = $char->speed();
    	$stamina = $char->stamina();
    	$char->addStatus(SLOW);
    	$this->assertNotEquals($char->speed(), $speed, 'Speed should be different due to slow status.');
    	$this->assertTrue($char->speed() < $speed, 'Speed should be less due to slow status, but isn\'t.');
    	$char->addStatus(POISON);
    	$this->assertTrue($char->stamina() < $stamina);
    	$char->addStatus(WEAKENED);
    	$this->assertTrue($char->strength() < $str);
    	$char->resetStatus();
    	$this->assertEquals($char->strength(), $str);
    	$char->addStatus(STR_UP2);
    	$this->assertTrue($char->strength() > $str);
    }

    function testPlayerObjectCAnReturnAnIPCorrectly(){
        $char = new Player($this->char_id);
        $this->assertEquals($this->mock_ip, $char->ip());
    }

    function testPlayerObjectCanSaveDetails(){
        $bel = 'Believes in the mirror goddess.';
        $traits = 'Weird,Blue';
        $desc = 'Some description for testing';
        $goals = 'Test: to rule the world';
        $instincts = 'Kill Samurai';
        $char = new Player($this->char_id);
        $char->set_traits($traits);
        $char->set_beliefs($bel);
        $char->set_description($desc);
        $char->set_goals($goals);
        $char->set_instincts($instincts);
        PlayerDAO::saveDetails($char);
        $char = new Player($this->char_id); // Create a new player copy.
        $this->assertEquals($desc, $char->description());
        $this->assertEquals($traits, $char->traits());
        $this->assertEquals($bel, $char->beliefs());
        $this->assertEquals($goals, $char->goals());
        $this->assertEquals($instincts, $char->instincts());
    }


	/**
	 * group char
	**/
    function testCreatePlayerObjectCanSaveChanges(){
    	$char = new Player($this->char_id);
    	$ki = $char->ki();
    	$char->add_ki(55);
    	$player_factory = new PlayerDAO();
    	$this->markTestIncomplete('Player objects not yet self-saving');
    	$player_factory->save($char->vo);
    	$char_copy = new Player($this->char_id);
    	$this->assertEquals($char_copy->ki(), $ki+55);
    }    


}

