<?php
$DB_HOST = 'db';
$DB_PORT = 3306;

$DB_NAME = getenv('MYSQL_DATABASE') ?: 'camagru';
$DB_USER = getenv('MYSQL_USER') ?: 'root';
$DB_PASSWORD = getenv('MYSQL_PASSWORD') ?: 'root';

$DB_DSN = "mysql:host=$DB_HOST;port=$DB_PORT;charset=utf8mb4";
