-- --------------------------------------------------------
-- Host:                         192.168.252.129
-- Server version:               5.5.33-log - Source distribution
-- Server OS:                    Linux
-- HeidiSQL Version:             9.4.0.5173
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
  `equipment_desc` varchar(256) NOT NULL DEFAULT '‘’' COMMENT '设备描述',
  `equipment_model` varchar(128) NOT NULL COMMENT '设备型号',
  `equipment_spec` varchar(256) NOT NULL COMMENT '设备规格',
  `equipment_param` varchar(512) NOT NULL COMMENT '设备参数',
  `create_date` datetime NOT NULL COMMENT '设备创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建该设备的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该设备的用户名称',
  PRIMARY KEY (`equipment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='设备信息表';

-- Dumping data for table poemip.tb_equipment: 3 rows
DELETE FROM `tb_equipment`;
/*!40000 ALTER TABLE `tb_equipment` DISABLE KEYS */;
INSERT INTO `tb_equipment` (`equipment_id`, `equipment_name`, `equipment_desc`, `equipment_model`, `equipment_spec`, `equipment_param`, `create_date`, `user_id`, `user_name`) VALUES
	(4, '设备1名称', '设备1描述', '设备1型号', '设备1规格', '设备1参数', '2017-05-23 08:48:53', 1, '用户1名称'),
	(5, '设备2名称', '设备2描述', '设备2型号', '设备2规格', '设备2参数', '2017-05-24 10:03:11', 1, '用户1名称'),
	(6, '设备3名称', '设备3描述', '设备3型号', '设备3规格', '设备3参数', '2017-05-25 17:03:11', 1, '用户1名称');
/*!40000 ALTER TABLE `tb_equipment` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_issue
CREATE TABLE IF NOT EXISTS `tb_issue` (
  `issue_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `issue_desc` varchar(1024) NOT NULL COMMENT '问题描述',
  `issue_voucher_no` varchar(128) NOT NULL COMMENT '问题所属凭单号',
  `issue_voucher_date` datetime NOT NULL COMMENT '问题所属凭单日期',
  `issue_causes` varchar(128) NOT NULL COMMENT '问题成因分析',
  `issue_solve` varchar(1024) NOT NULL COMMENT '问题解决过程',
  `issue_type` tinyint(4) NOT NULL COMMENT '问题所属阶段 0:招标/签约阶段 1:设计阶段 2:生产阶段 3:运输阶段 4:现场存储阶段 5:安装测试阶段 6:验收阶段 7:质保阶段',
  `supplier_id` int(11) NOT NULL COMMENT '问题所属供应商ID',
  `equipment_id` int(11) NOT NULL COMMENT '问题所在设备ID',
  `project_id` int(11) NOT NULL COMMENT '问题所在项目ID',
  `create_date` datetime NOT NULL COMMENT '问题创建日期',
  `user_id` int(11) NOT NULL COMMENT '提交该问题的用户ID',
  `user_name` varchar(64) NOT NULL COMMENT '提交该问题的用户名称',
  PRIMARY KEY (`issue_id`),
  KEY `issue_userid` (`user_id`),
  KEY `issue_voucher_no` (`issue_voucher_no`),
  KEY `issue_type_supplier_equipment_project_id` (`issue_type`,`supplier_id`,`equipment_id`,`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='问题信息表';

-- Dumping data for table poemip.tb_issue: 2 rows
DELETE FROM `tb_issue`;
/*!40000 ALTER TABLE `tb_issue` DISABLE KEYS */;
INSERT INTO `tb_issue` (`issue_id`, `issue_desc`, `issue_voucher_no`, `issue_voucher_date`, `issue_causes`, `issue_solve`, `issue_type`, `supplier_id`, `equipment_id`, `project_id`, `create_date`, `user_id`, `user_name`) VALUES
	(2, '问题1描述', '问题1所属凭单号', '2017-02-09 00:00:00', '问题1成因分析', '问题1解决过程', 1, 3, 4, 9, '2017-05-24 09:20:17', 1, '用户1名称'),
	(3, '问题2描述', '问题2所属凭单号', '2017-03-15 00:00:00', '问题2成因分析', '问题2解决过程', 4, 4, 5, 9, '2017-05-24 09:21:06', 1, '用户1名称');
/*!40000 ALTER TABLE `tb_issue` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_project
CREATE TABLE IF NOT EXISTS `tb_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `project_name` varchar(128) NOT NULL COMMENT '项目名称',
  `project_desc` varchar(256) NOT NULL DEFAULT '‘’' COMMENT '项目描述',
  `create_date` datetime NOT NULL COMMENT '项目创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建该项目的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该项目的用户名称',
  PRIMARY KEY (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='项目信息表';

-- Dumping data for table poemip.tb_project: 1 rows
DELETE FROM `tb_project`;
/*!40000 ALTER TABLE `tb_project` DISABLE KEYS */;
INSERT INTO `tb_project` (`project_id`, `project_name`, `project_desc`, `create_date`, `user_id`, `user_name`) VALUES
	(9, '项目名称2', '项目2的描述信息', '2017-05-24 02:37:10', 2, '用户名2');
/*!40000 ALTER TABLE `tb_project` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_project_equipment
CREATE TABLE IF NOT EXISTS `tb_project_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `project_id` int(11) NOT NULL COMMENT '所在项目ID',
  `equipment_id` int(11) NOT NULL COMMENT '设备ID',
  `create_date` datetime NOT NULL COMMENT '创建关联日期',
  `user_id` int(11) NOT NULL COMMENT '创建该关联的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该关联的用户名称',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='项目和设备对照表';

-- Dumping data for table poemip.tb_project_equipment: 3 rows
DELETE FROM `tb_project_equipment`;
/*!40000 ALTER TABLE `tb_project_equipment` DISABLE KEYS */;
INSERT INTO `tb_project_equipment` (`id`, `project_id`, `equipment_id`, `create_date`, `user_id`, `user_name`) VALUES
	(2, 9, 4, '2017-05-23 08:48:53', 1, '用户1名称'),
	(3, 9, 5, '2017-05-24 10:02:05', 1, '用户1名称'),
	(8, 9, 6, '2017-05-25 17:29:40', 1, '用户1名称');
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='项目和供应商对照表';

-- Dumping data for table poemip.tb_project_equipment_supplier: 3 rows
DELETE FROM `tb_project_equipment_supplier`;
/*!40000 ALTER TABLE `tb_project_equipment_supplier` DISABLE KEYS */;
INSERT INTO `tb_project_equipment_supplier` (`id`, `supplier_id`, `equipment_id`, `project_id`, `create_date`, `user_id`, `user_name`) VALUES
	(1, 3, 4, 9, '2017-05-24 06:20:11', 1, '用户1名称'),
	(2, 4, 5, 9, '2017-05-24 09:01:49', 1, '用户1名称'),
	(3, 4, 6, 9, '2017-05-25 17:49:29', 1, '用户1名称');
/*!40000 ALTER TABLE `tb_project_equipment_supplier` ENABLE KEYS */;

-- Dumping structure for table poemip.tb_supplier
CREATE TABLE IF NOT EXISTS `tb_supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `supplier_name` varchar(128) NOT NULL COMMENT '供应商名称',
  `supplier_desc` varchar(256) NOT NULL DEFAULT '‘’' COMMENT '供应商描述',
  `create_date` datetime NOT NULL COMMENT '供应商创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建该供应商的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该供应商的用户名称',
  PRIMARY KEY (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='供应商信息表';

-- Dumping data for table poemip.tb_supplier: 2 rows
DELETE FROM `tb_supplier`;
/*!40000 ALTER TABLE `tb_supplier` DISABLE KEYS */;
INSERT INTO `tb_supplier` (`supplier_id`, `supplier_name`, `supplier_desc`, `create_date`, `user_id`, `user_name`) VALUES
	(3, '供应商1名称', '供应商1描述', '2017-05-24 06:20:11', 1, '用户1名称'),
	(4, '供应商2名称', '供应商2描述', '2017-05-24 09:01:49', 1, '用户1名称');
/*!40000 ALTER TABLE `tb_supplier` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
