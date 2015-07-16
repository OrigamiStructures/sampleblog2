<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\CrudData;

/**
 * Navigators Controller
 *
 * @property \App\Model\Table\NavigatorsTable $Navigators
 */
class NavigatorsController extends AppController {

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index($search = FALSE) {
		$navigators = $this->Navigators->find();
		if ($search) {
			$navigators->where($search);
		}
		$navigators->contain(['ParentNavigators', 'SubNavigators'])
			->order(['Navigators.lft' => 'ASC']);
		
		$this->set('navigators', $this->paginate($navigators));
		$this->set('parents', $this->Navigators->find('list'));
		$this->set('_serialize', ['navigators']);
		$this->set('filter_property', 'parent_id');
		$this->set('filter_match', 'id');

		$this->configCrudDataOverrides('Navigators', 'whitelist', ['name'], TRUE);
		$this->crudData->whitelist(['name']);
		$this->crudData->overrideAction(['index' => 'liLink']);

		$this->RecordActions->add('default.index', [], TRUE);
		$this->ModelActions->add('default.index', [], TRUE);
		$this->AssociationActions->add('default.index', [], TRUE);
	}
}
