<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Repos extends Abstract_Controller_Page {

	public function before()
	{
		parent::before();

		// Everything in this controller required logged in users
		if ( ! $this->_user->loaded())
			$this->request->redirect(Route::url('log-in'));
	}

	public function action_list()
	{

	}
}