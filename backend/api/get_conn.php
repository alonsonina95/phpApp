<?php
function getConn()
{
    $host = 'localhost';
    $db   = '';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    return new PDO($dsn, $user, $pass, $options);
}
