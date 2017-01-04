/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2016-12-16 13:47:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_tongzhou_praise
-- ----------------------------
DROP TABLE IF EXISTS `ims_tongzhou_praise`;
CREATE TABLE `ims_tongzhou_praise` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '//表扬表',
  `pr_name` varchar(255) NOT NULL COMMENT '//民警姓名',
  `pr_num` varchar(255) DEFAULT NULL COMMENT '//民警编号',
  `pr_car_num` varchar(255) DEFAULT NULL COMMENT '//车牌',
  `pr_next_unit` varchar(255) DEFAULT NULL COMMENT '//下属单位',
  `pr_to_name` varchar(255) DEFAULT NULL COMMENT '//您的名字',
  `pr_to_num` int(11) DEFAULT NULL COMMENT '//您的电话',
  `pr_comment` varchar(255) NOT NULL COMMENT '//情况描述',
  `pr_time` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
