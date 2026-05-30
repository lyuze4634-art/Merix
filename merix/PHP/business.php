<?php
/*
 * 本页面负责前台业务范围展示。
 * 用来说明材料贸易、设备配套、采购代理和技术支持等服务。
 */
require_once __DIR__ . '/includes/layout.php';
render_public_header('业务范围', 'business');
?>
<main>
    <section class="section">
        <div class="section-head">
            <h2>业务范围</h2>
            <p>可以更改展示内容，后续可以添加图片</p>
        </div>
        <div class="grid-4">
            <article class="flat-card"><h3>材料贸易</h3><p>A材料、B材料、C材料的规格确认、询价和供货。</p></article>
            <article class="flat-card"><h3>设备配套</h3><p>围绕 A项目、B项目整理配套材料与 BOM 明细。</p></article>
            <article class="flat-card"><h3>采购代理</h3><p>协助客户处理供应商沟通、交期和替代品建议。</p></article>
            <article class="flat-card"><h3>技术支持</h3><p>按图面、规格、型号和现场条件整理可执行清单。</p></article>
        </div>
    </section>
</main>
<?php render_public_footer(); ?>
