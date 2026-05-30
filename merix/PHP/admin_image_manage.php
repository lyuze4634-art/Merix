<?php
/*
 * 本页面负责后台图片管理。
 * 仅允许为材料上传本地图片，不再接受手动图片链接。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/admin_views.php';
require_admin();
$materials = materials_all();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    foreach ($materials as $index => $row) {
        if ((int)$row['id'] === $id) {
            $materials[$index] = material_from_post($row);
            $materials[$index]['id'] = $id;
            materials_save($materials);
            add_log('更新材料图片', $materials[$index]['name_cn']);
            flash('图片资料已保存。');
            header('Location: admin_image_manage.php?id=' . $id);
            exit;
        }
    }
}
$id = (int)($_GET['id'] ?? ($materials[0]['id'] ?? 0));
$material = find_by_id($materials, $id);
render_admin_header('图片管理', 'images');
?>
<section class="admin-panel">
    <form class="filter-grid" method="get">
        <label>
            <span>选择材料</span>
            <select name="id">
                <?php foreach ($materials as $row): ?>
                    <option value="<?= e($row['id']) ?>" <?= (int)$row['id'] === $id ? 'selected' : '' ?>><?= e($row['name_cn']) ?> / <?= e($row['model']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="primary-btn" type="submit">管理图片</button>
    </form>
</section>
<?php if ($material) render_material_form($material); ?>
<?php render_admin_footer(); ?>
