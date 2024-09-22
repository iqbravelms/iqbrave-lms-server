<?php

require 'app/Database/Migrations/CreateUsersTable.php';
require 'app/Database/Migrations/CreateStudentsTable.php';
require 'app/Database/Migrations/CreateCoursesTable.php';
require 'app/Database/Migrations/CreateModulesTable.php';
require 'app/Database/Migrations/CreateLessonsTable.php';
require 'app/Database/Migrations/CreateLessonStepsTable.php';
require 'app/Database/Migrations/CreateAssignmentsTable.php';
require 'app/Database/Migrations/CreateAssignmentFilesTable.php';
require 'app/Database/Migrations/CreateStudentAssignmentsTable.php';

$UsersMigration = new CreateUsersTable();
$UsersMigration->up();

$StudentMigration = new CreateStudentsTable();
$StudentMigration->up();

$CourseMigration = new CreateCoursesTable();
$CourseMigration->up();

$ModuleMigration = new CreateModulesTable();
$ModuleMigration->up();

$LessonMigration = new CreateLessonsTable();
$LessonMigration->up();

$LessonStepsMigration = new CreateLessonStepsTable();
$LessonStepsMigration->up();

$AssignmentsMigration = new CreateAssignmentsTable();
$AssignmentsMigration->up();

$AssignmentFilesMigration = new CreateAssignmentFilesTable();
$AssignmentFilesMigration->up();

$StudentAssignmentsMigration = new CreateStudentAssignmentsTable();
$StudentAssignmentsMigration->up();
