<?php

error_reporting(0);
if ($_GET['data'] == "true") {
	if (!isset($_GET['cid']) || !isset($_GET['start']) || !isset($_GET['count'])) {
		exit('missing !');
	}
	$url = 'http://wallpaper.apc.360.cn/index.php?c=WallPaper&a=getAppsByCategory&cid='.$_GET['cid'].'&start='.$_GET['start'].'&count='.$_GET['count'].'&from=360chrome';
	$ct = file_get_contents($url);
	$ct = json_decode($ct,true);
	if ($ct["errno"] !== "0") :
		echo $ct['errmsg'];
	else:
		$rs = [];
		for ($i=0; $i < count($ct['data']); $i++) { 
			$rs[$i]['img'] = $ct['data'][$i]["img_1024_768"];
			$rs[$i]['url'] = $ct['data'][$i]["url"];
			$rs[$i]["update_time"] = $ct['data'][$i]["update_time"];
		}
		$rs = json_encode($rs);
		echo $rs;
	endif;
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>BD IMG</title>
	<meta name="referrer" content="never">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="s/jquery.lazyload.js"></script>
	<script type="text/javascript" src="s/jquery.xgallerify.min.js"></script>
	<link rel="stylesheet" type="text/css" href="s/i.css">
</head>
<body>

<div class="photos"></div>

<script type="text/javascript">

cid = [[15,0],[26,0],[5,0]];


<?php
/*
http://cdn.apc.360.cn/index.php?c=WallPaper&a=getAllCategoriesV2&from=360chrome
get all Categories id, and fill in 'cid';
cid=>
	[Categories id, 0 (for cookie)]

cid = [[36,0],[16,0],[30,0],[9,0],[15,0],[26,0],[11,0],[14,0],[5,0],[12,0],[10,0],[29,0],[7,0],[13,0],[22,0],[16,0],[18,0],[35,0]]; //all

ID: 36 => 4K专区
ID: 6 => 美女模特
ID: 30 => 爱情美图
ID: 9 => 风景大片
ID: 15 => 小清新
ID: 26 => 动漫卡通
ID: 11 => 明星风尚
ID: 14 => 萌宠动物
ID: 5 => 游戏壁纸
ID: 12 => 汽车天下
ID: 10 => 炫酷时尚
ID: 29 => 月历壁纸
ID: 7 => 影视剧照
ID: 13 => 节日美图
ID: 22 => 军事天地
ID: 16 => 劲爆体育
ID: 18 => BABY秀
ID: 35 => 文字控
	
*/
?>

</script>

<script type="text/javascript" src="s/i.js"></script>

</body>
</html>