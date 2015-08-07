use shadow;


INSERT INTO parameters VALUES ('', 'item_type', 'Item`s types');
INSERT INTO para_values VALUES ('1', '', 'common');
INSERT INTO para_values VALUES ('1', '', 'special');


INSERT INTO roles VALUES('', 'common');
INSERT into privileges VALUES ('', '日常事务', 'YES');
INSERT INTO privileges values ('', '生产问题', 'YES');
INSERT INTO role_priv VALUES('', '1', '1');
INSERT INTO role_priv VALUES('', '1', '1');
INSERT INTO users VALUES('', 'yiyd', sha1('123456'), '1', 'yiyd@qq.com');
INSERT INTO users VALUES('', 'yiyd1', sha1('123456'), '1', 'yiyd@qq.com');
