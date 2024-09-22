<?php

require 'app/Database/Seeders/UserSeeder.php';
require 'app/Database/Seeders/StudentSeeder.php';
require 'app/Database/Seeders/CourseSeeder.php';
require 'app/Database/Seeders/ModuleSeeder.php';
require 'app/Database/Seeders/LessonSeeder.php';
require 'app/Database/Seeders/LessonStepSeeder.php';
require 'app/Database/Seeders/AssignmentSeeder.php';
require 'app/Database/Seeders/StudentAssignmentSeeder.php';

$UsersSeeder = new UserSeeder();
$UsersSeeder->run();

$StudentSeeder = new StudentSeeder();
$StudentSeeder->run();

$CourseSeeder = new CourseSeeder();
$CourseSeeder->run();

$CourseSeeder = new ModuleSeeder();
$CourseSeeder->run();

$LessonSeeder = new LessonSeeder();
$LessonSeeder->run();

$LessonStepSeeder = new LessonStepSeeder();
$LessonStepSeeder->run();

$AssignmentSeeder = new AssignmentSeeder();
$AssignmentSeeder->run();

$AssignmentSeeder = new StudentAssignmentSeeder();
$AssignmentSeeder->run();
