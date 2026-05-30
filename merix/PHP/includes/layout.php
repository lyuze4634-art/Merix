<?php
/*
 * 本文件负责前台和后台的公共布局。
 * 包含页头、页脚、后台侧边栏和图片占位组件。
 */
require_once __DIR__ . '/config.php';

function render_public_header(string $title, string $active = 'home'): void
{
    ?>
    <!doctype html>
    <html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?>Demo</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/js/theme.js" defer></script>
    </head>
    <body>
    <header class="site-header">
        <a class="brand" href="index.php">商社 Demo</a>
        <nav class="top-nav" aria-label="前台导航">
            <a class="<?= e(is_active($active, 'home')) ?>" href="index.php">首页</a>
            <a class="<?= e(is_active($active, 'about')) ?>" href="about.php">会社介绍</a>
            <a class="<?= e(is_active($active, 'business')) ?>" href="business.php">业务范围</a>
            <a class="<?= e(is_active($active, 'projects')) ?>" href="projects.php">项目展示</a>
            <a class="<?= e(is_active($active, 'materials')) ?>" href="materials.php">材料展示</a>
            <a class="<?= e(is_active($active, 'contact')) ?>" href="contact.php">联系</a>
            <button class="theme-toggle" type="button" data-theme-toggle aria-label="切换白天和夜晚模式"><span></span></button>
        </nav>
    </header>
    <?php
}

function render_public_footer(): void
{
    ?>
    <footer class="site-footer">
        <div>
            <strong>商社 Demo</strong>
            <p>样版网站，非开源</p>
        </div>
        <div>
            <p>demo@example.com</p>
            <p>000-0000-0000</p>
        </div>
        <a class="admin-secret" href="admin_login.php" aria-label="后台入口">管理</a>
    </footer>
    </body>
    </html>
    <?php
}

function render_admin_header(string $title, string $active = 'dashboard'): void
{
    ?>
    <!doctype html>
    <html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?> | BOM 后台 Demo</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/js/theme.js" defer></script>
        <script src="assets/js/bom-copy.js" defer></script>
    </head>
    <body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="brand" href="admin_dashboard.php">BOM 后台 Demo</a>
            <nav class="admin-nav" aria-label="后台导航">
                <a class="<?= e(is_active($active, 'dashboard')) ?>" href="admin_dashboard.php">后台首页</a>
                <a class="<?= e(is_active($active, 'material_list')) ?>" href="admin_material_list.php">查看数据</a>
                <a class="<?= e(is_active($active, 'material_add')) ?>" href="admin_material_add.php">增加数据</a>
                <a class="<?= e(is_active($active, 'material_edit')) ?>" href="admin_material_edit.php">更改/删除</a>
                <a class="<?= e(is_active($active, 'bom')) ?>" href="admin_bom.php">BOM</a>
                <a class="<?= e(is_active($active, 'export')) ?>" href="admin_bom_export.php">输出页面</a>
                <a class="<?= e(is_active($active, 'images')) ?>" href="admin_image_manage.php">图片管理</a>
                <a class="<?= e(is_active($active, 'logs')) ?>" href="admin_logs.php">操作记录</a>
                <a href="admin_login.php?logout=1">退出</a>
            </nav>
        </aside>
        <main class="admin-main">
            <div class="admin-top">
                <div>
                    <p class="eyebrow">PHP/MySQL ready demo</p>
                    <h1><?= e($title) ?></h1>
                </div>
                <button class="theme-toggle" type="button" data-theme-toggle aria-label="切换白天和夜晚模式"><span></span></button>
            </div>
            <?php if ($message = flash()): ?>
                <p class="notice"><?= e($message) ?></p>
            <?php endif; ?>
    <?php
}

function render_admin_footer(): void
{
    ?>
        </main>
    </div>
    </body>
    </html>
    <?php
}

function image_placeholder(string $key, string $label): void
{
    ?>
    <div class="image-placeholder">
        <strong><?= e($label) ?></strong>
        <span><?= e($key) ?> = "请在此处填写图片链接"</span>
    </div>
    <?php
}

function local_image(string $key, string $label, string $class = ''): void
{
    global $siteImages;
    $path = trim((string)($siteImages[$key] ?? ''));
    if ($path !== '') {
        echo '<img class="' . e($class) . '" src="' . e(path_url($path)) . '" alt="' . e($label) . '">';
        return;
    }
    ?>
    <div class="image-placeholder">
        <strong><?= e($label) ?></strong>
        <span><?= e($key) ?> = "请在 config.php 填写本地图片路径"</span>
    </div>
    <?php
}
