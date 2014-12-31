<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>jiankong</title>
<script type="text/javascript" src="assets/plugins/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery.form.js"></script>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker/css/datepicker.css" />
<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
<script type="text/javascript" src="assets/plugins/jquery.md5.js"></script>
<script type="text/javascript" src="assets/plugins/jquery.jplayer.min.js"></script>
<script src="assets/plugins/validate.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function(){
	    $("#jplayer").jPlayer({
	        ready: function () {
		        $(this).jPlayer("setMedia", {
		          mp3: "assets/media/message_tip.mp3"
	        });
	      },
	      supplied: "mp3"
	    });
	})

	function playSound() {
		if($("#jplayer"))
		{
			$("#jplayer").jPlayer('play');
		}
    	else
    	{
    		setTimeout(playSound(),200);
    	}
	}
</script>
</head>
<body>
<div class="foreground" id="wrapper">
	<div id="jplayer"></div>
<?php
//预警条件
$high = isset($_GET['high']) ? (float)$_GET['high'] : 0;
$low = isset($_GET['low']) ? (float)$_GET['low'] : 0;
$code = isset($_GET['code']) ? $_GET['code'] : "0000011";
set_time_limit(60*60*2);
if($high || $low)
{
	$i = 0;
	while ($i <= 60*10) {
		sleep(10);
		$content = getContent($code);
		if($content)
		{
			$arr = explode(",",$content);
			if((float)$arr[3] >= $high)
			{
				print_r($arr);
echo <<<EOT
<script type="text/javascript">setTimeout("playSound()",2000);alert("arrarid high price");</script>
EOT;
				break;
			}
			if((float)$arr[3] <= $low)
			{
				print_r($arr);
echo <<<EOT
<script type="text/javascript">setTimeout("playSound()",2000);alert("arrarid low price");</script>
EOT;
				break;
			}
		}
		else
		{
			echo "出错";
		}
		$i++;
	}
echo <<<EOT
<script type="text/javascript">setTimeout("playSound()",2000);alert("moni again");</script>
EOT;
}
else
{
	echo "请输入价格";
}

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