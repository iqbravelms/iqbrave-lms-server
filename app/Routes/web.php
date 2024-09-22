<?php
$router->add('GET', '/api/users', 'UserController@index');
$router->add('POST', '/api/login', 'AuthController@login');
$router->add('GET', '/api/protected', 'ApiController@accessProtectedResource');
