<?php
/*
 * 本页面负责查看后台操作记录。
 * 用于追踪新增、修改、删除和导出等 demo 行为。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/store.php';
require_admin();
$logs = array_reverse(logs_all());
render_admin_header('操作记录页面', 'logs');
?>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>ID</th><th>动作</th><th>对象</th><th>时间</th></tr></thead>
        <tbody>
        <?php foreach ($logs as $log): ?>
            <tr><td><?= e($log['id']) ?></td><td><?= e($log['action']) ?></td><td><?= e($log['target']) ?></td><td><?= e($log['created_at']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php render_admin_footer(); ?>
