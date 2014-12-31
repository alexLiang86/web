<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>now</title>
</head>
<body>
<div class="foreground" id="wrapper">
<?php
//预警条件
$code = isset($_GET['code']) ? $_GET['code'] : "0000011";
$content = getContent($code);
print_r($content);

function getContent($code="0000011")
{
	$url = "http://hqdigi2.eastmoney.com/EM_Quote2010NumericApplication/CompatiblePage.aspx?Type=ZT&fav=".$code;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	$contents = curl_exec($ch);
	curl_close($ch);

	if($contents === FALSE)
	{
		return false;
	}

	$start = strpos($contents,"[\"");
	$end = strpos($contents,"\"]");
	if($start !== false && $end !== false)
	{
		return substr($contents, $start+2,$end-$start-2);
	}
	else
	{
		return false;
	}
}
?>
</div>
</body>
</html>