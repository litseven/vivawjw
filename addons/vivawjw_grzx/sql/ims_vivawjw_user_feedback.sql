/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-30 18:04:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivawjw_user_feedback
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivawjw_user_feedback`;
CREATE TABLE `ims_vivawjw_user_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `feedtype` int(1) DEFAULT NULL COMMENT '//1功能意见，2页面设计，3用户体验',
  `content` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '2',
  `turndown` varchar(255) DEFAULT NULL,
  `sittime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
