## 开始安装

请修改 `config.php` 内的数据，链接上数据库。

如果你的主机被限制了最大执行时间，则`$autoclimb`和`$keepclimbing`按照默认的设置即可。

若你想让蜘蛛无限爬，请直接修改{ `$keepclimbing` 为 `true` }和{ `set_time_limit()` }。

然后用浏览器打开 `example.com/spider.php` 登陆蜘蛛。

登陆后手动打开`example.com/spider.php?m=install`进行安装。

## 启动蜘蛛

这时候打开`exmaple.com/spider.php`，你可以看到有选项，输入框里的能指定蜘蛛爬的链，[瞎几把爬爬]按钮是从数据库里找到链给蜘蛛爬。因为蜘蛛每一次爬都会解析更多的链到库里，所以不用逐条添加。

一般一开始在输入框里输入一个链接，然后OJBK了，蜘蛛会自己去爬的（如果你设置让蜘蛛无限爬的话）

如果没有设置让蜘蛛无限爬，就用浏览器直接挂着`spider.php?m=auto`（也可以在spider.php中点击[瞎几把爬爬]），想停下来就直接关掉这个页面。

还可以添加cron作业来驱动蜘蛛。
如通过HTTP请求
```
curl "http://example/spider.php?pass={config.php中的密码}&m=auto"
```

## 搜索页面

你认为蜘蛛爬得足够深了后，直接在`index.php`打开即可看到搜索界面。
权重按照 url>title>html 排，并且没有title的页面会被降权

## 其他

### 强行停止蜘蛛爬行：
在蜘蛛目录建立一个空的`stop`文件即可，这时候蜘蛛就会停止爬行，并提示"Stop by force"，删掉即可恢复。