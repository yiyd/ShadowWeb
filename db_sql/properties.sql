use shadow;


INSERT INTO parameters VALUES ('', 'item_type', 'Item`s types');
INSERT INTO para_values VALUES ('1', '', 'common');
INSERT INTO para_values VALUES ('1', '', 'special');


INSERT INTO roles VALUES('', 'common');
INSERT INTO role_priv VALUES('', '1', '日常事务', 'YES');
INSERT INTO role_priv VALUES('', '1', '生产问题', 'YES');
INSERT INTO users VALUES('', 'yiyd', sha1('123456'), '1', 'yiyd@qq.com');
INSERT INTO users VALUES('', 'yiyd1', sha1('123456'), '1', 'yiyd@qq.com');
