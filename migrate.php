<?php

require 'app/Database/Migrations/CreateUsersTable.php';

$migration = new CreateUsersTable();
$migration->up();
