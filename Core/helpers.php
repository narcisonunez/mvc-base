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

	if (strpos($name, ".")) {
		$name = explode(".", $name);
	}

	if (is_string($name) && key_exists($name, $config)) {
		return $config[$name];
	}

	if (is_array($name)) {
		$value = $config;
		foreach ($name as $key) {
			if (key_exists($key, $value)) {
				$value = $value[$key];
			}
		}
		return $value;
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
	global $twig;

	$name = str_replace(".", "/", $name);
	$name .= ($twig != null) ? ".html" : ".php";
	$view = VIEWS_PATH . "/$name";

	if (!$twig && !file_exists($view)) {
		throw new \Exception("View doesn't exists.", 500);
	}

	if ($twig) {
		$template = $twig->load($name);
		echo $template->render($data);
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
	echo "<pre>";
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
	echo "</pre>";
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

/**
 * echo out the _csrf_token hidden input field used in form 
 * to validate the request with the one in sesion
 *
 */
function form_csrf()
{
	$token = bin2hex(random_bytes(25));
	$_SESSION['_session_id'] = $token;
	echo '<input type="hidden" name="_csrf_token" value="' . $token . '">';
}

/**
 * Load 404 error view
 * 
 */
function view_404()
{
	http_response_code(404);
	return view("errors.404");
}
