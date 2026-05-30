<?php
/*
 * 本页面负责前台材料展示。
 * 只展示客户可见的材料类别，不显示后台敏感字段。
 */
require_once __DIR__ . '/includes/layout.php';
render_public_header('材料展示', 'materials');
$materials = [
    ['A材料', 'A规格、A材质、常用单位 M / EA。', 'material_a_image_path'],
    ['B材料', 'B规格、B材质，常见于阀件或配套件。', 'material_b_image_path'],
    ['C材料', 'C规格、C材质，可用于电控或辅助件。', 'material_c_image_path'],
];
?>
<main>
    <section class="section">
        <div class="section-head">
            <h2>材料展示</h2>
            <p>每张卡片都从 config.php 中读取本地图片路径，后续可更改</p>
        </div>
        <div class="grid-3">
            <?php foreach ($materials as [$name, $desc, $imageKey]): ?>
                <article class="card">
                    <div class="media-frame placeholder-small"><?php local_image($imageKey, $name . '图片'); ?></div>
                    <h3><?= e($name) ?></h3>
                    <p><?= e($desc) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php render_public_footer(); ?>
