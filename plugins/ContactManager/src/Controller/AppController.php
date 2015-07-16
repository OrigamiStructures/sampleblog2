<?php

namespace ContactManager\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
	public function __construct(\Cake\Network\Request $request = null, \Cake\Network\Response $response = null, $name = null, $eventManager = null) {
		parent::__construct($request, $response, $name, $eventManager);
		debug('thing');
	}
}
