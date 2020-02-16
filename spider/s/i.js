$(function(){

wd = $("input[name=s]").val().split(" ");
for (var i = 0; i < wd.length; i++) {
	wd[i] = "(" + wd[i].replace("/", "\\/").replace("%", "[\\s\\S]?").replace("+", "\\+") + ")";
	$(".title").each(function(){
		$(this).text(
			$(this).text().replace(new RegExp(wd[i],"i"),"ｊｂＭ$1ｊｂＭ")
		)
	});
	$(".content").each(function(){
		$(this).text(
			$(this).text().replace(new RegExp(wd[i],"i"),"ｊｂＭ$1ｊｂＭ")
		)
	});
	$(".ops .a").each(function(){
		$(this).text(
			$(this).text().replace(new RegExp(wd[i],"i"),"ｊｂＭ$1ｊｂＭ")
		)
	});
}
$(".title").each(function(){
	$(this).html(
		$(this).html().replace(/ｊｂＭ(.*?)ｊｂＭ/g,
				"<span class='mark'>$1</span>")
	)
});
$(".content").each(function(){
	$(this).html(
		$(this).html().replace(/ｊｂＭ(.*?)ｊｂＭ/g,
				"<span class='mark'>$1</span>")
	)
});
$(".ops .a").each(function(){
	$(this).html(
		$(this).html().replace(/ｊｂＭ(.*?)ｊｂＭ/g,
				"<b>$1</b>")
	)
});

});
console.log("Jb 搜索引擎：%s","https://github.com/1443691826/gadget/tree/master/spider");