<?php
namespace App\Core;

class Router {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Define o Controller
        if (!empty($url[0])) {
            $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
            $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // Inclui e instancia o controller
        require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
        $class = "App\\Controllers\\" . $this->controller;
        $this->controller = new $class();

        // 2. Define o Método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Define os Parâmetros
        $this->params = $url ? array_values($url) : [];

        // 4. Chama o método do controller com os parâmetros
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parseia a URL amigável vinda do .htaccess
     * Ex: /militares/editar/1 => ['militares', 'editar', '1']
     */
    protected function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
?>