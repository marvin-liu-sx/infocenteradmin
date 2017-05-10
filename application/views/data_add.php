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
<form action="" method="post" id="data_form">
<input type="hidden" name="refer" value="<?php echo $refer; ?>">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="100" valign="top" align="right">ID:</td>
		<td><?php if($id){ echo $id; }else{ ?><input type="text" name="id" value=""><?php } ?>
		</td>
	</tr> 
	<?php foreach($fields as $field => $item){ ?>
	<tr>
		<td width="100" valign="top" align="right"><?php echo $item['comment'] ? $item['comment'] : $item['field'];?>:</td>
		<td>
			<?php
				if(isset($data[$field])){
					if($item['control'] == 'checkbox'){
						$data[$field] = (array)$data[$field];
					}else{
						$data[$field] = (is_array($data[$field]) || is_object($data[$field])) ? 
										implode(',', $data[$field]) : 
										(string)$data[$field];
					}
				}
			?>
			<?php if($item['control'] == 'select'){ ?>
			<select name="data[<?php echo $field; ?>]">
				<?php foreach($item['options'] as $value => $name){ ?>
				<option <?php echo ($data) ? (isset($data[$field]) && ($value == $data[$field]) ? "selected" : "") : (($item['default'] == $value) ? "selected" : ""); ?> value="<?php echo $value;?>"><?php echo $name;?></option>
				<?php } ?>
			</select>
			<?php }else if($item['control'] == 'radio'){ ?>
				<?php foreach($item['options'] as $value => $name){ ?>
				<label><input <?php echo ($data) ? (isset($data[$field]) && ($value == $data[$field]) ? "checked" : "") : (($item['default'] == $value) ? "checked" : ""); ?> type="radio" name="data[<?php echo $field; ?>]" value="<?php echo $value;?>"><?php echo $name;?></label>
				<?php } ?>
			<?php }else if($item['control'] == 'checkbox'){ ?>
				<?php foreach($item['options'] as $value => $name){ ?>
				<label><input <?php echo ($data) ? (isset($data[$field]) && in_array($value, (array)$data[$field]) ? "checked" : "") : (($item['default'] == $value) ? "checked" : ""); ?> type="checkbox" name="data[<?php echo $field; ?>][]" value="<?php echo $value;?>"><?php echo $name;?></label>
				<?php } ?>
			<?php }else if($item['control'] == 'multitext'){ ?>
				<textarea rows="8" cols="50" name="data[<?php echo $field;?>]"><?php echo isset($data[$field]) ? $data[$field] : ""; ?></textarea>
			<?php }else{ ?>
				<input type="text" size="50" name="data[<?php echo $field;?>]" value="<?php echo isset($data[$field]) ? $data[$field] : ""; ?>">
			<?php } ?> <?php echo $item['tip']; ?>
		</td>
	</tr> 
	<?php } ?>
	<tr>
		<td></td>
		<td height="50"><input type="submit" value="提交"> <input type="button" onclick="history.back()" value="返回"></td>
	</tr>	
</table>
</form>
<script language="javascript" src="http://static1.pplive.cn/public/jquery/jquery-1.7.1.min.js"></script>
</body>
</html>