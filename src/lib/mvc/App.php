<?php
namespace aprendamos\lib\mvc;

class App
{
    private $controller;
    private $action;
    private $parameter1;
    private $parameter2;
    private $parameter3;

    public function __construct()
    {
        $this->parseUrl();

        $controller = APP_VENDOR."\\controller\\{$this->controller}";
        $controller = new $controller();

        if (!method_exists($controller, $this->action)) {
            $controller->index();
            return;
        }

        if (isset($this->parameter3)) {
            $controller->{$this->action}($this->parameter1, $this->parameter2, $this->parameter3);
        } else if (isset($this->parameter2)) {
            $controller->{$this->action}($this->parameter1, $this->parameter2);
        } else if (isset($this->parameter1)) {
            $controller->{$this->action}($this->parameter1);
        } else {
            $controller->{$this->action}();
        }
    }

    /**
     * 
     * Parses the current URL to get it's components.
     * 
     * This function splits the current URL to get it's
     * Controller, Action and (optional) parameter.
     * 
     * @throws Exception If URL is not set.
     * 
     */
    public function parseUrl()
    {
        if (!isset($_GET['url'])) {
            throw new \Exception('Page not found');
        }

        $url = rtrim($_GET['url']);
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        $this->controller = isset($url[0]) ? ucfirst($url[0]) . 'Controller' : null;
        $this->action = isset($url[1]) ? $url[1] : null;
        $this->parameter1 = isset($url[2]) ? $url[2] : null;
        $this->parameter2 = isset($url[3]) ? $url[3] : null;
        $this->parameter3 = isset($url[4]) ? $url[4] : null;
    }
}