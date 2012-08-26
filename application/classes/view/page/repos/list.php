<?php defined('SYSPATH') or die('No direct script access.');

class View_Page_Repos_List extends View_Page_Repos {

	public function repo_list()
	{
		$request = $this->_github
			->request(HTTP_Request::GET, '/users/:user/repos', array('type' => 'owner'));

		$response = $request->execute();

		if ($response->status() !== 200)
			return NULL;

		$repos = json_decode($response->body(), TRUE);
		foreach ($repos as $k => $repo)
		{
			$model = ORM::factory('repo')
				->where('github_id', '=', $repo['id'])
				->find();

			if ($model->loaded())
			{
				$repos[$k]['has_dna'] = TRUE;
				$repos[$k]['model'] = $model;
				$repos[$k]['play_url'] = $this->_play_url($model);
			}
			else
			{
				$repos[$k]['has_dna'] = FALSE;
				$repos[$k]['play_form'] = $this->_play_form($repo);
			}
		}

		return $repos;
	}

	protected function _play_url(Model_Repo $model)
	{
		return Route::url('repos-play', array(
			'repo' => $model->id,
		));
	}

	protected function _play_form($repo)
	{
		$yform = YForm::factory($repo['name']);

		return $yform->open(Route::url('repos-create')).
			$yform->submit('repo')
				->set_attribute('value', $repo['url'])
				->set_label('Begin!').
			$yform->close();
	}
}