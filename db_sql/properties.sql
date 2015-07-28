use shadow;

INSERT INTO admin VALUES ('admin', 'admin');

INSERT INTO parameters VALUES ('', 'item_type', 'Item`s types');
INSERT INTO para_values VALUES ('1', '', 'common');
INSERT INTO para_values VALUES ('1', '', 'special');

INSERT INTO users VALUES('', 'yiyd', sha1('123456'), '1', 'yiyd@qq.com');
INSERT into roles VALUES('', 'common', 'YES', 'YES', 'YES', 'YES', 'YES', 'YES');