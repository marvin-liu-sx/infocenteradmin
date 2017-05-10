<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Info Center管理后台</title>
<style type="text/css">
*{margin:0;padding:0}
</style>
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="200" id="menu"><iframe frameborder="0" src="/welcome/menu" width="100%" height="100%"></iframe></td>
		<td width="5" bgcolor="#CCCCCC"></td>
		<td id="main"><iframe src="/module" name="main" frameborder="0" width="100%" height="100%"></iframe></td>
	</tr>
</table>
<script language="javascript" type="text/javascript">
var height = document.documentElement.clientHeight ? 
	document.documentElement.clientHeight : document.body.scrollHeight;
document.getElementById("main").style.height = height + 'px';
document.getElementById("menu").style.height = height + 'px';
</script> 
</body>
</html>