<?php
/*
 * 本文件负责 MySQL 数据库连接。
 * 会自动创建 demo 数据库，并返回全站共用的 PDO 连接。
 */
require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $serverDsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $server = new PDO($serverDsn, DB_USER, DB_PASS, $options);
    $server->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

    $dbDsn = $serverDsn . ';dbname=' . DB_NAME;
    $pdo = new PDO($dbDsn, DB_USER, DB_PASS, $options);
    $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

    return $pdo;
}
