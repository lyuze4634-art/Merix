<?php
/*
 * 本页面负责 BOM 输出功能。
 * 可选择已有 BOM 并导出为 xlsx 或 csv，也提供复制预览。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/admin_views.php';
require_admin();
$boms = boms_all();
$id = (int)($_GET['id'] ?? ($boms[0]['id'] ?? 0));
$bom = find_by_id($boms, $id) ?: ($boms[0] ?? null);
if ($bom && isset($_GET['type'])) {
    $rows = bom_export_rows($bom);
    $filename = preg_replace('/[^A-Za-z0-9_-]/', '_', (string)$bom['bom_no']) . '_' . date('Ymd');
    if ($_GET['type'] === 'csv') {
        add_log('导出 CSV', $bom['bom_no']);
        export_csv($rows, $filename);
    }
    add_log('导出 XLSX', $bom['bom_no']);
    export_xlsx($rows, $filename);
}
render_admin_header('输出', 'export');
?>
<section class="admin-panel">
    <form class="filter-grid" method="get">
        <label>
            <span>选择 BOM</span>
            <select name="id">
                <?php foreach ($boms as $row): ?>
                    <option value="<?= e($row['id']) ?>" <?= (int)$row['id'] === $id ? 'selected' : '' ?>><?= e($row['bom_no']) ?> / <?= e($row['project_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="primary-btn" type="submit">查看</button>
    </form>
</section>
<?php if ($bom): ?>
    <section class="admin-panel">
        <div class="copy-bar">
            <button class="primary-btn" type="button" data-copy-table="#copyTable">复制 BOM</button>
            <a class="ghost-btn" href="admin_bom_export.php?id=<?= e($bom['id']) ?>&type=xlsx">输出 .xlsx</a>
            <a class="ghost-btn" href="admin_bom_export.php?id=<?= e($bom['id']) ?>&type=csv">输出 .csv</a>
            <span data-copy-status></span>
        </div>
    </section>
    <?php render_bom_table($bom); ?>
<?php endif; ?>
<?php render_admin_footer(); ?>
