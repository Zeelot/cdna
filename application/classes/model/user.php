<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends ORM {

	public function create_from_github($access_token)
	{
		$this->access_token = $access_token;
		$config = Kohana::$config->load('oauth')->github;
		$github = new GitHub($this, $config);

		$request = $github->request(HTTP_Request::GET, '/user');

		$response = $request->execute();
		$body = json_decode($response->body(), TRUE);

		$data = array(
			'github_id'    => $body['id'],
			'github_login' => $body['login'],
			'name'         => $body['name'],
			'email'        => $body['email'],
			'access_token' => $access_token,
			'url'          => $body['url'],
		);

		$this
			->or_where('github_id', '=', $data['github_id'])
			->or_where('email', '=', $data['email'])
			->or_where('url', '=', $data['url'])
			->find();

		// Regardless of whether we found we, we should update the data
		$this
			->values($data, array_keys($data))
			->save();

		return $this;
	}
}