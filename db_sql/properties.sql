use shadow;

set names utf8;

INSERT INTO parameters VALUES ('', 'item_type', 'Item`s types');
INSERT INTO para_values VALUES ('', '1', '日常事务');
INSERT INTO para_values VALUES ('', '1', '生产问题');
INSERT INTO roles VALUES('', 'default');
INSERT INTO roles VALUES('', 'admin');
INSERT INTO roles VALUES('', 'common_1');
INSERT INTO roles VALUES('', 'common_2');
INSERT INTO roles VALUES('', 'manager');
INSERT into privileges VALUES ('', '系统管理');
INSERT into privileges VALUES ('', '日常事务');
INSERT INTO privileges values ('', '生产问题');
INSERT INTO role_priv VALUES('', '2', '1');
INSERT INTO role_priv VALUES('', '2', '2');
INSERT INTO role_priv VALUES('', '2', '3');
INSERT INTO role_priv VALUES('', '3', '2');
INSERT INTO role_priv VALUES('', '4', '3');
INSERT INTO role_priv VALUES('', '5', '2');
INSERT INTO role_priv VALUES('', '5', '3');
INSERT INTO users VALUES('', 'yiyd', sha1('123456'), '1', 'yiyd@qq.com');
INSERT INTO users VALUES('', 'yiyd1', sha1('123456'), '1', 'yiyd1@qq.com');
INSERT INTO users VALUES('', 'yiyd2', sha1('123456'), '1', 'yiyd2@qq.com');
INSERT INTO users VALUES('', 'yiyd3', sha1('123456'), '1', 'yiyd3@qq.com');
