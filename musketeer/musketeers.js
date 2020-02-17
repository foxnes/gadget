/*
Author: FoXnEs(luuljh)
UTF-8
How to use:

// 注意：颜色都应为RGB式，否则请用下面的函数mm.colorRgb把16进制颜色转为RGB

__## mini挂件 (简单) ##__:

var mm = new musketeers();
mm.justWidget('容器ID','canvasID', 火枪手最少量, 火枪手最多量);
最少量, 最多量按照容器大小来填
会自动获取"容器ID"的高度和宽度并设置为canvas的高度和宽度。
如果报错，可能是你写错了ID，请检查ID是否存在。
当容器长宽超过200时，有一定可能会自动开启据点战争（即填充更多火枪手）

__## 自定义  ##__

var mm = new musketeers();
mm.init(容器ID, canvasID);  // canvas 将会自动充满该容器
mm.fps = 30;  //  设置帧数  默认为30帧
mm.warOverCalling = function(){
  // 战斗结束时执行
}
mm.mainCalling = function(){
  // 每一帧画面执行
}
mm.clickCalling = function(){
  // 点击canvas时执行
}
mm.startCalling = function(){
  // 游戏开始时执行
}

mm.start()  // 战斗开始  一定要有NPC才有用
mm.cc.width   //获取canvas宽度
mm.cc.height  //获取canvas高度
mm.clearNPC()   //清空所有NPC
mm.addNPC(颜色, {x: 0, y: 0})  //添加NPC  
mm.warStart = true;  //开始战斗
mm.colorRgb("#000")  // 返回 RGB 0,0,0 (没有括号)
mm.changeTeam(colorRgb("#000000"))  // 颜色转化 16进制颜色转为RGB
mm.changeTeam('0,0,0')  // 更换颜色  鼠标放置的颜色，同颜色表示自己人
mm.rand(5, 100)  // 生成5 ~ 100随机数

 */

function musketeers(){
  var meMusk = this;
  meMusk.warOverCalling = function(){void 0}
  meMusk.mainCalling = function(){void 0}
  meMusk.clickCalling = function(){void 0}
  meMusk.startCalling = function(){void 0}
  meMusk.fps = 30;
  meMusk.diedpic = 'data:img/jpg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/4QAiRXhpZgAATU0AKgAAAAgAAQESAAMAAAABAAEAAAAAAAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAAQABADASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD6g0LT18N/tC+JNR+NHxv+IXwf+K3/AAsNfAo1rStZhuLPxjJqVn59hptjC0UsNrZpbSWksaTW/mwztITMWkZ3q/GHS11b4tK3wd+PPxC+Lnxv0Px1afD2wvNY1yC2g+HupQ2l7cyW18kcMMFzYzrBcG6RbeWadreNFcPEjRtX4p68P2l/HkPxE+Dvjj4qfFjxdqSW2peHdA0S0s28B2mlRao2m6/pVw6GOd2XUoraK/kuI7hjwqobcwDF8S+MNd8K/FvwVa/C34PfEz4WfFzwLMqeENG8S6Ta6nL8VpL6ytrW41XWpUG1DbR6e1tPqP2trmGK7LHJuUicpNO1/K34f8G9u/kxVL628/1/4Fr9vNH/2Q==';
  meMusk.init = function(pbox, cc) {
    meMusk.cc = document.getElementById(cc);
    if (meMusk.cc.getContext) {
      meMusk.ctx = meMusk.cc.getContext('2d');
    } else {
      return false;
    }
    meMusk.cc.width = document.getElementById(pbox).offsetWidth;
    meMusk.cc.height = document.getElementById(pbox).offsetHeight;
    meMusk.NPC = [];
    meMusk.warStart = false;
    meMusk.bullet = [];
    meMusk.teams = {
      teamarr: []
    };
    meMusk.team = '0,0,0';
    meMusk.cc.addEventListener('click', function(e) {
      meMusk.clickCalling();
      let pS = meMusk.getPosition(e);
      meMusk.addNPC(meMusk.team, pS);
    });
    let diedpic = new Image();
    diedpic.src = meMusk.diedpic;
    meMusk.diedpic = diedpic;
  }
  meMusk.getPosition = function(ev) {
    var x, y;
    if (ev.layerX || ev.layerX == 0) {
      x = ev.layerX;
      y = ev.layerY;
    } else if (ev.offsetX || ev.offsetX == 0) {
      x = ev.offsetX;
      y = ev.offsetY;
    }
    return {
      x: x - meMusk.cc.offsetLeft,
      y: y - meMusk.cc.offsetTop
    };
  }
  meMusk.changeTeam = function(color) {
    meMusk.team = color;
  }
  meMusk.addNPC = function(team, pS) {
    meMusk.NPC.push({
      team: team,
      position: pS,
      hp: 10,
      lastB: 0
    });
    meMusk.teams = {
      teamarr: []
    };
    for (var j = 0; j < meMusk.NPC.length; j++) {
      if (meMusk.teams.hasOwnProperty(meMusk.NPC[j].team)) {
        meMusk.teams[meMusk.NPC[j].team].push(meMusk.NPC[j]);
      } else {
        meMusk.teams[meMusk.NPC[j].team] = [meMusk.NPC[j]];
        meMusk.teams.teamarr.push(meMusk.NPC[j].team);
      }
    }
    if (!meMusk.warStart){
      meMusk.render();
    }
  }
  meMusk.clearNPC = function(){
    meMusk.teams = {teamarr:[]};
    meMusk.NPC = [];
    meMusk.render();
  }
  meMusk.colorRgb = function(sColor) {
    sColor = sColor.toLowerCase();
    var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
    if (sColor && reg.test(sColor)) {
      if (sColor.length === 4) {
        var sColorNew = "#";
        for (var i = 1; i < 4; i += 1) {
          sColorNew += sColor.slice(i, i + 1).concat(sColor.slice(i, i + 1));
        }
        sColor = sColorNew;
      }
      var sColorChange = [];
      for (var i = 1; i < 7; i += 2) {
        sColorChange.push(parseInt("0x" + sColor.slice(i, i + 2)));
      }
      return sColorChange.join(",");
    }
    return sColor;
  }
  meMusk.rand = function(min,max){
    return parseInt(Math.random()*(max-min+1)+min,10);
  }
  meMusk.arrRandBut = function(arr, but) {
    var jrr = [];
    arr.forEach(function(item) {
      if (item != but) {
        meMusk.teams[item].forEach(function(an) {
          if (an.hp > 0) {
            jrr.push(an.position);
          }
        });
      }
    });
    if (jrr.length == 0) {
      return false;
    } else {
      let tmp = jrr[Math.floor(Math.random() * jrr.length)];
      return {x: tmp.x, y: tmp.y}; // 这样写 避免指向同一个内存地址
    }
  }
  meMusk.gameOver = function() {
    meMusk.bullet = [];
    meMusk.warOverCalling();
    meMusk.warStart = false;
  }
  meMusk.start = function(){
    if (!meMusk.warStart){
      meMusk.startCalling();
      meMusk.warStart = true;
      meMusk.main();
    }
  }
  meMusk.render = function() {
    if (meMusk.warStart && meMusk.NPC.length == 0){
      meMusk.gameOver();
    }
    meMusk.ctx.clearRect(0, 0, meMusk.cc.width, meMusk.cc.height);
    meMusk.mainCalling();
    var hitMe = function(l){
      meMusk.NPC[i].hp -= l;
      if (meMusk.NPC[i].hp <= 0){
         meMusk.NPC[i].lastB = new Date().getTime();
      }
      meMusk.bullet.splice(q, 1);
      q--;
      meMusk.NPC[i].position.x -= sx / (skrx + skry) * 6;
      meMusk.NPC[i].position.y -= sy / (skrx + skry) * 6;
      // 击退效果
    }
    for (var i = 0; i < meMusk.NPC.length; i++) {
      // 中弹判断
      if (isNaN(meMusk.NPC[i].position.x) || isNaN(meMusk.NPC[i].position.y)){
        // 莫名BUG？？
        meMusk.NPC.splice(i, 1);
        i = i-1 <= 0 ? 0 : i-1;
        continue;
      }
      for (var q = 0; q < meMusk.bullet.length; q++) {
        if (meMusk.bullet[q].color != meMusk.NPC[i].team){
          var sx = meMusk.bullet[q].position.x - meMusk.NPC[i].position.x,
          sy = meMusk.bullet[q].position.y - meMusk.NPC[i].position.y;
          var skrx = Math.round(Math.abs(sx)),
          skry = Math.round(Math.abs(sy));
          if (skrx <= 3 && skry <= 3) {
            hitMe(6);
          } else if (skrx <= 4 && skry <= 4) {
            hitMe(3);
          } else if (skrx <= 6 && skry <= 6) {
            hitMe(2);
          } else if (skrx <= 7 && skry <= 7){
            hitMe(1);
          }
          if (q<0){q=0}
        }
      }
      if (meMusk.NPC[i].hp <= 0) {
        var timetofade = new Date().getTime() - meMusk.NPC[i].lastB;
        timetofade = -1/1250*timetofade+1;  // >1250ms 自动GG
        if (timetofade <= 0){
          meMusk.NPC.splice(i, 1);
          i = i-1 <= 0 ? 0 : i-1;
        }else{
          meMusk.ctx.globalAlpha = timetofade;
          meMusk.ctx.strokeStyle = 'rgb('+meMusk.NPC[i].team+')';
          meMusk.ctx.drawImage(meMusk.diedpic, meMusk.NPC[i].position.x-6, meMusk.NPC[i].position.y-6, 12, 12);
          meMusk.ctx.beginPath();
          meMusk.ctx.arc(meMusk.NPC[i].position.x, meMusk.NPC[i].position.y, 7.5, 0, Math.PI * 2);
          meMusk.ctx.closePath();
          meMusk.ctx.stroke();
          meMusk.ctx.globalAlpha = 1;
        }
        //死亡
        continue;
      }
      //自动避弹 愚蠢。。 hp少了动得慢
      if (meMusk.warStart && Math.random() > 0.45) {
        meMusk.NPC[i].position.x += meMusk.NPC[i].hp / 10 * meMusk.rand(0, 3) * (Math.random() > 0.5 ? -1 : 1);
        meMusk.NPC[i].position.y += meMusk.NPC[i].hp / 10 * meMusk.rand(0, 3) * (Math.random() > 0.5 ? -1 : 1);
        if (meMusk.NPC[i].position.x < 4) {
          meMusk.NPC[i].position.x = 4;
        }
        if (meMusk.NPC[i].position.y < 4) {
          meMusk.NPC[i].position.y = 4;
        }
        if (meMusk.NPC[i].position.x > meMusk.cc.width - 4) {
          meMusk.NPC[i].position.x = meMusk.cc.width - 4;
        }
        if (meMusk.NPC[i].position.y > meMusk.cc.height - 4) {
          meMusk.NPC[i].position.y = meMusk.cc.height - 4;
        }
      }
      // 射击啦
      if (meMusk.warStart && new Date().getTime() - meMusk.NPC[i].lastB > 2000) {
        // 堵枪
        if (Math.random() < 0.45) {
          meMusk.NPC[i].lastB += meMusk.NPC[i].hp * meMusk.rand(50, 200); //+x ms射击延时  血越少越短
        } else {
          // 正常开枪 获取不同队信息
          var anPs = meMusk.arrRandBut(meMusk.teams.teamarr, meMusk.NPC[i].team);
          if (anPs !== false) {
            // 找到了攻击目标
            // 目标偏离  简称手抖
            // 这里本来有BUG   anPs.x / .y 与NPC的坐标"关联"了，花了我一早上去排查。。一定要小心啊！！
            // 已经在arrRandBut修复好了
            anPs.x += (11 - meMusk.NPC[i].hp) * meMusk.rand(0, 3) * (Math.random() > 0.5 ? -1 : 1);
            anPs.y += (11 - meMusk.NPC[i].hp) * meMusk.rand(0, 3) * (Math.random() > 0.5 ? -1 : 1);
            var addx = anPs.x - meMusk.NPC[i].position.x;
            var addy = anPs.y - meMusk.NPC[i].position.y;
            let mod = Math.round(Math.pow(Math.pow(addx, 2) + Math.pow(addy, 2), 0.5));
            addx = addx / mod;
            addy = addy / mod;
            meMusk.bullet.push({
              color: meMusk.NPC[i].team,
              position: {
                x: meMusk.NPC[i].position.x,
                y: meMusk.NPC[i].position.y
              },
              addx: addx,
              addy: addy
            });
          }else{
            // 找不到目标了
            meMusk.gameOver();
          }
          if (meMusk.NPC[i].hp / 10 * Math.random() < 0.08 || (meMusk.NPC[i].hp <= 3 && Math.random() < 0.5)){
            meMusk.NPC[i].lastB = 0;  // 狂暴模式 连发
          }else{
            meMusk.NPC[i].lastB = new Date().getTime();
          }
        }
      }
      // 射击结束
      // 画图
      let hprate = meMusk.NPC[i].hp / 10;
      meMusk.ctx.fillStyle = 'rgba(' + meMusk.NPC[i].team + ',0.95)';
      meMusk.ctx.beginPath();
      meMusk.ctx.moveTo(meMusk.NPC[i].position.x, meMusk.NPC[i].position.y);
      meMusk.ctx.arc(meMusk.NPC[i].position.x, meMusk.NPC[i].position.y, 5, 0, Math.PI * 2 * hprate);
      meMusk.ctx.closePath();
      meMusk.ctx.fill();
      meMusk.ctx.fillStyle = 'rgba(' + meMusk.NPC[i].team + ',' + hprate + ')';
      meMusk.ctx.beginPath();
      meMusk.ctx.moveTo(meMusk.NPC[i].position.x, meMusk.NPC[i].position.y);
      meMusk.ctx.arc(meMusk.NPC[i].position.x, meMusk.NPC[i].position.y, 5, Math.PI * 2 * hprate, Math.PI * 2);
      meMusk.ctx.closePath();
      meMusk.ctx.fill();
    }
    if (meMusk.warStart) {
      meMusk.ctx.lineWidth = 2;
      for (var i = 0; i < meMusk.bullet.length; i++) {
        //子弹动画
        if (meMusk.bullet[i].position.x < 0 || meMusk.bullet[i].position.x > meMusk.cc.width || meMusk.bullet[i].position.y < 0 || meMusk.bullet[i].position.y > meMusk.cc.height) {
          meMusk.bullet.splice(i, 1);
          i--;
          if (i < 0) {
            i = 0;
          }
          continue;
        }
        meMusk.ctx.strokeStyle = 'rgb(' + meMusk.bullet[i].color + ')';
        meMusk.ctx.beginPath();
        meMusk.ctx.moveTo(meMusk.bullet[i].position.x, meMusk.bullet[i].position.y);
        meMusk.bullet[i].position.x += meMusk.bullet[i].addx * 12;
        meMusk.bullet[i].position.y += meMusk.bullet[i].addy * 12;
        meMusk.ctx.lineTo(meMusk.bullet[i].position.x, meMusk.bullet[i].position.y);
        meMusk.ctx.closePath();
        meMusk.ctx.stroke();
      }
    }
  }
  meMusk.main = function() {
    meMusk.render();
    if (meMusk.warStart) {
      setTimeout(function() {
        meMusk.main();
      },
      1000/meMusk.fps);
    }
  }
  meMusk.justWidget = function(paID, caID, min, max){
    var Widget = new musketeers();
    Widget.count = 1; //  自动换颜色
    Widget.init(paID, caID);
    Widget.record = {};  // 储存记录
    var randTeam = function(){
      return ['0,0,0', '176,115,97', '255,0,0', '255,128,64', '0,128,0', '138,27,228', '45,90,210'][Widget.rand(0, 6)];
    }
    var addNStart = function(){
      let sum = Widget.rand(min, max);
      for (var i = 0; i < sum; i++) {
        setTimeout(function(){
          Widget.addNPC(randTeam(), {
            x: Widget.rand(0, Widget.cc.width),
            y: Widget.rand(0, Widget.cc.height)
          });
        }, 800/sum*i);
      }
      if (Widget.cc.width >= 200 && Widget.cc.height >= 200 && Math.random() < 0.35){
        // 随机据点战争
        var rt = randTeam();
        var tmpx = Widget.rand(0, Widget.cc.width);
        var tmpy = Widget.rand(0, Widget.cc.height);
        for (var i = 0; i < min; i++) {
          setTimeout(function(){
            Widget.addNPC(rt, {
              x: tmpx + Widget.rand(2, 8) * (Math.random() > 0.5 ? 1 : -1),
              y: tmpy + Widget.rand(2, 8) * (Math.random() > 0.5 ? 1 : -1)
            });
          }, 500/min*i);
        }
        var rt_ = randTeam();
        var tmpx_ = Widget.rand(0, Widget.cc.width);
        var tmpy_ = Widget.rand(0, Widget.cc.height);
        for (var i = 0; i < max; i++) {
          setTimeout(function(){
            Widget.addNPC(rt_, {
              x: tmpx_ + Widget.rand(2, 8) * (Math.random() > 0.5 ? 1 : -1),
              y: tmpy_ + Widget.rand(2, 8) * (Math.random() > 0.5 ? 1 : -1)
            });
          }, 800/max*i);
        }
      }
      setTimeout(function(){
        // 上面timer动态添加
        Widget.start();
      }, 1080);
    }
    Widget.clickCalling = function(){
      // 点击canvas时执行
      Widget.start();
      if (++Widget.count > Math.ceil(max/min)){
        Widget.count = 0;
        Widget.changeTeam(randTeam());
      }
    }
    Widget.warOverCalling = function(){
      var ppLimit = Math.floor(Math.pow(Math.pow(max, 2) + Math.pow(min, 2), 0.5) * 2);
      if (Widget.NPC.length > ppLimit){
        // 计划生育  偶有生存力特别强的队伍
        Widget.NPC.splice(0, Widget.NPC.length - ppLimit);
      }
      // 记录战绩
      Widget.NPC.forEach(function(item){
        if (item.hp > 0){
          Widget.record[item.team] = Widget.record[item.team] ? Widget.record[item.team]+1 : 1;
        }
      });
      addNStart();
    }
    Widget.mainCalling = function(){
      var autoCL = 10;  // 换行
      Widget.ctx.globalAlpha = 0.6;
      Widget.ctx.font = '10px Arial';
      for (let item in Widget.record){
        // 打印出来
        Widget.ctx.fillStyle = 'rgb('+item+')';
        Widget.ctx.beginPath();
        Widget.ctx.arc(6, autoCL-4, 4, 0, Math.PI*2);
        Widget.ctx.closePath();
        Widget.ctx.fill();
        Widget.ctx.fillText(Widget.record[item], 12, autoCL);
        autoCL += 10;
      }
      Widget.ctx.globalAlpha = 1;
    }
    addNStart();
    return Widget;
  }
}