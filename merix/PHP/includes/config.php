<?php
declare(strict_types=1);

/*
 * 本文件负责全站基础配置。
 * 包含 session 启动、demo 管理员账号、目录路径和通用工具函数。
 */

session_start();

const DEMO_ADMIN_USER = 'admin';
const DEMO_ADMIN_PASS = 'admin123';

const DB_HOST = '127.0.0.1';
const DB_PORT = 3306;
const DB_NAME = 'merix_demo';
const DB_USER = 'root';
const DB_PASS = '';

const DATA_DIR = __DIR__ . '/../data';
const UPLOAD_DIR = __DIR__ . '/../uploads';

if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0777, true);
}
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

$siteImages = [
    // 在这里填写本地图片路径
    'hero_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'company_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'project_a_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'project_b_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'project_c_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'project_detail_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'material_a_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'material_b_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'material_c_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
    'map_image_path' => 'uploads/3644402e43d07ce679c9905146a104e1.jpg',
];

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function path_url(string $path): string
{
    return str_replace('\\', '/', $path);
}

function is_active(string $current, string $target): string
{
    return $current === $target ? 'active' : '';
}

function flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }
    $value = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $value;
}
