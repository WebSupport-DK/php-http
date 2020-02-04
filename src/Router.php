<?php
/**
 * Router Class
 *
 * Responsible for fechting the write controller-classes
 * and methods according to the "index.php?url=[controller class]/[method]/[parameters]"  URL.
 * protected $variable = Only accessible inside this class and childs!
 */

namespace PHP\Http;

class Router
{
    // class params
    protected $path;
    protected $queryString;
    protected $controller;
    protected $namespace;
    protected $action;
    protected $params = array();
    protected $uri;

    /*
     * Construct with default values
     */
    public function __construct()
    {
        $this->path = '';
        $this->controller = 'default';
        $this->action = 'index';
        $this->queryString = 'uri';
        $this->namespace = 'App\Controllers';
    }

    /**
     * Set path to controllers
     * @param type $path
     */
    public function setControllersPath($path)
    {
        $this->path = $path;
    }

    /**
     * Set default controller
     *
     * @param type $name
     */
    public function setDefaultController($name)
    {
        $this->controller = $name;
    }

    /**
     * Set default action
     *
     * @param type $name
     */
    public function setDefaultAction($name)
    {
        $this->action = $name;
    }

    /**
     * Set default query string from $GET
     *
     * @param type $name
     */
    public function setQueryString($name)
    {
        $this->queryString = $name;
    }
    
    /**
     * Set default namespace for psr-2 autoloading
     *
     * @param type $name
     */
    public function setNamespace($name)
    {
        $this->namespace = $name;
    }

    /**
     * Fetching the controller class and its methods
     * happens in the contructer doing every run.
     * Default params are:
     * array('controller' =>'default','action'=>'index','pathcontrollers' => '', 'rooturi'=> 'url')
     * $object can be something to pass to the controllers constructer
     */
    public function run($params = null)
    {
        // use this class method to parse the $GET[url]
        $this->uri = $this->parseUrl($this->queryString);

        if (!empty($this->uri)) {
            $this->controller = ucfirst($this->uri[0]);
        } else {
            $this->uri = array($this->controller, $this->action);
        }

        // checks if a controller by the name from the URL exists
        if (strreplace('', '', $this->uri[0]) &&
            fileexists($this->path . ucfirst($this->controller) . 'Controller.php')) {

            // if exists, use this as the controller instead of default
            $this->controller = ucfirst($this->controller) . 'Controller';

            /*
             * destroys the first URL parameter,
             *  to leave it like index.php?url=[0]/[1]/[parameters in array seperated by "/"]
             */
            unset($this->uri[0]);
        } else {
            return header("HTTP/1.0 404 Not Found");
        }

        // initiate the controller class as an new object
        $controller = "{$this->namespace}\\" . $this->controller;
    
        $this->controller = new $controller($params);

        // checks for if a second url parameter like index.php?url=[0]/[1] is set
        if (!empty($this->uri)) {

            // then check if an according method exists in the controller from $url[0]
            if (methodexists($this->controller, $this->uri[1])) {

                // if exists, use this as the method instead of default
                $this->action = $this->uri[1];

                /*
                 * destroys the second URL, to leave only the parameters
                 *  left like like index.php?url=[parameters in array seperated by "/"]
                 */
                unset($this->uri[1]);
            } else {
                return header("HTTP/1.0 404 Not Found");
            }
        }

        /**
         * checks if the $GET['url'] has any parameters left in the
         * index.php?url=[parameters in array seperated by "/"].
         * If it has, get all the values. Else, just parse is as an empty array.
         */
        $this->params = $this->uri ? arrayvalues($this->uri) : array();

        /**
         * 1. call/execute the controller and it's method.
         * 2. If the Router has NOT changed them, use the default controller and method.
         * 2. if there are any params, return these too. Else just return an empty array.
         */
        calluserfuncarray(array($this->controller, $this->action), $this->params);
    }

    /**
     * The parUrl method is responsible for getting the $GET['url']-parameters
     * as an array, for sanitizing it for anything we don't want and removing "/"-slashes
     * after the URL-parameter
     */
    private function parseUrl($name)
    {
        if (isset($GET[$name])) {
            return explode('/', filtervar(rtrim($GET[$name], '/'), FILTERSANITIZEURL));
        }

        return false;
    }
}
