<?php

// load class
require_once '../src/Input.php';

//use class
use thom855j\PHPHttp\Input;

// get input from posts,get or files. Item is the name form the form or url query string.
//  If it has more than one array, you can use $info to get that
Input::get($item = 'username', $info = null);

// check if input exists in form of posts, get or files.
Input::exists($type = 'post' , $data = null);

// in combination with Input::get(), you can return input to a input field
Input::exists($type = 'post' , Input::get($item = 'username', $info = null));

// strip a string form tags
Input::strip($string, $tags);

// escape a string for unwanted things
Input::escape($string);

// serialize a string
Input::serialize($input);

// unserialize as string
Input::unserialize($input);

// make a string to a slug, what to replace and what delimiter to use
Input::toSlug($string, $replace = array(), $delimiter = '-');

// create a jsqon string from input
Input::jsonEncode($input);

// create a array from json string
Input::jsonDecode($json);
