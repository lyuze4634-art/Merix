-- 本文件负责正式版 MySQL 数据库结构。
-- 当前 demo 已直接使用这些核心表，不再使用 JSON 作为数据库。

CREATE DATABASE IF NOT EXISTS merix_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE merix_demo;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS material_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  material_id INT NOT NULL,
  image_url TEXT NOT NULL,
  sort_order INT DEFAULT 0,
  INDEX (material_id),
  CONSTRAINT fk_material_images_material FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  CONSTRAINT fk_bom_items_bom FOREIGN KEY (bom_id) REFERENCES boms(id) ON DELETE CASCADE,
  CONSTRAINT fk_bom_items_material FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  action VARCHAR(255) NOT NULL,
  target VARCHAR(255) DEFAULT '',
  created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
