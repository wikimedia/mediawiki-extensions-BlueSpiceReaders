-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: extensions/BlueSpiceReaders/maintenance/db/sql/bs_readers.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE /*_*/bs_readers (
  readers_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  readers_user_id INT UNSIGNED NOT NULL,
  readers_user_name VARBINARY(255) DEFAULT '' NOT NULL,
  readers_page_id INT UNSIGNED NOT NULL,
  readers_rev_id INT UNSIGNED NOT NULL,
  readers_ts VARCHAR(16) DEFAULT '' NOT NULL,
  INDEX readers_user_id (readers_user_id),
  INDEX readers_page_id (readers_page_id),
  INDEX readers_rev_id (readers_rev_id),
  INDEX readers_user_name (readers_user_name),
  INDEX readers_ts (readers_ts),
  PRIMARY KEY(readers_id)
) /*$wgDBTableOptions*/;