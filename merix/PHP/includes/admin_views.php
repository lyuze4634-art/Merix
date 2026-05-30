<?php
/*
 * 本文件负责后台共用视图组件。
 * 包含筛选表单、材料表格、材料表单和 BOM 表格等渲染函数。
 */
require_once __DIR__ . '/store.php';

function render_filter_form(array $materials, string $action, bool $multiple = false): void
{
    $fields = ['brand' => '厂牌', 'supplier' => '供应商', 'material' => '材质', 'unit' => '单位', 'category' => '类别', 'status' => '状态'];
    ?>
    <form class="filter-grid" method="get" action="<?= e($action) ?>">
        <?php if (basename($action) === 'admin_bom.php'): ?>
            <?php if (isset($_GET['mode'])): ?><input type="hidden" name="mode" value="<?= e($_GET['mode']) ?>"><?php endif; ?>
            <?php if (isset($_GET['id'])): ?><input type="hidden" name="id" value="<?= e($_GET['id']) ?>"><?php endif; ?>
        <?php endif; ?>
        <?php foreach ($fields as $field => $label): ?>
            <label>
                <span><?= e($label) ?><?= $multiple ? '（可多选）' : '' ?></span>
                <select name="<?= e($field) ?><?= $multiple ? '[]' : '' ?>" <?= $multiple ? 'multiple' : '' ?>>
                    <?php if ($multiple): ?>
                        <option value="" data-empty-option>（未选择）</option>
                    <?php else: ?>
                        <option value="">全部</option>
                    <?php endif; ?>
                    <?php foreach (options_for($materials, $field) as $option): ?>
                        <?php
                        $selected = $_GET[$field] ?? [];
                        $selected = is_array($selected) ? $selected : [(string)$selected];
                        ?>
                        <option value="<?= e($option) ?>" <?= in_array($option, $selected, true) ? 'selected' : '' ?>><?= e($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        <?php endforeach; ?>
        <label>
            <span>关键词</span>
            <input name="keyword" value="<?= e($_GET['keyword'] ?? '') ?>" placeholder="中文名 / ITEM NAME / 型号">
        </label>
        <button class="primary-btn" type="submit">筛选</button>
    </form>
    <?php
}

function render_material_table(array $rows, bool $checks = false, bool $actions = false): void
{
    ?>
    <div class="table-wrap material-table-mobile-hide">
        <table class="data-table" id="copyTable">
            <thead>
            <tr>
                <?php if ($checks): ?><th><input type="checkbox" data-check-all></th><?php endif; ?>
                <?php foreach (['NO.', '类别', 'ITEM NAME', '中文名', '尺寸规格', '型号', '材质', '单位', '厂牌', '供应商', '状态'] as $head): ?>
                    <th><?= e($head) ?></th>
                <?php endforeach; ?>
                <?php if ($actions): ?><th>操作</th><?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr class="clickable-row" data-href="admin_material_detail.php?id=<?= e($row['id']) ?>" tabindex="0">
                    <?php if ($checks): ?><td><input type="checkbox" name="material_ids[]" value="<?= e($row['id']) ?>" data-row-check form="bomSelection" aria-label="选择材料"></td><?php endif; ?>
                    <?php foreach (['no', 'category', 'item_name', 'name_cn', 'spec', 'model', 'material', 'unit', 'brand', 'supplier', 'status'] as $field): ?>
                        <td><?= e($row[$field] ?? '') ?></td>
                    <?php endforeach; ?>
                    <?php if ($actions): ?>
                        <td class="row-actions">
                            <a class="mini-btn" href="admin_material_edit.php?id=<?= e($row['id']) ?>">更改</a>
                            <form method="post" action="admin_material_edit.php" onsubmit="return confirm('确定删除这条材料吗？Demo 会直接删除。')">
                                <input type="hidden" name="action" value="delete_material">
                                <input type="hidden" name="id" value="<?= e($row['id']) ?>">
                                <button class="mini-btn danger" type="submit">删除</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
                <tr><td class="empty" colspan="<?= 11 + ($checks ? 1 : 0) + ($actions ? 1 : 0) ?>">没有符合条件的资料。</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mobile-card-list">
        <?php foreach ($rows as $row): ?>
            <article class="card clickable-row" data-href="admin_material_detail.php?id=<?= e($row['id']) ?>" tabindex="0">
                <h3><?= e($row['name_cn'] ?: $row['item_name']) ?></h3>
                <p><?= e($row['model']) ?> / <?= e($row['brand']) ?> / <?= e($row['supplier']) ?></p>
                <?php if ($actions): ?><p><a class="mini-btn" href="admin_material_edit.php?id=<?= e($row['id']) ?>">更改</a></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
    <?php
}

function render_material_form(array $material = []): void
{
    $existingMaterials = materials_all();
    $comboFields = ['no', 'category', 'item_name', 'name_cn', 'spec', 'model', 'material', 'unit', 'brand', 'supplier', 'status'];
    ?>
    <form method="post" enctype="multipart/form-data" class="admin-panel">
        <input type="hidden" name="action" value="save_material">
        <input type="hidden" name="id" value="<?= e($material['id'] ?? '') ?>">
        <p class="notice">这些字段可以直接手动填写，也可以点击输入框后从已有资料下拉选项中选择。</p>
        <div class="form-grid">
            <?php foreach (material_fields() as $field => $label): ?>
                <?php if ($field === 'notes'): ?>
                    <label class="wide"><span><?= e($label) ?></span><textarea name="<?= e($field) ?>" rows="4"><?= e($material[$field] ?? '') ?></textarea></label>
                <?php elseif (in_array($field, $comboFields, true)): ?>
                    <label>
                        <span><?= e($label) ?>（可输入 / 可选择）</span>
                        <div class="combo-field">
                            <input name="<?= e($field) ?>" value="<?= e($material[$field] ?? '') ?>" autocomplete="off">
                            <select data-fill-input aria-label="选择已有<?= e($label) ?>">
                                <option value="">选择已有</option>
                            <?php foreach (options_for($existingMaterials, $field) as $option): ?>
                                    <option value="<?= e($option) ?>"><?= e($option) ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </label>
                <?php else: ?>
                    <label><span><?= e($label) ?></span><input name="<?= e($field) ?>" value="<?= e($material[$field] ?? '') ?>"></label>
                <?php endif; ?>
            <?php endforeach; ?>
            <label class="wide"><span>上传本地图片（可多选，仅允许图片文件）</span><input type="file" name="image_files[]" accept="image/*" multiple></label>
        </div>
        <div class="image-list">
            <?php foreach (($material['images'] ?? []) as $image): ?>
                <?php if (str_starts_with($image, 'uploads/')): ?>
                    <img src="<?= e(path_url($image)) ?>" alt="材料图片">
                <?php else: ?>
                    <div class="image-tile">旧图片链接已停用</div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="form-actions sticky-actions">
            <button class="primary-btn" type="submit">保存资料</button>
            <a class="ghost-btn" href="admin_material_list.php">返回列表</a>
        </div>
    </form>
    <?php
}

function render_bom_table(array $bom): void
{
    $rows = bom_export_rows($bom);
    ?>
    <div class="table-wrap">
        <table class="data-table" id="copyTable">
            <thead><tr>
                <?php foreach ($rows ? array_keys($rows[0]) : ['NO.', 'ITEM NAME', '中文名'] as $head): ?>
                    <th><?= e($head) ?></th>
                <?php endforeach; ?>
            </tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr><?php foreach ($row as $value): ?><td><?= e($value) ?></td><?php endforeach; ?></tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td class="empty">这个 BOM 暂无明细。</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
