-- MySQL dump 10.17  Distrib 10.3.25-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: rankupdate
-- ------------------------------------------------------
-- Server version	10.3.25-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ru_admins`
--

DROP TABLE IF EXISTS `ru_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ru_admins` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员序号',
  `aname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '管理员名称',
  `loginname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录名称',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录密码',
  `enable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否启用',
  `permission` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '比赛权限',
  PRIMARY KEY (`aid`),
  UNIQUE KEY `loginname` (`loginname`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ru_admins`
--

LOCK TABLES `ru_admins` WRITE;
/*!40000 ALTER TABLE `ru_admins` DISABLE KEYS */;
INSERT INTO `ru_admins` VALUES (1,'超级管理员','superadmin','25d55ad283aa400af464c76d713c07ad',1,'-1');
/*!40000 ALTER TABLE `ru_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ru_contest_problems`
--

DROP TABLE IF EXISTS `ru_contest_problems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ru_contest_problems` (
  `cid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '比赛序号',
  `seq` int(10) NOT NULL COMMENT '顺序号',
  `lang` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言',
  `problemscore` int(10) NOT NULL COMMENT '题目分数',
  `problemid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '题目序号',
  PRIMARY KEY (`cid`,`problemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ru_contest_problems`
--

LOCK TABLES `ru_contest_problems` WRITE;
/*!40000 ALTER TABLE `ru_contest_problems` DISABLE KEYS */;
/*!40000 ALTER TABLE `ru_contest_problems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ru_contest_ranks`
--

DROP TABLE IF EXISTS `ru_contest_ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ru_contest_ranks` (
  `cid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '比赛序号',
  `loginname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录名称',
  `rank` int(10) NOT NULL COMMENT '选手排名',
  `pscore` int(10) NOT NULL DEFAULT 0 COMMENT '选手总分',
  `ptime` int(50) NOT NULL COMMENT '选手总用时',
  PRIMARY KEY (`cid`,`loginname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ru_contest_ranks`
--

LOCK TABLES `ru_contest_ranks` WRITE;
/*!40000 ALTER TABLE `ru_contest_ranks` DISABLE KEYS */;
/*!40000 ALTER TABLE `ru_contest_ranks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ru_contest_records`
--

DROP TABLE IF EXISTS `ru_contest_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ru_contest_records` (
  `cid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '比赛序号',
  `data_rid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '记录特征值',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通过状态',
  `dscore` int(10) NOT NULL DEFAULT 0 COMMENT '记录分数',
  `problemid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '题目序号',
  `loginname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录名称',
  `sendtime` int(50) NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`cid`,`data_rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ru_contest_records`
--

LOCK TABLES `ru_contest_records` WRITE;
/*!40000 ALTER TABLE `ru_contest_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `ru_contest_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ru_contest_scores`
--

DROP TABLE IF EXISTS `ru_contest_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ru_contest_scores` (
  `cid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '比赛序号',
  `loginname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录名称',
  `problemid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '题目序号',
  `score` int(10) NOT NULL DEFAULT 0 COMMENT '单题得分',
  `scoretime` int(50) NOT NULL COMMENT '单题用时',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通过状态',
  `firstblood` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否一血',
  PRIMARY KEY (`cid`,`loginname`,`problemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ru_contest_scores`
--

LOCK TABLES `ru_contest_scores` WRITE;
/*!40000 ALTER TABLE `ru_contest_scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `ru_contest_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ru_contests`
--

DROP TABLE IF EXISTS `ru_contests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ru_contests` (
  `cid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '比赛序号',
  `cname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比赛名称',
  `cfavicon` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比赛网标',
  `clogo` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比赛图标',
  `ccolor` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比赛颜色',
  `caccent_color` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '比赛强调色',
  `clang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支持语言',
  `enable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否启用',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='比赛列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ru_contests`
--

LOCK TABLES `ru_contests` WRITE;
/*!40000 ALTER TABLE `ru_contests` DISABLE KEYS */;
/*!40000 ALTER TABLE `ru_contests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ru_global_settings`
--

DROP TABLE IF EXISTS `ru_global_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ru_global_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置项序号',
  `setting_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设置项名称',
  `setting_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '设置项值',
  `setting_remark` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '设置项备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='网站全局设置列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ru_global_settings`
--

LOCK TABLES `ru_global_settings` WRITE;
/*!40000 ALTER TABLE `ru_global_settings` DISABLE KEYS */;
INSERT INTO `ru_global_settings` VALUES (1,'site_url','','站点域名'),(2,'site_name','','站点名称'),(3,'site_favicon','','站点网标'),(4,'site_logo','','站点图标'),(5,'site_color','','站点颜色'),(6,'site_accent_color','','站点强调色');
/*!40000 ALTER TABLE `ru_global_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'rankupdate'
--

--
-- Dumping routines for database 'rankupdate'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-28 10:37:18
