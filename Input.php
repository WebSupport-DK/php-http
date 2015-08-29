<?php
namespace thom855j\http;

class Input
{

    public static
            function escape( $string )
    {
        return trim( filter_var( $string , FILTER_SANITIZE_STRING ) ) ;
    }

    public static
            function strip( $string , $tags = '' )
    {
        return strip_tags( $string , $tags ) ;
    }

    public static
            function post( $data , $url )
    {

        $ch          = curl_init() ;
        curl_setopt( $ch , CURLOPT_VERBOSE , 0 ) ;
        curl_setopt( $ch , CURLOPT_FORBID_REUSE , true ) ;
        curl_setopt( $ch , CURLOPT_URL , $url ) ;
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 ) ;
        curl_setopt( $ch , CURLOPT_POST , 1 ) ;
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $data ) ;
        curl_setopt( $ch , CURLOPT_TIMEOUT , 5 ) ;
        curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , false ) ;
        $http_result = curl_exec( $ch ) ;
        $error       = curl_error( $ch ) ;
        $http_code   = curl_getinfo( $ch , CURLINFO_HTTP_CODE ) ;
        curl_close( $ch ) ;
        if ( $http_code != 200 )
        {
            return array(
                'error' => $error
                    ) ;
        }
        else
        {
            return json_decode( $http_result ) ;
        }
    }

    public static
            function serialize( $input )
    {
        return serialize( $input ) ;
    }

    public static
            function unserialize( $input )
    {
        return userialize( $input ) ;
    }

    public static
            function jsonEncode( $input )
    {
        return json_encode( $input ) ;
    }

    public static
            function jsonDecode( $json )
    {
        return json_decode( $json ) ;
    }

    // This function expects the input to be UTF-8 encoded.
    public static
            function toSlug( $string , $replace = array() , $delimiter = '-' )
    {
//        if ( !empty( $replace ) )
//        {
//            $str = str_replace( ( array ) $replace , ' ' , $str ) ;
//        }
//
//        $clean = iconv( 'UTF-8' , 'ASCII//TRANSLIT' , $str ) ;
//        $clean = preg_replace( "/[^a-zA-Z0-9\/_|+ -]/" , '' , $clean ) ;
//        $clean = strtolower( trim( $clean , '-' ) ) ;
//        $clean = preg_replace( "/[\/_|+ -]+/" , $delimiter , $clean ) ;
//
//        return $clean ;
        $str1   = array( "Ã†" , "Ã˜" , "Ã…" , "Ã¦" , "Ã¸" , "Ã¥" ) ;
        $str2   = array( "AE" , "OE" , "AA" , "ae" , "oe" , "aa" ) ;
        $string = str_replace( $str1 , $str2 , $string ) ;
        $slug   = preg_replace( '/[^A-Za-z0-9-]+/' , '-' , $string ) ;
        return $slug ;
    }

    public static
            function exists( $type = 'post' , $data = null )
    {
        switch ( $type )
        {
            case 'post':
                if ( is_null( $data ) )
                {
                    return (!empty( $_POST )) ? true : false ;
                }
                return $data ;
                break ;

            case 'get':
                if ( is_null( $data ) )
                {
                    return (!empty( $_GET )) ? true : false ;
                }
                return $data ;
                break ;

            case 'files':
                if ( is_null( $data ) )
                {
                    return (!empty( $_FILES )) ? true : false ;
                }
                return $data ;
                break ;

            default:
                return false ;
                break ;
        }
    }

    public static
            function get( $item , $info = null )
    {
        if ( isset( $_POST[ $item ] ) )
        {
            return trim( filter_var( $_POST[ $item ] , FILTER_SANITIZE_STRING ) ) ;
        }
        elseif ( isset( $_GET[ $item ] ) )
        {
            return trim( filter_var( $_GET[ $item ] , FILTER_SANITIZE_STRING ) ) ;
        }
        elseif ( isset( $_FILES[ $item ][ $info ] ) )
        {
            return $_FILES[ $item ][ $info ] ;
        }
        return null ;
    }

}
