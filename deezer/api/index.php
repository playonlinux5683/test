<?php
	error_reporting(E_ALL);

	ini_set('display_errors', 1);
	ini_set('xdebug.var_display_max_depth', 5);
	ini_set('xdebug.var_display_max_children', 256);
	ini_set('xdebug.var_display_max_data', 1024);

	require_once 'AutoLoader.class.php';
	AutoLoader::register();

	require_once('Router.class.php');
	$router = new Router();

	$router->add('/user/.+', array('User', 'get'));
	$router->add('/user/.+/favorites', array('UserFavorites', 'get'));
	$router->add('/user/.+/favorites/.+', array('UserFavorites', 'post'));
	$router->add('/user/.+/favorites/.+', array('UserFavorites', 'delete'));
	$router->add('/song/.+', array('Song', 'get'));

	echo '<pre>';
	print_r($router);
	$router->perform();
?>
