<?php
$router->add('GET', '/api/users', 'UserControllerForAdmin@index');
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

$router->add('GET', '/api/alladmin', 'AdminController@getAllAdmin');
$router->add('POST', '/api/updateuser', 'AdminController@updateAdimin');
$router->add('POST', '/api/userregister', 'AdminController@adminRegister');
$router->add('GET', '/api/deactivateuser/{id}', 'AdminController@deactivateUser');
$router->add('GET', '/api/activateuser/{id}', 'AdminController@activateUser');

$router->add('GET', '/api/allstudent', 'UserControllerForAdmin@getAllStudent');
$router->add('POST', '/api/updatestudent', 'UserControllerForAdmin@updateStudent');
$router->add('POST', '/api/studentregister', 'UserControllerForAdmin@registerStudent');
$router->add('GET', '/api/deactivatestudent/{id}', 'UserControllerForAdmin@deactivateStudent');
$router->add('GET', '/api/activatestudent/{id}', 'UserControllerForAdmin@activateStudent');
$router->add('GET', '/api/deletestudent/{id}', 'UserControllerForAdmin@deleteStudent');

$router->add('GET', '/api/allcourse', 'CourseControllerAdmin@getAllCourses');
$router->add('POST', '/api/updatecourse', 'CourseControllerAdmin@updateCourse');
$router->add('POST', '/api/courseregister', 'CourseControllerAdmin@courseRegister');
$router->add('GET', '/api/deletecourse/{id}', 'CourseControllerAdmin@deleteCourse');

$router->add('GET', '/api/getallmodules/{id}', 'ModulesContorollerAdmin@getAllModules');
$router->add('POST', '/api/addmodule', 'ModulesContorollerAdmin@addModule');
$router->add('POST', '/api/updatemodule', 'ModulesContorollerAdmin@updateModule');
$router->add('GET', '/api/deletemodule/{id}', 'ModulesContorollerAdmin@deleteModule');

$router->add('GET', '/api/getcoursewithmodule', 'AddLessonController@getAllCorse');
$router->add('POST', '/api/lessonadd', 'AddLessonController@addLesson');
$router->add('GET', '/api/getlesson/{id}', 'AddLessonController@getLesson');
$router->add('GET', '/api/getsteps/{id}', 'AddLessonController@getSteps');
$router->add('POST', '/api/updatelesson', 'AddLessonController@updateLesson');
$router->add('POST', '/api/updatestep', 'AddLessonController@updateStep');
$router->add('GET', '/api/getassignmentsforadmin/{id}', 'AssignmentControllerAdimn@getAssignment');
$router->add('POST', '/api/updateassignment', 'AssignmentControllerAdimn@updateAssignmentAndStructure');
