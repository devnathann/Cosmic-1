-- ----------------------------
-- Table structure for website_membership
-- ----------------------------

DROP TABLE IF EXISTS `website_membership`;
CREATE TABLE `website_membership`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `old_rank` int(1) NULL DEFAULT NULL,
  `expires_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for website_referrals
-- ----------------------------

DROP TABLE IF EXISTS `website_referrals`;
CREATE TABLE `website_referrals`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `referral_user_id` int(11) NULL DEFAULT NULL,
  `ip_address` varchar(56) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `timestamp` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE users ADD shuttle_token varchar(128) NULL DEFAULT NULL;

INSERT INTO `website_settings` VALUES ('vip_membership_days', 31);
INSERT INTO `website_settings` VALUES ('referral_acc_create_days', 14);
INSERT INTO `website_settings` VALUES ('referral_points_type', 103);
INSERT INTO `website_settings` VALUES ('referral_points', 5);
INSERT INTO `website_settings` VALUES ('referral_waiting_seconds', 3600);
