<?php
/*
 * 本文件负责后台登录状态和权限检查。
 * Demo 使用固定账号，正式版可改成数据库账号验证。
 */
require_once __DIR__ . '/config.php';

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_user']);
}

function require_admin(): void
{
    if (!admin_logged_in()) {
        header('Location: admin_login.php');
        exit;
    }
}

function admin_login(string $username, string $password): bool
{
    if ($username === DEMO_ADMIN_USER && $password === DEMO_ADMIN_PASS) {
        $_SESSION['admin_user'] = $username;
        return true;
    }
    return false;
}

function admin_logout(): void
{
    unset($_SESSION['admin_user']);
}
