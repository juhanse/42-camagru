<?php
$DB_DSN = 'mysql:host=db;charset=utf8mb4';
$DB_USER = getenv('MYSQL_USER') ?: 'root';
$DB_PASSWORD = getenv('MYSQL_PASSWORD') ?: 'root';
$DB_NAME = getenv('MYSQL_DATABASE') ?: 'camagru';
