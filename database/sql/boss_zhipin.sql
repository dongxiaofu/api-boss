/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80021
 Source Host           : localhost:3306
 Source Schema         : my_boss_zhipin

 Target Server Type    : MySQL
 Target Server Version : 80021
 File Encoding         : 65001

 Date: 25/10/2020 21:04:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `company_id` int unsigned NOT NULL AUTO_INCREMENT,
  `business` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '工商信息',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '公司logo地址',
  `address` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '地址',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '公司名称',
  `stage` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '融资阶段：0.不限;1.未融资;2.天使轮;3.A轮;4.B轮;5.C轮;6.D轮及以上;7.已上市;8.不需要融资',
  `scale` tinyint unsigned NOT NULL DEFAULT '12' COMMENT '公司规模：0.不限;1.0-20人;2.20-99人;3.100-499人;4.500-999人;5.1000-9999人;6.10000人以上',
  `industry` tinyint unsigned NOT NULL DEFAULT '12' COMMENT '公司行业：太多了',
  `net` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '网址',
  `introduce` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '公司介绍',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公司';

-- ----------------------------
-- Table structure for company_employeer
-- ----------------------------
DROP TABLE IF EXISTS `company_employeer`;
CREATE TABLE `company_employeer` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `employeer_id` int unsigned NOT NULL DEFAULT '0' COMMENT '招聘者ID',
  `company_id` int unsigned NOT NULL DEFAULT '0' COMMENT '公司ID',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公司招聘者关联表';

-- ----------------------------
-- Table structure for district
-- ----------------------------
DROP TABLE IF EXISTS `district`;
CREATE TABLE `district` (
  `id` int NOT NULL DEFAULT '0' COMMENT '主键自增',
  `pid` int DEFAULT NULL COMMENT '父类id',
  `district_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '城市的名字',
  `first_letter` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名字首个字母，例如：北京，B',
  `type` int DEFAULT NULL COMMENT '城市的类型，0是国，1是省，2是市，3是区',
  `hierarchy` int DEFAULT NULL COMMENT '地区所处的层级',
  `district_sqe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '层级序列',
  `is_hot` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否热门：0.非热门；1.热门',
  `is_municipality` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否直辖市：0.不是；1.是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='中国省市区表';

-- ----------------------------
-- Table structure for employee
-- ----------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `online_status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '在线状态：1.在线；2.离线',
  `role` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '招聘者角色：1.管理员；2.普通人员',
  `title` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头衔，比如：创意总监',
  `telephone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电话号码',
  `wexin` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信号',
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='雇主，招聘者';

-- ----------------------------
-- Table structure for experience
-- ----------------------------
DROP TABLE IF EXISTS `experience`;
CREATE TABLE `experience` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `company_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '公司名称',
  `department` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '所属部门',
  `position_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信号',
  `industry` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '所属行业',
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '技能标签',
  `position_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '职位名称',
  `start_time` int unsigned NOT NULL DEFAULT '0' COMMENT '在职时间开始时间',
  `end_time` int unsigned NOT NULL DEFAULT '0' COMMENT '在职时间结束时间',
  `job_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '工作内容',
  `performance` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '工作业绩',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='工作经验';

-- ----------------------------
-- Table structure for feedback
-- ----------------------------
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '招聘者ID',
  `role` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '牛人身份：1.求职者；2.招聘者',
  `attitude` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '态度：1.不满意；2.一般；3.满意',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '内容',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='反馈表';

-- ----------------------------
-- Table structure for job
-- ----------------------------
DROP TABLE IF EXISTS `job`;
CREATE TABLE `job` (
  `job_id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '职位名称',
  `salary` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '薪水范围：0.不限;1.3K以下;2.3-5K;3.5-10K;4.10-15K;5.15-20K;6.20-30K;7.30-50K;8.50K以上',
  `salary_num` tinyint unsigned NOT NULL DEFAULT '12' COMMENT '薪水月数',
  `city_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '城市code',
  `area_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '城区code',
  `experience` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '工作经验：0.不限;1.在校生;2.应届生;3.1年以内;4.1-3年;5.3-5年;6.5-10年;7.10年以上',
  `degree` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '学历：0.不限;1.初中及以下;2.中专/中技;3.高中;4.大专;5.本科;6.硕士;7.博士;',
  `benefits` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '福利，用逗号分隔',
  `employee_id` int unsigned NOT NULL DEFAULT '0' COMMENT '招聘者ID',
  `job_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '工作描述',
  `company_id` int unsigned NOT NULL DEFAULT '0' COMMENT '公司ID',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '职位状态：1.正常；2.关闭',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='工作职位';

-- ----------------------------
-- Table structure for job_hunter
-- ----------------------------
DROP TABLE IF EXISTS `job_hunter`;
CREATE TABLE `job_hunter` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `job_search_status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '求职状态：1.离职-随时到岗；2.在职-暂不考虑；3.在职-考虑机会；4.在职-月内到岗；',
  `telephone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电话号码',
  `weixin` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信号',
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `degree` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '学历：',
  `experience` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '工作经验',
  `advantage` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '个人优势',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='雇员，求职者';

-- ----------------------------
-- Table structure for report
-- ----------------------------
DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '招聘者ID',
  `role` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '牛人身份：1.求职者；2.招聘者',
  `job_id` int unsigned NOT NULL DEFAULT '0' COMMENT '被举报的工作',
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '举报原因',
  `reason_detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '举报原因细节，用逗号分隔，例如：皮包公司,人身攻击',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '补充说明',
  `screen_capture_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '截图地址',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='举报表';

-- ----------------------------
-- Table structure for upload_file
-- ----------------------------
DROP TABLE IF EXISTS `upload_file`;
CREATE TABLE `upload_file` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `job_hunter_id` int unsigned NOT NULL DEFAULT '0' COMMENT '求职者ID',
  `company_id` int unsigned NOT NULL DEFAULT '0' COMMENT '招聘者ID',
  `type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '文件类型：1.简历附件；2.举报截图',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件地址',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='上传文件表';

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱，唯一',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `birthday` int unsigned NOT NULL DEFAULT '0' COMMENT '生日',
  `gender` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '性别：1.男；2.女',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '用户状态：1.正常；2.禁用',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户';

SET FOREIGN_KEY_CHECKS = 1;
