<?php
class Router
{
	private $routes = [];

	public function add($method, $path, $controller, $action)
	{
		$this->routes[] = [
			'method' => $method,
			'path' => $path,
			'controller' => $controller,
			'action' => $action
		];
	}

	public function dispatch($url, $method)
	{
		$url = parse_url($url, PHP_URL_PATH);

		foreach ($this->routes as $route) {
			if ($route['method'] === $method && $route['path'] === $url) {
				require_once __DIR__ . '/../controllers/' . $route['controller'] . '.php';
				$controllerInstance = new $route['controller']();
				$action = $route['action'];
				$controllerInstance->$action();
				return;
			}
		}

		http_response_code(404);
		require_once __DIR__ . '/../views/404.php';
	}
}
