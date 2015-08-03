
create database shadow;

use shadow;

CREATE TABLE parameters
(
  para_id int unsigned not null auto_increment primary key,
  para_name CHAR (32) not null,
  para_description VARCHAR (255)
) ENGINE = InnoDB;

CREATE TABLE para_values
(
  para_id int unsigned not null REFERENCES parameters(para_id),
  para_value_id int unsigned not null auto_increment primary key,
  para_value_name char (32) not null
) ENGINE = InnoDB;

create table items
(
  item_id int unsigned not null auto_increment primary key,
  item_name char(200) not null,
  item_creator_id int unsigned not null REFERENCES users(user_id),
  item_follower_id int unsigned not null REFERENCES users(user_id),
  item_create_time datetime not null,
  item_description varchar(255),
  item_type_id int unsigned not null REFERENCES para_values(para_value_id),
  item_state enum('PROCESSING', 'FINISH') not null DEFAULT 'PROCESSING'
) ENGINE = InnoDB;

CREATE TABLE item_follow_marks
( 
  item_follow_mark_id int unsigned not null auto_increment primary key,
  item_id int unsigned not null REFERENCES items(item_id),
  item_follow_mark VARCHAR(255) not null,
  mark_create_time datetime not null 
) ENGINE = InnoDB;

CREATE TABLE roles
(
  role_id int unsigned not null auto_increment PRIMARY KEY ,
  role_name CHAR (32) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE role_priv
(
  role_priv_id int unsigned not null auto_increment PRIMARY KEY,
  role_id int unsigned NOT NULL REFERENCES roles(role_id),
  role_priv_name char (32) not null,
  role_priv_value enum('YES', 'NO') NOT NULL DEFAULT 'NO'
) ENGINE = InnoDB;

CREATE TABLE users
(
  user_id int unsigned NOT NULL auto_increment PRIMARY KEY,
  user_name CHAR (32) NOT NULL ,
  user_passwd CHAR (40) not NULL ,
  role_id int unsigned NOT NULL REFERENCES roles(role_id),
  user_mail CHAR (100) not null
)  ENGINE = InnoDB;

CREATE TABLE auto_notify
(
  item_id int unsigned not null REFERENCES items(item_id),
  auto_date datetime not null,
  auto_type enum ('ONCE', 'DAILY', 'WEEKLY', 'MONTHLY', 'QUARTERLY', 'YEARLY') NOT NULL DEFAULT 'ONCE',
  user_id int unsigned not null REFERENCES users(user_id)
) ENGINE = InnoDB;

create table logs
(
  log_id int unsigned not null auto_increment  primary key,
  item_id int unsigned not null REFERENCES items(item_id),
  log_changer_id int unsigned not null REFERENCES users(user_id),
  log_time datetime not null
) ENGINE = InnoDB;

CREATE TABLE log_fields
(
  log_id int unsigned not null REFERENCES logs(log_id),
  log_field_name CHAR (32) not NULL,
  log_field_old CHAR (32) NOT NULL,
  log_field_new CHAR (32) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE admin_logs
(
  admin_log_id int unsigned not null auto_increment  primary key,
  admin_log_time datetime not null,
  admin_log_object char (32) not null,
  admin_log_object_id int unsigned not null
) ENGINE = InnoDB;

CREATE TABLE admin_log_fileds
(
  admin_log_id int unsigned not null REFERENCES admin_logs(admin_log_id),
  admin_log_field_name CHAR (32) not NULL,
  admin_log_field_old CHAR (32) NOT NULL,
  admin_log_field_new CHAR (32) NOT NULL
) ENGINE = InnoDB;

GRANT SELECT, INSERT, UPDATE, DELETE
on shadow.*
to shadow_admin@'%' identified by 'passwd';
