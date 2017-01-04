/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-30 18:03:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_vivawjw_user_warn
-- ----------------------------
DROP TABLE IF EXISTS `ims_vivawjw_user_warn`;
CREATE TABLE `ims_vivawjw_user_warn` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `title_key` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_vivawjw_user_warn
-- ----------------------------
INSERT INTO `ims_vivawjw_user_warn` VALUES ('1', '3', 'jtwf', '交通违法临近满分提醒');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('2', '3', 'gycl', '公用车辆未处理违法信息通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('3', '3', 'yqjy', '逾期未参加检验通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('4', '3', 'qzbf', '临近强制报废年限提示');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('5', '3', 'sttj', '未按期提交身体条件证明通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('6', '3', 'wfxw', '违法行为信息通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('7', '3', 'jzyq', '驾驶证逾期未审验通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('9', '3', 'dzjk', '电子监控单次违法通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('10', '3', 'jzhz', '驾驶证逾期未换证提示');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('11', '3', 'jzjj', '驾驶证降级通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('12', '3', 'ljjy', '临近检验提示');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('13', '3', 'wfmf', '交通违法满分通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('14', '3', 'ljgd', '机动车累积过多未处理违法信息通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('16', '3', 'jzyc', '驾驶证延长实习期内记分通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('17', '3', 'sxyc', '实习期延长通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('18', '3', 'tjzm', '提交身体条件证明提醒');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('19', '3', 'lwjy', '临近未参加检验提示');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('20', '3', 'sxzx', '驾驶证实习期内注销通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('21', '3', 'qzbf', '强制报废提示');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('8', '3', 'mysy', '驾驶证免于审验通知');
INSERT INTO `ims_vivawjw_user_warn` VALUES ('15', '3', 'bftz', '逾期未报废通知');
