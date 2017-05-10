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
<script language="javascript">
function onSearch()
{
	var dt = document.getElementById('s_dt');
	var ds = document.getElementById('s_ds');
	if((ds.value != '') && (dt.value == '')){
		alert('搜索包含“数据段”的同时也必须包含“标识”!');
		dt.focus();
		return false;
	}
	return true;
}
</script>
</head>
<body>
<form onsubmit="return onSearch()" action="/module" method="get">
<table cellpadding="1" cellspacing="1" border="0" width="100%" id="searchbox">
	<tr>
		<td><p class="sep"> 名称：<input type="text" name="name" value="<?php echo $name; ?>"> 
			标识：<input id="s_dt" type="text" name="dt" value="<?php echo $dt; ?>">
			数据段：<input id="s_ds" type="text" name="ds" value="<?php echo $ds; ?>">
			<input type="submit" value="搜索">
			
			</p>
		</td>
	</tr>
</table>
</form>
<span style="float:left;padding:5px"><a href="/module/add" style="color:red;font-weight:bold;">添加模型</a></span>
<table border="0" width="100%" align="center" id="datalist">
	<tr>
		<th>模型名称</th>
		<th>模型标识</th>
		<th>数据段</th>
		<th>创建时间</th>
		<th width="270">操作</th>
	</tr>
	<?php foreach($data['list'] as $val){ ?>
	<tr>
		<td><?php echo $val['name']; ?></td>
		<td><?php echo $val['ds']; ?></td>
		<td><?php echo $val['id']; ?></td>
		<td><?php echo $val['create_time']; ?></td>
		<td>&nbsp;<a href="<?php printf("/module/add?dt=%s&ds=%s", $val['ds'], $val['id']); ?>">编辑</a> 
		<?php if(!in_array($val['ds'], $deny_data_types)){ ?>
		|<a onclick="return confirm('确认删除该模型')" href="<?php printf("/module/del?dt=%s&ds=%s", $val['ds'], $val['id']); ?>">删除</a>| 
		<a href="<?php printf("/data/index?dt=%s&ds=%s", $val['ds'], $val['id']); ?>">数据列表</a> | 
		<a href="<?php printf("/data/add?dt=%s&ds=%s", $val['ds'], $val['id']); ?>">添加数据</a>
		<?php } ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td height="25" align="right" colspan="5">Total:<?php echo $data['count'];?>&nbsp;&nbsp;<?php echo $page; ?></td>
	</tr>	
</body>
</html>