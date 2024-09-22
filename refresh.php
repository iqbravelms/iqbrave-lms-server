<?php

require 'app/Database/Migrations/CreateUsersTable.php';
require 'app/Database/Seeders/UserSeeder.php';

require 'app/Database/Migrations/CreateStudentsTable.php';
require 'app/Database/Seeders/StudentSeeder.php';

require 'app/Database/Migrations/CreateCoursesTable.php';
require 'app/Database/Seeders/CourseSeeder.php';

require 'app/Database/Migrations/CreateModulesTable.php';
require 'app/Database/Seeders/ModuleSeeder.php';

require 'app/Database/Migrations/CreateLessonsTable.php';
require 'app/Database/Seeders/LessonSeeder.php';

require 'app/Database/Migrations/CreateLessonStepsTable.php';
require 'app/Database/Seeders/LessonStepSeeder.php';

require 'app/Database/Migrations/CreateAssignmentsTable.php';
require 'app/Database/Seeders/AssignmentSeeder.php';

require 'app/Database/Migrations/CreateAssignmentFilesTable.php';
require 'app/Database/Seeders/AssignmentFileSeeder.php';

require 'app/Database/Migrations/CreateStudentAssignmentsTable.php';
require 'app/Database/Seeders/StudentAssignmentSeeder.php';

$UsersMigration = new CreateUsersTable();
$UsersSeeder = new UserSeeder();

$UsersMigration->down();

$UsersMigration->up();

$UsersSeeder->run();




$StudentAssignmentMigration = new CreateStudentAssignmentsTable();
$StudentAssignmentsSeeder = new StudentAssignmentSeeder();

$StudentAssignmentMigration->down();

$AssignmentFileMigration = new CreateAssignmentFilesTable();
$AssignmentFileSeeder = new AssignmentFileSeeder();

$AssignmentFileMigration->down();

$AssignmentsMigration = new CreateAssignmentsTable();
$AssignmentSeeder = new AssignmentSeeder();

$AssignmentsMigration->down();

$LessonStepsMigration = new CreateLessonStepsTable();
$LessonStepSeeder = new LessonStepSeeder();

$LessonStepsMigration->down();

$LessonsMigration = new CreateLessonsTable();
$LessonSeeder = new LessonSeeder();

$LessonsMigration->down();


$ModuleMigration = new CreateModulesTable();
$ModuleSeeder = new ModuleSeeder();
echo "----";

$ModuleMigration->down();

$CoursesMigration = new CreateCoursesTable();
$CourseSeeder = new CourseSeeder();

$CoursesMigration->down();

$CoursesMigration->up();

$StudentsMigration = new CreateStudentsTable();
$StudentSeeder = new StudentSeeder();

$StudentsMigration->down();

$CourseSeeder->run();

$ModuleMigration->up();

$ModuleSeeder->run();

$LessonsMigration->up();

$LessonSeeder->run();

$LessonStepsMigration->up();

$LessonStepSeeder->run();

$AssignmentsMigration->up();

$AssignmentSeeder->run();

$AssignmentFileMigration->up();

$AssignmentFileSeeder->run();



$StudentsMigration->up();

$StudentSeeder->run();

$StudentAssignmentMigration->up();

$StudentAssignmentsSeeder->run();
