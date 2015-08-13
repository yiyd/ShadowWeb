
create database shadow CHARACTER set utf8;

use shadow;

CREATE TABLE parameters
(
  para_id int unsigned not null auto_increment primary key,
  para_name CHAR (32) not null,
  para_description VARCHAR (255)
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE para_values
(
  para_value_id int unsigned not null auto_increment primary key,
  para_id int unsigned not null REFERENCES parameters(para_id),
  para_value_name char (32) not null
) ENGINE = InnoDB CHARACTER SET utf8;

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
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE item_follow_marks
( 
  item_follow_mark_id int unsigned not null auto_increment primary key,
  item_id int unsigned not null REFERENCES items(item_id),
  item_follow_mark VARCHAR(255) not null,
  mark_creator_id int unsigned not null REFERENCES users(user_id),
  mark_create_time datetime not null
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE roles
(
  role_id int unsigned not null auto_increment PRIMARY KEY ,
  role_name CHAR (32) NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE privileges
(
  priv_id int unsigned not null auto_increment PRIMARY KEY,
  priv_name char (32) not null
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE role_priv
(
  role_priv_id int unsigned not null auto_increment PRIMARY KEY,
  role_id int unsigned not null REFERENCES roles(role_id),
  priv_id int unsigned not null REFERENCES privileges(priv_id)
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE users
(
  user_id int unsigned NOT NULL auto_increment PRIMARY KEY,
  user_name CHAR (32) NOT NULL ,
  user_passwd CHAR (40) not NULL ,
  role_id int unsigned NOT NULL REFERENCES roles(role_id),
  user_mail CHAR (100) not null
)  ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE auto_notify
(
  auto_id int unsigned not null auto_increment primary key,
  item_id int unsigned not null REFERENCES items(item_id),
  auto_date datetime not null,
  auto_type enum ('ONCE', 'DAILY', 'WEEKLY', 'MONTHLY', 'QUARTERLY', 'YEARLY') NOT NULL DEFAULT 'ONCE',
  user_id int unsigned not null REFERENCES users(user_id)
) ENGINE = InnoDB CHARACTER SET utf8;

create table logs
(
  log_id int unsigned not null auto_increment  primary key,
  item_id int unsigned not null REFERENCES items(item_id),
  log_changer_id int unsigned not null REFERENCES users(user_id),
  log_time datetime not null
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE log_fields
(
  log_id int unsigned not null REFERENCES logs(log_id),
  log_field_name CHAR (32) not NULL,
  log_field_old CHAR (32) NOT NULL,
  log_field_new CHAR (32) NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE admin_logs
(
  admin_log_id int unsigned not null auto_increment  primary key,
  admin_log_time datetime not null,
  admin_log_object char (32) not null,
  admin_log_object_id int unsigned not null
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE admin_log_fields
(
  admin_log_id int unsigned not null REFERENCES admin_logs(admin_log_id),
  admin_log_field_name CHAR (32) not NULL,
  admin_log_field_old CHAR (32) NOT NULL,
  admin_log_field_new CHAR (32) NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8;

CREATE TABLE user_access
(
    user_access_id int unsigned not null auto_increment primary key,
    user_id int unsigned not null REFERENCES users(user_id),
    session_id char(40) not null
);

GRANT SELECT, INSERT, UPDATE, DELETE
on shadow.*
to shadow_admin@'%' identified by 'passwd';
