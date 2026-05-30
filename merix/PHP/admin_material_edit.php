<?php
/*
 * 本页面负责材料资料的修改和删除。
 * 可编辑已有字段、图片，并对材料执行删除操作。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/admin_views.php';
require_admin();
$materials = materials_all();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($action === 'delete_material') {
        $materials = array_values(array_filter($materials, static fn($row) => (int)$row['id'] !== $id));
        materials_save($materials);
        add_log('删除材料', 'material_id=' . $id);
        flash('材料已删除。');
        header('Location: admin_material_edit.php');
        exit;
    }
    if ($action === 'save_material') {
        foreach ($materials as $index => $row) {
            if ((int)$row['id'] === $id) {
                $new = material_from_post($row);
                $new['id'] = $id;
                $materials[$index] = $new;
                materials_save($materials);
                add_log('修改材料', $new['name_cn'] ?: $new['item_name']);
                flash('材料已保存。');
                header('Location: admin_material_edit.php?id=' . $id);
                exit;
            }
        }
    }
}
$id = (int)($_GET['id'] ?? 0);
render_admin_header($id ? '更改资料' : '更改或删除', 'material_edit');
if ($id) {
    $material = find_by_id($materials, $id);
    echo $material ? '' : '<p class="notice">找不到材料。</p>';
    if ($material) {
        render_material_form($material);
    }
} else {
    $rows = filter_materials($materials, $_GET);
    echo '<section class="admin-panel">';
    render_filter_form($materials, 'admin_material_edit.php');
    echo '</section>';
    render_material_table($rows, false, true);
}
render_admin_footer();
