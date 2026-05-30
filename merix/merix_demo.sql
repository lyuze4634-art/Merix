-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2026-05-23 15:36:15
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `merix_demo`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `target` varchar(255) DEFAULT '',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `action`, `target`, `created_at`) VALUES
(1, '初始化 MySQL Demo 数据', 'system', '2026-05-23 13:52:58'),
(2, '导出 CSV', 'BOM-DEMO-20260523', '2026-05-23 13:54:15'),
(3, '导出 XLSX', 'BOM-DEMO-20260523', '2026-05-23 13:54:15'),
(4, '修改材料', 'D支架', '2026-05-23 13:58:24'),
(5, '修改材料', 'D支架', '2026-05-23 13:58:54'),
(6, '新增材料', '123', '2026-05-23 13:59:28'),
(7, '???? Demo ??', 'added=4', '2026-05-23 14:57:06'),
(8, '补充硬盘 Demo 数据', 'SSD/HDD', '2026-05-23 14:57:59');

-- --------------------------------------------------------

--
-- 表的结构 `boms`
--

CREATE TABLE `boms` (
  `id` int(11) NOT NULL,
  `bom_no` varchar(120) NOT NULL,
  `project_name` varchar(255) DEFAULT '',
  `customer_name` varchar(255) DEFAULT '',
  `machine_name` varchar(255) DEFAULT '',
  `status` varchar(80) DEFAULT '',
  `note` text DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `boms`
--

INSERT INTO `boms` (`id`, `bom_no`, `project_name`, `customer_name`, `machine_name`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1, 'BOM-DEMO-20260523', 'A项目', 'A社', 'A机型', 'Demo', '从材料资料库选择材料后制作的虚拟 BOM。', '2026-05-23', '2026-05-23 20:53:59'),
(2, 'BOM-DEMO-20260523', 'A项目', 'A社', 'A机型', 'Demo', '从材料资料库选择材料后制作的虚拟 BOM。', '2026-05-23', '2026-05-23 20:53:59'),
(3, 'BOM-DEMO-20260523', 'A项目', 'A社', 'A机型', 'Demo', '从材料资料库选择材料后制作的虚拟 BOM。', '2026-05-23', '2026-05-23 20:53:59'),
(4, 'BOM-DEMO-20260523', 'A项目', 'A社', 'A机型', 'Demo', '从材料资料库选择材料后制作的虚拟 BOM。', '2026-05-23', '2026-05-23 20:53:59'),
(5, 'BOM-DEMO-20260523', 'A项目', 'A社', 'A机型', 'Demo', '从材料资料库选择材料后制作的虚拟 BOM。', '2026-05-23', '2026-05-23 20:53:59'),
(6, 'BOM-DEMO-20260523133042', '', '', '', '', '', '2026-05-23', '2026-05-23 20:53:59'),
(7, 'BOM-DEMO-20260523134029', '', '', '', '', '', '2026-05-23', '2026-05-23 20:53:59');

-- --------------------------------------------------------

--
-- 表的结构 `bom_items`
--

CREATE TABLE `bom_items` (
  `id` int(11) NOT NULL,
  `bom_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `qty` decimal(12,2) DEFAULT 0.00,
  `machine_qty` decimal(12,2) DEFAULT 1.00,
  `spare_qty` decimal(12,2) DEFAULT 0.00,
  `order_status` varchar(80) DEFAULT '',
  `stock_status` varchar(80) DEFAULT '',
  `used_status` varchar(80) DEFAULT '',
  `position_ref` varchar(120) DEFAULT '',
  `remark` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `bom_items`
--

INSERT INTO `bom_items` (`id`, `bom_id`, `material_id`, `qty`, `machine_qty`, `spare_qty`, `order_status`, `stock_status`, `used_status`, `position_ref`, `remark`, `sort_order`) VALUES
(36, 1, 1, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-1', 'Demo', 0),
(37, 1, 2, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-2', 'Demo', 1),
(38, 1, 3, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-3', 'Demo', 2),
(39, 2, 1, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-1', 'Demo', 0),
(40, 3, 5, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-5', 'Demo', 0),
(41, 4, 1, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-1', 'Demo', 0),
(42, 4, 2, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-2', 'Demo', 1),
(43, 4, 3, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-3', 'Demo', 2),
(44, 5, 2, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-2', 'Demo', 0),
(45, 5, 3, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-3', 'Demo', 1),
(46, 5, 4, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-4', 'Demo', 2),
(47, 5, 5, 1.00, 1.00, 0.00, '未下订', '无库存', '未领料', 'P-5', 'Demo', 3),
(48, 6, 4, 0.00, 0.00, 0.00, '未下订', '无库存', '未领料', '', '', 0),
(49, 7, 1, 0.00, 0.00, 0.00, '未下订', '无库存', '未领料', '', '', 0),
(50, 7, 3, 0.00, 0.00, 0.00, '未下订', '无库存', '未领料', '', '', 1),
(51, 7, 4, 0.00, 0.00, 0.00, '未下订', '无库存', '未领料', '', '', 2),
(52, 7, 5, 0.00, 0.00, 0.00, '未下订', '无库存', '未领料', '', '', 3);

-- --------------------------------------------------------

--
-- 表的结构 `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `no` varchar(80) DEFAULT '',
  `category` varchar(120) DEFAULT '',
  `item_name` varchar(255) DEFAULT '',
  `name_cn` varchar(255) DEFAULT '',
  `spec` text DEFAULT NULL,
  `model` varchar(120) DEFAULT '',
  `material` varchar(120) DEFAULT '',
  `unit` varchar(40) DEFAULT '',
  `brand` varchar(120) DEFAULT '',
  `supplier` varchar(120) DEFAULT '',
  `status` varchar(80) DEFAULT '',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `materials`
--

INSERT INTO `materials` (`id`, `no`, `category`, `item_name`, `name_cn`, `spec`, `model`, `material`, `unit`, `brand`, `supplier`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'D-001', '管路材料', 'A Material Pipe', 'A材料管', '1 inch demo spec', 'AM-P100', 'PFA', 'M', 'A社', 'A供应商', '可采购', '', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(2, 'D-002', '阀件', 'B Valve Set', 'B阀件组', 'Manual valve demo spec', 'BV-20', 'PP', 'EA', 'B社', 'B供应商', '待确认', '', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(3, 'D-003', '电控', 'C Sensor', 'C感测器', 'M12 demo sensor', 'CS-12', 'Alloy', 'EA', 'C社', 'A供应商', '已确认', '', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(4, 'D-004', '支架结构', 'D Support Frame', 'D支架', 'Demo drawing size', 'DSF-01', 'SUS304', 'SET', 'A社', 'C供应商', '待确认', '', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(5, 'D-005', '精密仪器', 'HDD', '固态硬盘', '3.5英寸', 'HDD', 'HDD', 'TB', '三星', '三星', '可采购', '测试数据', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(6, '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '123', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(7, 'SSD-001', '精密仪器', 'Solid State Drive 1TB', '固态硬盘 1TB', '1TB；2.5 inch；SATA demo spec', 'SSD-A100', 'SSD', 'EA', 'A社', 'A供应商', '可采购', 'Demo 用固态硬盘资料。', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(8, 'SSD-002', '精密仪器', 'M.2 NVMe SSD 2TB', 'M.2固态硬盘 2TB', '2TB；M.2 2280；NVMe demo spec', 'SSD-B200', 'SSD', 'EA', 'B社', 'B供应商', '待确认', 'Demo 用高速固态硬盘资料。', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(9, 'HDD-001', '精密仪器', 'Hard Disk Drive 4TB', '机械硬盘 4TB', '4TB；3.5 inch；7200rpm demo spec', 'HDD-A400', 'HDD', 'EA', 'C社', 'C供应商', '可采购', 'Demo 用机械硬盘资料。', '2026-05-23 21:57:59', '2026-05-23 21:57:59'),
(10, 'HDD-002', '精密仪器', 'Enterprise HDD 8TB', '企业级机械硬盘 8TB', '8TB；3.5 inch；enterprise demo spec', 'HDD-B800', 'HDD', 'EA', 'D社', 'A供应商', '已确认', 'Demo 用企业级机械硬盘资料。', '2026-05-23 21:57:59', '2026-05-23 21:57:59');

-- --------------------------------------------------------

--
-- 表的结构 `material_images`
--

CREATE TABLE `material_images` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `image_url` text NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `material_images`
--

INSERT INTO `material_images` (`id`, `material_id`, `image_url`, `sort_order`) VALUES
(9, 5, 'uploads/image-20260523131351-a592bb.jpg', 0);

--
-- 转储表的索引
--

--
-- 表的索引 `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `boms`
--
ALTER TABLE `boms`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `bom_items`
--
ALTER TABLE `bom_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_id` (`bom_id`),
  ADD KEY `material_id` (`material_id`);

--
-- 表的索引 `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `material_images`
--
ALTER TABLE `material_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `boms`
--
ALTER TABLE `boms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `bom_items`
--
ALTER TABLE `bom_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- 使用表AUTO_INCREMENT `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `material_images`
--
ALTER TABLE `material_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 限制导出的表
--

--
-- 限制表 `bom_items`
--
ALTER TABLE `bom_items`
  ADD CONSTRAINT `fk_bom_items_bom` FOREIGN KEY (`bom_id`) REFERENCES `boms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bom_items_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`);

--
-- 限制表 `material_images`
--
ALTER TABLE `material_images`
  ADD CONSTRAINT `fk_material_images_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
