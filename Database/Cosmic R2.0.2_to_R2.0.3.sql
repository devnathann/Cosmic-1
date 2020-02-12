ALTER TABLE users ADD shuttle_token varchar(64) NULL DEFAULT NULL;

INSERT INTO `website_settings` VALUES ('vip_membership_days', 31);
INSERT INTO `website_settings` VALUES ('referral_acc_create_days', 14);
INSERT INTO `website_settings` VALUES ('referral_points_type', 103);
