/*
 Navicat Premium Data Transfer

 Source Server         : Cosmic
 Source Server Type    : MySQL
 Source Server Version : 100141
 Source Host           : 46.105.247.163:3306
 Source Schema         : cosmic

 Target Server Type    : MySQL
 Target Server Version : 100141
 File Encoding         : 65001

 Date: 28/01/2020 17:47:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for website_alert_messages
-- ----------------------------
DROP TABLE IF EXISTS `website_alert_messages`;
CREATE TABLE `website_alert_messages`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `message` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unacceptable for the Hotel Management',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_alert_messages
-- ----------------------------
INSERT INTO `website_alert_messages` VALUES (1, 'Use of language', 'Watch your language! You will be banned on repeated occasions.');
INSERT INTO `website_alert_messages` VALUES (2, 'Act as Staff', 'Acting as Staff is against the rules. You will be banned on repeated occasions.');
INSERT INTO `website_alert_messages` VALUES (3, 'Talking about retro hotels', 'Talking about retro hotels is against the rules! At repetition you will be banned.');
INSERT INTO `website_alert_messages` VALUES (4, 'Requesting/giving away personal information', 'Asking/giving away personal data is against the rules! You will be banned on repeated occasions.');
INSERT INTO `website_alert_messages` VALUES (5, 'Ask/give away Social Media', 'Ask/giving away snapchat, insta or other Social Media is against the rules! You will be banned on repeated occasions.');
INSERT INTO `website_alert_messages` VALUES (6, 'Unacceptable language/behavior', 'Unacceptable language/behavior is against the rules! You will be banned on repeated occasions.');
INSERT INTO `website_alert_messages` VALUES (7, 'Harassment', 'Don\'t bother other players! You will be banned on repeated occasions.');
INSERT INTO `website_alert_messages` VALUES (8, 'Sexual conversations/behaviors', 'Sexual conversations or behaviors are against the rules! You will be banned on repeated occasions.');

-- ----------------------------
-- Table structure for website_ban_messages
-- ----------------------------
DROP TABLE IF EXISTS `website_ban_messages`;
CREATE TABLE `website_ban_messages`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(75) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unacceptable for the Hotel Management',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_ban_messages
-- ----------------------------
INSERT INTO `website_ban_messages` VALUES (1, 'Advertising for Retro Hotels');
INSERT INTO `website_ban_messages` VALUES (2, 'Highlight one or more players');
INSERT INTO `website_ban_messages` VALUES (3, 'Illegal activities');
INSERT INTO `website_ban_messages` VALUES (4, 'Hate speech/discrimination');
INSERT INTO `website_ban_messages` VALUES (5, 'Pedophile activities');
INSERT INTO `website_ban_messages` VALUES (6, 'Requesting/giving away personal information');
INSERT INTO `website_ban_messages` VALUES (7, 'Ask/giving away snapchat, insta or other Social Media');
INSERT INTO `website_ban_messages` VALUES (8, 'Harassment/unacceptable language or behavior');
INSERT INTO `website_ban_messages` VALUES (9, 'Order disturbance');
INSERT INTO `website_ban_messages` VALUES (10, 'Embarrassing sexual behaviors');
INSERT INTO `website_ban_messages` VALUES (11, 'Requesting/offering webscam sex or sexual images');
INSERT INTO `website_ban_messages` VALUES (12, 'Threat of one or more players with ddos/hack/expose');
INSERT INTO `website_ban_messages` VALUES (13, 'Act as Staff');
INSERT INTO `website_ban_messages` VALUES (14, 'Username in violation of the rules');

-- ----------------------------
-- Table structure for website_ban_types
-- ----------------------------
DROP TABLE IF EXISTS `website_ban_types`;
CREATE TABLE `website_ban_types`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seconds` int(10) NOT NULL DEFAULT 7200,
  `message` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `min_rank` int(10) NOT NULL DEFAULT 6,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `min_rank`(`min_rank`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_ban_types
-- ----------------------------
INSERT INTO `website_ban_types` VALUES (1, 7200, '2 hours', 4);
INSERT INTO `website_ban_types` VALUES (2, 14400, '4 hours', 4);
INSERT INTO `website_ban_types` VALUES (3, 28800, '8 hours', 4);
INSERT INTO `website_ban_types` VALUES (4, 43200, '12 hours', 4);
INSERT INTO `website_ban_types` VALUES (5, 86400, '1 day', 6);
INSERT INTO `website_ban_types` VALUES (6, 259200, '3 days', 6);
INSERT INTO `website_ban_types` VALUES (7, 604800, '1 week', 6);
INSERT INTO `website_ban_types` VALUES (8, 2629743, '1 month', 6);
INSERT INTO `website_ban_types` VALUES (9, 7889231, '3 months', 6);
INSERT INTO `website_ban_types` VALUES (10, 946707780, 'permanent', 6);

-- ----------------------------
-- Table structure for website_bans_asn
-- ----------------------------
DROP TABLE IF EXISTS `website_bans_asn`;
CREATE TABLE `website_bans_asn`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asn` int(11) NOT NULL,
  `host` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `added_by` varchar(75) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `asn`(`asn`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_feeds
-- ----------------------------
DROP TABLE IF EXISTS `website_feeds`;
CREATE TABLE `website_feeds`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_user_id` int(11) NOT NULL,
  `message` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `is_hidden` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_feeds_likes
-- ----------------------------
DROP TABLE IF EXISTS `website_feeds_likes`;
CREATE TABLE `website_feeds_likes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_feeds_reactions
-- ----------------------------
DROP TABLE IF EXISTS `website_feeds_reactions`;
CREATE TABLE `website_feeds_reactions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `message` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `is_hidden` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_forum_likes
-- ----------------------------
DROP TABLE IF EXISTS `website_forum_likes`;
CREATE TABLE `website_forum_likes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for website_helptool_categories
-- ----------------------------
DROP TABLE IF EXISTS `website_helptool_categories`;
CREATE TABLE `website_helptool_categories`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_helptool_faq
-- ----------------------------
DROP TABLE IF EXISTS `website_helptool_faq`;
CREATE TABLE `website_helptool_faq`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category` int(11) NOT NULL DEFAULT 0,
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `author` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `category`(`category`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_helptool_logs
-- ----------------------------
DROP TABLE IF EXISTS `website_helptool_logs`;
CREATE TABLE `website_helptool_logs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT 0,
  `target` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `value` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `type` enum('CHANGE','SEND') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `player_id`(`player_id`) USING BTREE,
  INDEX `target`(`target`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for website_helptool_reactions
-- ----------------------------
DROP TABLE IF EXISTS `website_helptool_reactions`;
CREATE TABLE `website_helptool_reactions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NULL DEFAULT NULL,
  `practitioner_id` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `timestamp` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_helptool_requests
-- ----------------------------
DROP TABLE IF EXISTS `website_helptool_requests`;
CREATE TABLE `website_helptool_requests`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(72) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `player_id` int(11) NULL DEFAULT 0,
  `ip_address` varchar(75) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `status` enum('closed','open','in_treatment','wait_reply') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'open',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_jobs
-- ----------------------------
DROP TABLE IF EXISTS `website_jobs`;
CREATE TABLE `website_jobs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `small_description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `full_description` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for website_jobs_applys
-- ----------------------------
DROP TABLE IF EXISTS `website_jobs_applys`;
CREATE TABLE `website_jobs_applys`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `firstname` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `message` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `available_monday` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_tuesday` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_wednesday` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_thursday` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_friday` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_saturday` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_sunday` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('open','closed') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for website_marketplace
-- ----------------------------
DROP TABLE IF EXISTS `website_marketplace`;
CREATE TABLE `website_marketplace`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `currency_type` int(3) NULL DEFAULT NULL,
  `item_costs` int(11) NULL DEFAULT NULL,
  `timestamp_added` int(11) NULL DEFAULT NULL,
  `timestamp_expire` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_news
-- ----------------------------
DROP TABLE IF EXISTS `website_news`;
CREATE TABLE `website_news`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `short_story` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `full_story` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `images` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `author` int(11) NOT NULL DEFAULT 0,
  `header` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `category` int(4) NOT NULL DEFAULT 0,
  `form` enum('none','photo','badge','look','word') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'none',
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `hidden` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `slug`(`slug`) USING BTREE,
  INDEX `timestamp`(`timestamp`) USING BTREE,
  INDEX `hidden`(`hidden`) USING BTREE,
  INDEX `category`(`category`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_news
-- ----------------------------
INSERT INTO `website_news` VALUES (1, 'welcome-to-cosmic', 'Welcome to Cosmic', 'Curious about Cosmic\'s functions? Then be sure to read this!', '<p>Hey you! Who the hell are you?! Are you using CosmicCMS?!<br /><br />You managed to get Cosmic working!<br /><br />Our team has worked very hard on Cosmic to keep the installation as easy as possible. Cosmic has been specially developed with a new proprietary written Framework. So it\'s normal that you won\'t know how everything works yet, hence the installation in the beginning!<br /><br />Cosmic offers many functions, all of which are easy to use. Just think of adding currency ids, adding and editing furniture, managing the FAQ, managing and answering help tickets, VPN control, remote control (RCON), and much more!<br /><br />Since Cosmic already has the basic functions, you won\'t have to add anything yourself, but of course you can! Though you will have to learn the Framework first. We have thought about this as well. Our team has provided a documentation that will allow you to write new features in our Framework within minutes/hours/days/weeks! This depends on the skills you already have!<br /><br />Unfortunately Cosmic only works on Arcturus Morningstar, this means that you cannot set Cosmic to Plus Emulator/Comet Server or R63 Emulators. Now the question is, can I also use Cosmic on the normal Arcturus or upcoming Sirius Emulator? We advise you not to do this as some functions may differ between these emulators. If you don\'t want to use Arcturus Morningstar anyway, you\'ll have to convert Cosmic to the emulator you love!<br /><br />Cosmic is composed of about 42% JavaScript, 25.5% PHP, 17% HTML and 15.5% CSS. Now you will think, why is Cosmic almost half made of JavaScript? Cosmic wouldn\'t work without this JavaScript, because we made sure that you only need to load Cosmic once and you can control all functions without refresh! This also ensures that we can use a hotel that can stay open. This makes it easier to switch between website and hotel, but also to control the functions, since we can use notifications without refreshing the page and because of this your data is gone!<br /><br />Cosmic also has a shop and forum! In the shop you can buy GOTW-Points via paypal and/or phone, and you can buy vip with GOTW-Points! This can all be set via housekeeping. Because of the RCON system built into Cosmic, the user who buys GOTW-Points or vip doesn\'t have to leave the hotel or log in. Everything happens automatically and you immediately receive your GOTW-Points and/or vip! After the purchase the user can view their purchase / purchases through the purchase history page. The forum also offers multiple functions. You can create topics, respond to topics, like topics, report topics, pin messages, ... Members can ask questions or offer information that is useful for other members. The categories and so on can all be created/adjusted in the housekeeping.<br /><br />Cosmic also has a photo page. Here you can see all the nice pictures of members taken in the hotel. If you think there is a picture you really like, you can also give it a like. Or maybe a photo that is inappropriate does not belong on the page, you can report it and the team will delete it.<br /><br />With Cosmic we try to offer our members a nice environment. Because of the many features that Cosmic offers, this has certainly succeeded! You no longer need to communicate only through the hotel, but you can also do so through the forum, or if you have a try through the help tool. Parents will also be able to reach the team easily and they will gain more confidence in the hotel.<br /><br />Cosmic also owns a number of info pages. Just think of the general terms and conditions, the rules, a privacy statement, cookie policy and a guide for parents. All this to create the best playing experience for the members.<br /><br />Our team has also thought about the security of your account. You can use Google Authenticator. But what is that now? Google Authenticator provides a 6-digit code with which you can login to your account. This code is asked when your username and password are entered correctly. The code you get is changed every X number of seconds to ensure the security of your account. If you can\'t get this code anymore, because your phone is broken or for any reason, you can restore it via Google itself or you can report this to the team and they will reopen your account so you can get back in (if this is your account of course).<br /><br />When you visit a user\'s profile, you can see different things: which badges he/she has, who his/her friends are, in which groups he/she sits, which rooms he/she has taken and which photos he/she has taken. You will also find a guestbook on each profile page. If you want to let a user know something small, you can write it in the guestbook. Or you can let them know on your own profile what you are doing at that moment.<br /><br />The settings of your account also offer some special features: Can other users see your profile? Can other users see when you were last online or when you are online? Can other users add you at the hotel or follow you to other rooms? It\'s all possible! As an employee you also have the possibility not to show yourself on the employee page.<br /><br />If all this is not enough, you also have a very large and extensive housekeeping! In the beginning of this message a number of functions are already explained. The housekeeping has been developed in such a way that you hardly need to search your database for data. Because of the RCON system we were able to add some extra functions, like sending alerts, banning users, ... You can create permissions, manage rooms, manage users, block VPN\'s using ASN, manage the word filter, view chatlogs/banlogs/stafflogs, manage help tickets, manage FAQ\'s, manage the feed, manage news, shop and catalogue! As an administrator you can easily assign all these permissions to different ranks. All pages have different permissions, which can be found under the permissions tab in the menu.<br /><br />Changing your password is also no problem due to the implemented e-mail system. Users can request their password and will receive an e-mail in the inbox. There is a link in the inbox that allows them to change their password. You should make sure you use a secure mail server, so that your email is not spamming, as this can cause confusion for some users and/or parents.<br /><br />This was it for the explanation of Cosmic. We hope you\'re sufficiently informed about Cosmic. If you have any questions, you can always visit our Discord. The support team is always ready to answer any questions you might have.<br /><br />We wish you a lot of fun using Cosmic!<br /><br />- Cosmic support team</p>', 'https://images.habbo.com/c_images/Security/safetytips1_n.png', 1, 'https://habboo-a.akamaihd.net/c_images/web_promo/lpromo_SweetHome1.png', 1, 'none', 1536203060, '0');

-- ----------------------------
-- Table structure for website_news_categories
-- ----------------------------
DROP TABLE IF EXISTS `website_news_categories`;
CREATE TABLE `website_news_categories`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `category`(`category`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_news_categories
-- ----------------------------
INSERT INTO `website_news_categories` VALUES (1, 'Updates');

-- ----------------------------
-- Table structure for website_news_reactions
-- ----------------------------
DROP TABLE IF EXISTS `website_news_reactions`;
CREATE TABLE `website_news_reactions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL DEFAULT 0,
  `news_id` int(11) NULL DEFAULT NULL,
  `player_id` int(11) NULL DEFAULT NULL,
  `message` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hidden` int(11) NULL DEFAULT 0,
  `timestamp` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for website_notifications
-- ----------------------------
DROP TABLE IF EXISTS `website_notifications`;
CREATE TABLE `website_notifications`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT 0,
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `type` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_read` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_password_reset
-- ----------------------------
DROP TABLE IF EXISTS `website_password_reset`;
CREATE TABLE `website_password_reset`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `email` varchar(75) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `ip_address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0.0.0.0',
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `token_expires_at` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_permissions
-- ----------------------------
DROP TABLE IF EXISTS `website_permissions`;
CREATE TABLE `website_permissions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'housekeeping_',
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_permissions
-- ----------------------------
INSERT INTO `website_permissions` VALUES (1, 'housekeeping', 'Player has access to the housekeeping.');
INSERT INTO `website_permissions` VALUES (2, 'housekeeping_remote_control', 'Player is able to nd may adjust other players account information, except the ranks.');
INSERT INTO `website_permissions` VALUES (3, 'housekeeping_ban_user', 'Player is able to ban users from the control panel. A rank system is included in this permission.');
INSERT INTO `website_permissions` VALUES (4, 'housekeeping_ban_logs', 'Player is able to view which players have been denied access to the hotel');
INSERT INTO `website_permissions` VALUES (5, 'housekeeping_staff_logs', 'Player is able to view all loggings that staffs have committed in the cms');
INSERT INTO `website_permissions` VALUES (6, 'housekeeping_chat_logs', 'Player is able to read chat logs from other players');
INSERT INTO `website_permissions` VALUES (7, 'housekeeping_website', '\r\nPlayer has access to the website category');
INSERT INTO `website_permissions` VALUES (8, 'housekeeping_website_news', 'Player is able to manage news items');
INSERT INTO `website_permissions` VALUES (9, 'housekeeping_ranks', 'Player is able to change ranks of other players');
INSERT INTO `website_permissions` VALUES (10, 'housekeeping_permissions', 'Player can add/remove permissions for other players who have access to housekeeping');
INSERT INTO `website_permissions` VALUES (11, 'housekeeping_ip_display', 'Player is able to see IP addresses of other players');
INSERT INTO `website_permissions` VALUES (12, 'housekeeping_reset_user', 'Player is able to reset the player (motto, look, relationships)');
INSERT INTO `website_permissions` VALUES (13, 'housekeeping_alert_user', 'Player is able to send warn the player');
INSERT INTO `website_permissions` VALUES (14, 'housekeeping_room_control', 'Player is able to see rooms but not able to edit the room');
INSERT INTO `website_permissions` VALUES (15, 'housekeeping_moderation_tools', 'Player is able to use the moderation tools on the website');
INSERT INTO `website_permissions` VALUES (16, 'housekeeping_website_helptool', 'Player is able to handle the helptool');
INSERT INTO `website_permissions` VALUES (17, 'housekeeping_change_email', 'Player is able to change mail adresses');
INSERT INTO `website_permissions` VALUES (18, 'housekeeping_website_feeds', 'Player is able to remove feeds from the website');
INSERT INTO `website_permissions` VALUES (19, 'housekeeping_vpn_control', 'Player is able to ban of unban AS numbers');
INSERT INTO `website_permissions` VALUES (20, 'housekeeping_wordfilter_control', 'Player is able to manage the word filter');
INSERT INTO `website_permissions` VALUES (21, 'housekeeping_website_rarevalue', 'Player is able to change rare values');
INSERT INTO `website_permissions` VALUES (22, 'housekeeping_website_faq', 'Player is able to manage the FAQ');
INSERT INTO `website_permissions` VALUES (23, 'housekeeping_shop_control', 'Player is able to handle and see purchase logs');
INSERT INTO `website_permissions` VALUES (24, 'housekeeping_ranks_extra', 'Player is able to edit the extra rank');
INSERT INTO `website_permissions` VALUES (25, 'housekeeping_staff_logs_menu', 'Player is able to see logs in menu');
INSERT INTO `website_permissions` VALUES (26, 'housekeeping_config', 'Player can manage all the config settings');
INSERT INTO `website_permissions` VALUES (27, 'website_invisible_staff', 'Hide rank from staff page at website');
INSERT INTO `website_permissions` VALUES (28, 'website_extra_rank', '');

-- ----------------------------
-- Table structure for website_permissions_ranks
-- ----------------------------
DROP TABLE IF EXISTS `website_permissions_ranks`;
CREATE TABLE `website_permissions_ranks`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `rank_id`(`rank_id`) USING BTREE,
  INDEX `permission_id`(`permission_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 105 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_permissions_ranks
-- ----------------------------
INSERT INTO `website_permissions_ranks` VALUES (0, 1, 5);
INSERT INTO `website_permissions_ranks` VALUES (1, 1, 7);
INSERT INTO `website_permissions_ranks` VALUES (2, 2, 7);
INSERT INTO `website_permissions_ranks` VALUES (3, 3, 7);
INSERT INTO `website_permissions_ranks` VALUES (4, 4, 7);
INSERT INTO `website_permissions_ranks` VALUES (5, 5, 7);
INSERT INTO `website_permissions_ranks` VALUES (6, 6, 7);
INSERT INTO `website_permissions_ranks` VALUES (7, 7, 7);
INSERT INTO `website_permissions_ranks` VALUES (8, 8, 7);
INSERT INTO `website_permissions_ranks` VALUES (9, 9, 7);
INSERT INTO `website_permissions_ranks` VALUES (10, 10, 7);
INSERT INTO `website_permissions_ranks` VALUES (11, 11, 7);
INSERT INTO `website_permissions_ranks` VALUES (12, 12, 7);
INSERT INTO `website_permissions_ranks` VALUES (13, 13, 7);
INSERT INTO `website_permissions_ranks` VALUES (14, 14, 7);
INSERT INTO `website_permissions_ranks` VALUES (15, 15, 7);
INSERT INTO `website_permissions_ranks` VALUES (16, 16, 7);
INSERT INTO `website_permissions_ranks` VALUES (17, 17, 7);
INSERT INTO `website_permissions_ranks` VALUES (18, 18, 7);
INSERT INTO `website_permissions_ranks` VALUES (19, 19, 7);
INSERT INTO `website_permissions_ranks` VALUES (20, 20, 7);
INSERT INTO `website_permissions_ranks` VALUES (21, 21, 7);
INSERT INTO `website_permissions_ranks` VALUES (22, 22, 7);
INSERT INTO `website_permissions_ranks` VALUES (23, 23, 7);
INSERT INTO `website_permissions_ranks` VALUES (24, 24, 7);
INSERT INTO `website_permissions_ranks` VALUES (25, 25, 7);
INSERT INTO `website_permissions_ranks` VALUES (26, 26, 7);
INSERT INTO `website_permissions_ranks` VALUES (103, 27, 1);
INSERT INTO `website_permissions_ranks` VALUES (104, 27, 2);

-- ----------------------------
-- Table structure for website_photos_likes
-- ----------------------------
DROP TABLE IF EXISTS `website_photos_likes`;
CREATE TABLE `website_photos_likes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for website_rare_values
-- ----------------------------
DROP TABLE IF EXISTS `website_rare_values`;
CREATE TABLE `website_rare_values`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cat_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cat_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `is_hidden` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `discount` int(3) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for website_remembered_logins
-- ----------------------------
DROP TABLE IF EXISTS `website_remembered_logins`;
CREATE TABLE `website_remembered_logins`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token_hash` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `expires_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 188 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of website_remembered_logins
-- ----------------------------
INSERT INTO `website_remembered_logins` VALUES (2, '949ba9015d5071e5a5f5ab884a8fb6fee16584cf4a6ab54483e4bda6402a9ba7ebbd81fbeb739274221881a2caeb9c4b73a758d431fd23b5dc0726e73aa7eb1c', 70, '2020-02-10 04:18:48');
INSERT INTO `website_remembered_logins` VALUES (3, '40d0459f411d30ef601225a4b365544c2adb1bbd612694a04dcadff4425244eaee6830e3ad6feb5dbab90005cbe7ff0dcb3aa3e18cff3b6543bbf44a239c21dd', 1, '2020-02-10 14:42:01');
INSERT INTO `website_remembered_logins` VALUES (4, 'bc40ec2529d1f22bba9e6e2b81816bb4a184d72eecbbb924aa9aa46ac0ee8e69305a223b7470848941142fe913212c5c7af5d5cf03dc70d711e8a7120cb5f426', 22, '2020-02-10 15:19:35');
INSERT INTO `website_remembered_logins` VALUES (7, '49fc9267ef81466197c17f300983450a2861b6c6bb047452a1303f5be18df921526eee298d99928b9b712d87465fe31d20387aeb9e7494451c95d5bbca9dc06d', 120, '2020-02-10 21:09:49');
INSERT INTO `website_remembered_logins` VALUES (10, '3df931e726a460133b16f563c77cceda666b0b1a7f513fe918303d0b22e58304cd45fa31a7c004ef582f53bfec0aa92adf7b13a9ad6c93dabb49db1372a78b04', 1, '2020-02-11 01:30:03');
INSERT INTO `website_remembered_logins` VALUES (12, '7c860ff3a10b547a5c6cb7c6c854623f2e01c12444ea081cd1fe23d39205925e4f83c2712e8299063689cc267afb097d9a27887a65d0134fcc6df447bea99ec6', 116, '2020-02-11 12:57:51');
INSERT INTO `website_remembered_logins` VALUES (13, '29563cb4b0c5a9cea982c77fabab9f19cd3784eaec05942ed7e6a12b050940636f5c35a8cd01c1ac98f43e050f377e0c2969b82af2a2528d9b8b584a931dfd56', 106, '2020-02-11 17:07:25');
INSERT INTO `website_remembered_logins` VALUES (14, '053c1bcef33fa710dfaeb6a01f8ae8ba9ab39976a7c2878fcd57c0eea4724a917c68c2bb13069612b851a9957de36b3a67b7ec9c1a066996c18136440cbc495d', 1, '2020-02-11 18:57:08');
INSERT INTO `website_remembered_logins` VALUES (16, '2391e2d9a85c7d152c91f4a82999ab8ab10e8576a4dec76d47ce1b5d404589695b2bb8056ebeb42558bbaaac719e411b4ce366f9c595850b64fc3f72f5f0630c', 125, '2020-02-11 19:59:18');
INSERT INTO `website_remembered_logins` VALUES (17, '0af6c0db6b707a3cb397a17a98a25c1efeef10df174967781a558c01a5be892ca9e21aeaf659919f371f01579ba963284078b7a3e52794642c1bcee5e2366265', 83, '2020-02-11 20:12:27');
INSERT INTO `website_remembered_logins` VALUES (19, '2fcfab24f0bc2dc837ce9345774a9646a94267d13debbfd43e17687187fad5245ed39300abb7c871b7d4149b7676dc17bc229acc9d02acdcbbc3eedab8fe7791', 8, '2020-02-12 00:59:50');
INSERT INTO `website_remembered_logins` VALUES (20, '1026e678da645779bdbb19a8035483233d0adfa17b2487281c4dc7058232ff596edf250580d568cf446aa5f7b1f6add5fd697fbe75de61989fe6a368f6cac350', 8, '2020-02-12 02:50:43');
INSERT INTO `website_remembered_logins` VALUES (23, '3e55ced13fd8292db0721834d980db5a6390d3dd45d16e798ef2fc0d721c76b5ac8f383a4a2df2a85fc679f8b25c4b744eb5a70c4ba1f56ca19e6d8f8a252831', 1, '2020-02-12 10:10:18');
INSERT INTO `website_remembered_logins` VALUES (24, '2b76eb5a6a1e979197920e52f1afad83b36bc26bd26feb663010a9c2a9c14a06053aec7c7df93cf932e42a5158488c1e701a714bddb1dc80cb11be43e62e08fe', 8, '2020-02-12 14:02:47');
INSERT INTO `website_remembered_logins` VALUES (25, '415513967f9c582a56625fce6cce0b9e75241daf8e0bbdf58cadf11f81956efaeb16d36bea32a792aa84fab34e8b21aca03ad60af1c1295dc3777c0c86c9d27d', 106, '2020-02-12 15:21:11');
INSERT INTO `website_remembered_logins` VALUES (26, '0d4d72f1c474b9da3aa0a77b4027a42f5b72ef2afe6d081b6980918e30c0a6deba1ed511f6ed2d61fa17916035f5ebda4c7e5ca819ebd0df6674e38092d349a6', 83, '2020-02-12 18:26:15');
INSERT INTO `website_remembered_logins` VALUES (28, '5824cddc74044701a3a3c49e373ffa0362470dd15dfa1393dfdd1d22632e26c637f09c1a52ba03745584acf3392f81846af4106ca5a7d7694d3daa0de722852f', 83, '2020-02-12 21:12:25');
INSERT INTO `website_remembered_logins` VALUES (29, 'b2db9569d84ffc9e1c5f5cc9ef2a8a40157b872819cdaec06e849920c943938846c1e470587479bc2faa01891cfd70f083a0ae6479d445347659c7f17000b244', 135, '2020-02-13 03:33:02');
INSERT INTO `website_remembered_logins` VALUES (30, '246d80fa009bea02fcd3df5e8b729f35e3b4d796ffe4c531c82a71fa7141e06f6801bdea74e63982ece1ebbb8dd3e2e290e55ad04757e8da5d1659295f997d81', 136, '2020-02-13 04:50:46');
INSERT INTO `website_remembered_logins` VALUES (31, 'fd7670f96ad680ddf172bf40b125373062ff2deb836054d7122a0f45ee8020dc16fb3773b814fcb4aaeb759aab3aed865a975a137dd47e8816ec557ab0c76de1', 125, '2020-02-13 06:08:04');
INSERT INTO `website_remembered_logins` VALUES (32, '01b4e9325397f558183d2dd109fcf6456650b85889104d83877dd4a1b8ac013447d2ba5e34da7b958b623220e0c0cd6f4c85fef70db350496b1946cf949954a5', 110, '2020-02-13 07:54:01');
INSERT INTO `website_remembered_logins` VALUES (33, 'd8e311bfdd4db89f41c2dca244d2da308708c9bea23bec35d636d5fe43c051e28b8f8bde9e45f437d0a68613b183a2584f3340409045e9be87282a3caddd750a', 1, '2020-02-13 17:02:24');
INSERT INTO `website_remembered_logins` VALUES (37, '62e60654e07b3b6bac972103478b99447024e6d0e0c694b4f9a35a92e9954370222784c44e0e4290a2d35b9b348db1901c8f0155a8532694acedf42466992c20', 89, '2020-02-13 18:02:16');
INSERT INTO `website_remembered_logins` VALUES (40, '36375a96367912ddd2ce60f942a1878b50538696261142b856eeadf07906410f4bc19044bba411b609793549bf934e46e9cc15b0af5127de5ade36dc694e67fa', 83, '2020-02-13 20:22:51');
INSERT INTO `website_remembered_logins` VALUES (45, '7bb5cfb944a7800b16fbff0d6d6de0ecf1c9e203bec0bd0e4e5fc636d99e2adc92c6349ba2f515fdc573676f27401ab7f47d04895fdbfd451cc852c481586436', 103, '2020-02-14 08:58:08');
INSERT INTO `website_remembered_logins` VALUES (46, 'af29fadb18a63e5f40db5b5e84b6950a5dbd1c278e31110f36db63eaa3c5eb4e5a47097659e57f1f14d5132ec273094ece1a70abfee70e7f53fb92bc92f8721c', 103, '2020-02-14 11:56:53');
INSERT INTO `website_remembered_logins` VALUES (51, '0e5d72001744a6b21e4a4c0be9b99d9b7b0849313097c2ef55f185cc5433d4aeb125666514493d73e4b0abb60b98a91767cf4f2a92f762e577c144f128a1e698', 59, '2020-02-14 15:43:37');
INSERT INTO `website_remembered_logins` VALUES (52, '9bf973e3273d3d5bec1bb61284f9787e279ff96fc1f765c3c28f5d0affd3a2896df67bf74f8617e7ab717d5c02f5eecc70490ac7a250e76b29fc9c2799dd2d44', 139, '2020-02-14 20:07:14');
INSERT INTO `website_remembered_logins` VALUES (56, '57f7970615c00236839ee1defe9fc159f3565498ad5b541ba19c08c0e18080db3d88aa57d2ef4986d1aa87de65d52325031370fff0c81ba5d71b6ed57add64d3', 144, '2020-02-15 04:29:12');
INSERT INTO `website_remembered_logins` VALUES (58, '9f1518c3c2346f28a01f0371d21b85d690abdfa043a634feea82ac94236a5fc900f361ac09404e83561d6d38dc462e3d848c70fa6e7de9302b978d171dca49d2', 103, '2020-02-15 09:07:50');
INSERT INTO `website_remembered_logins` VALUES (59, '7d2679bba0429638eea08c03f7b30213c57db52fd2cb68fe011218093bb6bf8830ee06224bdd6a28aa6b87e3e0a70a2dec28a7143c7ebd7080d68fd01edf386a', 145, '2020-02-15 18:02:21');
INSERT INTO `website_remembered_logins` VALUES (64, 'c96ea93df051ad7491461e6f7e60343e269e1966cf11b4bdd6f156088afb100ec2ed192de45b54f71258855353cbfd031e5cfe3e0f8f269454aba24032baf344', 3, '2020-02-15 18:28:26');
INSERT INTO `website_remembered_logins` VALUES (65, '6c91d4d8a574194b93f9a58847da09e7c6e756de5f475d47765e3f97e3e64040f3a69b4672c834599915a298dd3b1f32921bd93840956370e13ebf09396d59b9', 103, '2020-02-15 21:48:20');
INSERT INTO `website_remembered_logins` VALUES (67, 'd6eb6613993597e286682f1faa1dd6d24e4af10f2f06466af2492b8f5ea2fe26090ba4bdafbf1c610b4f7e50e9dfba29b94da5e29f783bbdfad9590cdfe82205', 83, '2020-02-16 21:23:32');
INSERT INTO `website_remembered_logins` VALUES (68, '50d6de86f74a7bb38de46c08d59a8926ebf708d9a4e2da81df56c91ce9b98bead1dc61d851a2f858fc8d69824d306c4dd7e9a8122e52d028578f6be3af9c2fd7', 25, '2020-02-16 21:24:00');
INSERT INTO `website_remembered_logins` VALUES (78, 'fd6a004ab3b03b256044be82c352619be29308041e950b35dc37142b07dd3cf189100a0b524b1ce330c70cfed0493041faf7fb28a1fbf7b13706696151aded7c', 1, '2020-02-17 18:26:33');
INSERT INTO `website_remembered_logins` VALUES (79, '1536e6587692dcbd2366a059fbfd87cc78a595ccff276c1187f0ea9495d0f0d322291eccbeb3777d2699614f8b1bee8da4ff02b361ed7d7cd17405919b817a72', 1, '2020-02-17 18:26:33');
INSERT INTO `website_remembered_logins` VALUES (80, 'b8dff68b6a52df5815184f714f6219308f8a0c2d59303128607d367c8110c052a83c81062212411cbd9ec35a61dd7ba16140324dcdb1bb1e81efbe9f0fe39eab', 1, '2020-02-17 18:26:33');
INSERT INTO `website_remembered_logins` VALUES (82, '06698d6b2684750b6b6cf1005241f3dfee187d07eba31fb8c85b9bbeb4708bec9f483cbc86145e47ea84f13eb695df1a1a0bba28a5e38bd31750ff598fb0b29b', 1, '2020-02-17 20:03:02');
INSERT INTO `website_remembered_logins` VALUES (83, 'b94e41a22d259a8e06eef001554f3003132cacad058b821b1202359f7f761cdc76fd2dc8d615478546578360163acd51a28403d1ce6c9ee57962b3d8b298642d', 1, '2020-02-17 20:03:02');
INSERT INTO `website_remembered_logins` VALUES (85, '15054e22b89412505cc291e2fa73ff6c6b803c8f6eded65c595f7244065c554a89a2d784eb956fcc7dda13a097bfadab5db07459184704a0a5cdd1d8e7d2514a', 1, '2020-02-17 20:03:02');
INSERT INTO `website_remembered_logins` VALUES (92, '4309510f707e672c3f5095530393909e9240f6ad9cb907a357b2f99489570eb1d02210a45a9db05a07c10684bd6fea539bbfa3b5ff3f57c90eceeac4efcf9993', 70, '2020-02-18 00:09:40');
INSERT INTO `website_remembered_logins` VALUES (93, '6d2cf191a5d1b08f9979330c54410db53477e91554d1dcea07af8a09a95cadad0d560c33b9144bf2b312da3f48a5c5797b927b48c3fdf0f7f7c6765d1aa6594d', 1, '2020-02-18 01:28:59');
INSERT INTO `website_remembered_logins` VALUES (94, 'ee7fb4f0367fae121d1853df703c95e57b136e2b8d8bef59766fa87a6b61eb7377e57b51dfe19d44fa1437225ed7456aa59f8235754923b4c5eee5be914080df', 1, '2020-02-18 01:28:59');
INSERT INTO `website_remembered_logins` VALUES (95, '07a12f812ccf378145b48a66bfc08955b3aa5d6f9fd7349b0b91a5a80b6e7e6fe1ed0095bcd1f23be9f14524925489e836d8a174b9cd468a216a705eb1b51e75', 1, '2020-02-18 01:35:09');
INSERT INTO `website_remembered_logins` VALUES (98, 'b85435b25df7f31ddd6e8bfe3752605255befd7e7f16b3a135fdd83d527c0650dedaf990d25a1bc7318994868b580a051d9631392d432f20425169dfed65116e', 141, '2020-02-18 08:30:18');
INSERT INTO `website_remembered_logins` VALUES (99, '3b137f45f1eea543a3edc3da6cb736a0a589f57044abb0ce3517b5f579de700c438f5ecaf736b3d191740c67c14153dc13bad6597009b7439ee751f942b38b34', 103, '2020-02-18 10:10:15');
INSERT INTO `website_remembered_logins` VALUES (100, '386ceb7e653ce89bde297c101d10cfe10eccc868e8db508c14fbaff421f856ede262af6d1f078535c21bcaaaaba1088e399663ac7d4440c880a4cadc62d8df37', 155, '2020-02-19 05:29:39');
INSERT INTO `website_remembered_logins` VALUES (101, 'cffca747acbdd6184b8df014de8fe536401d270487dac0dea977e5956ec6d8061882fe784670c6910604aaa776c0a600fba23bcad6f4466358ba9bb7acc1213a', 1, '2020-02-19 09:55:19');
INSERT INTO `website_remembered_logins` VALUES (103, '6962f551107548b011aac02b0f8f63e9d0899f66b79a12635e2dc3be8c1c268369b4a0c6945d6b02d94fc0091dd2bbdde575a152feefca11fb59b6e130a9550e', 1, '2020-02-19 20:34:10');
INSERT INTO `website_remembered_logins` VALUES (105, '04908bf873ff0ed103dc495a56a95080fc691ea78dc0c71c16c734678c956bac49068a0ca9829dfab62f92cdc84782b9deba5de9bc961c92d72aff484270232d', 1, '2020-02-19 21:00:15');
INSERT INTO `website_remembered_logins` VALUES (107, 'e9372a37985e77c3559b682591afc04c95e60bb407a2674e7d5a15985d68be77b92db3612e429c838536c64fb341ddd667bfa54b47b057b21324a2107923c5d5', 1, '2020-02-19 21:02:45');
INSERT INTO `website_remembered_logins` VALUES (108, 'e4e135882ee0ffa8af6d1012f3f1eec071832bd597e2fdc0ce74098a3832200307e4ba6e087509814787fdf20f287da1b58c0d74ca62c1089061dc9cba468fb6', 8, '2020-02-19 22:35:49');
INSERT INTO `website_remembered_logins` VALUES (109, 'd6f3d5bc8f2805b95f7b4d102c93b674ecb7154ecc77ca975dbe10515fd780f1009f7526cacdc85727513d9deecf800fa9b59082a262b88cfde21ffc134779fe', 70, '2020-02-19 23:12:37');
INSERT INTO `website_remembered_logins` VALUES (112, '60c388ca5ec38b77416031506ee07d568711ed1ab5e39057ded6c16c748fc876ab0997f511e05f235baab7a13ded099a7c4cd220ce0a2001e6b3cd331d72c17d', 89, '2020-02-20 01:25:38');
INSERT INTO `website_remembered_logins` VALUES (114, '2b19552b9f73c902ecbcbf83d65f80c26e1285c2aa492b952bf78db6f9b2d52f76835a2c14a14cac5215b1aaac6a0119eefdf7468be9884e9491bbcfccb70b7d', 125, '2020-02-20 01:35:23');
INSERT INTO `website_remembered_logins` VALUES (116, '88f9b91558d43dd1a03390f53ed2a74753b239b77155ab31e3ee5caded85e995d018e2392f32ef1120a49185d7ec5f7d45e792c0b6a0dd9c1944c62a0666e66a', 125, '2020-02-20 03:24:42');
INSERT INTO `website_remembered_logins` VALUES (117, '3ce40d5e01f9135c91705a1364d732492d5d3649063bde1c16ccee2e53944eb6e35d2cac9da6f6c898d9b1ac85785e71ecb52b99e307d687cbd67a067abc83c3', 125, '2020-02-20 03:24:42');
INSERT INTO `website_remembered_logins` VALUES (118, '32368f811caaee29311522ef9cd813dfe3446684a39a689f8e5b05e161533355eb5b36ed22e83e9d7db644d2af97e30903ad8166f9e351c2452155d5af5ea185', 160, '2020-02-20 15:14:43');
INSERT INTO `website_remembered_logins` VALUES (119, '81d01ca8599e9d93fb7a6ead8d155b4e77eb4378852fa25449c01047c017fd9bbe9dd57d6cb07865e39d4b3bc2a422be7e312abe99cee5d057a39e8a11487b97', 1, '2020-02-21 01:09:58');
INSERT INTO `website_remembered_logins` VALUES (122, '63b3f8800b48764e481d1d5f7a1ae09bac8592112f42b35658eff2cb108621bdc7128a5738a9d356ee5291de6ad75df0377e8ecc432a44dbb49a5f191cd43fd0', 89, '2020-02-21 01:23:40');
INSERT INTO `website_remembered_logins` VALUES (124, '00a80be3dfa77f9edcc678cf6cd86ab863c7e03d27848760c0df299382a9bfbc68f87306ac4292d20e00656ba644b92347cee70d0b364575e0682dc98b01fef7', 1, '2020-02-21 02:00:54');
INSERT INTO `website_remembered_logins` VALUES (129, '5fd62cb8d510eccd7253ea4ecc72e55b29f380e99dda84a362d1c40038392fe99dea599c490efbeb8fe5d15eb0fd6a6eeaa137578a36f09542bebf08ff98fa85', 125, '2020-02-22 02:36:24');
INSERT INTO `website_remembered_logins` VALUES (130, '5bb75dc804ebfd4959b882f30a354ecc3782d19e2f4a5782c9d6f70e6a742a4e2db90b0ae0f0254003e761c9a84674f295beff3dfa50b5c2182de1b9257776cf', 59, '2020-02-22 14:27:31');
INSERT INTO `website_remembered_logins` VALUES (131, '015e7130dc64a0b5703cdde8e99ad96ba91802b9c0726d4c77dce933a216e55142bd2b3f182609a360dc882265120fed6ac8dbaf0982d6d293f7142e82506215', 165, '2020-02-22 16:54:52');
INSERT INTO `website_remembered_logins` VALUES (136, '49dcd04fd943f477df81e23b41227f2cb12daa0a24d27c20b09b809cd71ba3162c9be0a54c0550d07e28940786dffecf7d79e082e62e662ddd1afbc7a70fc484', 1, '2020-02-22 22:03:36');
INSERT INTO `website_remembered_logins` VALUES (139, '995f35e01cac2844eafe977f385c5191f3faf062310f41c911d49478200fa340b4c6ac81e142befe8019619886bd8ba6be8084a24d552993b80a4a251cbba04d', 1, '2020-02-22 23:33:32');
INSERT INTO `website_remembered_logins` VALUES (142, 'c1cdab025958329ff77dcc93eac713d911199e2261ffef42eb18a87bbab30206fe7693f8d08e9fb298b44db40cf1ce89f95552e22aed50405f8441e146cfea89', 151, '2020-02-23 12:06:41');
INSERT INTO `website_remembered_logins` VALUES (143, 'f92463e33952e2993f7689e859183cfb6ca87e1040ffe00f106952469e35a8b1589c3ed6fe3c367de680670c10a51c53b0b84a985f4001c7feb085f7b340bc48', 151, '2020-02-23 12:06:41');
INSERT INTO `website_remembered_logins` VALUES (145, 'ab680cdc2b8abba3ef8d2e4d054a9dd2ba9e7660b96f31bd9561959b0d054fc479e64c3f74484ba56de4a344a0bb127bcfce5920f49a9f6d52ad2dd6d3ac970f', 151, '2020-02-23 12:16:07');
INSERT INTO `website_remembered_logins` VALUES (146, '3c22925a4b26cc04a4a0b495fe8ebc57a43ff5b4573439078cc191a262f0ad3aecf34fc34dbcd9890a639733bc5d099d1ac911dc42b67c2f389dff7d858a365d', 151, '2020-02-23 12:16:07');
INSERT INTO `website_remembered_logins` VALUES (147, '4047f22e0c23487384ea2b7c0956dd875ebd1f94c9228663bfc536cc862503be047052fa3fd636dad0e01efc2b300e82d2c8ef1341d8c675e555309945e4622f', 125, '2020-02-23 12:23:53');
INSERT INTO `website_remembered_logins` VALUES (148, 'c72b99fc0f473a9a704579972b3c7f275e62a34a0237e270f2b681db8fdfcd4e5c72d297fcd089735ce88402df6bcca848ed1a2c2ed8a11571edde36ac04c432', 1, '2020-02-23 21:36:32');
INSERT INTO `website_remembered_logins` VALUES (149, '4860063052582bca7806c177bfbe6ee4e4d3153b22c03abbe9b12f8e95776adc4283a827a216fdb318e6700b08e003947c3bed16120cc10a7f039a08cd62aad9', 1, '2020-02-23 21:36:32');
INSERT INTO `website_remembered_logins` VALUES (150, 'e3403159f9d70464be230645745986e82fce6fd4f78bf019cdcee1c4779015660e5f6152e597e3c391669c7a4fb1c8742daf1b3a1ffab40a5f8b846f3623adf5', 1, '2020-02-23 21:36:32');
INSERT INTO `website_remembered_logins` VALUES (153, '8cfa30ffd050172ed75d7e17bf6236b194ed68e0b5c5ec4623e60c2d3bba298e292846278ccf504611fb4a6798d0583a64c52f81927e8943adf5faf5040238ca', 1, '2020-02-23 22:30:42');
INSERT INTO `website_remembered_logins` VALUES (154, '63a97132842450834f8ec591b2859f90d09538145f5759673dfc89e109e2e5a8d61ccff826b2f29cc7a6040437ddca47ae24c88f7b68feed75dc17fbf2531ac8', 1, '2020-02-23 22:39:13');
INSERT INTO `website_remembered_logins` VALUES (157, '8b12a7d7cd7dafce2f21f1de28ae737a60529e989911897c3b096abe7723645fb860feb60d016adb83c07f3cdea26e560fbe99e2747ad52890807cc4f630dfa8', 103, '2020-02-24 20:58:06');
INSERT INTO `website_remembered_logins` VALUES (158, 'f76a8bf5e0c6bb443d8cd1eec77e0d06b02414ef697aa3666f27c4543d18424eb28267b33c54f4a52f7fcf18abcdbadf2b5b756ab87d5d9a627ca3f6e94ee013', 172, '2020-02-24 21:23:32');
INSERT INTO `website_remembered_logins` VALUES (163, 'a8856f4351679b4eb09724904108233995c84bb9a54a2cfd1ecfe03971e2dd28758a75cf3aa9a27397ad88ccd3e510b41bc707f5f46b8a11c64d7b284a8368f3', 117, '2020-02-25 17:55:05');
INSERT INTO `website_remembered_logins` VALUES (165, '44b7e42800fad260489df308d8b95b45dcb2ab6c3a18a3d253bff045ab5a61bf52022fd3beb491a504c873551e0b139a3d60efb58041d31aa651825c8e6d9f85', 1, '2020-02-26 19:23:28');
INSERT INTO `website_remembered_logins` VALUES (167, 'b2cfdd8d949725f7f67b6d93102a31299f957d906ab03649a4bb4cb0989d04ab645e7fd0ad873b6544cb0641613910b123fa9538ff3fa6c77d77b97177eee468', 1, '2020-02-26 19:23:52');
INSERT INTO `website_remembered_logins` VALUES (170, '41488e9f1f835569768bea6976bc7bcfff15bb41c11bc40187112dce8d07c69fed10b924fbc332b81a7c9af613fc0e54074999a90191aa7d586b0a706b634316', 1, '2020-02-26 19:27:59');
INSERT INTO `website_remembered_logins` VALUES (172, '55e5873de8273e3cd2f7849a313972a490b0f73c2676303210f11a8dd74022f295d1081c260172c472ce98f85ef7f8b9bd1cd3273e01a9f0104c2fe9ede73c1a', 177, '2020-02-26 19:28:12');
INSERT INTO `website_remembered_logins` VALUES (173, 'db94618489788ccf91685f51ef36d8dc548f52e25172e7d6396a21bb540028b0c7adb7ca893701114b16c581dfa53d54820f567bac317f37524ba0133203b39a', 177, '2020-02-26 19:28:13');
INSERT INTO `website_remembered_logins` VALUES (174, '8d04368efc74df5f572eac314b32a9b5be8f1f290d5c8182c84dc68217ca67a8e806ece3c9573c9ca58547b4940caed2539d006e607ad79b0f06f0813ddd600d', 177, '2020-02-26 20:14:21');
INSERT INTO `website_remembered_logins` VALUES (175, 'b627268cfd5fab49b65fd0dbf1fe870356a12dbec1f7212253ba6fbea27dde7c676c9e5b3cd40c497f9913043d2190c5689ddfaa06aa7f4f14c63d86f9c68e79', 177, '2020-02-26 20:14:21');
INSERT INTO `website_remembered_logins` VALUES (177, 'ceb6fa48cfcd9a9a74a37f7bd1fa1d375ed440394114f78ce45162ecd8b32b0d13bd6100bc7fdc65ade93b12e346815ea123c6b550d9a04062d0bd64bf37d69f', 1, '2020-02-26 20:18:04');
INSERT INTO `website_remembered_logins` VALUES (178, 'a93e14431ca0a6dd2b8280f650e7563a988a6f31fb849b56c9333553b6a964b1f5eed502a8b7e6b0736c919d8d0998df07354846aa268d1a071e09ad565db9a8', 1, '2020-02-26 20:18:04');
INSERT INTO `website_remembered_logins` VALUES (181, '480ea88c60d1cd83ffa711afcdd84a11edfe613178b4a606ab8c90937a0aaf27b3902169532c5f9105d795d1cff227c6cb8d1b72d990cf8c9315214940861c30', 1, '2020-02-26 20:30:28');
INSERT INTO `website_remembered_logins` VALUES (184, 'e25f206a3adca0d8da2a8ed83179092330164c0743e263e38297039a945a488828d7e9a2b30a81d0cf2c073272892e523d98962b89c2f2818cf8edea2d087e04', 177, '2020-02-26 21:08:36');
INSERT INTO `website_remembered_logins` VALUES (186, '3920568ac47d06e0f1034afd37752fca0b4e254d2d53dd1b2ac0645639ec6eded36f53a9b62bb1c94fe404bf6653b27558ec7f2ab82555849d4ee4d2dcf2db85', 160, '2020-02-27 06:43:40');
INSERT INTO `website_remembered_logins` VALUES (187, '55b0e72bc90a2698936cabe788f8a0ec7a7540af955f03e7f0a7979e72b61285f6c37ee0d5ef2edb9e9986d45f1a67232e3f48de5c6af7aff794922f86ce8045', 160, '2020-02-27 06:43:40');

-- ----------------------------
-- Table structure for website_settings
-- ----------------------------
DROP TABLE IF EXISTS `website_settings`;
CREATE TABLE `website_settings`  (
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `value` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_settings
-- ----------------------------
INSERT INTO `website_settings` VALUES ('krews_api_useragent', NULL);
INSERT INTO `website_settings` VALUES ('krews_api_advanced_stats', '1');
INSERT INTO `website_settings` VALUES ('rcon_api_host', '127.0.0.1');
INSERT INTO `website_settings` VALUES ('rcon_api_port', '3001');
INSERT INTO `website_settings` VALUES ('recaptcha_publickey', '');
INSERT INTO `website_settings` VALUES ('recaptcha_secretkey', NULL);
INSERT INTO `website_settings` VALUES ('maintenance', '0');
INSERT INTO `website_settings` VALUES ('start_credits', '1000');
INSERT INTO `website_settings` VALUES ('vip_permission_id', '2');
INSERT INTO `website_settings` VALUES ('vip_currency_type', '103');
INSERT INTO `website_settings` VALUES ('vip_price', '1000');
INSERT INTO `website_settings` VALUES ('vip_badges', '[{\"value\":\"VIP\"},{\"value\":\"ACH_VipClub1\"}]');
INSERT INTO `website_settings` VALUES ('club_page_content', '<ul>\r\n<li>Je krijgt <strong>50 Bel-Credits</strong> en <strong>1000 Diamanten</strong>.</li>\r\n<li>Je krijgt <strong>10000 Duckets</strong> en <strong>20000 Achievement Score</strong>.</li>\r\n<li>Je kunt meubels uit de <strong>Donatie Winkel</strong> kopen.</li>\r\n<li>Je kunt als eerste de <strong>Nieuwste Meubi\'s</strong> kopen.</li>\r\n<li>Je kunt <strong>volle kamers</strong> bezoeken.</li>\r\n<li>Je hebt een korter typverbod (<strong>10 seconden</strong>).</li>\r\n<li>Je kunt <strong>1000 kamers</strong> maken (Normaal 150).</li>\r\n<li>Je kunt lid worden van <strong>500 groepen</strong> (Normaal 150).</li>\r\n<li>Je kunt <strong>10 respect</strong> geven aan Leet\'s (Normaal 5).</li>\r\n<li>Je hebt toegang tot <strong>speciale spraakcommando\'s<br /><br /></strong></li>\r\n</ul>\r\n<h4>De speciale spraakcommando\'s</h4>\r\n<p>Je hebt toegang tot speciale spraakcommando\'s.</p>\r\n<ul>\r\n<li><strong>:duw (<em>Cosmicnaam</em>)</strong> duwt de gewenste Leet 1 vak naar voor</li>\r\n<li><strong>:trek (<em>Cosmicnaamnaam</em>)</strong> trek een Leet naar je toe</li>\r\n<li><strong>:kus (<em>Cosmicnaamnaam</em>)</strong> geef een kusje aan een Leet</li>\r\n<li><strong>:sla (<em>Cosmicnaamnaam</em>)</strong> sla een Leet</li>\r\n<li><strong>:gezichtloos</strong> haal je gezicht weg</li>\r\n<li><strong>:trashvrij</strong> zorgt ervoor dat Leet\'s met rechten niks kunnen verplaatsen.</li>\r\n<li><strong>:huisdier (<em>lijst</em>)</strong> zal je veranderen in het dier dat je kiest uit de lijst</li>\r\n<li><strong>:zit</strong> zitten op de grond</li>\r\n<li><strong>:lig</strong> liggen op de grond</li>\r\n</ul>');
INSERT INTO `website_settings` VALUES ('namechange_currency_type', '103');
INSERT INTO `website_settings` VALUES ('namechange_price', '1000');
INSERT INTO `website_settings` VALUES ('registration_max_ip', '3');
INSERT INTO `website_settings` VALUES ('user_of_the_week', NULL);

-- ----------------------------
-- Table structure for website_settings_currencys
-- ----------------------------
DROP TABLE IF EXISTS `website_settings_currencys`;
CREATE TABLE `website_settings_currencys`  (
  `currency` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` int(3) NULL DEFAULT NULL,
  `amount` int(12) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of website_settings_currencys
-- ----------------------------
INSERT INTO `website_settings_currencys` VALUES ('duckets', 0, 1000);
INSERT INTO `website_settings_currencys` VALUES ('diamonds', 5, 1000);
INSERT INTO `website_settings_currencys` VALUES ('belcredits', 103, 1000);

-- ----------------------------
-- Table structure for website_shop_offers
-- ----------------------------
DROP TABLE IF EXISTS `website_shop_offers`;
CREATE TABLE `website_shop_offers`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` enum('belcredits','diamonds','credits','vip') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'belcredits',
  `amount` int(11) NOT NULL DEFAULT 20,
  `price` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1.50',
  `image` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `offer_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `private_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `lang` enum('NL','BE','FR') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'NL',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `lang`(`lang`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_shop_purchases
-- ----------------------------
DROP TABLE IF EXISTS `website_shop_purchases`;
CREATE TABLE `website_shop_purchases`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `data` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lang` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `timestamp` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for website_staff_logs
-- ----------------------------
DROP TABLE IF EXISTS `website_staff_logs`;
CREATE TABLE `website_staff_logs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `player_id` int(11) NULL DEFAULT NULL,
  `target` int(11) NULL DEFAULT NULL,
  `timestamp` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of website_staff_logs
-- ----------------------------
INSERT INTO `website_staff_logs` VALUES (1, 'check', 'Checked All user information', 1, 8, 1580216683);
INSERT INTO `website_staff_logs` VALUES (2, 'MANAGE', 'User Info saved', 1, 8, 1580216748);
INSERT INTO `website_staff_logs` VALUES (3, 'check', 'Checked All user information', 1, 8, 1580216828);
INSERT INTO `website_staff_logs` VALUES (4, 'MANAGE', 'User Info saved', 1, 8, 1580216834);
INSERT INTO `website_staff_logs` VALUES (5, 'check', 'Checked All user information', 1, 8, 1580216838);
INSERT INTO `website_staff_logs` VALUES (6, 'LOGIN', 'Staff logged in: 2a02:a446:4fd:0:d087:6ad5:1ce8:65da', 1, -1, 1580226161);
INSERT INTO `website_staff_logs` VALUES (7, 'LOGIN', 'Staff logged in: 2a02:a446:4fd:0:d087:6ad5:1ce8:65da', 1, -1, 1580226161);

-- ----------------------------
-- Table structure for website_user_logs_email
-- ----------------------------
DROP TABLE IF EXISTS `website_user_logs_email`;
CREATE TABLE `website_user_logs_email`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `old_mail` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `new_mail` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ip_address` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `timestamp` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
