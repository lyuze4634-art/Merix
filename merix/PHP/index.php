<?php
/*
 * 本页面负责前台展示首页。
 * 用来展示商社形象、代表项目、材料服务和后台入口。
 */
require_once __DIR__ . '/includes/layout.php';
render_public_header('商社展示首页', 'home');
?>
<main>
    <section class="hero">
        <div>
            <p class="eyebrow">可自更改主页内容与样式</p>
            <h1>商社展示页面 + 后台管理</h1>
            <p class="lead">展示站 demo，前台展示商社形象、业务项目、材料服务，后台用于材料资料库、BOM 制作、筛选、复制与导出。</p>
            <p class="lead">后续可以定制样式和各种参数，也可以加入各种动态效果。</p>
            <div class="button-row">
                <a class="primary-btn" href="business.php">业务范围</a>
                <a class="ghost-btn" href="projects.php">项目展示</a>
            </div>
        </div>
        <?php local_image('hero_image_path', '首页 Hero 大图'); ?>
    </section>

    <section class="section">
        <div class="section-head">
            <h2>展示站定位</h2>
            <p>客户能快速看懂公司能做什么；后续可以继续把内容接入后台维护。</p>
        </div>
        <div class="grid-4">
            <article class="flat-card"><h3>商社形象</h3><p>后续可添加或更改文字和本地图片。</p></article>
            <article class="flat-card"><h3>业务项目</h3><p>展示材料贸易、设备配套和采购代理。</p></article>
            <article class="flat-card"><h3>材料展示</h3><p>展示 A材料、B材料、C材料等前台内容。</p></article>
            <article class="flat-card"><h3>后台管理</h3><p>管理材料资料、BOM 制作、导出和图片。</p></article>
        </div>
    </section>

    <section class="section">
        <div class="section-head">
            <h2>代表项目</h2>
            <p>A项目、B项目、C项目，正式版后续可替换为真实项目介绍。</p>
        </div>
        <div class="grid-3">
            <?php
            $projects = [
                ['A项目', '设备配套与材料清单整理', 'project_a_image_path'],
                ['B项目', '采购代理与供应商协调', 'project_b_image_path'],
                ['C项目', '工程耗材与交付追踪', 'project_c_image_path'],
            ];
            ?>
            <?php foreach ($projects as [$name, $desc, $imageKey]): ?>
                <article class="card">
                    <div class="media-frame placeholder-small"><?php local_image($imageKey, $name . '图片'); ?></div>
                    <h3><?= e($name) ?></h3>
                    <p><?= e($desc) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
        <div class="section-head">
            <h2>材料与服务</h2>
            <p>前台只展示客户容易理解的信息，完整规格和供应状态放在后台管理。</p>
        </div>
        <div class="grid-3">
            <article class="flat-card"><h3>A材料</h3><p>自选展示，可以放本地图片。</p></article>
            <article class="flat-card"><h3>B材料</h3><p>自选展示，可以放本地图片。</p></article>
            <article class="flat-card"><h3>C服务</h3><p>筛选、比价、交期追踪、BOM 输出。</p></article>
        </div>
    </section>
</main>
<?php render_public_footer(); ?>
