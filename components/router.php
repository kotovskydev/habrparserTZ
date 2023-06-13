<?php



/**
 * 
 */
class Router{
	
	private $routes;

	public function __construct(){
		
		$routesPath = ROOT.'/config/routes.php';
		$this->routes = include($routesPath);
	}

	private function getURI(){
		//получаем строку запроса
		if (!empty($_SERVER['REQUEST_URI'])) {
			return trim($_SERVER['REQUEST_URI'], '/');
		}
	}

	public function run(){
		
		$uri = $this->getURI();
		foreach ($this->routes as $uriPattern => $path){

			//Сравниваем $urlPattern и $uri
			if (preg_match("~$uriPattern~", $uri)) {


				$internalRoute = preg_replace("~$uriPattern~", $path, $uri);

				//Определяем какой контроллер и action обрабатывает запрос
				$segments = explode('/', $internalRoute);
 				
				$controllerName = array_shift($segments).'Controller';
				$controllerName = ucfirst($controllerName);

				$actionName = 'action'.ucfirst(array_shift($segments));

				$parameters = $segments;


				$controllerFile = ROOT.'/controllers/'.$controllerName.'.php';
				if (file_exists($controllerFile)) {
					include_once($controllerFile);
				}


				$controllerObject = new $controllerName;
				

				$result = call_user_func_array(array($controllerObject, $actionName), $parameters);
				if ($result != null) {
					break;
				}
			}

		}
	}
}

