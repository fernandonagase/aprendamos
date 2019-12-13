<?php
namespace aprendamos\lib\mvc;

use aprendamos\lib\Path;

abstract class Controller
{
    private $controllerName;

    public function __construct()
    {
        $className = str_replace(APP_VENDOR.'\\controller\\', '', get_class($this));
        $className = str_replace('Controller', '', $className);
        $this->controllerName = strtolower($className);
    }

    /**
     * 
     * Simply returns the request method for the page.
     * 
     * @return string A string representation for the
     * request method.
     * 
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * 
     * Redirects user to a given action (optionally)
     * using parameters.
     * 
     * This function basically builds a redirect path
     * based on the parameters passed to it and sends
     * a Location header for that path.
     * 
     */
    public function redirectToAction(
        string $action,
        $parameter1 = null,
        $parameter2 = null,
        $parameter3 = null
    ) {
        if ($parameter3 !== null) {
            $redirectPath = Path::join(
                APP_VENDOR,
                $this->controllerName,
                $action,
                $parameter1,
                $parameter2,
                $parameter3
            );
        } else if ($parameter2 !== null) {
            $redirectPath = Path::join(
                APP_VENDOR,
                $this->controllerName,
                $action,
                $parameter1,
                $parameter2
            );
        } else if ($parameter1 !== null) {
            $redirectPath = Path::join(
                APP_VENDOR,
                $this->controllerName,
                $action,
                $parameter1
            );
        } else {
            $redirectPath = Path::join(APP_VENDOR, $this->controllerName, $action);
        }

        header("Location: /$redirectPath");
    }
}