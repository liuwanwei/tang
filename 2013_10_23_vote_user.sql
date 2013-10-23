ALTER TABLE vote ADD COLUMN `create_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE  `user` CHANGE  `extension_user_id`  `extension_user_id` BIGINT( 11 ) NOT NULL;