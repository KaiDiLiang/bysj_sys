/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.7.21-0ubuntu0.16.04.1 : Database - cms_sys
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cms_sys` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cms_sys`;

/*Table structure for table `ys_auth_allot` */

DROP TABLE IF EXISTS `ys_auth_allot`;

CREATE TABLE `ys_auth_allot` (
  `r2r_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rule_id` mediumint(8) unsigned NOT NULL COMMENT '规则id',
  `role_id` smallint(5) unsigned NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`r2r_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `ys_auth_allot` */

insert  into `ys_auth_allot`(`r2r_id`,`rule_id`,`role_id`) values (1,1,2);

/*Table structure for table `ys_auth_roles` */

DROP TABLE IF EXISTS `ys_auth_roles`;

CREATE TABLE `ys_auth_roles` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `role_name` varchar(15) NOT NULL DEFAULT '' COMMENT '角色名字',
  `role_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '角色状态',
  `role_parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '角色所属上级',
  `role_remark` varchar(150) DEFAULT '' COMMENT '角色说明',
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `NewIndex1` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='角色表';

/*Data for the table `ys_auth_roles` */

insert  into `ys_auth_roles`(`role_id`,`role_name`,`role_status`,`role_parent_id`,`role_remark`) values (1,'学生组','Y',0,'所有学生组'),(2,'普通学生组','Y',1,'普通学生');

/*Table structure for table `ys_auth_rules` */

DROP TABLE IF EXISTS `ys_auth_rules`;

CREATE TABLE `ys_auth_rules` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则ID',
  `rule_name` varchar(25) DEFAULT '' COMMENT '规则名称',
  `rule_visable` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '规则是否可见',
  `rule_enable` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '规则是否开启',
  `rule_parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '规则所属上级ID',
  `rule_module` varchar(15) DEFAULT 'index' COMMENT '规则所属模块',
  `rule_controller` varchar(15) DEFAULT 'index' COMMENT '规则所属控制器',
  `rule_action` varchar(15) DEFAULT 'index' COMMENT '规则所属行为',
  `rule_icon` varchar(15) DEFAULT 'list' COMMENT '规则图标',
  `rule_remark` varchar(150) DEFAULT '' COMMENT '规则描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex3` (`rule_module`,`rule_controller`,`rule_action`),
  KEY `NewIndex1` (`rule_enable`),
  KEY `NewIndex2` (`rule_visable`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='规则表';

/*Data for the table `ys_auth_rules` */

insert  into `ys_auth_rules`(`id`,`rule_name`,`rule_visable`,`rule_enable`,`rule_parent_id`,`rule_module`,`rule_controller`,`rule_action`,`rule_icon`,`rule_remark`) values (1,'cms管理','Y','Y',0,'cms','index','index','list',''),(2,'栏目管理','Y','Y',1,'cms','category','index','list',''),(3,'栏目添加','Y','Y',3,'cms','category','add','list',''),(4,'后台首页','Y','Y',1,'admin','index','index','home',''),(5,'学生添加','Y','Y',2,'admin','student','add','users','');

/*Table structure for table `ys_auth_token` */

DROP TABLE IF EXISTS `ys_auth_token`;

CREATE TABLE `ys_auth_token` (
  `token_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户id',
  `token_val` text NOT NULL COMMENT 'token值',
  `token_create_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'token创建时间',
  `token_invali_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'token过期时间',
  PRIMARY KEY (`token_id`),
  KEY `token_invali_at` (`token_invali_at`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ys_auth_token` */

insert  into `ys_auth_token`(`token_id`,`user_id`,`token_val`,`token_create_at`,`token_invali_at`) values (1,1,'0.00126200 1525870080$2a$08$pfEuQo6Fpp0UEkipNXincul9mmQCbxrH6Z6jqiquJMVxp4Iw1ZXti5af2ee00004f42.11212674',1524035539,1525873679),(2,3,'0.05648600 1525866388$2a$08$7YnXjYVfC6wz1UJQwzf7HO2zaQDixATahOQ2sFgKfVcDQXxIIknq65af2df940dcaf8.97738538',1525692391,1525869988);

/*Table structure for table `ys_class_list` */

DROP TABLE IF EXISTS `ys_class_list`;

CREATE TABLE `ys_class_list` (
  `class_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '班级ID',
  `class_code` mediumint(8) unsigned NOT NULL COMMENT '班级代码',
  `class_name` varchar(15) NOT NULL COMMENT '班级名称',
  `class_boys` smallint(3) unsigned DEFAULT '0' COMMENT '班级的男生总数',
  `class_girls` smallint(3) unsigned DEFAULT '0' COMMENT '班级的女生总数',
  `class_status` enum('B','J') DEFAULT 'J' COMMENT '班级状态 B=已毕业 J=就读中',
  `class_year` varchar(4) NOT NULL DEFAULT '' COMMENT '班级入学年份',
  `class_instructor_id` mediumint(8) unsigned DEFAULT '0' COMMENT '班级所属辅导员id',
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `NewIndex2` (`class_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ys_class_list` */

insert  into `ys_class_list`(`class_id`,`class_code`,`class_name`,`class_boys`,`class_girls`,`class_status`,`class_year`,`class_instructor_id`) values (1,15004,'15软件技术4班',43,0,'J','',1);

/*Table structure for table `ys_department_list` */

DROP TABLE IF EXISTS `ys_department_list`;

CREATE TABLE `ys_department_list` (
  `department_id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '系部id',
  `department_name` varchar(15) NOT NULL DEFAULT '' COMMENT '系部名称',
  `department_code` varchar(5) DEFAULT '' COMMENT '系部代码',
  `department_remark` varchar(150) DEFAULT '' COMMENT '系部简介',
  `department_count` smallint(8) unsigned NOT NULL DEFAULT '0' COMMENT '系部总人数',
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ys_department_list` */

insert  into `ys_department_list`(`department_id`,`department_name`,`department_code`,`department_remark`,`department_count`) values (1,'计算机应用技术系','10001','系部拥有5个专业，以为社会输送优质技术人才为宗旨',1000);

/*Table structure for table `ys_instructor_list` */

DROP TABLE IF EXISTS `ys_instructor_list`;

CREATE TABLE `ys_instructor_list` (
  `instructor_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '辅导员id',
  `instructor_name` varchar(10) NOT NULL COMMENT '辅导员名字',
  `instructor_phone` char(11) NOT NULL COMMENT '辅导员联系电话',
  `instructor_year` varchar(4) NOT NULL DEFAULT '0' COMMENT '辅导员工龄',
  PRIMARY KEY (`instructor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ys_instructor_list` */

insert  into `ys_instructor_list`(`instructor_id`,`instructor_name`,`instructor_phone`,`instructor_year`) values (1,'梁少萍','13824606753','');

/*Table structure for table `ys_major_list` */

DROP TABLE IF EXISTS `ys_major_list`;

CREATE TABLE `ys_major_list` (
  `major_id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '专业ID',
  `major_name` varchar(15) NOT NULL DEFAULT '' COMMENT '专业名称',
  `major_code` char(5) NOT NULL DEFAULT '' COMMENT '专业代码',
  `major_remark` varchar(120) DEFAULT '' COMMENT '专业简介',
  `major_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '专业人数',
  PRIMARY KEY (`major_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ys_major_list` */

insert  into `ys_major_list`(`major_id`,`major_name`,`major_code`,`major_remark`,`major_count`) values (1,'软件技术','10001','以时下社会所需技术为主要教程',200);

/*Table structure for table `ys_mid_cmj` */

DROP TABLE IF EXISTS `ys_mid_cmj`;

CREATE TABLE `ys_mid_cmj` (
  `mid_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `c_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '班级id',
  `m_id` mediumint(5) unsigned NOT NULL DEFAULT '0' COMMENT '专业id',
  `j_id` mediumint(5) unsigned NOT NULL DEFAULT '0' COMMENT '系部id',
  PRIMARY KEY (`mid_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ys_mid_cmj` */

insert  into `ys_mid_cmj`(`mid_id`,`c_id`,`m_id`,`j_id`) values (1,1,1,1);

/*Table structure for table `ys_sys_api` */

DROP TABLE IF EXISTS `ys_sys_api`;

CREATE TABLE `ys_sys_api` (
  `api_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `api_code` varchar(45) NOT NULL DEFAULT '' COMMENT '接口代码，唯一',
  `api_short_code` mediumint(5) NOT NULL DEFAULT '0' COMMENT '接口响应码',
  `api_remark` varchar(150) DEFAULT '' COMMENT '接口说明',
  PRIMARY KEY (`api_id`),
  UNIQUE KEY `api_code` (`api_code`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `ys_sys_api` */

insert  into `ys_sys_api`(`api_id`,`api_code`,`api_short_code`,`api_remark`) values (1,'login-success',0,'登陆成功'),(2,'account-not-esists-error',20101,'账号不存在'),(3,'account-status-error',20102,'账号状态异常'),(4,'account-password-error',20103,'账号密码错误'),(5,'get-student-info-success',0,'获取学生登录信息成功'),(6,'get-data-success',0,'获取信息成功'),(7,'user-unauthorizod-error',20401,'用户未获授权'),(8,'user-token-parse-error',20403,'用户token解析出错'),(11,'no-data-error',20106,'没有找到信息'),(12,'acesss-reuqest-error',20107,'权限不足');

/*Table structure for table `ys_user_accounts` */

DROP TABLE IF EXISTS `ys_user_accounts`;

CREATE TABLE `ys_user_accounts` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_account` varchar(20) NOT NULL COMMENT '账号',
  `user_password` char(60) NOT NULL COMMENT '用户登陆密码',
  `user_last_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `user_last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录IPV4数字',
  `user_role_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户所属角色ID',
  `user_type` enum('S','T','A') DEFAULT 'S' COMMENT '用户类型 A=管理员 S=学生 T=教师',
  `user_enable` enum('Y','N') DEFAULT 'Y' COMMENT '用户状态 Y=可用 N=禁用',
  `user_salt` char(60) NOT NULL DEFAULT '' COMMENT '用户加密盐',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `NewIndex1` (`user_account`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ys_user_accounts` */

insert  into `ys_user_accounts`(`user_id`,`user_account`,`user_password`,`user_last_at`,`user_last_ip`,`user_role_id`,`user_type`,`user_enable`,`user_salt`) values (2,'201511033430','$2a$08$QWT4WFHbv7rzy.m/SdaFBOrruNCx/GObF74QoVZo0Jz0RnTc4at22',1525866387,3232238081,0,'S','Y','$2a$08$7YnXjYVfC6wz1UJQwzf7HO2zaQDixATahOQ2sFgKfVcDQXxIIknq6'),(7,'201511033423','022130',0,0,0,'S','Y','');

/*Table structure for table `ys_user_base` */

DROP TABLE IF EXISTS `ys_user_base`;

CREATE TABLE `ys_user_base` (
  `base_id` smallint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '基本信息ID',
  `address` varchar(30) NOT NULL COMMENT '住址',
  `nation` varchar(10) NOT NULL DEFAULT '' COMMENT '民族',
  `politics_status` varchar(5) NOT NULL DEFAULT '' COMMENT '政治面貌',
  `account_number` bigint(20) unsigned NOT NULL COMMENT '对应账号',
  `user_name` varchar(5) NOT NULL DEFAULT '' COMMENT '用户姓名',
  PRIMARY KEY (`base_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ys_user_base` */

insert  into `ys_user_base`(`base_id`,`address`,`nation`,`politics_status`,`account_number`,`user_name`) values (1,'广东省','汉','团员',0,''),(2,'广东省','汉','团员',201511033423,'李锐找');

/*Table structure for table `ys_user_student` */

DROP TABLE IF EXISTS `ys_user_student`;

CREATE TABLE `ys_user_student` (
  `student_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_number` varchar(15) NOT NULL DEFAULT '' COMMENT '学号',
  `student_name` varchar(8) NOT NULL DEFAULT '' COMMENT '姓名',
  `student_class_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所在班级',
  `student_sex` enum('F','M','S') NOT NULL COMMENT '性别 F=男 M=女 S=未知',
  `student_phone_number` char(11) DEFAULT '' COMMENT '电话号码',
  `student_idcard` char(18) NOT NULL DEFAULT '' COMMENT '身份证号码',
  PRIMARY KEY (`student_id`,`student_number`,`student_idcard`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ys_user_student` */

insert  into `ys_user_student`(`student_id`,`student_number`,`student_name`,`student_class_id`,`student_sex`,`student_phone_number`,`student_idcard`) values (1,'201511033430','梁凯迪',0,'F','18122566083','440982199303221414'),(2,'201511033423','李锐钊',0,'F','','');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
