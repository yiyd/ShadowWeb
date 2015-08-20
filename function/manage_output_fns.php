<?php
/**
 * Coder: ZX
 * Date: 2015/8/19
 * Time: 16:00
 */
		

	function display_user_manage_page($roles_array){
?>
<!DOCTYPE html>
<html>
    <head>

    <meta charset="UTF-8">

    <title>中银开放平台-事项跟踪工具</title>
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="type/css" href="resources/common/css/style.css">
    <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
	</head>
	<body>
		<div>
            <table class="easyui-datagrid" width="100%" data-options="rownumbers:true,nowrap:false,pagination:true,pageSize:50,pageList:[30,50,80,100],toolbar:toolbar">
                <thead>
                    <tr>
                        <th width="8%" data-options="field:'log_changer'">修改人</th>
                        <th width="15%" data-options="field:'log_time'">修改时间</th>
                        <th width="65%" data-options="field:'log_detail'">修改详情</th>
                    </tr>
                </thead>
            </table>   
        </div>
    	<div id="dlg" class="easyui-dialog" title="新增用户" style="width:auto;height:auto;" data-options="iconCls: 'icon-add',closed:true,modal:true,buttons: '#dlg-buttons'">
    		<table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right=2px;">
							用户名：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<input id="user_name" type="text" name="user_name" maxlength="32" size="32"></input>
						</div>
					</td>
					
				</tr>
                <tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right=2px;">
							初始密码：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<input id="password" type="password" name="password" maxlength="40" size="40"></input>
						</div>
					</td>
					
				</tr>
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right=2px;">
							所属角色：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<select name="item_follower">
								<?php
									foreach ($roles_array as $role) {
        								echo "<option value=\"".$role['role_id']."\" >".$role['role_name']."</option>";
    								}
    							?>
							</select>
						</div>
					</td>
					
				</tr>
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right=2px;">
							绑定邮箱：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<input id="email" type="email" name="email" maxlength="100"></input>
						</div>
					</td>
					
				</tr>
			</table>
		</div>
		<div id='dlg-buttons' style="padding-top:10px;text-align:center;">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="save()" data-options="iconCls:'icon-save'">保存</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancel()" data-options="iconCls:'icon-undo'">取消</a>
        </div>

        <script>
        	var toolbar = [{
        		text:'新增',
        		iconCls:'icon-add',
        		handler:function(){
        			$('#dlg').dialog('open');
        		}
        	}];
        </script>
	</body>
</html>
<?php
	}
?>