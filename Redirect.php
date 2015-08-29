<?php
namespace thom855j\http;

class Redirect
{

    public static
            function to($location = null)
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
            function back()
    {
        header('Location: javascript://history.go(-1)');
        exit;
    }

}
