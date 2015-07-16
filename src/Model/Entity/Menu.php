<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Menu Entity.
 */
class Menu extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'name' => true,
        'controller' => true,
        'action' => true,
        'type' => true,
        'user_id' => true,
        'sub_menus' => true,
        'parent_menu' => true,
    ];
	
	protected function _getLevel(){
		$menus = TableRegistry::get('Menus');
		return $menus->getLevel($this);
	}
	
	protected function _getTableTag() {
		return "<table class=\"Menu Tree Level-$this->level\">";
	}
}
