
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_messages`
-- ----------------------------
DROP TABLE IF EXISTS `ims_messages`;
CREATE TABLE `ims_messages` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(100) NOT NULL,
  `uniacid` int(100) unsigned NOT NULL COMMENT '//公众号id',
  `uname` varchar(255) NOT NULL COMMENT '//用户名',
  `content` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '//头像',
  `times` int(20) NOT NULL,
  `pass` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '//是否显示，0否，1是',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`uname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_messages
-- ----------------------------

