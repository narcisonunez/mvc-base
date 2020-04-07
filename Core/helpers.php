<?php

/**
 * Get a configuration value
 * 
 * @param string $name
 * @param mixed $default
 */
function config($name, $default = null)
{
	$config = require APP_PATH . "/config.php";
	if (key_exists($name, $config)) {
		return $config[$name];
	}
	return $default;
}

/**
 * Load a view file with the data
 * 
 * @param string $name
 * @param array $data
 */
function view($name, $data = [])
{
	$name = str_replace(".", "/", $name);
	$view = VIEWS_PATH . "/$name.php";

	if (!file_exists($view)) {
		echo 'View doesn\'t exists.';
		return;
	}

	extract($data);
	unset($data);
	return require $view;
}

/**
 * Get a value from the variables in .env file
 * 
 * @param string $key
 * @param string $default
 */
function env_value($key, $default = '')
{
	global $globalEnvironmentValues;

	if (key_exists($key, $globalEnvironmentValues)) {
		return $globalEnvironmentValues[$key];
	}

	return $default;
}

/**
 * Redirect to an specific path
 * 
 * @param string $path
 */
function redirect($path)
{
	if (!headers_sent()) {
		return header('Location: ' . $path);
	}
}

/**
 * Debug and Die
 * 
 * @param array $data
 */
function dd(...$data)
{
	if (is_array($data[0])) {
		$data = $data[0];
	}

	foreach ($data as $key => $info) {
		if (is_string($key)) {
			echo "<h3>$key</h3>\n";
		}
		var_dump($info);
		echo "\n\n";
	}

	die();
}

/**
 * echo out the _method hidden input field used in form 
 * to fake request method different than get or post
 * 
 * @param string $method
 */
function form_method($method)
{
	echo '<input type="hidden" name="_method" value="' . $method . '">';
}
