<?php
/*
 * 本文件负责 MySQL 数据读写和业务数据处理。
 * 页面仍调用这些函数，底层统一读写 MySQL，不再使用 JSON 作为数据库。
 */
require_once __DIR__ . '/db.php';

function store_init(): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    $pdo = db();
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS materials (
            id INT AUTO_INCREMENT PRIMARY KEY,
            no VARCHAR(80) DEFAULT '',
            category VARCHAR(120) DEFAULT '',
            item_name VARCHAR(255) DEFAULT '',
            name_cn VARCHAR(255) DEFAULT '',
            spec TEXT,
            model VARCHAR(120) DEFAULT '',
            material VARCHAR(120) DEFAULT '',
            unit VARCHAR(40) DEFAULT '',
            brand VARCHAR(120) DEFAULT '',
            supplier VARCHAR(120) DEFAULT '',
            status VARCHAR(80) DEFAULT '',
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS material_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            material_id INT NOT NULL,
            image_url TEXT NOT NULL,
            sort_order INT DEFAULT 0,
            INDEX (material_id),
            CONSTRAINT fk_material_images_material
                FOREIGN KEY (material_id) REFERENCES materials(id)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS boms (
            id INT AUTO_INCREMENT PRIMARY KEY,
            bom_no VARCHAR(120) NOT NULL,
            project_name VARCHAR(255) DEFAULT '',
            customer_name VARCHAR(255) DEFAULT '',
            machine_name VARCHAR(255) DEFAULT '',
            status VARCHAR(80) DEFAULT '',
            note TEXT,
            created_at DATE NOT NULL,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bom_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            bom_id INT NOT NULL,
            material_id INT NOT NULL,
            qty DECIMAL(12,2) DEFAULT 0,
            machine_qty DECIMAL(12,2) DEFAULT 1,
            spare_qty DECIMAL(12,2) DEFAULT 0,
            order_status VARCHAR(80) DEFAULT '',
            stock_status VARCHAR(80) DEFAULT '',
            used_status VARCHAR(80) DEFAULT '',
            position_ref VARCHAR(120) DEFAULT '',
            remark TEXT,
            sort_order INT DEFAULT 0,
            INDEX (bom_id),
            INDEX (material_id),
            CONSTRAINT fk_bom_items_bom
                FOREIGN KEY (bom_id) REFERENCES boms(id)
                ON DELETE CASCADE,
            CONSTRAINT fk_bom_items_material
                FOREIGN KEY (material_id) REFERENCES materials(id)
                ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            action VARCHAR(255) NOT NULL,
            target VARCHAR(255) DEFAULT '',
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $ready = true;
    seed_mysql_if_empty();
}

function seed_mysql_if_empty(): void
{
    $pdo = db();
    $materialCount = (int)$pdo->query('SELECT COUNT(*) FROM materials')->fetchColumn();
    if ($materialCount === 0) {
        materials_save(seed_materials());
    }

    $bomCount = (int)$pdo->query('SELECT COUNT(*) FROM boms')->fetchColumn();
    if ($bomCount === 0) {
        boms_save(seed_boms());
    }

    $logCount = (int)$pdo->query('SELECT COUNT(*) FROM admin_logs')->fetchColumn();
    if ($logCount === 0) {
        add_log('初始化 MySQL Demo 数据', 'system');
    }
}

function seed_materials(): array
{
    return [
        ['id' => 1, 'no' => 'D-001', 'category' => '管路材料', 'item_name' => 'A Material Pipe', 'name_cn' => 'A材料管', 'spec' => '1 inch demo spec', 'model' => 'AM-P100', 'material' => 'PFA', 'unit' => 'M', 'brand' => 'A社', 'supplier' => 'A供应商', 'status' => '可采购', 'notes' => '演示用材料，可替换为真实资料。', 'images' => []],
        ['id' => 2, 'no' => 'D-002', 'category' => '阀件', 'item_name' => 'B Valve Set', 'name_cn' => 'B阀件组', 'spec' => 'Manual valve demo spec', 'model' => 'BV-20', 'material' => 'PP', 'unit' => 'EA', 'brand' => 'B社', 'supplier' => 'B供应商', 'status' => '待确认', 'notes' => '参数未确认时可以先留空。', 'images' => []],
        ['id' => 3, 'no' => 'D-003', 'category' => '电控', 'item_name' => 'C Sensor', 'name_cn' => 'C感测器', 'spec' => 'M12 demo sensor', 'model' => 'CS-12', 'material' => 'Alloy', 'unit' => 'EA', 'brand' => 'C社', 'supplier' => 'A供应商', 'status' => '已确认', 'notes' => '用于展示条件并选查询。', 'images' => []],
        ['id' => 4, 'no' => 'D-004', 'category' => '支架结构', 'item_name' => 'D Support Frame', 'name_cn' => 'D支架', 'spec' => 'Demo drawing size', 'model' => 'DSF-01', 'material' => 'SUS304', 'unit' => 'SET', 'brand' => 'A社', 'supplier' => 'C供应商', 'status' => '可采购', 'notes' => '结构件示例。', 'images' => []],
    ];
}

function seed_boms(): array
{
    return [
        ['id' => 1, 'bom_no' => 'BOM-A-20260523', 'project_name' => 'A项目', 'customer_name' => 'A社', 'machine_name' => 'A机型', 'status' => 'Demo', 'note' => '虚拟 BOM 示例。', 'created_at' => date('Y-m-d'), 'items' => [
            ['id' => 1, 'material_id' => 1, 'qty' => 10, 'machine_qty' => 1, 'spare_qty' => 2, 'order_status' => '未下订', 'stock_status' => '无库存', 'used_status' => '未领料', 'position_ref' => 'P-01', 'remark' => 'A项目用'],
            ['id' => 2, 'material_id' => 2, 'qty' => 4, 'machine_qty' => 1, 'spare_qty' => 1, 'order_status' => '待确认', 'stock_status' => '无库存', 'used_status' => '未领料', 'position_ref' => 'V-01', 'remark' => 'B阀件'],
        ]],
    ];
}

function material_fields(): array
{
    return [
        'no' => 'NO.',
        'category' => '类别',
        'item_name' => 'ITEM NAME',
        'name_cn' => '中文名',
        'spec' => '尺寸规格',
        'model' => '型号',
        'material' => '材质',
        'unit' => '单位',
        'brand' => '厂牌',
        'supplier' => '供应商',
        'status' => '状态',
        'notes' => '备注',
    ];
}

function normalize_materials(array $rows): array
{
    foreach ($rows as &$row) {
        if (isset($row['cn_name']) && !isset($row['name_cn'])) {
            $row['name_cn'] = $row['cn_name'];
        }
        $row += ['id' => null, 'no' => '', 'category' => '', 'item_name' => '', 'name_cn' => '', 'spec' => '', 'model' => '', 'material' => '', 'unit' => '', 'brand' => '', 'supplier' => '', 'status' => '', 'notes' => '', 'images' => []];
        if (!is_array($row['images'])) {
            $row['images'] = [];
        }
    }
    return array_values($rows);
}

function materials_all(): array
{
    store_init();
    $pdo = db();
    $rows = $pdo->query('SELECT * FROM materials ORDER BY id ASC')->fetchAll();
    $stmt = $pdo->query('SELECT material_id, image_url FROM material_images ORDER BY material_id ASC, sort_order ASC, id ASC');
    $images = [];
    foreach ($stmt->fetchAll() as $image) {
        $images[(int)$image['material_id']][] = $image['image_url'];
    }
    foreach ($rows as &$row) {
        $row['id'] = (int)$row['id'];
        $row['images'] = $images[(int)$row['id']] ?? [];
    }
    return normalize_materials($rows);
}

function materials_save(array $rows): void
{
    store_init();
    $pdo = db();
    $rows = normalize_materials($rows);
    $pdo->beginTransaction();
    try {
        $pdo->exec('DELETE FROM material_images');
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        $pdo->exec('DELETE FROM materials');
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

        $materialSql = 'INSERT INTO materials (id, no, category, item_name, name_cn, spec, model, material, unit, brand, supplier, status, notes)
            VALUES (:id, :no, :category, :item_name, :name_cn, :spec, :model, :material, :unit, :brand, :supplier, :status, :notes)';
        $materialStmt = $pdo->prepare($materialSql);
        $imageStmt = $pdo->prepare('INSERT INTO material_images (material_id, image_url, sort_order) VALUES (:material_id, :image_url, :sort_order)');

        foreach ($rows as $row) {
            $id = (int)($row['id'] ?: 0);
            if ($id > 0) {
                $materialStmt->bindValue(':id', $id, PDO::PARAM_INT);
            } else {
                $materialStmt->bindValue(':id', null, PDO::PARAM_NULL);
            }
            foreach (['no', 'category', 'item_name', 'name_cn', 'spec', 'model', 'material', 'unit', 'brand', 'supplier', 'status', 'notes'] as $field) {
                $materialStmt->bindValue(':' . $field, (string)($row[$field] ?? ''));
            }
            $materialStmt->execute();
            $materialId = $id > 0 ? $id : (int)$pdo->lastInsertId();
            foreach (($row['images'] ?? []) as $sort => $image) {
                $image = trim((string)$image);
                if ($image === '') {
                    continue;
                }
                $imageStmt->execute([':material_id' => $materialId, ':image_url' => $image, ':sort_order' => (int)$sort]);
            }
        }
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function boms_all(): array
{
    store_init();
    $pdo = db();
    $boms = $pdo->query('SELECT * FROM boms ORDER BY id ASC')->fetchAll();
    $itemStmt = $pdo->query('SELECT * FROM bom_items ORDER BY bom_id ASC, sort_order ASC, id ASC');
    $items = [];
    foreach ($itemStmt->fetchAll() as $item) {
        $item['id'] = (int)$item['id'];
        $item['material_id'] = (int)$item['material_id'];
        $item['qty'] = (float)$item['qty'];
        $item['machine_qty'] = (float)$item['machine_qty'];
        $item['spare_qty'] = (float)$item['spare_qty'];
        $items[(int)$item['bom_id']][] = $item;
    }
    foreach ($boms as &$bom) {
        $bom['id'] = (int)$bom['id'];
        $bom['items'] = $items[(int)$bom['id']] ?? [];
    }
    return $boms;
}

function boms_save(array $rows): void
{
    store_init();
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $pdo->exec('DELETE FROM bom_items');
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        $pdo->exec('DELETE FROM boms');
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

        $bomStmt = $pdo->prepare('INSERT INTO boms (id, bom_no, project_name, customer_name, machine_name, status, note, created_at)
            VALUES (:id, :bom_no, :project_name, :customer_name, :machine_name, :status, :note, :created_at)');
        $itemStmt = $pdo->prepare('INSERT INTO bom_items (bom_id, material_id, qty, machine_qty, spare_qty, order_status, stock_status, used_status, position_ref, remark, sort_order)
            VALUES (:bom_id, :material_id, :qty, :machine_qty, :spare_qty, :order_status, :stock_status, :used_status, :position_ref, :remark, :sort_order)');

        foreach ($rows as $bom) {
            $bomId = (int)($bom['id'] ?? 0);
            $bomStmt->execute([
                ':id' => $bomId ?: null,
                ':bom_no' => (string)($bom['bom_no'] ?? ''),
                ':project_name' => (string)($bom['project_name'] ?? ''),
                ':customer_name' => (string)($bom['customer_name'] ?? ''),
                ':machine_name' => (string)($bom['machine_name'] ?? ''),
                ':status' => (string)($bom['status'] ?? ''),
                ':note' => (string)($bom['note'] ?? ''),
                ':created_at' => (string)($bom['created_at'] ?? date('Y-m-d')),
            ]);
            $actualBomId = $bomId ?: (int)$pdo->lastInsertId();
            foreach (($bom['items'] ?? []) as $sort => $item) {
                $materialId = (int)($item['material_id'] ?? 0);
                if ($materialId <= 0 || find_by_id(materials_all(), $materialId) === null) {
                    continue;
                }
                $itemStmt->execute([
                    ':bom_id' => $actualBomId,
                    ':material_id' => $materialId,
                    ':qty' => (float)($item['qty'] ?? 0),
                    ':machine_qty' => (float)($item['machine_qty'] ?? 1),
                    ':spare_qty' => (float)($item['spare_qty'] ?? 0),
                    ':order_status' => (string)($item['order_status'] ?? ''),
                    ':stock_status' => (string)($item['stock_status'] ?? ''),
                    ':used_status' => (string)($item['used_status'] ?? ''),
                    ':position_ref' => (string)($item['position_ref'] ?? ''),
                    ':remark' => (string)($item['remark'] ?? ''),
                    ':sort_order' => (int)$sort,
                ]);
            }
        }
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function logs_all(): array
{
    store_init();
    return db()->query('SELECT * FROM admin_logs ORDER BY id ASC')->fetchAll();
}

function add_log(string $action, string $target): void
{
    store_init();
    $stmt = db()->prepare('INSERT INTO admin_logs (action, target, created_at) VALUES (:action, :target, :created_at)');
    $stmt->execute([':action' => $action, ':target' => $target, ':created_at' => date('Y-m-d H:i:s')]);
}

function next_id(array $rows): int
{
    $ids = array_map(static fn($row) => (int)($row['id'] ?? 0), $rows);
    return $ids ? max($ids) + 1 : 1;
}

function find_by_id(array $rows, int $id): ?array
{
    foreach ($rows as $row) {
        if ((int)($row['id'] ?? 0) === $id) {
            return $row;
        }
    }
    return null;
}

function options_for(array $rows, string $field): array
{
    $values = [];
    foreach ($rows as $row) {
        $value = trim((string)($row[$field] ?? ''));
        if ($value !== '') {
            $values[$value] = true;
        }
    }
    $options = array_keys($values);
    sort($options, SORT_NATURAL);
    return $options;
}

function filter_materials(array $materials, array $query): array
{
    return array_values(array_filter($materials, static function (array $row) use ($query): bool {
        foreach (['brand', 'supplier', 'material', 'unit', 'category', 'status'] as $field) {
            $selected = $query[$field] ?? [];
            $selected = is_array($selected) ? array_values(array_filter($selected, 'strlen')) : array_filter([(string)$selected], 'strlen');
            if ($selected && !in_array((string)($row[$field] ?? ''), $selected, true)) {
                return false;
            }
        }
        $keyword = trim((string)($query['keyword'] ?? ''));
        if ($keyword !== '') {
            $haystack = implode(' ', [$row['item_name'] ?? '', $row['name_cn'] ?? '', $row['model'] ?? '', $row['spec'] ?? '', $row['notes'] ?? '']);
            if (mb_stripos($haystack, $keyword, 0, 'UTF-8') === false) {
                return false;
            }
        }
        return true;
    }));
}

function material_from_post(array $base = []): array
{
    $row = $base;
    foreach (material_fields() as $field => $label) {
        $row[$field] = trim((string)($_POST[$field] ?? ''));
    }
    $images = [];
    foreach (($base['images'] ?? []) as $image) {
        $image = trim((string)$image);
        if ($image !== '' && str_starts_with($image, 'uploads/')) {
            $images[] = $image;
        }
    }
    foreach (handle_uploaded_images() as $image) {
        $images[] = $image;
    }
    $row['images'] = array_values(array_unique($images));
    return $row;
}

function handle_uploaded_images(): array
{
    if (empty($_FILES['image_files']['name']) || !is_array($_FILES['image_files']['name'])) {
        return [];
    }
    $saved = [];
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    foreach ($_FILES['image_files']['name'] as $index => $name) {
        if (($_FILES['image_files']['error'][$index] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            continue;
        }
        $tmp = $_FILES['image_files']['tmp_name'][$index] ?? '';
        $mime = uploaded_image_mime((string)$tmp);
        if (!isset($allowed[$mime])) {
            continue;
        }
        $file = 'image-' . date('YmdHis') . '-' . bin2hex(random_bytes(3)) . '.' . $allowed[$mime];
        if (move_uploaded_file($tmp, UPLOAD_DIR . '/' . $file)) {
            $saved[] = 'uploads/' . $file;
        }
    }
    return $saved;
}

function uploaded_image_mime(string $tmp): string
{
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return '';
    }

    if (function_exists('mime_content_type')) {
        $mime = @mime_content_type($tmp);
        if (is_string($mime) && $mime !== '') {
            return $mime;
        }
    }

    $info = @getimagesize($tmp);
    return is_array($info) && isset($info['mime']) ? (string)$info['mime'] : '';
}

function material_map(): array
{
    $map = [];
    foreach (materials_all() as $material) {
        $map[(int)$material['id']] = $material;
    }
    return $map;
}

function bom_total(array $item): float
{
    return ((float)($item['qty'] ?? 0) * max(1, (float)($item['machine_qty'] ?? 1))) + (float)($item['spare_qty'] ?? 0);
}

function bom_export_rows(array $bom): array
{
    $materials = material_map();
    $rows = [];
    foreach (($bom['items'] ?? []) as $index => $item) {
        $m = $materials[(int)($item['material_id'] ?? 0)] ?? [];
        $rows[] = [
            'NO.' => (string)($index + 1),
            'ITEM NAME' => $m['item_name'] ?? '',
            '中文名' => $m['name_cn'] ?? '',
            '尺寸规格' => $m['spec'] ?? '',
            '型号' => $m['model'] ?? '',
            '材质' => $m['material'] ?? '',
            '单位' => $m['unit'] ?? '',
            '数量' => $item['qty'] ?? '',
            '机台数量' => $item['machine_qty'] ?? '',
            '备用数量' => $item['spare_qty'] ?? '',
            '总数量' => bom_total($item),
            '已下订' => $item['order_status'] ?? '',
            '库存' => $item['stock_status'] ?? '',
            '领料/安装' => $item['used_status'] ?? '',
            '厂牌' => $m['brand'] ?? '',
            '供应商' => $m['supplier'] ?? '',
            '备注' => $item['remark'] ?? '',
            '位置参照' => $item['position_ref'] ?? '',
        ];
    }
    return $rows;
}

function export_csv(array $rows, string $filename): void
{
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    echo "\xEF\xBB\xBF";
    $out = fopen('php://output', 'w');
    if ($rows) {
        fputcsv($out, array_keys($rows[0]));
        foreach ($rows as $row) {
            fputcsv($out, array_values($row));
        }
    }
    fclose($out);
    exit;
}

function zip_dos_time(): array
{
    $time = getdate();
    $dosTime = (($time['hours'] & 0x1f) << 11) | (($time['minutes'] & 0x3f) << 5) | (intdiv((int)$time['seconds'], 2) & 0x1f);
    $dosDate = ((($time['year'] - 1980) & 0x7f) << 9) | (($time['mon'] & 0x0f) << 5) | ($time['mday'] & 0x1f);
    return [(int)$dosTime, (int)$dosDate];
}

function build_zip(array $files): string
{
    [$dosTime, $dosDate] = zip_dos_time();
    $body = '';
    $central = '';
    $offset = 0;
    foreach ($files as $name => $content) {
        $name = str_replace('\\', '/', (string)$name);
        $content = (string)$content;
        $crc = crc32($content);
        $size = strlen($content);
        $nameLength = strlen($name);
        $local = pack('VvvvvvVVVvv', 0x04034b50, 20, 0, 0, $dosTime, $dosDate, $crc, $size, $size, $nameLength, 0) . $name . $content;
        $central .= pack('VvvvvvvVVVvvvvvVV', 0x02014b50, 20, 20, 0, 0, $dosTime, $dosDate, $crc, $size, $size, $nameLength, 0, 0, 0, 0, 0, $offset) . $name;
        $body .= $local;
        $offset += strlen($local);
    }
    $centralStart = strlen($body);
    return $body . $central . pack('VvvvvVVv', 0x06054b50, 0, 0, count($files), count($files), strlen($central), $centralStart, 0);
}

function export_xlsx(array $rows, string $filename): void
{
    $sheetRows = [];
    $rowNum = 1;
    foreach ($rows ? [array_keys($rows[0])] : [[]] as $headers) {
        $cells = [];
        foreach ($headers as $col => $value) {
            $cells[] = xlsx_cell($col + 1, $rowNum, $value);
        }
        $sheetRows[] = '<row r="' . $rowNum . '">' . implode('', $cells) . '</row>';
    }
    foreach ($rows as $row) {
        $rowNum++;
        $cells = [];
        foreach (array_values($row) as $col => $value) {
            $cells[] = xlsx_cell($col + 1, $rowNum, $value);
        }
        $sheetRows[] = '<row r="' . $rowNum . '">' . implode('', $cells) . '</row>';
    }
    $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' . implode('', $sheetRows) . '</sheetData></worksheet>';
    $xlsx = build_zip([
        '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>',
        '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
        'xl/workbook.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="BOM Demo" sheetId="1" r:id="rId1"/></sheets></workbook>',
        'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>',
        'xl/worksheets/sheet1.xml' => $sheet,
    ]);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
    header('Content-Length: ' . strlen($xlsx));
    echo $xlsx;
    exit;
}

function xlsx_cell(int $col, int $row, mixed $value): string
{
    $letters = '';
    while ($col > 0) {
        $col--;
        $letters = chr(65 + ($col % 26)) . $letters;
        $col = intdiv($col, 26);
    }
    $text = htmlspecialchars((string)$value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    return '<c r="' . $letters . $row . '" t="inlineStr"><is><t>' . $text . '</t></is></c>';
}
