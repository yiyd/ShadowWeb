<?php
/**
 * User: ZX
 * Date: 2015/8/14
 * Time: 16:12
 */
    require_once('log_fns.php');
    require_once('item_fns.php');
    require_once('translation_fns.php');
    function display_new_item($row, $auto_notify_result, $follow_mark_result){

?>  
<!DOCTYPE html>
<html>
    <head>

    <meta charset="UTF-8">

    <title>中银开放平台-事项跟踪工具</title>
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="type/css" href="resources/common/css/style.css">
    <script type="text/javascript" src="resources/common/js/public.js"></script>
    <script type="text/javascript" src="resources/common/js/datagrid.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>   
    <script>
        $(function(){
            
            
            $('#dg').datagrid({
                toolbar:[
                    {
                        id:'add',
                        text:'增加跟踪备注',
                        iconCls:'icon-add',
                        disabled:false,
                        handler:function(){
                            var time = getTime();
                            lastIndex = $('#dg').datagrid('getRows').length;
                            if (lastIndex == rows) {
                                $('#dg').datagrid('appendRow',{
                                    mark_content:'',
                                    mark_creator:'<?php 
                                        $user_id = $_SESSION['current_user_id'];
                                        try {
                                            $user_name = get_user_name($user_id);          
                                        } catch (Exception $e) {
                                            echo '';
                                        }
                                        echo $user_name;    
                                    ?>',
                                    create_time:time
                                });
                                $('#dg').datagrid('selectRow', lastIndex);
                                $('#dg').datagrid('beginEdit', lastIndex);
                                isSave = false;
                                $('#save').linkbutton('enable');
                                $('#delete').linkbutton('enable');
                                $('#add').linkbutton('disable');
                            }
                            
                        }
                    },'-',
                    {
                        id:'save',
                        text:'保存所增备注',
                        disabled:true,
                        iconCls:'icon-save',
                        handler:function(){
                            lastIndex = $('#dg').datagrid('getRows').length - 1;
                            if (lastIndex == rows) {
                                if (validateFollowMarkData()) {
                                    $('#dg').datagrid('unselectRow', lastIndex);
                                    $('#dg').datagrid('endEdit', lastIndex);
                                    isSave = true;
                                    saveFollowMark();
                                    $('#save').linkbutton('disable');
                                    $('#delete').linkbutton('disable');
                                    $('#add').linkbutton('disable');
                                }else{
                                    $('#dg').datagrid('selectRow', lastIndex);
                                    $('#dg').datagrid('beginEdit', lastIndex);
                                }
                                
                                
                            }
                            
                        }
                    },'-',
                    {
                        id:'delete',
                        text:'删除所增备注',
                        disabled:true,
                        iconCls:'icon-remove',
                        handler:function(){
                            lastIndex = $('#dg').datagrid('getRows').length - 1;
                            if (lastIndex == rows && !isSave) {
                                $('#dg').datagrid('deleteRow', lastIndex);
                                $('#save').linkbutton('disable');
                                $('#delete').linkbutton('disable');
                                $('#add').linkbutton('enable');
                            }
                            
                        }
                    }
                ]
            });

            <?php
                $mark_number = count($follow_mark_result);
                if ($mark_number > 0) {
                    for ($i = 0; $i < $mark_number; $i++) { 
                        $follow_mark_row = $follow_mark_result[$i];
                        $follow_mark_row['mark_creator_name'] = get_user_name($follow_mark_row['mark_creator_id']);
                        echo "$('#dg').datagrid('appendRow',{";
                            echo "mark_content:'".$follow_mark_row['item_follow_mark']."',";
                            echo "mark_creator:'".$follow_mark_row['mark_creator_name']."',";
                            echo "create_time:'".$follow_mark_row['mark_create_time']."'";
                        echo "});";
                    }
                }
                
                $notify_number = count($auto_notify_result);
                if ($notify_number > 0) {
                    for ($i = 0; $i < $notify_number; $i++) { 
                        $notify_row = $auto_notify_result[$i];
                        $notify_row['user_name'] = get_user_name($notify_row['user_id']);
                        echo "$('#an').datagrid('appendRow',{";
                            echo "notify_type:'";
                            echo notify_type_translation($notify_row['auto_type']);
                            echo "',";
                            echo "notify_user:'".$notify_row['user_name']."',";
                            echo "notify_date:'".$notify_row['auto_date']."'";
                        echo "});";
                    }
                }

                // $log_result = get_log();
                // foreach ($result as $log) {
                //     echo "$('#log').datagrid('appendRow',{";
                //         echo "log_changer:'".get_user_name($log['log_changer_id'])."',";
                //         echo "log_time:'".$log['log_time']."',";
                //         $log_detail = get_log_detail($log['log_id']);
                //         foreach ($log_detail as $key) {
                //             echo $key['log_field_name']." ".$key['log_field_old']." 改成 ".$key['log_field_new']." ";
                //         }
                //     echo "});";

                //     echo $key['item_id']." ".get_user_name($key['log_changer_id'])." ".$key['log_time']." ";
                //     $log_detail = get_log_detail($key['log_id']);
                //     foreach ($log_detail as $key) {
                //         echo $key['log_field_name']." ".$key['log_field_old']." 改成 ".$key['log_field_new']." ";
                //     }
                //     echo "<hr /><br />";
                // }
            ?>

            var rows = $('#dg').datagrid('getRows').length;
            var isSave = false;

        })

        

        function updateItem(){ 
            window.parent.updateTab('update_item.php', '<?php echo $row['item_name'] ?>', '<?php echo $_SESSION['current_item_id'] ?>');
        }

        function deleteItem(){
            $.messager.confirm('确认','您确认想要删除该事项吗？删除后，该事项将无法恢复！',function(r){    
                if (r){
                    $.ajax({
                        url:"ajax_php/delete_item.php",
                        type:"POST",
                        success:function(){
                            window.parent.$.messager.show({
                                title:"删除",
                                msg:"删除事项成功",
                                timeout:5000,
                                showType:'slide'
                            });

                            window.parent.closeCurrentTab();
                        }
                    });
                }    
            }); 
        }

        function completeItem(){
            $.messager.confirm('确认','您确定此事项已完成吗？完成事项后，该事项将关闭，并且无法再修改！',function(r){    
                if (r){
                    $.ajax({
                        url:"ajax_php/finish_item.php",
                        type:"POST",
                        success:function(){
                            window.parent.$.messager.show({
                                title:"完成",
                                msg:"事项已完成",
                                timeout:5000,
                                showType:'slide'
                            });

                            window.parent.updateTab('display_item.php', '<?php echo $row['item_name'] ?>', '<?php echo $_SESSION['current_item_id'] ?>');
                        }
                    });
                }    
            });
        }
    </script>
    <body>
        <div class="easyui-panel" style="height:auto;" data-options="title:'事项基本信息',collapsible:true">
            <table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" >
                <tr height="26">
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            事项编号：
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_id'];
                            ?>
                        </div>
                    </td>
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            创建人：
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_creator_name'];
                            ?>
                        </div>
                    </td>
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            创建时间：
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_create_time'];
                            ?>
                        </div>
                    </td>
                </tr>
                <tr height="26">
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            事项名称：<font color="red">*</font>
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;word-wrap:break-word;word-break:break-all;">
                            <?php
                                echo $row['item_name'];
                            ?>
                        </div>
                    </td>
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            跟踪人：<font color="red">*</font>
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_follower_name'];
                            ?>
                        </div>
                    </td>
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            事项类型：<font color="red">*</font>
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_type_name'];
                            ?>
                        </div>
                    </td>
                </tr>
                <tr height="26">
                    <td nowrap="nowrap">
                        <div align="right" style="padding-right=2px;">
                            事项状态：
                        </div>
                    </td>
                    <td>
                        <div align="left" style="padding-left:2px;">
                            <?php
                                
                                echo item_state_translation($row['item_state']);
                            ?>
                        </div>
                    </td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td height="26">
                        <div align="right" style="padding-right:2px;">事项描述：</div>

                    </td>
                    <td colspan="2">
                        <div align="left" style="margin:5px 0 5px 0;">
                            <textarea rows="6" cols="40" readonly='readonly' ><?php echo $row['item_description']; ?></textarea>
                        </div>
                    </td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </div>
        <div>
            <table id="an" class="easyui-datagrid" title="自动提醒" data-options="collapsible:true,rownumbers:true">
                <thead>
                    <tr>
                        <th width="15%" data-options="field:'notify_type'">自动提醒类型</th>
                        <th width="15%" data-options="field:'notify_user'">自动提醒人员</th>
                        <th width="20%" data-options="field:'notify_date'">自动提醒时间</th>
                    </tr>
                </thead>
            </table>   
        </div>
        <div>
            <table id="dg" class="easyui-datagrid" title="事务跟踪备注(新增备注后请点击‘保存所增备注’按钮，否则备注不予保存)" data-options="collapsible:true,rownumbers:true,nowrap:false">
                <thead>
                    <tr>
                        <th width="65%" data-options="field:'mark_content',editor:'textarea'">跟踪备注</th>
                        <th width="8%" data-options="field:'mark_creator'">创建人</th>
                        <th width="15%" data-options="field:'create_time'">创建时间</th>
                    </tr>
                </thead>
            </table>   
        </div>

        <div>
            <table id="log" class="easyui-datagrid" title="事项日志" data-options="collapsible:true,rownumbers:true,nowrap:false">
                <thead>
                    <tr>
                        <th width="8%" data-options="field:'log_changer'">修改人</th>
                        <th width="15%" data-options="field:'log_time'">修改时间</th>
                        <th width="65%" data-options="field:'log_detail'">修改详情</th>
                    </tr>
                </thead>
            </table>   
        </div>

        <div style="padding-top:10px;text-align:center;height:40px">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateItem()" data-options="iconCls:'icon-edit'">修改事项</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="deleteItem()" data-options="iconCls:'icon-cut'">删除事项</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="completeItem()" data-options="iconCls:'icon-ok'">完成事项</a>
        </div>
    </body>
</html>
<?php
    }

    function display_finished_item($row, $auto_notify_result, $follow_mark_result){

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
    <script>
        $(function(){
            
            
            

            <?php
                $mark_number = count($follow_mark_result);
                if ($mark_number > 0) {
                    for ($i = 0; $i < $mark_number; $i++) { 
                        $follow_mark_row = $follow_mark_result[$i];
                        $follow_mark_row['mark_creator_name'] = get_user_name($follow_mark_row['mark_creator_id']);
                        echo "$('#dg').datagrid('appendRow',{";
                            echo "mark_content:'".$follow_mark_row['item_follow_mark']."',";
                            echo "mark_creator:'".$follow_mark_row['mark_creator_name']."',";
                            echo "create_time:'".$follow_mark_row['mark_create_time']."'";
                        echo "});";
                    }
                }
                
                $notify_number = count($auto_notify_result);
                if ($notify_number > 0) {
                    for ($i = 0; $i < $notify_number; $i++) { 
                        $notify_row = $auto_notify_result[$i];
                        $notify_row['user_name'] = get_user_name($notify_row['user_id']);
                        echo "$('#an').datagrid('appendRow',{";
                            echo "notify_type:'";
                            echo notify_type_translation($notify_row['auto_type']);
                            echo "',";
                            echo "notify_user:'".$notify_row['user_name']."',";
                            echo "notify_date:'".$notify_row['auto_date']."'";
                        echo "});";
                    }
                }
            ?>


        })


    </script>
    <body>
        <div class="easyui-panel" style="height:auto;" data-options="title:'事项基本信息',collapsible:true">
            <table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" >
                <tr height="26">
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            事项编号：
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_id'];
                            ?>
                        </div>
                    </td>
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            创建人：
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_creator_name'];
                            ?>
                        </div>
                    </td>
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            创建时间：
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_create_time'];
                            ?>
                        </div>
                    </td>
                </tr>
                <tr height="26">
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            事项名称：<font color="red">*</font>
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_name'];
                            ?>
                        </div>
                    </td>
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            跟踪人：<font color="red">*</font>
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_follower_name'];
                            ?>
                        </div>
                    </td>
                    <td nowrap="nowrap" width="10%">
                        <div align="right" style="padding-right=2px;">
                            事项类型：<font color="red">*</font>
                        </div>
                    </td>
                    <td width="23%">
                        <div align="left" style="padding-left:2px;">
                            <?php
                                echo $row['item_type_name'];
                            ?>
                        </div>
                    </td>
                </tr>
                <tr height="26">
                    <td nowrap="nowrap">
                        <div align="right" style="padding-right=2px;">
                            事项状态：
                        </div>
                    </td>
                    <td>
                        <div align="left" style="padding-left:2px;">
                            <?php
                                
                                echo item_state_translation($row['item_state']);
                            ?>
                        </div>
                    </td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td height="26">
                        <div align="right" style="padding-right:2px;">事项描述：</div>

                    </td>
                    <td colspan="2">
                        <div align="left" style="margin:5px 0 5px 0;">
                            <textarea rows="6" cols="40" readonly='readonly' ><?php echo $row['item_description']; ?></textarea>
                        </div>
                    </td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </div>
        <div>
            <table id="an" class="easyui-datagrid" title="自动提醒" data-options="collapsible:true,rownumbers:true">
                <thead>
                    <tr>
                        <th width="15%" data-options="field:'notify_type'">自动提醒类型</th>
                        <th width="15%" data-options="field:'notify_user'">自动提醒人员</th>
                        <th width="20%" data-options="field:'notify_date'">自动提醒时间</th>
                    </tr>
                </thead>
            </table>   
        </div>
        <div>
            <table id="dg" class="easyui-datagrid" title="事务跟踪备注" data-options="collapsible:true,rownumbers:true,nowrap:false">
                <thead>
                    <tr>
                        <th width="65%" data-options="field:'mark_content'">跟踪备注</th>
                        <th width="8%" data-options="field:'mark_creator'">创建人</th>
                        <th width="15%" data-options="field:'create_time'">创建时间</th>
                    </tr>
                </thead>
            </table>   
        </div>

    </body>
</html>
<?php
    }

?>