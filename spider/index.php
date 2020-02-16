<?php
/**
* @author Luuljh <http://github.com/1443691826>
* @license GPL-2.0 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

include 'config.php';
if ($_GET['go']) {
	htmlinfo('<meta content="always" name="referrer"><script>window.location.replace("'.urldecode($_GET['go']).'")</script>
<noscript><META http-equiv="refresh" content="0;URL=\''.urldecode($_GET['go']).'\'"></noscript>');
	exit();
}
if ($_GET['m']=="cache" && $_GET['id']) {
	!is_nan($_GET['id']) ?: exit('What are you doing ?');
	htmlinfo($db->query("SELECT html FROM jb_spider WHERE id=".$_GET['id'])->fetch_row()[0]);
	exit();
}
$wd = htmlspecialchars($_GET['s']);
$wd = preg_replace("@ +@", " ", $wd);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>JB Search</title>
	<link rel="stylesheet" type="text/css" href="s/i.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<script src="//libs.baidu.com/jquery/1.8.3/jquery.min.js"></script>
</head>
<body>

<div class="container-a">
	<div class="searchbar">
		<img src="s/logo.jpg" class="logo" ondragstart='return false;'>
		<div class="search">
		    <form action="" method="get">
		        <input type="text" name="s" value="<?php echo $wd ?>">
		        <label><div class="button">搜索</div><button type="submit" class="hid"></button></label>
		    </form>
		</div>
		<ul id="oUl">
			<li>[Server Info]</li>
			<?php
				$rs = $db->query('SHOW TABLE STATUS where Name = "jb_spider"');
				$tmp = $rs->fetch_row();
				echo "<li>Size: " . number_format($tmp[6] / 1048576, 4) . "mB</li>";
				echo "<li>Rows: ".$tmp[4]."</li>";
				echo "<li title='lastest climbed time'>LCT: ".$tmp[12]."</li>";
				unset($rs, $tmp);
			?>
		</ul>
		<small class="foot">
		&copy; 2018 <?php echo $_SERVER['HTTP_HOST'] ?>. Powered By <a href="https://github.com/1443691826/gadget/tree/master/spider">What?</a>
		</small>
		<!--
		链接禁止去除
		-->
	</div>
</div>


<div class="container-b">
<div class="result">

<?php
if (empty($wd)):
	$wd = "";
endif;
$s = $wd;
if (strpos($s, " ") !== false) {
	$s = explode(" ", $s);
	$gs = $s;
	$s = implode("%", $s);
}else{
	$gs[0] = $s;
}
$pageNo = $_GET['page'];
$pageNo ?: $pageNo = 1;
if (is_nan($pageNo) || $pageNo<1) {
	echo "<div class='warning'>请勿修改page参数</div>";
	$pageNo = 1;
}
$pageNo--;
$banid = "id <> -1";
$tmp_addon = $pageNo*$searchlimit;
$tmp_wd = str_replace(" ", "|", $wd);
if (substr($tmp_wd, 0, 1) == "|") {
	$tmp_wd = substr($tmp_wd, 1);
}
if (substr($tmp_wd, -1) == "|") {
	$tmp_wd = substr($tmp_wd,0,strlen($tmp_wd)-1); 
}
$tmp_wd = fixmarks($tmp_wd);
$tmp_banid_ = $db->query("SELECT id FROM jb_spider WHERE concat(url,title,html) like '%".$s."%' ORDER BY url REGEXP '(".$tmp_wd.")' desc, title REGEXP '(".$tmp_wd.")' desc, date desc limit ".$tmp_addon);
while ($tmp_banid = $tmp_banid_->fetch_row()) {
	$banid .= " AND id <>".$tmp_banid[0];
}
unset($tmp_banid_,$tmp_banid,$tmp_addon);
if ($wd == "") {
	$rs = $db->query("select * from jb_spider ORDER BY rand() limit " . $searchlimit);
}else{
	$rs = $db->query("SELECT * FROM jb_spider WHERE concat(url,title,html) like '%".$s."%' AND ".$banid." ORDER BY url REGEXP '(".$tmp_wd.")' desc, title REGEXP '(".$tmp_wd.")' desc, date desc limit ".$searchlimit);
}
unset($tmp_wd);
$count = 0;
while($tmp = $rs->fetch_row()){
	if ($count >= $searchlimit) {
		break;
	}
	$count++;
	$info[$count]['id'] = $tmp[0];
	$info[$count]['url'] = $tmp[1];
	$info[$count]['content'] = $tmp[2];
	$info[$count]['title'] = $tmp[3];
	$info[$count]['date'] = $tmp[4];
	$info[$count]['pr'] = 0;
	for ($i=0; $i < count($gs); $i++) {
		//计算权重
		if (preg_match("/".$gs[$i]."/i", $info[$count]['title'])) {
			$info[$count]['pr'] += 5;
		}
		if (preg_match("/".$gs[$i]."/i", $info[$count]['url'])) {
			$info[$count]['pr'] += 5;
		}
		$strrepeatcount = substr_count($info[$count]['content'], $gs[$i]);
		if ($strrepeatcount>1 && $strrepeatcount<100) {
			$info[$count]['pr'] += $strrepeatcount;
		}
		$tmp_title = str_replace("*NoTitle*", "", $info[$count]['title']);
		if ($info[$count]['title'] !== $tmp_title) {
			//无标题
			$info[$count]['title'] = $tmp_title;
			$info[$count]['pr'] -= 1;
		}
	}
}
unset($strrepeatcount);
if ($count !== 0) :
//按照权重重新排序
foreach ($info as $key => $row){
    $volume[$key]  = $row['pr'];
    $edition[$key] = $row['date'];
}
array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $info);
for ($i=0; $i < count($info); $i++) : ?>

<div class="rs">
	<a class="title" href="?go=<?php echo urlencode($info[$i]['url']) ?>" target="_blank">
		<?php echo $info[$i]['title'] ?>
	</a>
	<div class="content"><?php echo excerpt($info[$i]['content']) ?></div>
	<div class="ops">
		<a class="a" href="?go=<?php echo urlencode($info[$i]['url']) ?>" target="_blank">
			<?php echo str_replace(["https://","http://", "/"], "", getsiteurl($info[$i]['url'])) ?>
		</a>
		&nbsp;
		<a target="_blank" href="?m=cache&id=<?php echo $info[$i]['id'] ?>">查看快照</a>
		&nbsp;
		<span><?php echo $info[$i]['date'] ?></span>
		&nbsp;
		<span>动态权重：<?php echo $info[$i]['pr'] ?></span>
	</div>
</div>

<?php
endfor;

else:
?>
<div class="rs">
	抱歉哦~什么都没有搜索到啊 QAQ
    <img style="display: block;max-width: 100%;" src="./s/404.gif">
</div>
<?php
endif;

function excerpt($str){
	global $gs;
    $start = mb_strpos($str, $gs[0], 0,"utf-8");
    $sub = mb_substr($str, $start, 300, "utf-8");
	return $sub."...";
}

?>

<?php if (!empty($wd)) : ?>
<div class="nav">
	<?php if ($pageNo>0): ?>
		<a class="f-l" href="?s=<?php echo $wd ?>&page=<?php echo $pageNo ?>"><li>上一页</li></a>
	<?php endif ?>
	<?php if ($count >= $searchlimit) : ?>
	<a class='f-r' href="?s=<?php echo $wd ?>&page=<?php echo $pageNo+2 ?>"><li>下一页</li></a>
	<?php endif ?>
</div>
<?php endif ?>
</div>
</div>

<div style="clear: both;"></div>

<script type="text/javascript" src="s/i.js"></script>
</body>
</html>