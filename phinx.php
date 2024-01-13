<?php
return [
    "paths" => [
        "migrations" => "database/migrations",
        "seeds" => "database/seeds"
    ],
    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database" => "dev",
        "default_environment" => "dev",
        "dev" => [
            "adapter" => "mysql",
            "host" => "127.0.0.1",
            "name" => "webman",
            "user" => "root",
            "pass" => "",
            "port" => "3306",
            "charset" => "utf8mb4"
        ]
    ]
];