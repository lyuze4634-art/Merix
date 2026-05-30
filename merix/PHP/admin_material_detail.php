<?php
/*
 * 本页面负责展示单条材料详情。
 * 会显示该材料已保存的全部参数和已添加图片。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/admin_views.php';
require_admin();

$materials = materials_all();
$id = (int)($_GET['id'] ?? 0);
$material = find_by_id($materials, $id);

render_admin_header('材料资料详情', 'material_list');
?>
<?php if (!$material): ?>
    <p class="notice">找不到这条材料资料。</p>
    <div class="form-actions"><a class="ghost-btn" href="admin_material_list.php">返回查看数据</a></div>
<?php else: ?>
    <section class="admin-panel">
        <div class="copy-bar">
            <a class="ghost-btn" href="admin_material_list.php">返回查看数据</a>
            <a class="primary-btn" href="admin_material_edit.php?id=<?= e($material['id']) ?>">更改资料</a>
            <a class="ghost-btn" href="admin_bom.php?mode=create&material_ids[]=<?= e($material['id']) ?>">加入 BOM</a>
        </div>
    </section>

    <section class="admin-panel">
        <h2><?= e($material['name_cn'] ?: $material['item_name']) ?></h2>
        <div class="detail-grid">
            <?php foreach (material_fields() as $field => $label): ?>
                <div class="detail-item <?= $field === 'notes' ? 'wide' : '' ?>">
                    <strong><?= e($label) ?></strong>
                    <span><?= e($material[$field] ?? '') ?></span>
                </div>
            <?php endforeach; ?>
            <div class="detail-item">
                <strong>资料 ID</strong>
                <span><?= e($material['id']) ?></span>
            </div>
        </div>
    </section>

    <section class="admin-panel">
        <h2>已添加图片</h2>
        <?php if (!empty($material['images'])): ?>
            <div class="detail-images">
                <?php foreach ($material['images'] as $image): ?>
                    <?php if (str_starts_with((string)$image, 'uploads/')): ?>
                        <img src="<?= e(path_url($image)) ?>" alt="材料图片">
                    <?php elseif (filter_var($image, FILTER_VALIDATE_URL)): ?>
                        <img src="<?= e($image) ?>" alt="材料图片" onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'detail-image-url',textContent:this.src}))">
                    <?php else: ?>
                        <div class="detail-image-url"><?= e($image) ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="notice">这条资料还没有添加图片。</p>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php render_admin_footer(); ?>
