/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-30 18:10:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivawjw_wfcx
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivawjw_wfcx`;
CREATE TABLE `ims_vivawjw_wfcx` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `appeal_name` varchar(255) DEFAULT NULL,
  `appeal_phone` varchar(255) DEFAULT NULL,
  `appeal_why` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '2',
  `sittime` int(11) DEFAULT NULL,
  `turndown` varchar(255) DEFAULT NULL,
  `wftime` int(11) DEFAULT NULL COMMENT '//违法时间',
  `wfaddr` varchar(255) DEFAULT NULL COMMENT '//违法地点',
  `wfcontent` varchar(255) DEFAULT NULL COMMENT '//违法行为',
  `wfimage` varchar(255) DEFAULT NULL COMMENT '//违法图片',
  `wfmoney` int(11) DEFAULT NULL COMMENT '//罚款金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
