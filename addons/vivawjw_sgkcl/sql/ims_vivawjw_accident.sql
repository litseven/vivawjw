/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-30 18:07:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivawjw_accident
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivawjw_accident`;
CREATE TABLE `ims_vivawjw_accident` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `accaddr` varchar(255) DEFAULT NULL COMMENT '//事故地点',
  `proof` text COMMENT '//现场图片',
  `accname` varchar(255) DEFAULT NULL COMMENT '//姓名',
  `accothername` varchar(255) DEFAULT NULL COMMENT '//对方姓名',
  `accnum` varchar(10) DEFAULT NULL COMMENT '//车牌号',
  `accothernum` varchar(10) DEFAULT NULL,
  `accotherphone` int(11) DEFAULT NULL,
  `accserve` varchar(255) DEFAULT NULL COMMENT '//理赔服务点',
  `recordnum` varchar(255) DEFAULT NULL COMMENT '//报警记录号',
  `acctime` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '2' COMMENT '//状态，1确认、2待确认、3驳回',
  `turndown` varchar(255) DEFAULT NULL COMMENT '//驳回原因',
  `sittime` int(11) DEFAULT NULL COMMENT '//受理时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
