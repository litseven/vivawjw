/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-30 18:12:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivawjw_change_car
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivawjw_change_car`;
CREATE TABLE `ims_vivawjw_change_car` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `carname` varchar(255) DEFAULT NULL,
  `carnum` varchar(15) DEFAULT NULL,
  `cartype` int(100) DEFAULT NULL,
  `caraddr` varchar(255) DEFAULT NULL,
  `carphone` int(11) DEFAULT NULL,
  `cartime` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '2',
  `turndown` varchar(255) DEFAULT NULL,
  `sittime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
