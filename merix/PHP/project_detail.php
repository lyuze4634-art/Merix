<?php
/*
 * 本页面负责前台项目详情展示。
 * 用来展示项目背景、材料清单摘要和本地项目图片。
 */
require_once __DIR__ . '/includes/layout.php';
render_public_header('项目详情', 'projects');
$id = (int)($_GET['id'] ?? 1);
?>
<main>
    <section class="section">
        <div class="section-head">
            <h2><?= e(chr(64 + max(1, min(3, $id)))) ?>项目详情</h2>
            <div>
                <p>项目背景、材料清单摘要、图片占位与相关文件入口。</p>
                <p><a class="ghost-btn" href="projects.php">返回项目列表</a></p>
            </div>
        </div>
        <div class="grid-2">
            <?php local_image('project_detail_image_path', '项目详情图片'); ?>
            <div class="card">
                <h3>项目背景</h3>
                <p>本页使用虚拟项目说明</p>
                <h3>材料清单摘要</h3>
                <p>A材料、B材料、C材料</p>
                <h3>相关文件入口</h3>
                <p>正式版可商议</p>
            </div>
        </div>
    </section>
</main>
<?php render_public_footer(); ?>
