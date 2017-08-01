-- --------------------------------------------------------
-- Host:                         192.168.252.129
-- Server version:               5.5.33-log - Source distribution
-- Server OS:                    Linux
-- HeidiSQL Version:             9.4.0.5174
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for poemip
CREATE DATABASE IF NOT EXISTS `poemip` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `poemip`;

-- Dumping structure for table poemip.tb_equipment
CREATE TABLE IF NOT EXISTS `tb_equipment` (
  `equipment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `equipment_name` varchar(128) NOT NULL COMMENT '设备名称',
  `equipment_desc` varchar(512) NOT NULL DEFAULT '‘’' COMMENT '设备描述',
  `equipment_model` varchar(128) NOT NULL COMMENT '设备型号',
  `equipment_spec` varchar(256) NOT NULL COMMENT '设备规格',
  `equipment_param` varchar(512) NOT NULL COMMENT '设备参数',
  `create_date` datetime NOT NULL COMMENT '设备创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建该设备的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该设备的用户名称',
  `last_update` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`equipment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='设备信息表';

-- Dumping data for table poemip.tb_equipment: 9 rows
DELETE FROM `tb_equipment`;
/*!40000 ALTER TABLE `tb_equipment` DISABLE KEYS */;
INSERT INTO `tb_equipment` (`equipment_id`, `equipment_name`, `equipment_desc`, `equipment_model`, `equipment_spec`, `equipment_param`, `create_date`, `user_id`, `user_name`, `last_update`) VALUES
	(4, '设备1名称', '设备1描述', '设备1型号', '设备1规格', '设备1参数', '2017-05-23 08:48:53', 1, '用户1名称', NULL),
	(5, '设备2名称', '设备2描述', '设备2型号', '设备2规格', '设备2参数', '2017-05-24 10:03:11', 1, '用户1名称', NULL),
	(6, '设备3名称', '设备3描述', '设备3型号', '设备3规格', '设备3参数', '2017-05-25 17:03:11', 1, '用户1名称', '2017-06-26 14:36:57'),
	(7, '设备4名称', '设备4描述', '设备4型号', '设备4规格', '设备4参数', '2017-05-31 16:51:11', 1, '用户1名称', NULL),
	(8, '设备5名称', '设备5描述', '设备5型号', '设备5规格', '设备5参数', '2017-06-22 10:17:02', 1, '用户1名称', '2017-06-22 10:17:02'),
	(9, '设备6名称', '设备6设备描述', '设备6型号', '设备6规格', '设备6参数', '2017-06-22 13:43:01', 1, '用户1名称', '2017-06-22 13:43:01'),
	(10, '项目55的设备', '5adfadfadf', '2adsfasdf', '3adsfasdf', '4asdfadsf', '2017-06-23 15:14:08', 1, '用户1名称', '2017-06-26 14:39:13'),
	(11, '项目名称665的设备', 'sdfasdfasdfasdf', 'adfadf', 'adfsasdf', 'asdfa', '2017-06-23 15:22:26', 1, '用户1名称', '2017-06-26 11:29:16'),
	(12, '设备名', 'fasdfadfafds', 'sdfasdfas', 'dfasdfsadf', 'asdfasdfasd', '2017-06-23 15:22:59', 1, '用户1名称', '2017-06-26 14:38:50');
/*!40000 ALTER TABLE `tb_equipment` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_issue
CREATE TABLE IF NOT EXISTS `tb_issue` (
  `issue_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `issue_desc` varchar(1024) NOT NULL COMMENT '问题描述',
  `issue_voucher_no` varchar(128) NOT NULL COMMENT '问题所属凭单号',
  `issue_voucher_date` date NOT NULL COMMENT '问题所属凭单日期',
  `issue_causes` varchar(1024) NOT NULL COMMENT '问题成因分析',
  `issue_solve` varchar(1024) NOT NULL COMMENT '问题解决过程',
  `issue_type` tinyint(4) NOT NULL COMMENT '问题所属阶段 0:招标/签约阶段 1:设计阶段 2:生产阶段 3:运输阶段 4:现场存储阶段 5:安装测试阶段 6:验收阶段 7:质保阶段',
  `supplier_id` int(11) NOT NULL COMMENT '问题所属供应商ID',
  `equipment_id` int(11) NOT NULL COMMENT '问题所在设备ID',
  `project_id` int(11) NOT NULL COMMENT '问题所在项目ID',
  `create_date` datetime NOT NULL COMMENT '问题创建日期',
  `user_id` int(11) NOT NULL COMMENT '提交该问题的用户ID',
  `user_name` varchar(64) NOT NULL COMMENT '提交该问题的用户名称',
  `last_update` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`issue_id`),
  KEY `issue_userid` (`user_id`),
  KEY `issue_voucher_no` (`issue_voucher_no`),
  KEY `issue_type_supplier_equipment_project_id` (`issue_type`,`supplier_id`,`equipment_id`,`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='问题信息表';

-- Dumping data for table poemip.tb_issue: 6 rows
DELETE FROM `tb_issue`;
/*!40000 ALTER TABLE `tb_issue` DISABLE KEYS */;
INSERT INTO `tb_issue` (`issue_id`, `issue_desc`, `issue_voucher_no`, `issue_voucher_date`, `issue_causes`, `issue_solve`, `issue_type`, `supplier_id`, `equipment_id`, `project_id`, `create_date`, `user_id`, `user_name`, `last_update`) VALUES
	(2, '问题1描述', '问题1所属凭单号', '2017-02-13', '问题1成因分析', '问题1解决过程', 3, 3, 4, 9, '2017-05-24 09:20:17', 1, '用户1名称', '2017-06-26 12:31:12'),
	(3, '问题2描述', '问题2所属凭单号', '2017-03-15', '问题2成因分析', '问题2解决过程', 2, 4, 5, 9, '2017-05-24 09:21:06', 1, '用户1名称', '2017-06-26 14:38:10'),
	(4, '问题3描述修改', '问题3所属凭单号修改', '2017-05-10', '问题3成因分析修改', '问题3解决过程修改', 3, 5, 7, 10, '2017-05-31 17:31:42', 1, '用户1名称', '2017-06-02 18:49:21'),
	(5, '问题4描述', '问题4所属凭单号', '2017-05-10', '问题4成因分析', '问题4解决过程', 4, 6, 8, 11, '2017-06-22 12:18:23', 1, '用户1名称', '2017-06-22 12:18:23'),
	(6, '问题5描述', '问题5所属凭单号', '2017-04-12', '问题5成因分析', '问题5解决过程', 6, 4, 4, 9, '2017-06-22 12:25:13', 1, '用户1名称', '2017-06-22 12:25:13'),
	(7, '问题6描述', '问题7所属凭单号', '2017-04-12', '问题6成因分析', '成因分析解决过程', 5, 7, 9, 10, '2017-06-22 13:43:59', 1, '用户1名称', '2017-06-22 13:43:59');
/*!40000 ALTER TABLE `tb_issue` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_project
CREATE TABLE IF NOT EXISTS `tb_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `project_name` varchar(128) NOT NULL COMMENT '项目名称',
  `project_desc` varchar(512) NOT NULL DEFAULT '‘’' COMMENT '项目描述',
  `create_date` datetime NOT NULL COMMENT '项目创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建该项目的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该项目的用户名称',
  `last_update` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='项目信息表';

-- Dumping data for table poemip.tb_project: 5 rows
DELETE FROM `tb_project`;
/*!40000 ALTER TABLE `tb_project` DISABLE KEYS */;
INSERT INTO `tb_project` (`project_id`, `project_name`, `project_desc`, `create_date`, `user_id`, `user_name`, `last_update`) VALUES
	(9, '项目名称2', '项目2的描述信息', '2017-05-24 02:37:10', 2, '用户2名称', NULL),
	(10, '项目名称3', '项目3的描述信息修改', '2017-05-31 16:27:25', 1, '用户1名称', '2017-06-26 14:37:50'),
	(11, '项目名称4', '项目4的描述信息', '2017-06-14 13:57:37', 1, '用户1名称', '2017-06-14 13:57:37'),
	(12, '项目名称55', '项目5的描述信息', '2017-06-14 14:04:26', 1, '用户1名称', '2017-06-23 18:03:26'),
	(22, '项目名称665', '项目描述项目描述项目描述项目描述项目描述项目描述项目描述\n项目描述项目描述项目描述项目描述项目描述\n项目描述项目描述项目描述项目描述项目描述项目描述项目描述项目描述项目描述项目描述', '2017-06-22 10:01:15', 1, '用户1名称', '2017-06-26 11:05:42');
/*!40000 ALTER TABLE `tb_project` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_project_equipment
CREATE TABLE IF NOT EXISTS `tb_project_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `equipment_id` int(11) NOT NULL COMMENT '设备ID',
  `project_id` int(11) NOT NULL COMMENT '所在项目ID',
  `create_date` datetime NOT NULL COMMENT '创建关联日期',
  `user_id` int(11) NOT NULL COMMENT '创建该关联的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该关联的用户名称',
  PRIMARY KEY (`id`),
  KEY `project_id_equipment_id` (`project_id`,`equipment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='项目和设备对照表';

-- Dumping data for table poemip.tb_project_equipment: 13 rows
DELETE FROM `tb_project_equipment`;
/*!40000 ALTER TABLE `tb_project_equipment` DISABLE KEYS */;
INSERT INTO `tb_project_equipment` (`id`, `equipment_id`, `project_id`, `create_date`, `user_id`, `user_name`) VALUES
	(2, 4, 9, '2017-05-23 08:48:53', 1, '用户1名称'),
	(3, 5, 9, '2017-05-24 10:02:05', 1, '用户1名称'),
	(9, 7, 10, '2017-05-31 16:51:11', 1, '用户1名称'),
	(8, 6, 9, '2017-05-25 17:29:40', 1, '用户1名称'),
	(10, 4, 10, '2017-06-01 15:48:42', 1, '用户1名称'),
	(11, 8, 11, '2017-06-22 10:17:02', 1, '用户1名称'),
	(12, 9, 10, '2017-06-22 13:43:01', 1, '用户1名称'),
	(13, 10, 12, '2017-06-23 15:14:08', 1, '用户1名称'),
	(14, 11, 22, '2017-06-23 15:22:26', 1, '用户1名称'),
	(15, 12, 12, '2017-06-23 15:22:59', 1, '用户1名称'),
	(16, 10, 11, '2017-06-23 15:58:30', 1, '用户1名称'),
	(17, 10, 22, '2017-06-23 16:01:50', 1, '用户1名称'),
	(18, 12, 11, '2017-06-23 16:02:00', 1, '用户1名称');
/*!40000 ALTER TABLE `tb_project_equipment` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_project_equipment_supplier
CREATE TABLE IF NOT EXISTS `tb_project_equipment_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `supplier_id` int(11) NOT NULL COMMENT '供应商ID',
  `equipment_id` int(11) NOT NULL COMMENT '设备ID',
  `project_id` int(11) NOT NULL COMMENT '所在项目ID',
  `create_date` datetime NOT NULL COMMENT '创建关联日期',
  `user_id` int(11) NOT NULL COMMENT '创建该关联的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该关联的用户名称',
  PRIMARY KEY (`id`),
  KEY `project_id_equipment_id` (`project_id`,`equipment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='项目和供应商对照表';

-- Dumping data for table poemip.tb_project_equipment_supplier: 9 rows
DELETE FROM `tb_project_equipment_supplier`;
/*!40000 ALTER TABLE `tb_project_equipment_supplier` DISABLE KEYS */;
INSERT INTO `tb_project_equipment_supplier` (`id`, `supplier_id`, `equipment_id`, `project_id`, `create_date`, `user_id`, `user_name`) VALUES
	(1, 3, 4, 9, '2017-05-24 06:20:11', 1, '用户1名称'),
	(2, 4, 5, 9, '2017-05-24 09:01:49', 1, '用户1名称'),
	(3, 4, 6, 9, '2017-05-25 17:49:29', 1, '用户1名称'),
	(4, 5, 7, 10, '2017-05-31 17:06:28', 1, '用户1名称'),
	(5, 3, 4, 10, '2017-06-01 15:54:50', 1, '用户1名称'),
	(6, 4, 4, 9, '2017-06-19 15:20:07', 1, '用户1名称'),
	(7, 6, 8, 11, '2017-06-22 10:55:27', 1, '用户1名称'),
	(8, 7, 9, 10, '2017-06-22 13:43:30', 1, '用户1名称'),
	(9, 4, 8, 11, '2017-06-23 16:06:58', 1, '用户1名称');
/*!40000 ALTER TABLE `tb_project_equipment_supplier` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_supplier
CREATE TABLE IF NOT EXISTS `tb_supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `supplier_name` varchar(128) NOT NULL COMMENT '供应商名称',
  `supplier_desc` varchar(256) NOT NULL DEFAULT '‘’' COMMENT '供应商描述',
  `create_date` datetime NOT NULL COMMENT '供应商创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建该供应商的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该供应商的用户名称',
  `last_update` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='供应商信息表';

-- Dumping data for table poemip.tb_supplier: 5 rows
DELETE FROM `tb_supplier`;
/*!40000 ALTER TABLE `tb_supplier` DISABLE KEYS */;
INSERT INTO `tb_supplier` (`supplier_id`, `supplier_name`, `supplier_desc`, `create_date`, `user_id`, `user_name`, `last_update`) VALUES
	(3, '供应商1名称', '供应商1描述', '2017-05-24 06:20:11', 1, '用户1名称', NULL),
	(4, '供应商2的名称', '供应商2描述修改', '2017-05-24 09:01:49', 1, '用户1名称', '2017-06-26 14:38:29'),
	(5, '供应商3名称', '供应商3描述', '2017-05-31 17:06:28', 1, '用户1名称', NULL),
	(6, '供应商4的名称', '供应商4描述', '2017-06-22 10:55:27', 1, '用户1名称', '2017-06-26 11:46:35'),
	(7, '供应商5名称', '供应商5供应商描述', '2017-06-22 13:43:30', 1, '用户1名称', '2017-06-22 13:43:30');
/*!40000 ALTER TABLE `tb_supplier` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
