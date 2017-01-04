/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-30 18:04:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivawjw_user_bound_car
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivawjw_user_bound_car`;
CREATE TABLE `ims_vivawjw_user_bound_car` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `wx_type` varchar(255) DEFAULT NULL,
  `wx_car_num` varchar(18) DEFAULT NULL,
  `wx_car_engine` varchar(6) DEFAULT NULL,
  `wd_type` varchar(255) DEFAULT NULL,
  `wd_car_num` varchar(18) DEFAULT NULL,
  `wd_car_engine` varchar(6) DEFAULT NULL,
  `distinction` int(1) DEFAULT NULL COMMENT '//1为无锡，0为外地',
  `status` int(1) DEFAULT '2' COMMENT '//1通过，2待受理，3驳回',
  `turndown` varchar(255) DEFAULT NULL COMMENT '//驳回原因',
  `time` int(11) NOT NULL,
  `sittime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
