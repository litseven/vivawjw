/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-30 18:05:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivawjw_bszn_hotkey
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivawjw_bszn_hotkey`;
CREATE TABLE `ims_vivawjw_bszn_hotkey` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotkey` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_vivawjw_bszn_hotkey
-- ----------------------------
INSERT INTO `ims_vivawjw_bszn_hotkey` VALUES ('1', '机动车', '1', '3');
INSERT INTO `ims_vivawjw_bszn_hotkey` VALUES ('2', '验车', '1', '3');
INSERT INTO `ims_vivawjw_bszn_hotkey` VALUES ('3', '12分', '1', '3');
INSERT INTO `ims_vivawjw_bszn_hotkey` VALUES ('4', '换证', '1', '3');
INSERT INTO `ims_vivawjw_bszn_hotkey` VALUES ('6', '检验合格标志', '1', '3');
INSERT INTO `ims_vivawjw_bszn_hotkey` VALUES ('7', '免检', '1', '3');
INSERT INTO `ims_vivawjw_bszn_hotkey` VALUES ('8', '上牌', '0', '3');
INSERT INTO `ims_vivawjw_bszn_hotkey` VALUES ('9', '新', '0', '3');
