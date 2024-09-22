<?php
namespace App\Controllers;

class UserController {
    public function index() {
        // Sample response
        echo json_encode([
            'status' => 'success',
            'users' => [
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Smith']
            ]
        ]);
    }
}
