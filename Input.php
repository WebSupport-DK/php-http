<?php

namespace thom855j\PHPHttp;

class Input
{

    public static
            function escape($string)
    {
        return trim(filter_var($string, FILTER_SANITIZE_STRING));
    }

    public static
            function strip($string, $tags = '')
    {
        return strip_tags($string, $tags);
    }

    public static
            function serialize($input)
    {
        return serialize($input);
    }

    public static
            function unserialize($input)
    {
        return userialize($input);
    }

    public static
            function jsonEncode($input)
    {
        return json_encode($input);
    }

    public static
            function jsonDecode($json)
    {
        return json_decode($json);
    }

    // This function expects the input to be UTF-8 encoded.
    public static
            function toSlug($string, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace))
        {
            $string = str_replace((array) $replace, ' ', $string);
        }
        $slug = preg_replace('/[^A-Za-z0-9-]+/', $delimiter, $string);
        return $slug;
    }

    public static
            function exists($type = 'post', $data = null)
    {
        switch ($type)
        {
            case 'post':
                if (is_null($data))
                {
                    return (!empty($_POST)) ? true : false;
                }
                return $data;
                break;

            case 'get':
                if (is_null($data))
                {
                    return (!empty($_GET)) ? true : false;
                }
                return $data;
                break;

            case 'files':
                if (is_null($data))
                {
                    return (!empty($_FILES)) ? true : false;
                }
                return $data;
                break;

            default:
                return false;
                break;
        }
    }

    public static
            function get($item, $info = null)
    {
        if (isset($_POST[$item]))
        {
            return trim(filter_var($_POST[$item], FILTER_SANITIZE_STRING));
        }
        elseif (isset($_GET[$item]))
        {
            return trim(filter_var($_GET[$item], FILTER_SANITIZE_STRING));
        }
        elseif (isset($_FILES[$item][$info]))
        {
            return $_FILES[$item][$info];
        }
        return null;
    }

}
