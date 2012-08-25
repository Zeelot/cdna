<?php defined('SYSPATH') or die('No direct script access.');

class GitHub {

	public static function factory(Model_User $user)
	{
		return new GitHub($user);
	}

	// The user model
	protected $_user;

	// Config for GitHub OAuth
	protected $_config;

	public function __construct(Model_User $user, array $config)
	{
		$this->_user = $user;
		$this->_config = $config;
	}

	public function request($method, $uri, array $query = NULL, array $post = NULL, array $body = NULL)
	{
		// Create the full URL to  the GitHub API
		$url = (strpos($uri, '://') !== FALSE)
			? $uri
			: $this->_config['api_url'].trim($uri. '/');

		// Replace :user in URL
		$url = str_replace(':user', $this->_user->github_login, $url);

		$query = $query ?: array();
		$post  = $post  ?: array();
		$body  = $body  ?: array();

		// Use the access_token from the user model
		$query['access_token'] = $this->_user->access_token;

		return Request::factory($this->_config['api_url'].trim($uri, '/'))
			->query($query)
			->post($post)
			->method($method);
	}
}