use shadow;

delimiter //

create trigger t_users_insert
	after insert on users for each row
	begin
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '新建用户', NEW.user_id);
	end;
//

create trigger t_users_update
	after update on users for each row
	begin
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '修改用户', NEW.user_id);
		if (NEW.user_name <> OLD.user_name) then
			insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '用户名', OLD.user_name, NEW.user_name);
		end if;
		if (NEW.user_passwd <> OLD.user_passwd) then
			insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '密码', OLD.user_passwd, new.user_passwd);
		end if;
		if (new.role_id <> OLD.role_id) then
			insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '角色', old.role_id, new.role_id);
		end if;
		if (new.user_mail <> old.user_mail) then
			insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '邮箱', old.user_mail, new.user_mail);		
		end if;
	end;
//

create trigger t_users_delete
	after delete on users for each row
	begin
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '删除用户', old.user_id);
	end;
//

create trigger t_roles_insert 
	after insert on roles for each row
	begin 
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '新建角色', new.role_id);
	end;
//

create trigger t_roles_update
	after update on roles for each row
	begin
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '修改角色', new.role_id);
		if (new.role_name <> old.role_name) then
			insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '角色名', old.role_name, new.role_name);
		end if;
	end;
//

create trigger t_roles_delete 
	after delete on roles for each row
	begin 
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '删除角色', old.role_id);
	end;
//

create trigger t_priv_insert 
	after insert on privileges for each row
	begin 
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '新建权限', new.priv_id);
	end;
//

create trigger t_priv_update 
	after update on privileges for each row
	begin 
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '修改权限', new.priv_id);
			if (new.priv_name <> old.priv_name) then
				insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '权限名', old.priv_name, new.priv_name);
			end if;
			if (new.priv_value <> old.priv_value) then
				insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '权限值', old.priv_value, new.priv_value);
			end if;
	end;
//

create trigger t_priv_delete 
	after delete on privileges for each row
	begin 
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '删除权限', OLD.priv_id);
	end;
//

create trigger t_role_priv_insert 
	after insert on role_priv for each row
	begin
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '添加权限', new.role_id);
		insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '权限ID', 'null', new.priv_id);
	end;
//

create trigger t_role_priv_delete
	after delete on role_priv for each row
	begin
		insert into admin_logs values ('', CURRENT_TIMESTAMP, '取消权限', old.role_id);
		insert into admin_log_fields values ('select max(admin_log_id) from admin_logs', '权限ID', old.priv_id, 'null');
	end;
//

create trigger t_item_follow_mark_insert
	after insert on item_follow_marks for each row 
	begin
		insert into logs values ('', new.item_id, new.mark_creator_id, new.mark_create_time);
		insert into log_fields values ('select max(log_id) from logs', '添加跟踪备注', 'null', new.item_follow_mark);
	end;
//
