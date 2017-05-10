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
	<form action="/data" method="get">
	<input type="hidden" name="dt" value="<?php echo $dt; ?>">
	<input type="hidden" name="ds" value="<?php echo $ds; ?>">
	<tr>
		<td><span style="float:right"><a href="<?php printf("/data/add?dt=%s&ds=%s", $dt, $ds); ?>">添加数据</a></span>
		<p>ID: <input type="text" name="ids" value="<?php echo isset($_GET['ids']) ? $_GET['ids'] : '';?>">
		<?php $n = 1; ?>
		<?php foreach($module['mapping'] as $field){ ?>
		<?php if(isset($module['fields'][$field]) && ($item = $module['fields'][$field])){ ?>
			<?php echo $item['comment'] ? $item['comment']: $item['field'];?>:
			<?php if($item['control'] == 'select'){ ?>
			<select name="<?php echo $field; ?>">
				<option value="">选择<?php echo $item['comment'];?></option>
				<?php foreach($item['options'] as $value => $name){ ?>
				<option <?php echo (isset($_GET[$field]) && ($_GET[$field] == $value)) ? "selected" : "";?> value="<?php echo $value;?>"><?php echo $name;?></option>
				<?php } ?>
			</select>
			<?php }else if($item['control'] == 'radio'){ ?>
				<?php foreach($item['options'] as $value => $name){ ?>
				<label><input <?php echo (isset($_GET[$field]) && ($_GET[$field] == $value)) ? "checked" : "";?> type="radio" name="<?php echo $field; ?>" value="<?php echo $value;?>"><?php echo $name;?></label>
				<?php } ?>
			<?php }else if($item['control'] == 'checkbox'){ ?>
				<?php foreach($item['options'] as $value => $name){ ?>
				<label><input type="checkbox" name="<?php echo $field; ?>" value="<?php echo $value;?>"><?php echo $name;?></label>
				<?php } ?>
			<?php }else{ ?>
				<input type="text" name="<?php echo $field;?>" value="<?php echo isset($_GET[$field]) ? $_GET[$field] : "";?>">
			<?php } ?>			
			<?php echo ((++$n)%3 == 0) ? "</p><p>" : "";?>
		<?php } ?>
		<?php } ?>
		<?php if($n != 0){ ?><input type="submit" value="搜索"><?php } ?></p>
		</td>
	</tr>
	</form>
</table>
<?php $fields = 2; ?>
<table border="0" width="100%" align="center" id="datalist">
	<tr>
		<th>ID</th>
		<?php foreach($module['show'] as $field){ ?>
		<?php if(isset($module['fields'][$field])){ $fields++;?>
		<th><?php echo $module['fields'][$field]['comment'] ? $module['fields'][$field]['comment'] : $module['fields'][$field]['field']; ?></th>
		<?php } ?>
		<?php } ?>
		<th width="150">操作</th>
	</tr>
	<?php foreach($data['list'] as $val){ ?>
	<tr>
		<td><?php echo $val['id']; ?></td>
<?php
foreach($module['show'] as $field)
{
	if(isset($module['fields'][$field]))
	{
		echo '<td>';
		if(isset($val[$field]))
		{
			if(in_array($module['fields'][$field]['control'], array('select', 'checkbox', 'radio'))){
				$f = '';
				foreach((array)$val[$field] as $v)
				{
					if(isset($module['fields'][$field]['options'][$v]))
					{
						echo $f . $module['fields'][$field]['options'][$v];
						$f = ',';
					}
				}
			}else{
				echo (is_array($val[$field]) || is_object($val[$field])) ? implode(',', $val[$field]) : (string)$val[$field];
			}
		}
		echo '</td>';
	}
}
?>
		<td align="center"><a href="<?php printf("/data/add?dt=%s&ds=%s&id=%s", $val['dt'], $val['ds'], $val['id']); ?>">编辑</a> | 
			<a onclick="return confirm('确认删除此记录?')" href="<?php printf("/data/del?dt=%s&ds=%s&id=%s", $val['dt'], $val['ds'], $val['id']); ?>">删除</a>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td height="25" align="right" colspan="<?php echo $fields;?>">Total:<?php echo $data['count'];?>&nbsp;&nbsp;<?php echo $page; ?></td>
	</tr>	
</body>
</html>