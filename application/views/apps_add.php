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
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="100" align="right">应用标识:</td>
		<td><?php if($appid){ echo $appid; }else{ ?><input type="text" value="" name="appid"><?php } ?></td>
	</tr>	
	<tr>
		<td width="100" align="right">应用名称:</td>
		<td><input type="text" id="appname" name="appname" value="<?php echo $appid ? $app['appname'] : ""; ?>"></td>
	</tr>
<?php if($appid){ ?>
	<tr>
		<td width="100" align="right">安全码:</td>
		<td><?php echo $app['security']; ?></td>
	</tr>
	<tr>
		<td width="100" align="right">创建时间:</td>
		<td><?php echo $app['create_time']; ?></td>
	</tr>
	<tr>
		<td width="100" align="right">更新时间:</td>
		<td><?php echo $app['update_time']; ?></td>
	</tr>	
<?php } ?>	
	<tr>
		<td></td>
		<td height="50"><input type="submit" value="保存数据"></td>
	</tr>	
</table>
</form>
</body>
</html>