<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('home', '')
	->defaults(array(
		'controller' => 'main',
		'action'     => 'index',
	));

Route::set('log-in', 'login')
	->defaults(array(
		'controller' => 'auth',
		'action'     => 'login',
	));

Route::set('logoutn', 'logout')
	->defaults(array(
		'controller' => 'auth',
		'action'     => 'logout',
	));

Route::set('repos', 'repos')
	->defaults(array(
		'controller' => 'repos',
		'action'     => 'list',
	));

Route::set('repos-play', 'repos/<repo>')
	->defaults(array(
		'controller' => 'repos',
		'action'     => 'play',
	));