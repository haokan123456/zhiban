/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : paiban

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-12-21 21:11:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ban`
-- ----------------------------
DROP TABLE IF EXISTS `ban`;
CREATE TABLE `ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(2) NOT NULL,
  `nums` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ban
-- ----------------------------
INSERT INTO `ban` VALUES ('1', '早', '2');
INSERT INTO `ban` VALUES ('2', '中', '4');
INSERT INTO `ban` VALUES ('3', '晚', '1');

-- ----------------------------
-- Table structure for `pepole`
-- ----------------------------
DROP TABLE IF EXISTS `pepole`;
CREATE TABLE `pepole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `class` tinyint(4) NOT NULL DEFAULT '0',
  `interest` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pepole
-- ----------------------------
INSERT INTO `pepole` VALUES ('39', 'gg', '0', '1');
INSERT INTO `pepole` VALUES ('2', 'b', '0', '2');
INSERT INTO `pepole` VALUES ('3', 'c', '0', '3');
INSERT INTO `pepole` VALUES ('4', 'd', '0', '1');
INSERT INTO `pepole` VALUES ('5', 'e', '0', '2');
INSERT INTO `pepole` VALUES ('6', 'f', '0', '3');
INSERT INTO `pepole` VALUES ('40', 'hh', '0', '2');
INSERT INTO `pepole` VALUES ('41', 'ii', '0', '3');

-- ----------------------------
-- Table structure for `time`
-- ----------------------------
DROP TABLE IF EXISTS `time`;
CREATE TABLE `time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  `date2` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of time
-- ----------------------------
INSERT INTO `time` VALUES ('16', '3', '3', '1608307200', '1608825600');
INSERT INTO `time` VALUES ('17', '2', '2', '1608220800', '1608739200');
INSERT INTO `time` VALUES ('18', '3', '2', '1608220800', '1608739200');
INSERT INTO `time` VALUES ('19', '4', '2', '1608134400', '1608652800');
INSERT INTO `time` VALUES ('25', '5', '1', '1608134400', '1608652800');
