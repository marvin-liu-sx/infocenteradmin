<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>menu</title>
<style type="text/css">
body{font-size:12px}
a{text-decoration:none}
</style>
</head>
<body>
<table cellpadding="2" cellspacing="0" border="0" width="100%" class="table_menu">
	<tr>
		<td align="center"><b><?php echo $username;?></b> | <a target="main" href="/member/info">编辑</a> | <a href="/welcome/logout" target="_parent">退出</a></td>
	</tr>
	<tr>
		<td><a href="/module" target="main">模型管理</a></td>
	</tr>
	<tr>
		<td><a href="/apps" target="main">应用管理</a></td>
	</tr>	
	<tr>
		<td><a href="/member" target="main">用户管理</a></td>
	</tr>	
</table>
</body>
</html>