<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
date_default_timezone_set('PRC');
$pwd = 'e10adc3949ba59abbe56e057f20f883e'; //在这里修改密码（md5）这里的默认密码是123456

if ($_POST['do']):
  (file_exists('./data/'))?:mkdir('./data/');
  $do = explode(' ', $_POST['do']);
  // add pwd 200815 1 2
  if ($do[0] == 'show'){
    // 特殊的，不一样 给定范围或者一个日期
    if (is_null($do[2])){
      $do[2] = $do[1];
      $do[3] = 'true';
    }
    checkNumber($do, [1, 2]);
    $MoneyDatas_ = scandir('./data/', 0); //默认升序
    unset($MoneyDatas_[0], $MoneyDatas_[1]);
    $beginYear = substr($do[1], 0, 2);
    $endYear = substr($do[2], 0, 2);
    $MoneyData = [];
    foreach ($MoneyDatas_ as $NO => $year) {
      if ($beginYear <= $year && $year <= $endYear){
        // 在范围内
        $tmpData = json_decode(file_get_contents('./data/'.$year), true);
        $MoneyData = array_merge($MoneyData, $tmpData);
      }
    }
    unset($beginYear, $endYear, $MoneyDatas_);
  }else{
    // 一定有要操作的日期
    $IDDate = $do[2];
    $IDYear = substr($IDDate, 0, 2);
    if (file_exists('./data/'.$IDYear)){
      $MoneyData = json_decode(file_get_contents('./data/'.$IDYear), true);
    }else{
      $MoneyData = [];
    }
  }

  switch ($do[0]) {
    case 'add':
      checkNumber($do, [2,3,4]); // 这几位必须是数字
      if (md5($do[1]) != $pwd){
        exit('err: Wrong Password!');
      }else{
        // 有无detial
        $out = add($IDDate, $do[3], $do[4], $do[5]?$do[5]:'');
        if (save($IDYear)){
          exit('msg: added! ');
        }else{
          exit('msg: failed to add!');
        }
      }
      break;
    case 'del':
      checkNumber($do, [2]);
      if (md5($do[1]) != $pwd){
        exit('err: Wrong Password!');
      }else{
        del($IDDate);
        if (save($IDYear)){
          exit('msg: OK!');
        }else{
          exit('msg: failed to del!');
        }
      }
      break;
    case 'show':
      // checkNumber($do, [1,2]); 上面已经单独检查过了
      // 这里添加一个 limit 用来限制详细输出的个数。
      // show 0 999999 true limit 30 // limit 默认为0
      if ($do[4] == 'limit'){
        checkNumber($do, [5]);
      }
      $out = show($do[1], $do[2], ($do[3] == 'true')?$do[3]:false, ($do[4] == 'limit')?$do[5]:0);
      exit('json'.json_encode($out));
      break;
    case 'help':
      exit('msg: 
Help:
add [pwd] [date] [income] [expense] [detail]
del [pwd] [date]
show [date]
show [beginDate] [endDate] [true/false]
show [beginDate] [endDate] true limit [limit]
专业   :)

Notice:
date format(1996/3/4): 960304
income & expense should be a number');
      break;
    case '专业':
      echo '<p style="font-size: 10px;line-height: 10px;">                                                                      ...........                                                                 .. 
                                                                    ,o\^o***,\/\*..                                    .                             
                                                                   .oo`.`.....[[`..                       ....                                       
                                          =o\.                    ..`,OOOOOOOOOOOOO`                     .....                                       
                                         ,]*,[O\.                 ,OOOOOOOOOOOOOOOOOO^..         .    .,/. .]\.                   ..                 
           ..*]..                            ...,`.,` .         .=OOOOOO[[....,OOOOOOO.            ,,..   ,OOOO\                 ....                
           ./OOOo\*.                    ./[    ......`.        .=OOOOO....... ..*OOOOO.           =..,. ]      ...              ..,]`                
           =O^,`...                    ..  ]OOo/]]`***^         =OOOO^.O]]]]]O\]]/OOO/.          ..*,,\/[    ]]]oo.           ../OOOo`.              
          ,`      ...,`                /^.[..   ...=O^^         ,OOO/ =OOOO/`.\OO[.O`            .OOOO`**.=@OOOOOO.          ,[[`   [\].             
             =^*]]*,/oOO`                 ..**..]`*.\^            \/^.... .]]/\`.,`..             .OO`,OOOOOOOOO]`          ...  =O^                 
           ]OOOO[[..\OOOO              =OOOOo[OOOOO\*\^            .`.*=o`.,]]]],O^.              ,`..,\oOOOO[OOO/..       ..]]/.   ]OOOO^           
         .   ..,]]` /OOOO.              ,oo^. .[...,*o.              .=oo^.]ooo]OO..              ,*...  ..*.  \[.        .OO`....,[[[[[..           
         .]`]/OOOOO`..*/^,.             .]^[[[[[..]o^                .=OOOo*.,/OO/                 .*ooo]]/\]O]oo.         =OO\]OOOOOOOOOO`        ..
.        .\OO/,OOo*..*ooo.              .\oOo`*=[\oo^               ] .\Oo[**[*[^.                 .*ooOO`...*]*.         .o....,oooO` \O/        ,]]
^.         ,/   ...**^=oo.                ,OOO[ooooO^             /OO     ,\O@O/ ,@\.              ..\ooooo]]]..           .,oo^...*/OO\]].      =O^ 
O..        .`,[`  ../oOOO^                 ,OOOOOO`  =O`     .]OOOOOO.     ,`    .O@@@@\]        ,OO`  .[oooO^.             .ooo\...,]]`         =/ .
\*.          .//`.,/oOOO^ .`             ,^  .*/    =OOOO]]OOOOOOOOOO^    O^/O    OOO@@OOOOO\` .OOOOOO     ./..              ,/\oo^]]]]].       .,^ /
o`.           .[O/[\OO/  ,O@`        ,/OOO   ].    =OOOOOOOOOOOOOOOOOO    .*O.    OOOOOOOOOOOOOOOOOOOOO\    O\=\.          /\  ,\oo\*]o`         ..=]
o^.            ..,[//  ,OOOOO\`   ]OOOOOO`  \oO^  =OOOOOOOOOOOOOOOOOOO^   *=O\    OOOOO@OOOOOOOO@OOOOOOOO^ [\oO,O@]     ./OOOO^   .\O\.           .,o
oo`.           ,\o.   /OOOOOOOO@OOOOOOOO/  ,\O^  =OOOOOOOOOOOOOOOOOOOOO  .oOOO    /OOOOOOOOOOOOO@OOOOOOOOOO` \O`.\O@\ /OOOOOOOOO`   O^@`           .,
OO/..        /OOoO/ =OOOOOOOOOOOOOOOOOOO. =ooO` /OOOOOOOOOOOOOOOOOOOOOO\ ,ooOO^   OOOOOOOOOOOOOO@OOOOOOOOOOO^.OO` ,OOOOOOOOOOOOOO\`,\O^[@O`       ]`.
O^ =O\      OOOooO.OOOOOOOOOOOOOOOOOOOOO ,o\=O =OOOOOOOOOOOOOOOOOOOOOOOO^,o/oo^   OOOOOOOO@@OOOOOOOOOOOOOOOOO\,OO` ,@OOOOOOOOOOOOOO^ OO .OO@`    =OOO
.,OOOOO`   ,OOOoOOOOOOOOOOOOOOOOOOOOOOO^ ooo\^=OOOOOOOOOOOOOOOOOOOOOOOOOO/\/oo^  =OO@\]]]/O@@OOOO@O@@OOOOOOOOOOOOO  =OOOOOOOOOOOOOOO\,O\  \OO  /OOOOO
/OOOOOOOO\./OOOOOOOOOOOOOOOOOOOOOOOOOOO^,ooo/,OOOOOO@@OOOOOOOOOOOOOOOOOOOO=,/o^  OOOO@O@OO@OOOOO@O@@@@@OOOOOOOOOOOO  OOOOOOOOOOOOOOOOO/OO  \OOOOOOOOO
OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO/oooOOOOO]@OOOOOOOOOOOOOOOOOOOOOOOO`=*^ =O@@OOOO@@@@OO@@OOO@@OOO@OOOOOOOOOO^ =OOOOOOOOOO`./OOOOOOO .OOOOOOOOO
OOOOOOOOOOOOOOOOO]]]]]]/OOOOOOOOOOOOOOOOOOoOOOOOOOOOOOO@@@OOOOOOOOOOOOOOOOO\^o^ OOO@OOOO@@@@OO@@OOOOOO@O@@OOOOOOOOOO =OOOOOOO/`./OOOOOOOOO^ =OOOOOOOO
OOOOOOOOOOOOOOOOOOOOOOOO..\OOOOOOOOOOOOOOOOOOOOOO@@@OO@@@@OOOOOOOOOOOOOOOOOO\o`=OO@OOOOO@@@@OO@@@@@@OOOOOOOO@@@@OOOO^OOOOO/`...=OOOOOOOOOOO OOOOOOOOO
OOOOOOOOOOOOOOOOOOOOOOOOOO\..,\OOOOOOOOOOOOOOOO@OOOOO@@@@@@OOOOOOOOOOOOOOOOOOo.OO@OOOOOO@@@@O@OOOOOOOOOOOOO@OOOOOOOOO@O@O`.......,*\OOOOOOO`OOOOOOOOO
OOOOOOOOOO@OOOOOOOOOOOOOOOO`   . ,OOOOOOOOO@@@@O@@@@@@@@@@@@OOOOOOOOOOOOOOOOOOOO@@OOOOO@OOOO@@OOOOOOOOOOOOOOOOO@@@@@OOOO  ......,`*OOOOOOO@oOOOOOOOOO
]/OOOOOOOOOOOOOOOOOOOO].]\`......  \@@@@@@@@@@@@@@@@@@@@@O@@OOOOOOOOOOOOOOOOOO@@OOOOOOOOOOOO@OOOO@@@OOOOOOOOOOOO@@@@OOOO^ ....*]OOOOOOOOOOOOOOOOOOOO@
OOOOOOOOOO@OOOOOOOOOOOOO\.[]`.....  O@@@@@@@@@@@@@@@@@@@OO@@OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO@@OOOO@@OOOOOOOOOOOOO@@@@@OOO\..=OOOOOOOOOOOOOOOOO@OOOOO@@
OOOOOOOOOOOOOOOOOOOOOOOOO@@O\`,.., O@@@@@@@@@@@@@@@OOOO@@@@@OOOOOOOOOOOOOOOOOOOOOOOOOOOOOO@@OOOOO@@OOOOOOOOOOOOO@@@@@OOOO@@@@OOOOOOOOOOOOOO@@@OOOOO@@
OOOOOOOOOOOOOOOOOOOOOOO@@@@@OOOO@/O@@O@@O@OOOOOOOOOOOOO@@@@@OOOOOOOOOOOOOOOOOOOOOOOOOOOOO@@@@OOO@@@OOOOOOOOOOOO@O@@@@OOOO@@@@O@OOOOOOOOOOOOO@OOOOOO@@
OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO   /OOOOOOOOOOOOOOOOOO@@@O   /O^   OOOOOOOOOOOO[[[[[[[[[[[[[[[[[[[[[[[OOOOOOOOO@@@@@OOO@@    OOOOOOOOOOOOO@@OOOOOOO@
OOOO@OOOOOOOOOOOOOOOOOOOOOOOOOOO^  =@OOOOOOOOOOOOOOOOOO@@@@  =OOO  =OOOOOOOOOOOO                      =OOOO^         ,OOO@   =OOOOOOOOOOO@OO@OOOOOOO@
OOOO@OOOOOOOOOOOOOOOOOOOO                    =OOOOOOOO@OOO@  =OOO  =OOOO\OOOOOO@  .@OOO@@@@@   /OO@O  =OOOO^  OOOO   O@@OO   OOOOOOOOOOOOOOOO@OOOOOO@
O@@@@OOOOOOOOOOOOOOOOOOOO@@@O@@^  =@@@@@@OOOOOOOOOO^  =@@@@  =OOO  =OOO^   /OOO@  .@O@@@@@@@` =@@@@O  =@OOO^  OOO^  O@@OO@   OOOOOOOOOOOOOOO@@OOOOO@@
@@OOOOOOOOOOOOOOOOOOOO/OOOOOOOO  ,OOOOOOOOOOOO/\OOO@   OOO@  =OOO  =@OO   OOOOO@  .@^             =O  =@OOO^  OO/  =OO@OO@   OOOOOOOOOOOOOO@@@OOOOOO@
OOOOOOOOOOOOOOOOOOOOO@`                         OOOOO   @O@  =OOO  =@O^  =OOOOO@  .@O@OO@O    =OO@OO  =@OOO^  OO`  \O@@@OO   OOOOOOOOOOOOO@@@@@OOOOOO
OOOOO@OOOOOOOOOOOOOOOOO@O@@OO/  =OOOOO@O.@OOOOOOOOOOO^  =@@  =OOO  =@O   OOOOO/O  .@@@O@/  /^ =@OOOO  =@OOO^  OOO\  ,@@@@^   =@OOOOOOOOOOO@@@@@OOOOO@
OOOOO@O@OOOOOOOOOOOOOOOO@@@@/   [[[[[[`` ,``,OOOOOOOO@`  =@  =OOO  =@`  /O@OOOOO  .@@O/  ,O@^ =@OOOO  =@OOO^  OOO@O  =@@@     \OOOO@OOO@@@@O@/\OOOOOO
OOOO@@O//@OOOOOOOOOOOOOO@@@OO  ,]]]]]]]     ,OOOOOOOOO^ ,]@  =OOO  =\]`=OOOOOO\@  ,/`   /O@@^ =@OOOO  =@OOO^  OOOO@   ^=^  =^  OOOOOOOO@@@@O@OOOOOOOO
@OO@@@O^=@OOOOOOOOOOOOOO@@O@O[    OO/   ,OOOOOOOOO@OOOO@@@@  =OOO  =@OOOOOOOOO@@  .@\]/@@`    =@OOOO  =@OOO^  O/[[   =O`  =@@   \@@O@@@O@@O@@O`OOOOOO
OOO@@OOO/@OOOOOOOOOOOOOO@@OOOOO....*  /@@@@OOOOOOO@OOO@@@@@  =OOO  =OOOOOOO@@O@@  .@@@@@@O]]O@OOOOOO  =@OOO^  O@^ OO\*..........\OOO@@@@@@O@@O\@OOOOO
O@@@@OOO@OOOOOOOOOOOOOOOO@O@O^....*=   [O@O@@OOOOO^                         =O@O                      =@@O@^  OO@@OOO\*..........=OO@@@@@@@@OOO@@OOOO
O@@@OOOOO@@@OOOOOOOOOO@OO[`......./O\`    /@@OOOOOO]]]]]OO`/OOOOOOOOOOOO]]]]/@@O  .@OOOOOOOOO@O.....  .OOO@^  OOO@@OOOO\*.....**=OO@@@@@@@@@@@OOOOOOO
OO@@OOOOO@@@OO@@OOOOO@O......*oO`OO@OO@@\@@O@@OO@@OOOOOO@^..=@OOOOOOOOOOOOOO@@@@@@@@@@@@@@@@@@@^......`=@O@OOO@@@@@@OOOOO*.*=.\=OO@@@@@@@@@@@OOOOO@@O
O@@@OOOOO@@@@@@@@@@@O@^....*..O@@@@@@@@@@@@@@@@@@@OOOOOOO.=^=OO@@@@@@@@@O@@@@@@@@@@@@@@@@@@@@@@O..****^=@@O@@@@@@@@@@^=@@O`**^=@@@@@@@@@@@@@@OOOO@O@O</p>';
      break;
    default:
      echo "msg: Have fun. Server Time: ".date('H:i:s');
      break;
  }
  exit;
else:
?>
<!DOCTYPE html>
<html onclick="$('command').focus()">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>The account book</title>
<style type="text/css">
*{margin: 0;padding: 0;border: none;font-family: inherit;}
html{background-color: black;padding: 15px;user-select:none;}
body{word-break: break-all;color: white;font-family: SimHei,monospace;font-size: 18px;line-height: 18px;}
p{margin-bottom: 5px;}
pre{white-space: pre-wrap;}
.hidden{
  position: fixed;
  bottom: -999px;
  height: 1px;
}
table{
  text-align: left;
}
th{
  padding-left: 0.8em;
}
#cursor{
  display: inline-block;
  width: 0.4em;
  height: 1em;
  background: white;
  vertical-align: text-bottom;
  margin-left: 2px;
  animation: blink 1.2s infinite steps(1, start);
}
#output{
  white-space: pre-wrap;
}
@keyframes blink {
  0%, 100% {background-color: white;}
  50%{background-color: transparent;} 
}
::-webkit-scrollbar {
  width: 10px;
}
::-webkit-scrollbar-thumb{
  border-radius: 10px;
  background-color: rgba(255,255,255,0.7)
}
::-webkit-scrollbar-track {
  border-radius: 0;
  background: rgba(255,255,255,0.1);
}
</style>
</head>
<body id="body">

<div id="output">Welcome. --My account book</div>

<pre>
root@user:~# <span id="showPrint"></span><span id="cursor"></span>
</pre>

<input id='command' class="hidden" type="input" AUTOCOMPLETE="off" />

<script type="text/javascript">
$("command").dom.addEventListener('keydown', function(event){
  event = event || window.event;
  if (event.keyCode == 13){
    sendDo();
  }
});
$("command").dom.addEventListener('keyup', function(event){
  event = event || window.event;
  if(event.keyCode == 40){
    $('command').val('').focus().val(commandRec.last());
  }else if(event.keyCode == 38){
    $('command').val('').focus().val(commandRec.next());
  }else if (event.keyCode == 13){
    commandRec.index = -1;
  }else{
    commandRec.input = $('command').val();
  }
});
setInterval(function(){
    $('showPrint').html($('command').val());
}, 65);
function sendDo(){
  var command = $('command').val();
  if (command == ''){
    $('output').dom.innerHTML += '<p>root@user:~# </p>';
    scroll(0, document.body.scrollHeight);
    return '';
  }
  commandRec.history.unshift(command);
  $('cursor').dom.style = 'animation: none;';
  Ajax.post('<?php echo $_SERVER['REQUEST_URI']; ?>', 'do='+encodeURIComponent(command), function(res){
    // 命令移动上去
    if (in_array(command.split(' ')[0], ['add', 'del'])){
      var command_ = command.split(' ');
      var output_ = '<p>root@user:~# ';
      for (var i = 0; i < command_.length; i++) {
        if (i != 1){
          output_ += command_[i]+' ';
        }else{
          output_ += '*'+' ';
        }
      }
      $('output').append(output_+'</p>');
    }else{
      $('output').append('<p>root@user:~# '+command+'</p>');
    }
    // 判断是否json
    if(res.substring(0, 4) == 'json'){
      res = res.substring(4);
      // 正规数据
      var data = JSON.parse(res);
      //console.log(res);
      var output_ = '<p>'+CO.g('INCOME: '+data[0]+'￥')+' '+CO.r('EXPENSE: '+data[1]+'￥')+' SUM: '+(function(){
        let tmp_sum = data[0]-data[1];
        if (tmp_sum > 0){
          return CO.g(tmp_sum+'￥');
        }else{
          return CO.r(tmp_sum+'￥');
        }
      })()+'</p>';
      if (typeof(data[2]) == 'object'){
        output_ += '<table>';
        for (let key in data[2]){
          output_ += '<tr>';
          output_ += '<th>DATE: '+key.substring(1, 3)+'/'+key.substring(3, 5)+'/'+key.substring(5)+'</th><th>'+CO.g('INCOME: '+data[2][key][0]+'￥')+'</th><th>'+CO.r('EXPENSE: '+data[2][key][1]+'￥')+'</th><th>'+data[2][key][2]+''+'</th>';
          output_ += '</tr>';
        }
        output_ += '</table>';
      }
      $('output').append(output_);
    }else{
      // 普通类信息
      res = res.replace('err: ', CO.r('err: ')).replace('msg: ', CO.g('msg: '))
      $('output').append('<p>'+res+'</p>');
    }
    $('command').val('');$('showPrint').html('');
    $('cursor').dom.style = '';
    // 自动滚动最底下
    scroll(0, document.body.scrollHeight);
  });
}
var CO = {
  g: function(text){
    return this.output(text, '#00a800');
  },
  r: function(text){
    return this.output(text, '#ff3131');
  },
  output: function(text, color){
    return '<span style="font-weight: bold;color: '+color+'">'+text+'</span>';
  }
}
var Ajax = {
  get: function(url, fn) {
    // XMLHttpRequest对象用于在后台与服务器交换数据   
    var xhr = new XMLHttpRequest();            
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function() {
      // readyState == 4说明请求已完成
      if (xhr.readyState == 4 && xhr.status == 200 || xhr.status == 304) { 
        // 从服务器获得数据 
        fn.call(this, xhr.responseText);  
      }
    };
    xhr.send();
  },
  // data应为'a=a1&b=b1'这种字符串格式，在jq里如果data为对象会自动将对象转成这种字符串格式
  post: function (url, data, fn) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, false);
    // 添加http头，发送信息至服务器时内容编码类型
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");  
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
        fn.call(this, xhr.responseText);
      }
    };
    xhr.send(data);
  }
}
function in_array(search, array){
  for(var i in array){
    if(array[i] == search){
      return true;
    }
  }
  return false;
}

commandRec = {
  index: -1,
  input: '',
  history: [],
  next: function(){
    if (commandRec.index < commandRec.history.length-1){
      commandRec.index++;
    }
    return commandRec.history[commandRec.index];
  },
  last: function(){
    if (commandRec.index >= 0){
      commandRec.index--;
    }
    if (commandRec.index < 0){
      return commandRec.input;
    }
    return commandRec.history[commandRec.index];
  }
}

function $(id){
  // 原生操作必加.dom.
  return {
    dom: document.getElementById(id),
    html: function(v){
      if (v === undefined){
        return $(id).dom.innerHTML;
      }else{
        $(id).dom.innerHTML = v;
        return $(id);
      }
    },
    val: function(v){
      if (v === undefined){
        return $(id).dom.value;
      }else{
        $(id).dom.value = v;
        return $(id);
      }
    },
    focus: function(){
      $(id).dom.focus();
      return $(id);
    },
    append: function(h){
      $(id).dom.innerHTML += h;
      return $(id);
    }
  }
}
function PrefixInteger(num, n) {
  return (Array(n).join(0) + num).slice(-n);
}

// automatically get 30days data
$('command').val('show 0 900000 true limit 30');
sendDo();

</script>

</body>
</html>
<?php
endif;

function add($date, $inSum, $outSum, $detail){
  global $MoneyData;
  $MoneyData['y'.$date] = [$inSum, $outSum, $detail];
  return [$date, $MoneyData['y'.$date]];
}

function del($date){
  global $MoneyData;
  unset($MoneyData['y'.$date]);
}

function show($begin, $end, $detail=false, $limit=0){
  global $MoneyData; // 需要排序一下
  $in = 0;
  $out = 0;
  $showDetail = [];
  // 读取后面的 从第几个开始读取 可读够limit个
  $limit_start_key = count($MoneyData) - $limit;
  $index = 0; // 记录读取多少个了
  if ($detail && $limit != 0 && $limit_start_key > 0){
    // 有的没输出 用...提醒用户
    $showDetail['y......'] = ['...', '...', '(auto cut)'];
  }
  foreach ($MoneyData as $date => $value) {
    if ($begin <= substr($date, 1) && substr($date, 1) <= $end){
      // 日期在范围内
      $in += $value[0];
      $out += $value[1];
      if ($detail){
        // 显示细节
        if ($limit == 0 || $index >= $limit_start_key){
          // 可输出详细 ('0' == 0)
          $showDetail[$date] = $value;
        }
      }
    }
    $index++;
  }
  if ($detail){
    return [$in, $out, $showDetail];
  }else{
    return [$in, $out];
  }
}

function save($name){
  //  name -- date('y')
  global $MoneyData;
  // 要排个序先，方便输出的时候显示是按照日期排序的
  ksort($MoneyData);
  return file_put_contents("./data/".$name, json_encode($MoneyData));
}

function checkNumber($arr, $limit){
  for ($i = 0; $i < count($limit); $i++) { 
    if (!is_numeric($arr[$limit[$i]])){
      exit('err: syntax error');
    }
  }
}