##使用拓展坞外接键盘在充电情况下无法使用
~~插着的情况下，打开活动监视器，强行结束 usbd 这个进程有时候可以短暂解决这一次的连接问题。[原链接](https://discussionschinese.apple.com/thread/251443860)~~
事实证明没什么用，最后从重新买的拓展坞(😓)

.DS_Store文件产生及解决

* 解决
为了加快网络磁盘的浏览速度，苹果提供了针对通过SMB共享的网络磁盘上DS_Store文件的阻止生成方案：

1. 在「访达」中打开「应用程序」 > 「实用工具」 > 「终端」。
2. 输入以下命令：
```
defaults write com.apple.desktopservices DSDontWriteNetworkStores -bool TRUE
```
3. 然后退出登录 macOS 账户并重新登录。
要重新启用，请输入以下命令：
```
defaults write com.apple.desktopservices DSDontWriteNetworkStores -bool FALSE
```
以上命令只是针对网络磁盘

