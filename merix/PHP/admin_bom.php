<?php
/*
 * 本页面负责后台 BOM 的统一入口。
 * 用户可在这里制作新 BOM、选择已有 BOM 并进入修改流程。
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/admin_views.php';
require_admin();

$materials = materials_all();
$boms = boms_all();
$mode = $_GET['mode'] ?? '';

function bom_form_value(?array $bom, string $field): string
{
    return (string)($bom[$field] ?? '');
}

function render_bom_work_page(?array $bom, array $materials): void
{
    $isEdit = (bool)$bom;
    $selectedIds = array_map('intval', $_GET['material_ids'] ?? []);
    $candidateRows = $selectedIds
        ? array_values(array_filter($materials, static fn($row) => in_array((int)$row['id'], $selectedIds, true)))
        : filter_materials($materials, $_GET);

    $initialItems = [];
    foreach (($bom['items'] ?? []) as $item) {
        $initialItems[(int)$item['material_id']] = [
            'qty' => (string)($item['qty'] ?? ''),
            'machine_qty' => (string)($item['machine_qty'] ?? ''),
            'spare_qty' => (string)($item['spare_qty'] ?? ''),
            'position_ref' => (string)($item['position_ref'] ?? ''),
            'remark' => (string)($item['remark'] ?? ''),
        ];
    }
    $materialPayload = [];
    foreach ($materials as $material) {
        $materialPayload[(int)$material['id']] = [
            'id' => (int)$material['id'],
            'name_cn' => (string)($material['name_cn'] ?? ''),
            'item_name' => (string)($material['item_name'] ?? ''),
            'model' => (string)($material['model'] ?? ''),
            'material' => (string)($material['material'] ?? ''),
            'unit' => (string)($material['unit'] ?? ''),
            'brand' => (string)($material['brand'] ?? ''),
            'supplier' => (string)($material['supplier'] ?? ''),
        ];
    }
    ?>
    <script>
        window.bomEditorInitial = <?= json_encode($initialItems, JSON_UNESCAPED_UNICODE) ?>;
        window.bomEditorMaterials = <?= json_encode($materialPayload, JSON_UNESCAPED_UNICODE) ?>;
        window.bomEditorKey = 'merix-bom-preview-<?= e($isEdit ? ('edit-' . ($bom['id'] ?? 0)) : 'create') ?>';
    </script>

    <section class="admin-panel">
        <p><?= $isEdit ? '当前为修改 BOM，已有材料会先出现在下方预览 BOM 中。' : '先筛选备选材料，再把需要的材料添加到预览 BOM。可以多次筛选不同厂牌后继续添加。' ?></p>
        <?php render_filter_form($materials, 'admin_bom.php', true); ?>
    </section>

    <section class="admin-panel">
        <h2>材料明细备选项</h2>
        <p class="notice">在这里勾选需要的材料，点击“添加到预览 BOM”。已添加的材料会保留在下方预览区，不会因为重新筛选而丢失。</p>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                <tr><th>选择</th><th>中文名</th><th>ITEM NAME</th><th>型号</th><th>材质</th><th>单位</th><th>厂牌</th><th>供应商</th><th>状态</th></tr>
                </thead>
                <tbody>
                <?php foreach ($candidateRows as $row): ?>
                    <tr data-candidate-row data-id="<?= e($row['id']) ?>">
                        <td><input type="checkbox" data-candidate-check value="<?= e($row['id']) ?>"></td>
                        <td><?= e($row['name_cn']) ?></td>
                        <td><?= e($row['item_name']) ?></td>
                        <td><?= e($row['model']) ?></td>
                        <td><?= e($row['material']) ?></td>
                        <td><?= e($row['unit']) ?></td>
                        <td><?= e($row['brand']) ?></td>
                        <td><?= e($row['supplier']) ?></td>
                        <td><?= e($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$candidateRows): ?><tr><td class="empty" colspan="9">没有符合条件的材料。</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <button class="primary-btn" type="button" data-add-to-bom-preview>添加到预览 BOM</button>
            <button class="ghost-btn" type="button" data-clear-candidate-checks>取消勾选</button>
        </div>
    </section>

    <form method="post" class="admin-panel" data-bom-editor-form>
        <input type="hidden" name="action" value="save_bom">
        <input type="hidden" name="id" value="<?= e($bom['id'] ?? '') ?>">

        <h2>预览 BOM</h2>
        <p class="notice">确认这里的材料无误后，再填写下方 BOM 信息并点击<?= $isEdit ? '保存 BOM' : '制作 BOM' ?>。</p>
        <div class="table-wrap">
            <table class="data-table" data-bom-preview-table>
                <thead>
                <tr><th>操作</th><th>中文名</th><th>ITEM NAME</th><th>型号</th><th>材质</th><th>单位</th><th>厂牌</th><th>供应商</th><th>数量</th><th>机台数量</th><th>备用数量</th><th>位置参照</th><th>备注</th></tr>
                </thead>
                <tbody data-bom-preview-body>
                <tr data-empty-row><td class="empty" colspan="13">尚未添加材料。</td></tr>
                </tbody>
            </table>
        </div>
        <div data-bom-hidden-fields></div>

        <h2>BOM 信息</h2>
        <div class="form-grid">
            <label><span>BOM 编号</span><input name="bom_no" value="<?= e(bom_form_value($bom, 'bom_no')) ?>"></label>
            <label><span>项目名称</span><input name="project_name" value="<?= e(bom_form_value($bom, 'project_name')) ?>"></label>
            <label><span>客户名称</span><input name="customer_name" value="<?= e(bom_form_value($bom, 'customer_name')) ?>"></label>
            <label><span>机型</span><input name="machine_name" value="<?= e(bom_form_value($bom, 'machine_name')) ?>"></label>
            <label><span>状态</span><input name="status" value="<?= e(bom_form_value($bom, 'status')) ?>"></label>
            <label class="wide"><span>备注</span><textarea name="note" rows="3"><?= e(bom_form_value($bom, 'note')) ?></textarea></label>
        </div>

        <div class="form-actions sticky-actions">
            <button class="primary-btn" type="submit"><?= $isEdit ? '保存 BOM' : '制作 BOM' ?></button>
            <?php if ($isEdit): ?>
                <button class="ghost-btn danger" name="action" value="delete_bom" onclick="return confirm('确定删除这个 BOM 吗？')">删除 BOM</button>
            <?php endif; ?>
            <button class="ghost-btn" type="button" data-clear-bom-preview>清空预览 BOM</button>
            <a class="ghost-btn" href="admin_bom.php">返回 BOM</a>
        </div>
    </form>
    <?php
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'delete_bom' && $id) {
        $boms = array_values(array_filter($boms, static fn($row) => (int)$row['id'] !== $id));
        boms_save($boms);
        add_log('删除 BOM', 'bom_id=' . $id);
        flash('BOM 已删除。');
        header('Location: admin_bom.php?mode=list');
        exit;
    }

    if ($action === 'save_bom') {
        $ids = array_values(array_unique(array_map('intval', $_POST['material_ids'] ?? [])));
        $items = [];
        foreach ($ids as $index => $materialId) {
            $items[] = [
                'id' => $index + 1,
                'material_id' => $materialId,
                'qty' => (float)($_POST['qty'][$materialId] ?? 0),
                'machine_qty' => (float)($_POST['machine_qty'][$materialId] ?? 1),
                'spare_qty' => (float)($_POST['spare_qty'][$materialId] ?? 0),
                'order_status' => '未下订',
                'stock_status' => '无库存',
                'used_status' => '未领料',
                'position_ref' => trim((string)($_POST['position_ref'][$materialId] ?? '')),
                'remark' => trim((string)($_POST['remark'][$materialId] ?? '')),
            ];
        }

        $row = [
            'id' => $id ?: next_id($boms),
            'bom_no' => trim((string)($_POST['bom_no'] ?? '')) ?: 'BOM-DEMO-' . date('YmdHis'),
            'project_name' => trim((string)($_POST['project_name'] ?? '')),
            'customer_name' => trim((string)($_POST['customer_name'] ?? '')),
            'machine_name' => trim((string)($_POST['machine_name'] ?? '')),
            'status' => trim((string)($_POST['status'] ?? '')),
            'note' => trim((string)($_POST['note'] ?? '')),
            'created_at' => date('Y-m-d'),
            'items' => $items,
        ];

        $updated = false;
        foreach ($boms as $index => $bom) {
            if ((int)$bom['id'] === (int)$row['id']) {
                $row['created_at'] = $bom['created_at'] ?? $row['created_at'];
                $boms[$index] = $row;
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            $boms[] = $row;
        }

        boms_save($boms);
        add_log($updated ? '修改 BOM' : '创建 BOM', $row['bom_no']);
        flash($updated ? 'BOM 已保存。' : 'BOM 已制作。');
        header('Location: admin_bom.php?mode=edit&id=' . $row['id']);
        exit;
    }
}

render_admin_header('BOM', 'bom');
?>
<?php if ($mode === ''): ?>
    <section class="admin-panel">
        <p>请选择要进行的 BOM 操作。</p>
        <div class="grid-2">
            <a class="card clickable-row" href="admin_bom.php?mode=create">
                <h3>制作 BOM</h3>
                <p>先筛选材料并加入预览 BOM，再填写 BOM 信息并制作。</p>
            </a>
            <a class="card clickable-row" href="admin_bom.php?mode=list">
                <h3>修改 BOM</h3>
                <p>查看已经制作完成的 BOM，点击后进入同一个页面进行修改。</p>
            </a>
        </div>
    </section>
<?php elseif ($mode === 'list'): ?>
    <section class="admin-panel">
        <p>已制作 BOM 列表。点击所在行进入修改。</p>
    </section>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>BOM 编号</th><th>项目名称</th><th>客户</th><th>机型</th><th>状态</th><th>创建日期</th><th>明细数量</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($boms as $bom): ?>
                <tr class="clickable-row" data-href="admin_bom.php?mode=edit&id=<?= e($bom['id']) ?>" tabindex="0">
                    <td><?= e($bom['bom_no']) ?></td>
                    <td><?= e($bom['project_name']) ?></td>
                    <td><?= e($bom['customer_name']) ?></td>
                    <td><?= e($bom['machine_name']) ?></td>
                    <td><?= e($bom['status']) ?></td>
                    <td><?= e($bom['created_at']) ?></td>
                    <td><?= e(count($bom['items'] ?? [])) ?></td>
                    <td><a class="mini-btn" href="admin_bom.php?mode=edit&id=<?= e($bom['id']) ?>">修改</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$boms): ?><tr><td class="empty" colspan="8">暂无 BOM。</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
<?php elseif ($mode === 'create'): ?>
    <?php render_bom_work_page(null, $materials); ?>
<?php elseif ($mode === 'edit'): ?>
    <?php
    $bom = find_by_id($boms, (int)($_GET['id'] ?? 0));
    if ($bom) {
        render_bom_work_page($bom, $materials);
    } else {
        echo '<p class="notice">找不到这个 BOM。</p><div class="form-actions"><a class="ghost-btn" href="admin_bom.php?mode=list">返回列表</a></div>';
    }
    ?>
<?php endif; ?>
<?php render_admin_footer(); ?>
