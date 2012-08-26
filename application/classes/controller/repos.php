<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Repos extends Abstract_Controller_Page {

	public function before()
	{
		parent::before();

		// Everything in this controller required logged in users
		if ( ! $this->_user->loaded())
			$this->request->redirect(Route::url('log-in'));
	}

	public function action_list() {}

	public function action_create()
	{
		if ($this->request->method() !== HTTP_Request::POST)
			$this->request->redirect(Route::url('repos'));

		$repo_url = $this->request->post('repo');

		$request = $this->_github
			->request(HTTP_Request::GET, $repo_url);

		$response = $request->execute();

		if ($response->status() !== 200)
			$this->request->redirect(Route::url('repos'));

		$repo_data = json_decode($response->body(), TRUE);
		$repo = ORM::factory('repo')
			->set('user_id'        , $this->_user->id)
			->set('github_id'      , Arr::get($repo_data, 'id'))
			->set('name'           , Arr::get($repo_data, 'name'))
			->set('github_url'     , Arr::get($repo_data, 'url'))
			->set('github_html_url', Arr::get($repo_data, 'html_url'))
			->set('git_url'        , Arr::get($repo_data, 'git_url'))
			->set('description'    , Arr::get($repo_data, 'description'))
			->set('language'       , Arr::get($repo_data, 'language'))
			->set('size'           , Arr::get($repo_data, 'size'))
			->save();

		$this->request->redirect(Route::url('repos-play', array(
			'repo' => $repo->id,
		)));
	}

	public function action_play()
	{
		$this->_view
			->bind('repo', $repo);

		$repo = ORM::factory('repo')
			->where('id', '=', $this->request->param('repo'))
			->find();

		if ( ! $repo->loaded())
			$this->request->redirect(Route::url('repos'));

		echo Debug::vars($repo);die;
	}
}