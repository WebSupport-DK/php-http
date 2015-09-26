<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ErrorController
{

    function __construct()
    {
        
    }
    public
            function index($code = 404)
    {
           echo $code;
    }

    public
            function code($code = 404)
    {
 echo $code;
    }

}
