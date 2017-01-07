/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-01-07 18:28:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivanjyh_xytp_num
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivanjyh_xytp_num`;
CREATE TABLE `ims_vivanjyh_xytp_num` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `numid` varchar(11) DEFAULT NULL,
  `sittime` int(11) DEFAULT NULL,
  `degree` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;
