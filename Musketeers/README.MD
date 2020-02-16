下载JS：[musketeers.js](musketeers.js)

预览：
1. [自定义](game.html)
2. [小挂件](widget.html)

How to use:

__## mini挂件 (简单) ##__:

```
var mm = new musketeers();
mm.justWidget('容器ID','canvasID', 火枪手最少量, 火枪手最多量);
```
最少量, 最多量按照容器大小来填
会自动获取"容器ID"的高度和宽度并设置为canvas的高度和宽度。
如果报错，可能是你写错了ID，请检查ID是否存在。
当容器长宽超过200时，有25%可能会开启据点战争（即填充更多火枪手）

__## 自定义  ##__

注意：颜色都应为RGB式，否则请用下面的函数mm.colorRgb把16进制颜色转为RGB

```
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
```