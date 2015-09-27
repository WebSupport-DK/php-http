<?php

namespace thom855j\PHPHttp;

class Url
{

    public static
            function redirect($location = null)
    {
        if ($location)
        {
            if (is_numeric($location))
            {
                switch ($location)
                {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        exit();
                        break;
                }
            }
            if (!headers_sent())
            {
                header('Location: ' . $location);
                exit;
            }
            else
            {
                echo '<script type="text/javascript">';
                echo 'window.location.href="' . $location . '";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
                echo '</noscript>';
                exit;
            }
        }
    }

    public static
            function redirectBack()
    {
        header('Location: javascript://history.go(-1)');
        exit;
    }

    public static
            function getRoot($path = '')
    {
        return self::getProtocol() . $_SERVER['HTTP_HOST'] . str_replace($path,
                                                                         '',
                                                                         dirname($_SERVER['SCRIPT_NAME']));
    }

    public static
            function get()
    {
        return self::getProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public static
            function getError()
    {
        return http_response_code();
    }

    public static
            function getHost()
    {
        return self::getProtocol() . $_SERVER['HTTP_HOST'];
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
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        {
            $isSecure = true;
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] ==
                'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] ==
                'on')
        {
            $isSecure = true;
        }
        return $isSecure ? 'https://' : 'http://';
    }

}
