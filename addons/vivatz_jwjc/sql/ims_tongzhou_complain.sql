/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2016-12-16 13:47:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_tongzhou_complain
-- ----------------------------
DROP TABLE IF EXISTS `ims_tongzhou_complain`;
CREATE TABLE `ims_tongzhou_complain` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '//投诉表',
  `co_type` varchar(255) DEFAULT NULL COMMENT '//投诉类型',
  `co_name` varchar(255) NOT NULL COMMENT '//民警姓名',
  `co_num` varchar(255) DEFAULT NULL COMMENT '//民警编号',
  `co_car_num` varchar(255) DEFAULT NULL COMMENT '//车牌',
  `co_next_unit` varchar(255) DEFAULT NULL COMMENT '//下属单位',
  `co_to_name` varchar(255) DEFAULT NULL COMMENT '//您的姓名',
  `co_to_num` int(11) DEFAULT NULL COMMENT '//您的电话',
  `co_time` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `co_comment` varchar(255) NOT NULL COMMENT '//投诉事项',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
