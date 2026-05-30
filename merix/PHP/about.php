<?php
/*
 * 本页面负责前台会社/企业介绍。
 * 用来展示公司简介、经营理念和合作流程。
 */
require_once __DIR__ . '/includes/layout.php';
render_public_header('会社介绍', 'about');
?>
<main>
    <section class="section">
        <div class="section-head">
            <h2>会社 / 企业介绍</h2>
            <p>这里可以放公司简介、经营理念、合作流程与资质占位，图片从本地路径读取。</p>
        </div>
        <div class="grid-2">
            <?php local_image('company_image_path', '公司形象图片'); ?>
            <div class="card">
                <h3>经营理念</h3>
                <p>后续可更改，可自定义图片或其他内容</p>
                <hr>
                <h3>合作流程</h3>
                <p>后续可更改，可自定义图片或其他内容</p>
            </div>
        </div>
    </section>
</main>
<?php render_public_footer(); ?>
