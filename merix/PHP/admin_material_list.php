<?php
/*
 * 本页面负责查看材料资料库列表。
 * 用户可筛选、勾选加入 BOM，或点击行查看材料详情。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/admin_views.php';
require_admin();
$materials = materials_all();
$rows = filter_materials($materials, $_GET);
render_admin_header('查看数据', 'material_list');
?>
<section class="admin-panel"><?php render_filter_form($materials, 'admin_material_list.php'); ?></section>
<?php render_material_table($rows, true); ?>
<form id="bomSelection" class="form-actions sticky-actions" method="get" action="admin_bom.php">
    <input type="hidden" name="mode" value="create">
    <button class="primary-btn" type="submit">把勾选材料加入 BOM</button>
    <a class="ghost-btn" href="admin_material_add.php">新增资料</a>
</form>
<?php render_admin_footer(); ?>
