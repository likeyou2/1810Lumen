<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/user/login','User\UserController@login'); //登录

$router->post('/user/register','User\UserController@register');  //注册



$router->get('/user/test','User\UserController@test');//测试
$router->get('/user/cuPost','User\UserController@cuPost');
$router->post('/user/decode','User\UserController@decode'); //解密
$router->post('/user/rsa','User\UserController@rsa'); //非对称解密

$router->post('/user/st','User\UserController@st'); //私钥解密
