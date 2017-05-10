<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="http://admin.synacast.com/resources/css/login.css" />
<title>登录 - APlusCMS</title>
</head>

<body style="overflow:hidden;">
<form action="/welcome/login2" method="post" name="loginform">
<input type="hidden" name="forward" value="" />
<div class="login clearfix">
	<div class="bbg">
		<img src="http://admin.synacast.com/resources/images/bg_login.jpg" alt="" width="100%" height="100%" />
	</div>
	<div class="loginbox">
		<h1><input type="image" src="http://static9.pplive.cn/oth/infoc_logo.png" /></h1>
		<table>
			<tr>
				<td>用户名：</td>
				<td><input type="text" name="username" id="username" class="w165" /></td>
			</tr>
			<tr>
				<td>密&nbsp;&nbsp;&nbsp;&nbsp;码：</td>
				<td><input type="password" name="password" id="password" class="w165" /></td>
			</tr>
						<tr>
				<td> </td>
				<td><a href="###" class="btn_login" onclick="loginform.submit();"></a>
				</td>
			</tr>
		</table>
		<p align="center">InfoCenter V2.0 &nbsp;&nbsp;Support by RnD Web Div</p>
	</div>
</div>
</form>

</body>
</html>
<script language="javascript">document.getElementById('username').focus();</script>