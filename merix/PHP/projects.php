<?php
/*
 * 本页面负责前台项目列表展示。
 * 使用项目卡片展示项目分类、摘要和详情入口。
 */
require_once __DIR__ . '/includes/layout.php';
render_public_header('项目展示', 'projects');
$projects = [
    ['id' => 1, 'name' => 'A项目', 'category' => '设备配套', 'summary' => '整理 A材料与 A供应商资料，形成可输出 BOM。', 'image' => 'project_a_image_path'],
    ['id' => 2, 'name' => 'B项目', 'category' => '采购代理', 'summary' => '协助 B社处理材料规格确认与交期追踪。', 'image' => 'project_b_image_path'],
    ['id' => 3, 'name' => 'C项目', 'category' => '技术支持', 'summary' => '把 C材料规格、型号和位置参照整理成清单。', 'image' => 'project_c_image_path'],
];
?>
<main>
    <section class="section">
        <div class="section-head">
            <h2>项目展示列表</h2>
            <p>每张卡片都从 config.php 中读取本地图片路径，后续可更改</p>
        </div>
        <div class="grid-3">
            <?php foreach ($projects as $project): ?>
                <article class="card">
                    <div class="media-frame placeholder-small"><?php local_image($project['image'], $project['name'] . '封面图'); ?></div>
                    <p class="eyebrow"><?= e($project['category']) ?></p>
                    <h3><?= e($project['name']) ?></h3>
                    <p><?= e($project['summary']) ?></p>
                    <p><a class="mini-btn" href="project_detail.php?id=<?= e($project['id']) ?>">查看详情</a></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php render_public_footer(); ?>
