
create database shadow;

use shadow;

CREATE TABLE parameters
(
  para_id int unsigned not null auto_increment primary key,
  para_name CHAR (32) not null,
  para_description VARCHAR (255)
) TYPE = InnoDB;

CREATE TABLE para_values
(
  para_id int unsigned not null REFERENCES parameters(para_id),
  para_value_id int unsigned not null auto_increment primary key,
  para_value_name char (32) not null
) TYPE = InnoDB;

create table items
(
  item_id int unsigned not null auto_increment primary key,
  item_name char(32) not null,
  item_creator_id int unsigned not null REFERENCES users(user_id),
  item_follower_id int unsigned not null REFERENCES users(user_id),
  item_create_time date not null,
  item_description varchar(255),
  item_type_id int unsigned not null REFERENCES para_values(para_value_id),
  item_state enum('PROCESSING', 'FINISH') not null DEFAULT 'PROCESSING',
  item_follow_mark VARCHAR(255)
) TYPE = InnoDB;

CREATE TABLE roles
(
  role_id int unsigned not null auto_increment PRIMARY KEY ,
  role_name CHAR (32) NOT NULL ,
  role_create_priv enum('YES', 'NO') NOT NULL DEFAULT 'NO',
  role_update_priv enum('YES', 'NO') NOT NULL DEFAULT 'NO',
  role_delete_priv enum('YES', 'NO') NOT NULL DEFAULT 'NO',
  role_search_priv enum('YES', 'NO') NOT NULL DEFAULT 'NO',
  role_finish_priv enum('YES', 'NO') NOT NULL DEFAULT 'NO',
  role_viewlog_priv enum('YES', 'NO') NOT NULL DEFAULT 'NO'
) TYPE = InnoDB;

CREATE TABLE users
(
  user_id int unsigned NOT NULL auto_increment PRIMARY KEY,
  user_name CHAR (32) NOT NULL ,
  user_passwd CHAR (40) not NULL ,
  role_id int unsigned NOT NULL REFERENCES roles(role_id),
  user_mail CHAR (100) not null
)  TYPE = InnoDB;

CREATE TABLE auto_notify
(
  item_id int unsigned not null REFERENCES items(item_id),
  auto_date DATE not null,
  auto_type enum ('ONCE', 'DAILY', 'WEEKLY', 'MONTHLY', 'QUARTERLY', 'YEARLY') NOT NULL DEFAULT 'ONCE',
  user_id int unsigned not null REFERENCES users(user_ic)
) TYPE = InnoDB;

create table logs
(
  log_id int unsigned not null auto_increment primary key,
  item_id int unsigned not null REFERENCES items(item_id),
  log_changer_id int unsigned not null REFERENCES users(user_id),
  log_time DATE not null
) TYPE = InnoDB;

CREATE TABLE log_fields
(
  log_id int unsigned not null REFERENCES logs(log_id),
  log_field_name CHAR (32) not NULL,
  log_field_old CHAR (32) NOT NULL,
  log_field_new CHAR (32) NOT NULL
) TYPE = InnoDB;

create table admin
(
	admin_name char(16) not null primary key,
	admin_passwd char(40) not null
);

GRANT SELECT, INSERT, UPDATE, DELETE
on shadow.*
to shadow_admin@'%' identified by 'passwd';
