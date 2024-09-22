<?php

require 'app/Database/Migrations/CreateUsersTable.php';
require 'app/Database/Seeders/UserSeeder.php';

// Initialize the migration and seeder objects
$migration = new CreateUsersTable();
$seeder = new UserSeeder();

// Rollback the migration (drop the table)
$migration->down();

// Run the migration (create the table)
$migration->up();

// Seed the database with sample data
$seeder->run();
