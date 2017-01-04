/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2016-12-16 13:47:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_tongzhou_question
-- ----------------------------
DROP TABLE IF EXISTS `ims_tongzhou_question`;
CREATE TABLE `ims_tongzhou_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL COMMENT '//选项类型',
  `title` varchar(255) NOT NULL COMMENT '//问题',
  `key` varchar(255) NOT NULL COMMENT '//选项',
  `is_show` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
