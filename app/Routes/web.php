<?php
$router->add('GET', '/api/users', 'UserController@index');
$router->add('GET', '/api/protected', 'ApiController@accessProtectedResource');


$router->add('GET', '/api/courses', 'CourseController@index');
$router->add('GET', '/api/modules/{id}', 'ModuleController@index');
$router->add('GET', '/api/lessons/{id}', 'LessonController@index');

$router->add('POST', '/api/signup', 'StudentController@index');
//Auth section
$router->add('POST', '/api/signin', 'AuthController@signin');
$router->add('GET', '/api/lesson/{id}', 'LessonController@getLesson');
$router->add('GET', '/api/assignments/{id}', 'AssignmentController@getAssignment');
$router->add('POST', '/api/submitassignment', 'AssignmentController@submitAssignment');
$router->add('POST', '/api/studentregister', 'StudentController@studentRegister');
