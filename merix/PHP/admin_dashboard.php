<?php
/*
 * 本页面负责后台首页。
 * 用来展示材料、供应商、BOM 和操作记录的概览入口。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/admin_views.php';
require_admin();
$materials = materials_all();
$boms = boms_all();
$logs = logs_all();
render_admin_header('后台首页', 'dashboard');
?>
<div class="metrics">
    <div class="metric"><strong><?= count($materials) ?></strong><span>材料数量</span></div>
    <div class="metric"><strong><?= count(options_for($materials, 'supplier')) ?></strong><span>供应商数量</span></div>
    <div class="metric"><strong><?= count($boms) ?></strong><span>BOM 数量</span></div>
    <div class="metric"><strong><?= count($logs) ?></strong><span>操作记录</span></div>
</div>
<section class="admin-panel">
    <h2>快捷入口</h2>
    <div class="button-row">
        <a class="primary-btn" href="admin_material_add.php">新增材料</a>
        <a class="ghost-btn" href="admin_bom.php?mode=create">制作 BOM</a>
        <a class="ghost-btn" href="admin_bom.php?mode=list">修改 BOM</a>
        <a class="ghost-btn" href="admin_bom_export.php">输出 BOM</a>
    </div>
</section>
<?php render_material_table(array_slice($materials, 0, 5)); ?>
<?php render_admin_footer(); ?>
