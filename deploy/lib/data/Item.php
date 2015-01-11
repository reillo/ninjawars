<?php

require_once ROOT.'core/control/lib_inventory.php';

// Managing items like shuriken, dimmak
class Item {
    protected $m_name;
    protected $m_plural;
    protected $m_ignoresStealth;
    protected $m_targetDamage;
    protected $m_maxDamage;
    protected $m_turnCost;
    protected $m_turnChange;
    protected $m_maxTurnChange;
    protected $m_covert;
    protected $m_selfUse;
    protected $m_otherUsable;
    protected $m_type;
    protected $m_identity;

    /*
    public function __construct($p_name) {
        $this->m_ignoresStealth = false;
        $this->m_name = trim($p_name);
        $this->m_turnCost = 1;
        $this->m_turnChange = null;
        $this->m_type = item_id_from_display_name($p_name);
    }*/

    // Set all the default settings for items, overridden by specified settings.
    public function __construct($dirty_identity=null) {
        if(is_string($dirty_identity) && $dirty_identity != ''){
            $data = item_info_from_identity($dirty_identity);
            $this->buildFromArray($data);
        }
    }

    // Eventually this perhaps should be abstracted to a factory pattern
    // For now, this method build the item from the database table data
    public function buildFromArray($p_data) {
        $this->m_type              = $p_data['item_id'];
        $this->m_identity          = $p_data['item_internal_name'];
        $this->m_name              = $p_data['item_display_name'];
        $this->m_plural            = $p_data['plural'];
        $this->m_turnCost          = ($p_data['turn_cost']     ? $p_data['turn_cost']     : 1);
        $this->m_maxTurnChange     = ($p_data['turn_change']   ? $p_data['turn_change']   : 0);
        $this->m_targetDamage	   = $p_data['target_damage'] ? $p_data['target_damage'] : null;
        $this->m_maxDamage 		   = $this->m_targetDamage;
        $this->m_ignoresStealth	   = ($p_data['ignore_stealth'] == 't');
        $this->m_covert            = ($p_data['covert']         == 't');
        $this->m_selfUse           = ($p_data['self_use']       == 't');
        $this->m_otherUsable           = ($p_data['other_usable']       == 't');
    }

    // Not the identity, but the display name
    public function getName()
    { 
        return $this->m_name; 
    }

    // Convenience function to get the plural name for the object.
    public function getPluralName()
    { 
        return $this->m_name.$this->m_plural; 
    }

    public function __toString()
    { 
        return $this->getName(); 
    }

    // The numeric id for this item type.
    public function id()
    { 
        return $this->m_type; 
    }

    // The item's internally used name.
    public function identity()
    { 
        return $this->m_identity; 
    }

    // Gets the list of effects that the item does.
    public function effects() {
        // Pull the effects array via the external function.
        return item_effects($this->id());
    }

    // Checks whether the item causes a certain effect.
    public function hasEffect($effect_identity)
    {
        $effects = $this->effects();
        return ($effect_identity && is_array($effects) && array_key_exists(strtolower($effect_identity), $effects)); 
    }

    // Note that this just determines the -maximum- turn change.
    public function setTurnChange($p_turns)
    { $this->m_turnChange = (float)$p_turns; }

    // Unmodified ideal maximum turn change
    public function getMaxTurnChange()
    { return $this->m_maxTurnChange; }

    // Returns the turn change that the object will cause.
    // TODO: this should be modified by existing effects, so should accept a target object
    public function getTurnChange()
    { 
        return $this->m_turnChange; 
    }

    // Set whether the object will be able to bypass stealth mode
    public function setIgnoresStealth($p_ignore)
    { 
        $this->m_ignoresStealth = (boolean)$p_ignore; 
    }

    public function ignoresStealth()
    { return $this->m_ignoresStealth; }

    public function setTargetDamage($p_damage)
    { $this->m_targetDamage = (int)$p_damage; }

    public function getTargetDamage()
    { return (int)$this->m_targetDamage; }

    public function getMaxDamage()
    { return $this->m_maxDamage; }

    public function getRandomDamage()
    { return rand(0, $this->m_maxDamage); }

    public function getTurnCost()
    { return $this->m_turnCost; }

    public function setCovert($p_covert)
    { $this->m_covert = (boolean)$p_covert; }

    public function isCovert()
    { return $this->m_covert; }

    public function isSelfUsable()
    { return $this->m_selfUse;	}

    // Check whether the item is usable upon others.
    public function isOtherUsable()
    { return $this->m_otherUsable; }

    public function getType()
    { return $this->m_type; }
}
