<?php
namespace App\Controller;

use Cake\Controller\Controller;
use \CrudViews\Lib\CrudConfig;
use \CrudViews\Controller\AppController as BaseController;

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
//use Bakeless\Controller\AppController as BaseController;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends BaseController {
		
	public function simpleSearch($action) {
		$this->request->action = $action;
		$alias = $this->modelClass;
		$search = ["$alias.{$this->$alias->displayField()} LIKE" => "%{$this->request->data['search']}%"];
		$this->$action($search);
	}
	
	/**
	 * Setup navigation system for all pages
	 * 
	 */
	protected function loadMainNavigation() {
		$Menus = \Cake\ORM\TableRegistry::get('Menus');
		$this->set('navigators', $Menus->find()->all());
		
		//setup Crud Config
		$this->navigatorsIndex();
	}

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();
		$this->loadComponent('Flash');
	}

	public function beforeFilter(\Cake\Event\Event $event) {
		parent::beforeFilter($event);
		$this->loadMainNavigation();
	}
	
	//Customized Crud Setups
	
	public function articlesIndex() {
		$this->configIndex('Articles');
		
		//modify configurations
		$this->configCrudDataOverrides('Articles', 'override', ['text' => 'leadPlus', 'summary' => 'leadPlus']);
	}
	
	/**
	 * Setup custom Crud Config for navigators
	 * 
	 */
	public function navigatorsIndex() {
		$this->configIndex('Navigators');
		
		//modify configurations
		$this->configCrudDataOverrides('Navigators', 'whitelist', ['name']);
		$this->configCrudDataOverrides('Navigators', 'overrideAction', ['index' => 'liLink']);
		
		//set viewVars
		$this->set('filter_property', 'parent_id');
		$this->set('filter_match', 'id');
	}
	
	/**
	 * Setup custom Crud Config for menus
	 * 
	 */
	public function menusIndex() {
		$this->configIndex('Menus');
		
		//modify configurations
		$this->configCrudDataOverrides('Menus', 'blacklist', ['lft', 'rght']);
		$this->configCrudDataOverrides('Menus', 'override', ['parent_id' => 'input']);
		$this->configCrudDataOverrides('Menus', 'attributes', ['parent_id' => [ 'empty' => 'Choose one', 'label' => FALSE ]]);
	}
	
}
