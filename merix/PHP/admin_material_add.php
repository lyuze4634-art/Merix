<?php
/*
 * 本页面负责新增材料资料。
 * 字段可手动填写，也可从已有选项中选择后保存。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/admin_views.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materials = materials_all();
    $row = material_from_post();
    $row['id'] = next_id($materials);
    $materials[] = $row;
    materials_save($materials);
    add_log('新增材料', $row['name_cn'] ?: $row['item_name']);
    flash('材料已新增。');
    header('Location: admin_material_list.php');
    exit;
}
render_admin_header('增加数据', 'material_add');
render_material_form();
render_admin_footer();
