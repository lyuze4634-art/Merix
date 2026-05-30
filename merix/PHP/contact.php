<?php
/*
 * 本页面负责前台联系入口。
 * 展示联系信息、地图本地图片和咨询表单 demo。
 */
require_once __DIR__ . '/includes/layout.php';
render_public_header('联系', 'contact');
?>
<main>
    <section class="section">
        <div class="section-head">
            <h2>联系入口</h2>
            <p>Demo 咨询表单不发送邮件，正式版可接邮件或后台留言管理。</p>
        </div>
        <div class="grid-2">
            <form class="card">
                <label><span>公司名称</span><input placeholder="A社"></label>
                <label><span>联系人</span><input placeholder="张先生"></label>
                <label><span>邮箱</span><input placeholder="demo@example.com"></label>
                <label><span>咨询内容</span><textarea rows="5" placeholder="请填写项目或材料需求"></textarea></label>
                <button class="primary-btn" type="button">送出 Demo</button>
            </form>
            <?php local_image('map_image_path', '地图图片'); ?>
        </div>
    </section>
</main>
<?php render_public_footer(); ?>
