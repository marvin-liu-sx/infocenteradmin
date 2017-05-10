<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>add</title>
<style type="text/css">
*{margin:0;padding:0}
body{font-size:12px}
td{padding:5px}
a{text-decoration:none}
.mapping{line-height:30px;}
.mapping li{display:block;}
.field_item{display:block;line-height:30px;width:555px}
.field_item li{display: inline-block;}
.field_item .expand{width:60px;}
.field_item .type{width:100px}
.field_item .comment{width:180px;}
.field_item .field{width:180px;}
.field_item .expandA{display:block;display:none;background: #FAFFD7;padding: 5px;border: 1px #CCC solid;}
</style>
</head>
<body>
<form action="" method="post" id="module_form">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="100" align="right">模型名称:</td>
		<td><input type="text" id="module_name" maxlength="20" name="name" <?php if($row){ ?>value="<?php echo $row['name'];?>"<?php } ?>></td>
	</tr>
	<tr>
		<td width="100" align="right">模型标识:</td>
		<td><input type="text" id="module_mark" maxlength="20" name="mark" <?php if($row){ ?>readonly<?php } ?> value="<?php echo $dt;?>"> <font color=red>*不可更改</font></td>
	</tr>
	<tr>
		<td width="100" align="right">数据段:</td>
		<td><input type="text" id="segment" maxlength="20" name="segment" <?php if($row){ ?>readonly<?php } ?> value="<?php echo $ds;?>"> <font color=red>*不可更改</font></td>
	</tr>
	<tr>
		<td width="100" valign="top" align="right">字段映射:</td>
		<td>
			<div class="mapping" id="mappings">
				<?php foreach($base_fields as $field => $item){ ?>
				<li><input type="text" name="<?php echo $field;?>" <?php if(isset($row['mapping'][$field])){ ?>value="<?php echo $row['mapping'][$field]; ?>"<?php } ?>>  ==> <?php echo $item['name']; ?></li>
				<?php } ?>
			</div>
		</td>
	</tr>
	<tr>
		<td width="100" valign="top" align="right">字段显示:</td>
		<td><input type="text" id="show" size="50" name="show" value="<?php echo isset($row['show']) ? $row['show'] : ""; ?>"> 用于管理列表显示字段,多个用逗号隔开</td>
	</tr>  
	<tr>
		<td width="100" valign="top" align="right">字段列表:<Br><input type="button" id="add_extend_prototype" value="添加字段"></td>
		<td id="extend_prototype">
		<?php if($row){ ?>
		<?php foreach($row['fields'] as $idx => $item){ ?>
			<div class="field_item">
				<li class="expand"><a href="#" class="expand">显示/隐藏</a></li>
				<li class="type"><a href="#" class="up">上移</a> <a href="#" class="down">下移</a> <a href="#" class="del">删除</a></li>
				<li class="field">字段标识：<input maxlength="20" style="width:110px" name="ext_field[]" value="<?php echo $item['field'];?>" type="text"></li>
				<li class="comment">字段描述：<input maxlength="20" style="width:110px" name="ext_comment[]" type="text" value="<?php echo $item['comment'];?>"></li>
				<div class="expandA">
					<p>
						<select class="control" name="ext_control[]">
						<option <?php if($item['control'] == 'text'){ ?>selected<?php } ?> value="text">文本框</option>
						<option <?php if($item['control'] == 'multitext'){ ?>selected<?php } ?> value="multitext">多行文本框</option>
						<option <?php if($item['control'] == 'checkbox'){ ?>selected<?php } ?> value="checkbox">复选框</option>
						<option <?php if($item['control'] == 'radio'){ ?>selected<?php } ?> value="radio">单选框</option>
						<option <?php if($item['control'] == 'select'){ ?>selected<?php } ?> value="select">列表框</option>						
						</select>					
						<label>默认值：<input maxlength="20" name="ext_default[]" value="<?php echo $item['default']; ?>" type="text"></label>
						<label><input name="ext_required[]"  <?php if($item['required']){?>checked<?php } ?> value="1" type="checkbox">必填</label>
						<label>注释：<input maxlength="20" name="ext_tip[]" value="<?php echo $item['tip'];?>" type="text"></label>
					</p>
					<p class="option_list" <?php if(($item['control'] != 'checkbox') && 
													($item['control'] != 'radio') &&
													($item['control'] != 'select')){ ?>style="display:none"<?php }?>>
						选项：<input name="ext_options[]" value="<?php echo $item['options'];?>" type="text" size="50"> 如: val1,name1|val2,name2</p>
				</div>
			</div>
		<?php }} ?>
		</td>
	</tr>  
	<tr>
		<td></td>
		<td><font color=red>注：系统字段：id、dt、ds、create_time、update_time不能使用</font></td>
	</tr>
	<tr>
		<td></td>
		<td height="50"><input type="submit" value="保存设置"></td>
	</tr>	
</table>
</form>
<script language="javascript" src="http://static1.pplive.cn/public/jquery/jquery-1.7.1.min.js"></script>
<script language="javascript">
$(function(){
	$('.field_item a.expand').live('click', function(){
		var parent = $(this).closest('.field_item');
		$(parent).find('div.expandA').toggle();
		return false;
	});
	$('.field_item a.del').live('click', function(){
		var parent = $(this).closest('.field_item');
		$(parent).remove();
		return false;
	});	
	$('.field_item a.up').live('click', function(){
		var parent = $(this).closest('.field_item');
		var prev = parent.prev('.field_item');
		if(prev){
			parent.insertBefore(prev);
		}
		return false;
	});		
	$('.field_item a.down').live('click', function(){
		var parent = $(this).closest('.field_item');
		var next = parent.next('.field_item');
		if(next){
			parent.insertAfter(next);
		}
		return false;
	});
	$('.field_item select.control').live('change', function(){
		var parent = $(this).closest('.field_item');
		if($(this).val() == 'checkbox' || $(this).val() == 'radio' || $(this).val() == 'select'){
			parent.find('p.option_list').show();
		}else{
			parent.find('p.option_list').hide();
		}
		return false;
	});
	$('#module_form').submit(function(){
		var e = $('#module_name');
		if(e.val().trim() == ''){
			alert('模型名称不能为空');
			e.focus();
			return false;
		}
		
		//数据类型检测
		e = $('#module_mark');
		if(e.val().trim() == ''){
			alert('模型标识不能为空');
			e.focus();
			return false;
		}
		if(!e.val().match(/^\w+$/)){
			alert('模型标识必须由数字、字母、下划线组成');
			e.focus();
			return false;
		}
		
		//数据段检测
		e = $('#segment');
		if(e.val().trim() == ''){
			alert('数据段不能为空');
			e.focus();
			return false;
		}
		if(!e.val().match(/^\w+$/)){
			alert('数据段必须由数字、字母、下划线组成');
			e.focus();
			return false;
		}

		//字段检测
		var fields = {};
		var field_count = 0;
		var deny = ["id", "dt", "ds", "create_time", "update_time"];
		var extend = $('#extend_prototype .field_item');
		for(var i = 0; i < extend.length; i++)
		{
			var field = $(extend[i]).find('input[name="ext_field[]"]');
			var v = field.val().trim();
			if(v == '')
			{
				alert('必须填写字段标识');
				field.focus();
				return false;
			}
			if(!(v.match(/^\w+$/)))
			{
				alert('字段标识必须由数字、字母、下划线组成');
				field.focus();
				return false;
			}
			if($.inArray(v, deny) != -1){
				alert('系统字段不能使用');
				field.focus();
				return false;
			}
			if(v){
				if(fields[v] == true)
				{
					alert('字段标识已存在，请更换！');
					field.focus();
					return false;
				}
				fields[v] = true;
				field_count++;
			}
		}
		if(field_count == 0){
			alert('字段列表不能为空');
			return false;
		}
		
		//映射检测
		var eles = $('#mappings input');
		for(var i = 0; i < eles.length; i++){
			if((eles[i].value != '') && (fields[eles[i].value] != true)){
				alert('被映射"' + eles[i].value + '"字段不在列表内，请检查!');
				eles[i].focus();
				return false;
			}
		}
		
		//字段显示检测
		e = $('#show');
		if(e.val() != ''){
			var show_list = e.val().split(',');
			for(var i=0; i<show_list.length; i++){
				if(fields[show_list[i]] != true){
					alert('显示列表中"' + show_list[i] + '"字段不在列表内，请检查!');
					e.focus();
					return false;
				}
			}
		}
		
		return true;
	});
	$('#add_extend_prototype').click(function(){
		addExtendPrototype();
	});
	
	function addExtendPrototype()
	{
		var html = '<div class="field_item">';
		html+= '<li class="expand"><a href="#" class="expand">显示/隐藏</a></li>\n';
		html+= '<li class="type"><a href="#" class="up">上移</a> <a href="#" class="down">下移</a> <a href="#" class="del">删除</a></li>\n';
		html+= '<li class="field">字段标识：<input maxlength="20" style="width:110px" name="ext_field[]" type="text"></li>\n';
		html+= '<li class="comment">字段描述：<input maxlength="20" style="width:110px" type="text" name="ext_comment[]" value=""></li>\n';
		html+= '<div class="expandA">\n';
		html+= '<p>\n';
		html+= '<select class="control" name="ext_control[]">\n';
		html+= '<option value="text">文本框</option>\n';
		html+= '<option value="multitext">多行文本框</option>';
		html+= '<option value="checkbox">复选框</option>\n';
		html+= '<option value="radio">单选框</option>\n';
		html+= '<option value="select">列表框</option>\n';		
		html+= '</select>\n';
		html+= '默认值：<input name="ext_default[]" type="text">\n';
		html+= '<input value="1" name="ext_reqiured[]" type="checkbox">必填\n';
		html+= '注释：<input maxlength="20" name="ext_tip[]" type="text">';
		html+= '</p>';
		html+= '<p class="option_list" style="display:none">选项：<input name="ext_options[]" type="text" size="50"> 如: val1,name1|val2,name2</p>';		
		html+= '</div>';
		html+= '</div>';
			
		$('#extend_prototype').append(html);
	}
});
</script>
</body>
</html>