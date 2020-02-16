count = 25;
loadlock = false;

function setCookie(cname,cvalue,exdays){
  var d = new Date();
  d.setTime(d.getTime()+(exdays*24*60*60*1000));
  var expires = "expires="+d.toGMTString();
  document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname){
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++) 
  {
    var c = ca[i].trim();
    if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
  return "";
}

if (getCookie('cid_ck').length > 10) {
	if (!new RegExp(/[A-Za-z\"\:]/,'ig').test(getCookie('cid_ck'))) {
		cid = eval(getCookie('cid_ck'));
	}
}

function loadata(cb){
	var nu = Math.floor(Math.random()*cid.length);
	var cid_f = cid[nu][0];
	var start = Number(cid[nu][1]);
	$.ajax({
		url: "?data=true&count="+count+"&cid="+cid_f+"&start="+start,
		type: 'get',
		dataType: 'json',
		success: function(rs){
			cb(rs)
		}
	});
	cid[nu][1] = start + count;
}

function loadImageA(u, org, time){
	hold = u.replace(/bdm\/(.*?)\//i,"bdm\/1024_768_75\/");
	u1 = u.replace(/bdm\/(.*?)\//i,"bdm\/2560_1600_100\/");
	u2 = u.replace(/bdm\/(.*?)\//i,"bdm\/1600_900_100\/");
	u3 = u.replace(/bdm\/(.*?)\//i,"bdm\/1440_900_100\/");
	u4 = u.replace(/bdm\/(.*?)\//i,"bdm\/1280_800_100\/");
	u5 = u.replace(/bdm\/(.*?)\//i,"bdm\/1024_768_100\/");
	return '<div class="photo">\
		<img data-original="'+hold+'">\
		<div class="title">\
		<p><b>➠</b> <a href="'+u1+'" target="_blank">2560 x 1600</a></p>\
		<p><b>➠</b> <a href="'+u2+'" target="_blank">1600 x 900</a></p>\
		<p><b>➠</b> <a href="'+u3+'" target="_blank">1440 x 900</a></p>\
		<p><b>➠</b> <a href="'+u4+'" target="_blank">1280 x 800</a></p>\
		<p><b>➠</b> <a href="'+u5+'" target="_blank">1024 x 768</a></p>\
		<p><b>➠</b> <a href="'+org+'" target="_blank">Original</a></p>\
		<time><b>🕙</b> '+time+'</time>\
		</div>\
		</div>';
}

function arr2string(objarr){
　　var typeNO = objarr.length;
  　 var tree = "[";
 　　for (var i = 0 ;i < typeNO ; i++){
   　　　tree += "[";
   　　　tree +="'"+ objarr[i][0]+"',";
   　　　tree +="'"+ objarr[i][1]+"'";
  　　　 tree += "]";
  　　　 if(i<typeNO-1){
    　　 　　tree+=",";
 　　　  }
  　 }
  　 tree+="]";
  　 return tree;
}

function asyn(){
	if (loadlock) {return false}
	var view = window.scrollY || document.body.scrollTop;
	view += $(window).height();
	if (view > $(document).height() - 1080) {
		loadlock = true;
		loadata(function(ct){
			for (var i = 0; i < ct.length; i++) {
				$(".photos")[0].innerHTML += loadImageA(ct[i]['img'], ct[i]['url'], ct[i]['update_time']);
			}
			$('.photo').each(function(){
				$(this).hover(function(){
					$(this).children('.title').css('display',"block");
					$(this).css({'box-shadow':'0 0 15px black',"z-index":'9'});
				},function(){
					$(this).children('.title').css('display',"none");
					$(this).css({'box-shadow':'none',"z-index":'2'});
				})
			});
			$("img[data-original]").lazyload({effect: "fadeIn", threshold: 250, placeholder: "s/holder.jpeg"});
			setCookie("cid_ck",arr2string(cid),7);
			loadlock = false;
		});
	}
}

asyn();

window.onscroll = function(){
	asyn()
}