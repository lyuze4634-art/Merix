<?php
/*
 * 本页面负责后台登录和退出。
 * Demo 使用固定管理员账号，正式版可替换为数据库用户。
 */
require_once __DIR__ . '/includes/auth.php';
if (isset($_GET['logout'])) {
    admin_logout();
    flash('已退出后台。');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (admin_login(trim((string)($_POST['username'] ?? '')), (string)($_POST['password'] ?? ''))) {
        header('Location: admin_dashboard.php');
        exit;
    }
    flash('账号或密码不正确。Demo 账号：admin / admin123');
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台登录 | BOM Demo</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/theme.js" defer></script>
</head>
<body>
<main class="login-page">
    <form class="login-card" method="post">
        <p class="eyebrow">Admin login</p>
        <h1>后台登录</h1>
        <?php if ($message = flash()): ?><p class="notice"><?= e($message) ?></p><?php endif; ?>
        <label><span>账号</span><input name="username" value="admin"></label>
        <label><span>密码</span><input type="password" name="password" value="admin123"></label>
        <div class="form-actions">
            <button class="primary-btn" type="submit">登录</button>
            <a class="ghost-btn" href="index.php">返回前台</a>
            <button class="theme-toggle" type="button" data-theme-toggle aria-label="切换白天和夜晚模式"><span></span></button>
        </div>
    </form>
</main>
</body>
</html>
