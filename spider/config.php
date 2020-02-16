<?php

$autoclimb = 4; //自动爬行次数 一般设置为2 ~ 4即可
$keepclimbing = false;//一直爬行直到达到最长爬行时间
$searchlimit = 15;//每页搜索限制N条
$password = "admin888";//蜘蛛的密码
ignore_user_abort(true);//关掉页面也会继续执行
set_time_limit(30);
$sitelimit = array();//域名限制 如："baidu.com","mo.jj.com" 写入字符串则蜘蛛爬行的url会包含该字符串。不写则为全网爬
$dbhost = "localhost";
$username = "root";
$userpass = "root";
$dbdatabase = "test";
date_default_timezone_set("PRC");
$db = new mysqli($dbhost,$username,$userpass,$dbdatabase);
if (mysqli_connect_error()) {
	exit('Could not connect to database.');
}
$db->query("set names utf-8");

function fixmarks($str){
	return str_replace(["\"","'","+"], ["\\\"","\\'","\\\\+"], $str);
}
function getsiteurl($n){
	$n = $n."/";
	preg_match("/http.*?\/\/.*?\//i", $n, $rs);
	return $rs[0];
}
function htmlinfo($str){
	echo '<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body>
'.$str.'
</body>
</html>';
}
function jsgo($url,$timeout=3000){
	echo '
<script>setTimeout(function(){
			window.location.href = "'.$url.'";
		},'.$timeout.');
		setTimeout(function(){
			window.location.href = "'.$url.'";
		},60000);</script>';
}