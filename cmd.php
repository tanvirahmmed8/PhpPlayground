#!/usr/bin/env php
<?php

// Check if the script is being run from the command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.");
}

// Parse CLI arguments
$command = isset($argv[1]) ? $argv[1] : null;

// Define commands and actions
$commands = [
    'hello' => function () {
        echo "Hello, world!\n";
    },
    'create-table' => function ($tableName) {
        create_table($tableName);
    },
];

// Command dispatcher
if (isset($commands[$command])) {
    $action = $commands[$command];
    $args = array_slice($argv, 2);
    $action(...$args);
} else {
    echo "Command not found. Available commands: hello, create-table\n";
}

function create_table($tableName)
{
    try {
        $pdo = db_connect();

        $query = "CREATE TABLE $tableName (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            age INT(3),
            email VARCHAR(100)
        )";

        $pdo->exec($query);

        echo "Table '$tableName' has been created successfully!";
    } catch (PDOException $e) {
        die("Table creation failed: " . $e->getMessage());
    }
}

function db_connect()
{
    // Replace with your database credentials
    $host = 'localhost';
    $dbname = 'dbphp';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// command 
//    chmod +x cmd.php //make it executable
//   ./cmd.php hello
//  ./cmd.php create-table my_dynamic_table
