-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: w.rdc.sae.sina.com.cn:3307
-- Generation Time: Jul 03, 2017 at 10:23 AM
-- Server version: 5.6.23
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `app_poemip`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_equipment`
--

DROP TABLE IF EXISTS `tb_equipment`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='设备信息表' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tb_equipment`
--

INSERT INTO `tb_equipment` (`equipment_id`, `equipment_name`, `equipment_desc`, `equipment_model`, `equipment_spec`, `equipment_param`, `create_date`, `user_id`, `user_name`, `last_update`) VALUES
(12, '箱罐', '(请填写箱罐描述)', '(请填写箱罐型号)', '(请填写箱罐规格)', '(请填写箱罐参数)', '2017-06-23 15:22:59', 1, '用户1名称', '2017-06-29 14:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `tb_issue`
--

DROP TABLE IF EXISTS `tb_issue`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='问题信息表' AUTO_INCREMENT=14 ;

--
-- Dumping data for table `tb_issue`
--

INSERT INTO `tb_issue` (`issue_id`, `issue_desc`, `issue_voucher_no`, `issue_voucher_date`, `issue_causes`, `issue_solve`, `issue_type`, `supplier_id`, `equipment_id`, `project_id`, `create_date`, `user_id`, `user_name`, `last_update`) VALUES
(10, '设计院热控、机务专业收提资不畅导致供货和设计不符，设计院提供的招标规范书供货形式有误。', 'XXXX-XX-XX', '2017-03-15', '设计院内部提资不畅，技术规范书供货形式有误', '水箱的磁翻板液位计，设计院设计为整段设计（分别为9m和5.2m），到货的液位计为分段式（分别为3段3m和2段2.6m），与设计院设计不符无法安装。设计院回复确认为分段设计，请厂家针对目前分段式供货，给出安装处理措施。但经查询了图纸，图中均为整段设计，并非分段设计，厂家回复的安装方案为分段平行安装，水箱侧壁上的开孔及接管均按照设计院图纸在同一条垂线上，目前水箱内壁玻璃钢防腐已经完成并业主验收，无法再开孔和焊接。', 1, 7, 12, 22, '2017-06-29 15:31:00', 1, '白举宜', NULL),
(9, '油罐油位无配供法兰，实际供货与设计院图纸不符', 'XXXX-XX-XX', '2017-04-17', '厂家错供', '厂家补供', 2, 7, 12, 22, '2017-06-29 15:27:46', 2, '李百', NULL),
(7, '加工的弯头和连接管件数量差缺', 'XXXX-XX-XX', '2017-03-15', '供货差缺', '厂家补供', 2, 7, 12, 22, '2017-06-22 13:43:59', 3, '杜府', '2017-06-29 14:52:01'),
(8, '罐壁加强圈漏发，加强圈不到影响下部已施工完成的箱罐体变形', 'XXXX-XX-XX', '2017-04-12', '厂家漏供', '厂家补供', 2, 7, 12, 22, '2017-06-29 15:19:50', 4, '贺只彰', NULL),
(11, '顶圈梁加劲板、踏步格栅板和花纹板、透光孔平台格栅板差缺', 'XXXX-XX-XX', '2017-04-07', '供货差缺', '厂家补供', 2, 7, 12, 22, '2017-06-29 16:18:34', 5, '刘雨溪', NULL),
(12, '板材及附件未按图纸数量制造，成品应打图号便于安装', 'XXXX-XX-XX', '2017-04-19', '未按图纸数量生产，成品未标图号', '提醒厂家注意', 2, 7, 12, 22, '2017-06-29 16:20:25', 6, '孟昊冉', NULL),
(13, '设计图和厂家图不符，造成漏供', 'XXXX-XX-XX', '2017-04-28', '设计提资不畅', '提醒设计院厂家注意', 1, 7, 12, 22, '2017-06-29 16:21:20', 7, '韦映勿', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_project`
--

DROP TABLE IF EXISTS `tb_project`;
CREATE TABLE IF NOT EXISTS `tb_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `project_name` varchar(128) NOT NULL COMMENT '项目名称',
  `project_desc` varchar(512) NOT NULL DEFAULT '‘’' COMMENT '项目描述',
  `create_date` datetime NOT NULL COMMENT '项目创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建该项目的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该项目的用户名称',
  `last_update` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`project_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='项目信息表' AUTO_INCREMENT=24 ;

--
-- Dumping data for table `tb_project`
--

INSERT INTO `tb_project` (`project_id`, `project_name`, `project_desc`, `create_date`, `user_id`, `user_name`, `last_update`) VALUES
(22, '委内瑞拉中央电厂6号600MW蒸汽轮机发电机组项目', '委内瑞拉中央电厂6号600MW蒸汽轮机发电机组项目', '2017-06-22 10:01:15', 1, '用户1名称', '2017-06-30 14:42:04'),
(23, '塞尔维亚KOSTOLAC-B电站项目二期工程', '塞尔维亚KOSTOLAC-B电站项目二期工程', '2017-07-02 01:17:06', 1, '用户1名称', '2017-07-02 01:17:06');

-- --------------------------------------------------------

--
-- Table structure for table `tb_project_equipment`
--

DROP TABLE IF EXISTS `tb_project_equipment`;
CREATE TABLE IF NOT EXISTS `tb_project_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `equipment_id` int(11) NOT NULL COMMENT '设备ID',
  `project_id` int(11) NOT NULL COMMENT '所在项目ID',
  `create_date` datetime NOT NULL COMMENT '创建关联日期',
  `user_id` int(11) NOT NULL COMMENT '创建该关联的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该关联的用户名称',
  PRIMARY KEY (`id`),
  KEY `project_id_equipment_id` (`project_id`,`equipment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='项目和设备对照表' AUTO_INCREMENT=19 ;

--
-- Dumping data for table `tb_project_equipment`
--

INSERT INTO `tb_project_equipment` (`id`, `equipment_id`, `project_id`, `create_date`, `user_id`, `user_name`) VALUES
(18, 12, 22, '2017-06-23 16:02:00', 1, '用户1名称');

-- --------------------------------------------------------

--
-- Table structure for table `tb_project_equipment_supplier`
--

DROP TABLE IF EXISTS `tb_project_equipment_supplier`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='项目和供应商对照表' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `tb_project_equipment_supplier`
--

INSERT INTO `tb_project_equipment_supplier` (`id`, `supplier_id`, `equipment_id`, `project_id`, `create_date`, `user_id`, `user_name`) VALUES
(9, 7, 12, 22, '2017-06-23 16:06:58', 1, '用户1名称');

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier`
--

DROP TABLE IF EXISTS `tb_supplier`;
CREATE TABLE IF NOT EXISTS `tb_supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `supplier_name` varchar(128) NOT NULL COMMENT '供应商名称',
  `supplier_desc` varchar(256) NOT NULL DEFAULT '‘’' COMMENT '供应商描述',
  `create_date` datetime NOT NULL COMMENT '供应商创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建该供应商的用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '创建该供应商的用户名称',
  `last_update` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`supplier_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='供应商信息表' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tb_supplier`
--

INSERT INTO `tb_supplier` (`supplier_id`, `supplier_name`, `supplier_desc`, `create_date`, `user_id`, `user_name`, `last_update`) VALUES
(7, '北京峰瑞达机械设备有限公司', '(请填写供应商“北京峰瑞达”的描述信息)', '2017-06-22 13:43:30', 1, '用户1名称', '2017-07-02 01:08:54');
