<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Abstract_Controller_Page {

	public function action_login()
	{
		$config = Kohana::$config->load('oauth')->github;

		if ($this->request->query('code'))
		{
			// User sent back with a code
			$url = $config['oauth_token'];
			$query = array(
				'client_id'     => $config['client_id'],
				'client_secret' => $config['secret'],
				'code'          => $this->request->query('code'),
			);

			$request = Request::factory($url)
				->headers('accept', 'application/json')
				->query($query);

			$response = $request->execute();
			$data = json_decode($response->body(), TRUE);

			// Create a user from github
			$user = ORM::factory('user')
				->create_from_github($data['access_token']);

			// Log the user in
			Cookie::set('auth', $user->id);

			// Send the user back home
			$this->request->redirect(Route::url('home'));
		}
		else
		{
			$url = $config['oauth_dialog'];
			$query = array(
				'client_id' => $config['client_id'],
				'redirect_uri' => URL::site($this->request->uri()),
			);

			$this->request->redirect($url.'?'.http_build_query($query));
		}
	}

	public function action_logout()
	{

	}
}