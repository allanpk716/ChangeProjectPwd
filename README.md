ChangeProjectPwd
================

用于独立服务器Project用户的密码修改

作为独立服务器来使用的Project，使用windows的用户验证机制，一般来说不方便给予其他用户命令行执行的权限去修改密码。

那么就只能写个exe来执行命令，然后弄个简单的网站来做界面，之后限制一下只能本地的连接（.htaccess）能连接就好了。

没啥技术含量，仅仅是为了方便操作。

当然新建windows用户的时候也很麻烦，这个也是用代码搞定，之前还考虑过先把人名转拼音再做，不过貌似多音字很麻烦，
以后真的再次需要录入大量人名的时候再具体弄吧。

1，Exe执行CMD命令的用C#编写，VS2010打开。

2，网页部分由ThinkPHP完成。
