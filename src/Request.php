<?php

namespace PHP\Http;

class Request
{
    public static function exists($type = 'post') {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            case 'files':
                return (!empty($_FILES)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    public static function get($item, $info = null)
    {
        if (isset($_POST[$item])) {
            return trim(filter_var($_POST[$item], FILTER_SANITIZE_STRING));
        } elseif (isset($_GET[$item])) {
            return trim(filter_var($_GET[$item], FILTER_SANITIZE_STRING));
        } elseif (isset($_FILES[$item][$info])) {
            return $_FILES[$item][$info];
        }
        
        return null;
    }
}
