<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\CrudData;
use Cake\ORM\TableRegistry;

/**
 * Menus Controller
 *
 * @property \App\Model\Table\MenusTable $Menus
 */
class MenusController extends AppController {

//	public $helpers = ['CrudViews.Crud'];

//	protected $_associations = NULL;

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index($search = FALSE) {
		$menus = $this->Menus->find();
		if ($search) {
			$menus->where($search);
		}
		$menus->contain(['ParentMenus', 'SubMenus'])
			->order(['Menus.lft' => 'ASC']);
		
		$this->set('menus', $this->paginate($menus));
		$this->set('parents', $this->Menus->find('list'));
		$this->set('_serialize', ['menus']);

		// verify and document config options. 
		// Look into cakes array merge tools (used for class setup) and co-opt if possible
//		$this->crudData->blacklist(['lft', 'rght']);
//		$this->crudData->override(['parent_id' => 'input']);
//		$this->crudData->attributes(['parent_id' => [ 'empty' => 'Choose one', 'label' => FALSE ]]);
		
		$this->menusIndex();
		$this->_RecordActions->add('default.index', [['Move Up' => 'move_up'], ['Move Down' => 'move_down']]);
		$this->_ModelActions->add('default.index', ['search']);
		
		$this->render('CrudViews.CRUD/index_form');
	}

	/**
	 * View method
	 *
	 * @param string|null $id Menu id.
	 * @return void
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function view($id = null) {
		$menu = $this->Menus->get($id, [
			'contain' => ['ParentMenus', 'SubMenus']
		]);
		$this->set('menu', $menu);
		$this->set('_serialize', ['menu']);
		
		$this->configView('Menus');
//		$crud_data = new CrudData($this->Menus);
//		$helper_config = [
//			'crudData' => [$crud_data]
//		];
//		$this->helpers['Crud'] = $helper_config;
		$this->set(compact('crud_data'));

		$this->render('CrudViews.CRUD/edit');
	}

	/**
	 * Add method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$menu = $this->Menus->newEntity();
		if ($this->request->is('post')) {
			$menu = $this->Menus->patchEntity($menu, $this->request->data);
			if ($this->Menus->save($menu)) {
				$this->Flash->success('The menu has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The menu could not be saved. Please, try again.');
			}
		}
		$this->configAdd('Menus');
		$this->configCrudDataOverrides('Menus', 'blacklist', ['lft', 'rght']);

		$childMenus = []; //$this->Menus->ChildMenus->find('list', ['limit' => 200]);
		$this->set('parents', $this->Menus->ParentMenus->find('list', ['limit' => 200]));
		$this->set(compact('menu', 'childMenus', 'parentMenus'));
		$this->set('_serialize', ['menu']);
		
		$this->render('CrudViews.CRUD/edit');
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Menu id.
	 * @return void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null) {
		$menu = $this->Menus->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$menu = $this->Menus->patchEntity($menu, $this->request->data);
			if ($this->Menus->save($menu)) {
				$this->Flash->success('The menu has been saved.');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('The menu could not be saved. Please, try again.');
			}
		}
		$this->configEdit('Menus');
		$this->configCrudDataOverrides('Menus', 'blacklist', ['lft', 'rght']);
		$this->configCrudDataOverrides('Menus', 'attributes', ['parent_id' => [ 'empty' => 'Choose one', 'label' => FALSE ]]);
		$subMenus = $this->Menus->SubMenus->find('list', ['limit' => 200]);
		$parents = $this->Menus->ParentMenus->find('list', ['limit' => 200]);
		$this->set(compact('menu', 'subMenus', 'parents'));
		$this->set('_serialize', ['menu']);
		
		$this->render('CrudViews.CRUD/edit');
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Menu id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$menu = $this->Menus->get($id);
		if ($this->Menus->delete($menu)) {
			$this->Flash->success('The menu has been deleted.');
		} else {
			$this->Flash->error('The menu could not be deleted. Please, try again.');
		}
		return $this->redirect(['action' => 'index']);
	}

	public function move_up($id = null) {
//		$this->request->allowMethod(['post', 'put']);
		$menu = $this->Menus->get($id);
		if ($this->Menus->moveUp($menu)) {
			$this->Flash->success('The menu has been moved Up.');
		} else {
			$this->Flash->error('The menu could not be moved up. Please, try again.');
		}
		return $this->redirect($this->referer(['action' => 'index']));
	}

	public function move_down($id = null) {
//		$this->request->allowMethod(['post', 'put']);
		$menu = $this->Menus->get($id);
		if ($this->Menus->moveDown($menu)) {
			$this->Flash->success('The menu has been moved down.');
		} else {
			$this->Flash->error('The menu could not be moved down. Please, try again.');
		}
		return $this->redirect($this->referer(['action' => 'index']));
	}
	
	public function recover(){
		$this->Menus->recover();
		$this->redirect(['action' => 'index']);
	}

}
