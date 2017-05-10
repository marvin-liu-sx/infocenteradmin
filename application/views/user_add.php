<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>add</title>
<style type="text/css">
*{margin:0;padding:0}
body{font-size:12px}
td{padding:5px}
.field_item{display:block;line-height:30px;width:585px}
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
<table cellpadding="1" cellspacing="1" border="0" width="100%" id="searchbox">
	<tr>
		<td width="100" align="right">用户名:</td>
		<td><?php if($username){ echo $username;}else{?><input type="text" id="username" name="username"><?php } ?></td>
	</tr>	
	<tr>
		<td width="100" align="right">密码:</td>
		<td><input type="text" id="password" name="password" value=""></td>
	</tr>
	<tr>
		<td width="100" align="right">权限:</td>
		<td>
			<?php if($username == 'admin'){ ?>所有权限
			<?php }else{ ?>
			<table width="400" border="1" style="BORDER-COLLAPSE:collapse" bordercolor="#CCCCCC" cellpadding="0" cellspacing="0">
				<tr>
					<th height="25" width="200">模型名称</th>
					<th align="center" width="50">列表</th>
					<th align="center" width="50">添加</th>
					<th align="center" width="50">编辑</th>
					<th align="center" width="50">删除</th>
				</tr>
				<?php foreach($module as $mod){ ?>
				<tr>
					<td><?php echo $mod['name'];?></td>
					<td align="center"><input <?php if(isset($user['permission'][$mod['ds']][$mod['id']]) && ($user['permission'][$mod['ds']][$mod['id']] & 1)){?>checked<?php } ?> name="permission[<?php echo $mod['ds'];?>][<?php echo $mod['id'];?>][]" value="1" type="checkbox"></td>
					<td align="center"><input <?php if(isset($user['permission'][$mod['ds']][$mod['id']]) && ($user['permission'][$mod['ds']][$mod['id']] & 2)){?>checked<?php } ?> name="permission[<?php echo $mod['ds'];?>][<?php echo $mod['id'];?>][]" value="2" type="checkbox"></td>
					<td align="center"><input <?php if(isset($user['permission'][$mod['ds']][$mod['id']]) && ($user['permission'][$mod['ds']][$mod['id']] & 4)){?>checked<?php } ?> name="permission[<?php echo $mod['ds'];?>][<?php echo $mod['id'];?>][]" value="4" type="checkbox"></td>
					<td align="center"><input <?php if(isset($user['permission'][$mod['ds']][$mod['id']]) && ($user['permission'][$mod['ds']][$mod['id']] & 8)){?>checked<?php } ?> name="permission[<?php echo $mod['ds'];?>][<?php echo $mod['id'];?>][]" value="8" type="checkbox"></td>
				</tr>
				<?php } ?>
			</table>
			<?php } ?>
		</td>
	</tr>
<?php if($username){ ?>
	<tr>
		<td width="100" align="right">注册时间:</td>
		<td><?php echo $user['create_time']; ?></td>
	</tr>
	<tr>
		<td width="100" align="right">更新时间:</td>
		<td><?php echo $user['update_time']; ?></td>
	</tr>
<?php } ?>
	<tr>
		<td></td>
		<td height="50"><input type="submit" value="保存"></td>
	</tr>	
</table>
</form>
</body>
</html>