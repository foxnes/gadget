// * blog.lljh.bid
_keyStr = "; \\/ 0 1 2 3 4 5 6 7 8 9 \\+ = : a b c d e f g h i j k l m n o p q r s t u v w x y z A B C D E F G H I J K L M N O P Q R S T U V W X Y Z";
_keyStr = _keyStr.split(" ");
for (var i = 0; i < _keyStr.length; i++) {
	_keyStr[i] = "("+_keyStr[i]+")";
}
_ttStr = [];
for (var i = 0; i < _keyStr.length; i++) {
	_ttStr[i] = "&#x0"+(300+i)+";";
}
function file2str(){
	var reader = new FileReader();
	reader.onload = function(evt){
		var f = evt.target.result;
		var wrt = "";
		f = f.split("");
		for (var i = 0; i < f.length; i++) {
			f[i] = "("+f[i]+")";
		}
		f = chunk(f,1024*5);//每组1024*n个字符 （影响速率） f[array(n),array(n)];
		var pgscount = 0;
		progress.value=0;
    	var dow = setInterval(function(){
    		progress.value=(pgscount+1)/f.length*100;
    		wrt += tstrencode(f[pgscount]);
			if (pgscount>=f.length-1) {
				clearInterval(dow);
        		var limit = kblimit.value*1024;
				if (wrt.length>limit) {//达到一定大小自动下载
					r.innerHTML = "文件超出大小，已经自动帮您下载！";
					toobigtodownload(wrt,"big."+Date()+".txt");
				}else{
					r.innerHTML = "正在输出字符串到此框...很快将会完成...请耐心等待...";
					setTimeout(function(){
						r.innerHTML = wrt;
					},50);
				}
			}
    		pgscount++;
    	},6);
	}
	reader.readAsDataURL(f.files[0]);
}
function download(content, filename) {
	if (rf.files[0]) {
		var reader = new FileReader();
		reader.onload = function(evt){
			content = evt.target.result;
			downloader(content,filename);
		}
		reader.readAsText(rf.files[0], "UTF-8");
	}else{
		downloader(content,filename);
	}
}
function downloader(content, filename){
	var eleLink = document.createElement('a');
    eleLink.download = filename+"_["+window.location.host+"]_"+"."+fext.value;
    eleLink.style.display = 'none';
    content = content.replace(/[^\u0000-\u00FF]/g, function ($0) {return escape($0).replace(/(%u)(\w{4})/gi, "&#x$2;") });
    content = content.split(";");
    for (var i = 0; i < content.length; i++) {
    	content[i] = content[i]+";";
    }
    content = chunk(content,1024*5); //每组1024*n个字符 （影响速率）
    var wrt = "";
    var count = 0;
    progress.value="0";
    var dow = setInterval(function(){
    	progress.value=(count+1)/content.length*100;
    	wrt += tstrdecode(content[count]);
		if (count>=content.length-1) {
			clearInterval(dow);
    		var blob = wrt.substr(0,wrt.length-1);
            if (isvimg.checked) {
                aovimg.src = blob;
            }
            if (isvtext.checked) {
                aovtext.value=new Base64().decode(blob);
            }
            if (!isvimg.checked && !isvtext.checked) {
                blob = base642bin(blob);
                eleLink.href = URL.createObjectURL(blob);
                document.body.appendChild(eleLink);
                eleLink.click();
                document.body.removeChild(eleLink);
            }
		}
    	count++;
    },6);
}
function base642bin(urlData){
	try{
		var bytes=window.atob(urlData.split('base64')[1].replace(",",""));
	}catch(e){
		var bytes=window.atob("");
	}
    var ab = new ArrayBuffer(bytes.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < bytes.length; i++) {
        ia[i] = bytes.charCodeAt(i);
    }
    return new Blob([ab]);
}
  function Bin2Str(Bin) {
    var result = "";
    for (var i = 0; i < Bin.length; i += 8) {
      result += String.fromCharCode(parseInt(Bin.substr(i, 8), 2));
    }
    return result;
  }
function chunk(array, size) {
    const length = array.length;
    if (!length || !size || size < 1) {
        return [];
    }
    let index = 0;
    let resIndex = 0;
    let result = new Array(Math.ceil(length / size));
    while (index < length) {
        result[resIndex++] = array.slice(index, (index += size))
    }
    for (var i = 0; i < result.length; i++) {
    	result[i] = result[i].join("");
    }
    return result;
}
function tstrencode(str){
	for (var i = 0; i < _keyStr.length; i++) {
		if (Math.random()>llimit.value) {
			str = str.replace(new RegExp("\\("+_keyStr[i]+"\\)","g"), _ttStr[i]);
		}
	}
	str = str.replace(/\(,\)/g,"&#x0367;");
	return unescape(str.replace(/&#x/g,'%u').replace(/\\u/g,'%u').replace(/;/g,''));
}
function tstrdecode(str){
	for (var i = 0; i < _keyStr.length; i++) {
		str = str.replace(new RegExp(_ttStr[i],"g"), _keyStr[i]);
	}
	str = str.replace(/\(|\)/g,"").replace(/\\/g,"").replace(/&#x0367;/g,",");
	return str;
}
function toobigtodownload(content, filename){
	content = unescape(content.replace(/&#x/g,'%u').replace(/\\u/g,'%u').replace(/;/g,''));
	var eleLink = document.createElement('a');
    eleLink.download = filename;
    eleLink.style.display = 'none';
    var blob = new Blob([content]);
    eleLink.href = URL.createObjectURL(blob);
    document.body.appendChild(eleLink);
    eleLink.click();
    document.body.removeChild(eleLink);
}

function Base64() {
 _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
 this.decode = function (input) { 
  var output = ""; 
  var chr1, chr2, chr3; 
  var enc1, enc2, enc3, enc4; 
  var i = 0; 
  input = input.replace(/[^A-Za-z0-9\+\/\=]/g, ""); 
  while (i < input.length) { 
   enc1 = _keyStr.indexOf(input.charAt(i++)); 
   enc2 = _keyStr.indexOf(input.charAt(i++)); 
   enc3 = _keyStr.indexOf(input.charAt(i++)); 
   enc4 = _keyStr.indexOf(input.charAt(i++)); 
   chr1 = (enc1 << 2) | (enc2 >> 4); 
   chr2 = ((enc2 & 15) << 4) | (enc3 >> 2); 
   chr3 = ((enc3 & 3) << 6) | enc4; 
   output = output + String.fromCharCode(chr1); 
   if (enc3 != 64) { 
    output = output + String.fromCharCode(chr2); 
   } 
   if (enc4 != 64) { 
    output = output + String.fromCharCode(chr3); 
   } 
  } 
  output = _utf8_decode(output); 
  return output; 
 } 
 _utf8_decode = function (utftext) { 
  var string = ""; 
  var i = 0; 
  var c = c1 = c2 = 0; 
  while ( i < utftext.length ) { 
   c = utftext.charCodeAt(i); 
   if (c < 128) { 
    string += String.fromCharCode(c); 
    i++; 
   } else if((c > 191) && (c < 224)) { 
    c2 = utftext.charCodeAt(i+1); 
    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63)); 
    i += 2; 
   } else { 
    c2 = utftext.charCodeAt(i+1); 
    c3 = utftext.charCodeAt(i+2); 
    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63)); 
    i += 3; 
   } 
  } 
  return string; 
 } 
} 