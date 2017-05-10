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
		<td width="100" align="right">用户名:</td>
		<td><?php echo $username;?></td>
	</tr>	
	<tr>
		<td width="100" align="right">旧密码:</td>
		<td><input type="password" id="oldpassword" name="oldpassword" value=""></td>
	</tr>
	<tr>
		<td width="100" align="right">新密码:</td>
		<td><input type="password" id="password1" name="password1" value=""></td>
	</tr>	
	<tr>
		<td width="100" align="right">确认新密码:</td>
		<td><input type="password" id="password2" name="password2" value=""></td>
	</tr>
	<tr>
		<td></td>
		<td height="50"><input type="submit" value="保存"></td>
	</tr>	
</table>
</form>
</body>
</html>