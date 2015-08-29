<?php

/*
 * Router Class 
 *  
 * responsible for fechting the write controller-classes  
 * and methods according to the "index.php?url=[controller class]/[method]/[parameters]"  URL. 
 * protected $variable = Only accessible inside this class and childs! 
 */

namespace thom855j\http ;

class Router
{

    // object instance
    private static
            $_instance = null ;
    // variable containing default controller 
    protected
            $controller    = 'DefaultController' ;
    // variable containing default params
    protected
            $params        = array() ;
    // variable containing default action
    protected
            $action        = 'index' ;

    public
            function setAction( $action )
    {
        $this->action = $action ;
    }

    public
            function setController( $controller )
    {
        $this->controller = $controller ;
    }

    /*
     * fetching the controller class and its methods  
     * happens in the contructer doing every run 
     */

    public
            function __construct( $name = 'url' , $path = null )
    {
        // use this class method to parse the $_GET[url] 
        $url = self::parseUrl( $name ) ;
        if ( isset( $url ) )
        {
            $controllerName = ucfirst( $url[ 0 ] ) ;
        }
        else
        {
            $url            = array( $this->action ) ;
            $controllerName = ucfirst( $url[ 0 ] ) ;
        }
        // checks if a controller by the name from the URL exists 
        if ( ctype_lower( str_replace( '_' , '' , $url[ 0 ] ) ) && file_exists( $path . $controllerName . 'Controller.php' ) )
        {

            // if exists, use this as the controller instead of default 
            $this->controller = $controllerName . 'Controller' ;

            /*
             * destroys the first URL parameter, 
             *  to leave it like index.php?url=[0]/[1]/[parameters in array seperated by "/"] 
             */
            unset( $url[ 0 ] ) ;
        }

        #var_dump($path);
        // use the default controller if file NOT exists, or else use the controller name from the URL 
        require_once $path . $this->controller . '.php' ;

        // initiate the controller class as an new object 
        $this->controller = new $this->controller ;

        // checks for if a second url parameter like index.php?url=[0]/[1] is set 
        if ( isset( $url[ 1 ] ) )
        {

            // then check if an according method exists in the controller from $url[0] 
            if ( method_exists( $this->controller , $url[ 1 ] ) )
            {

                // if exists, use this as the method instead of default 
                $this->method = $url[ 1 ] ;

                /*
                 * destroys the second URL, to leave only the parameters 
                 *  left like like index.php?url=[parameters in array seperated by "/"] 
                 */
                unset( $url[ 1 ] ) ;
            }
        }

        /*
         * checks if the $_GET['url'] has any parameters left in the   
         * index.php?url=[parameters in array seperated by "/"]. 
         * If it has, get all the values. Else, just parse is as an empty array. 
         */
        $this->params = $url ? array_values( $url ) : array() ;

        /*
         * 1. call/execute the controller and it's method.  
         * 2. If the Router has NOT changed them, use the default controller and method. 
         * 2. if there are any params, return these too. Else just return an empty array. 
         */
        call_user_func_array( array( $this->controller , $this->action ) ,
                              $this->params ) ;
    }

    /*
     * The parUrl method is responsible for getting the $_GET['url']-parameters  
     * as an array, for sanitizing it for anything we don't want and removing "/"-slashes  
     * after the URL-parameter 
     */

    public static
            function parseUrl( $name , $array = true )
    {

        if ( isset( $_GET[ $name ] ) && $array === true )
        {
            return explode( '/' ,
                            filter_var( rtrim( $_GET[ $name ] , '/' ) ,
                                               FILTER_SANITIZE_URL ) ) ;
        }
    }

    public static
            function getUrl()
    {
        return self::getProtocol() . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ;
    }

    public static
            function getHost()
    {
        return self::getProtocol() . $_SERVER[ 'HTTP_HOST' ] ;
    }

    public static
            function getProjectUrl($path = '')
    {
        return self::getProtocol() . $_SERVER[ 'HTTP_HOST' ] . str_replace( $path ,
                                                                            '' ,
                                                                            dirname( $_SERVER[ 'SCRIPT_NAME' ] ) ) . DIRECTORY_SEPARATOR ;
    }

    public static
            function getProtocol()
    {
        /**
         * Configuration for: URL
         * Here we auto-detect your applications URL and the potential sub-folder. Works perfectly on most servers and in local
         * development environments (like WAMP, MAMP, etc.). Don't touch this unless you know what you do.
         *
         * URL_PUBLIC_FOLDER:
         * The folder that is visible to public, users will only have access to that folder so nobody can have a look into
         * "/application" or other folder inside your application or call any other .php file than index.php inside "/public".
         *
         * URL_PROTOCOL:
         * The protocol. Don't change unless you know exactly what you do.
         *
         * URL_DOMAIN:
         * The domain. Don't change unless you know exactly what you do.
         *
         * URL_SUB_FOLDER:
         * The sub-folder. Leave it like it is, even if you don't use a sub-folder (then this will be just "/").
         *
         * URL:
         * The final, auto-detected URL (build via the segments above). If you don't want to use auto-detection,
         * then replace this line with full URL (and sub-folder) and a trailing slash.
         */
        $isSecure = false ;
        if ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] == 'on' )
        {
            $isSecure = true ;
        }
        elseif ( !empty( $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) && $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ==
                'https' || !empty( $_SERVER[ 'HTTP_X_FORWARDED_SSL' ] ) && $_SERVER[ 'HTTP_X_FORWARDED_SSL' ] ==
                'on' )
        {
            $isSecure = true ;
        }
        return $isSecure ? 'https://' : 'http://' ;
    }

}
