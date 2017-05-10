<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<title>message page</title>
<style type="text/css">
body {font-size:12px;background-color:#fff;font-family:Lucida Grande, Verdana, Sans-serif;font-size:12px;color:#000;}
a{text-decoration:none;color:#000}
#content{text-align: center;width:600px;height:150px;margin:40px auto}
#content h1{color:#FF6C00;line-height:30px;font-size:16px;}
#content .msg{;background:#F7F7F7;color:#095E00;padding:20px 0 20px 0;font-size:16px;font-weight:bold;}
a.back{font-size:12px;font-weight:normal}
a.back:hover{text-decoration: underline}
</style>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<?php if($url){ ?>
<meta http-equiv="refresh" content="<?php echo $time; ?>;url=<?php echo $url; ?>">
<?php } ?>
</head>
<body>

<div id="content">
    <div class="msg">
		<p><?php echo $msg; ?></p>
		<p><A class="back" href="<?php if($url){ echo $url; }else{ ?>javascript:history.back()<?php } ?>">如果页面没有跳转,请点击这里</a></p></div>
</div>
</body>
</html>