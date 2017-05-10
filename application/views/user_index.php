<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>list</title>
<style type="text/css">
body{font-size:12px}
#datalist th{background:#99FF99;line-height:22px;}
#datalist tr:nth-child(odd){background:#CCFF99;}
#datalist tr:hover{background:#CCFFFF;}
#datalist td{line-height:22px;}
a{text-decoration:none}
</style>
</head>
<body>
<table cellpadding="1" cellspacing="1" border="0" width="100%" id="searchbox">
	<tr>
		<td><a href="/member/add" style="color:red;font-weight:bold;">添加用户</a></td>
	</tr>
</table>
<table border="0" width="100%" align="center" id="datalist">
	<tr>
		<th>用户名</th>
		<th width="200">注册时间</th>
		<th width="150">操作</th>
	</tr>
	<?php foreach($data['list'] as $val){ ?>
	<tr>
		<td><?php echo $val['id']; ?></td>
		<td><?php echo $val['create_time']; ?></td>
		<td align="center"><a href="/member/add?username=<?php echo $val['id']; ?>">编辑</a> | 
		<a onclick="return confirm('确认删除该用户')" href="/member/del?username=<?php echo $val['id']; ?>">删除</a>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td height="25" align="right" colspan="3">Total:<?php echo $data['count'];?>&nbsp;&nbsp;<?php echo $page; ?></td>
	</tr>	
</body>
</html>