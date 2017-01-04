/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-01-03 16:20:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivawjw_sgkc_addr
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivawjw_sgkc_addr`;
CREATE TABLE `ims_vivawjw_sgkc_addr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_vivawjw_sgkc_addr
-- ----------------------------
INSERT INTO `ims_vivawjw_sgkc_addr` VALUES ('1', '3', '东方城理赔服务中心', '金城东路290号', '0510-82108815');
INSERT INTO `ims_vivawjw_sgkc_addr` VALUES ('2', '3', '大昌理赔服务中心', '运河东路138号', '0510-82813555');
