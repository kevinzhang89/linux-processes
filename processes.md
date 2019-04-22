## 什么是进程（process）？
正在运行的程序就是进程
## 如何中止（terminate）进程？
kill pid
## 进程有名称（name）吗？请举一例
进程有名字
例:
root@zhangyongqi:/proc/1507# cat /proc/13155/status  | more
Name:	php
Umask:	0002
State:	S (sleeping)
## 运行 Psysh，查看其进程 ID 和父进程（parent process）的命令（command）
PID是其进程ID, PPID是父进程
admini@zhangyongqi:~/docker$ ps -ef | grep psysh
UID       PID  PPID   C  STIME  TTY       TIME         CMD
admini   13263 20963  0 17:33 pts/4    00:00:00 php ./psysh
admini   13274 14032  0 17:33 pts/4    00:00:00 php ./psysh
admini   13280 13274  0 17:34 pts/4    00:00:00 php ./psysh
admini   15932 24943  0 17:57 pts/20   00:00:00 grep --color=auto psysh

## 进程都有父进程的说法对吗？如果不正确，请举一反例
0 就没有
## 写代码实现父进程 fork 子进程
<?php
$pid = pcntl_fork();
if ($pid == -1) {
    //如果出错执行这里
    die('could not fork');
} else if ($pid) {
    //这是父进程
    sleep(100);
    echo "parent";
    pcntl_wait($status); //Protect against Zombie children
} else {
   //这是子进程
   sleep(20);
   echo "child";
}

?>

admini@zhangyongqi:/tmp$ ps aux | grep fork.php
admini   16516  0.5  0.3 321536 29040 pts/20   S+   22:36   0:00 php b.php
admini   16517  0.0  0.0 321536  7840 pts/20   S+   22:36   0:00 php b.php
admini   16522  0.0  0.0  15988   880 pts/19   S+   22:36   0:00 grep --color=auto b.php

## 请用前一题的代码确认父进程被中止后，子进程的行为
kill -9 123  子进程也会终止
但是 为什么fork产生的进程没有终止???
## 把运行中的 Psysh 切换到后台（background）运行
./psysh &   
bg ./psysh
## 把被切换到后台的 Psysh 再切换回前台（foreground）
fg ./psysh
## 前台进程 / 后台进程的区别是什么？
前台进程：是用户使用的有控制终端的进程
后台进程：是在当前没有使用的但是也在运行的进程，包括那些系统隐藏或者没有图形化界面的程序。
## 请根据自己的开发实践对选择前或后台运行程序的场景分别举例
后台 redis可以是后台的,nginx后台的
前台 我们需要可视化操作的时候,就需要切换到前台进程,例如redis, 我们使用的图形化工具都是前台进程
## 什么是守护进程（daemon process），一直运行的进程就是守护进程吗？
一直运行在内存里面的进程,就是守护进程,例如fast-cgi
## 请总结，进程的信息有哪些，各有哪些使用场景？
USER 进程所属用户      
PID 就是Pid
%CPU %MEM 进程占用了cpu和内存的比重 
VSZ 占用虚拟内存的总量  
RSS 进程占用的固定的内存量 
TTY 登陆者终端机的位置      
STAT  进程的状态 R 运行 S 睡眠
START  进程开始创建的时间  
TIME 进程使用的总cpu时间
COMMAND : 进程对应的实际程序
## 什么是环境变量？
操作系统中用来指定操作系统运行环境的一些参数
## 添加系统环境变量，写代码读取该变量的值
>>> $_ENV['a']=123123
=> 123123
>>> var_dump($_ENV);
array(1) {
  'a' =>
  int(123123)
}
$_ENV存储了一些系统的环境变量,因环境不同而值不同。
$_SERVER包含服务器和执行环境的一些信息，不同的服务器包含的内容可能有差异。
## 如何只对一个进程添加环境变量？请以 Psysh 演示
不知道
## 如何用 netstat 确认 Nginx 的服务进程监听了网络端口？如何查看端口被哪个进程监听？
admini@zhangyongqi:/tmp$ sudo netstat -ntlp | grep nginx
tcp        0      0 0.0.0.0:80              0.0.0.0:*               LISTEN      1615/nginx -g daemo
tcp        0      0 0.0.0.0:799             0.0.0.0:*               LISTEN      1615/nginx -g daemo
tcp6       0      0 :::80                   :::*                    LISTEN      1615/nginx -g daemo
tcp6       0      0 :::799                  :::*                    LISTEN      1615/nginx -g daemo

## 查看端口被哪个进程监听
sudo ss -antlp | grep 80

nginx 是多进程的,可以在配置文件中配置多少个
## 如何用 lsof 查看 MySQL 的服务进程打开了哪些文件来确认其日志文件的路径？如何查看文件被哪个进程打开？
admini@zhangyongqi:/proc/3312$ sudo lsof | grep mysql
## 查看 yiisoft/yii2-queue 的 queue/listen 处理 job 的进程 ID
admini@zhangyongqi:/tmp$ ps -ef | grep queue (2)
admini    6970  1421  2 18:25 ?        00:00:00 /usr/bin/php7.1 /home/admini/docker/njfaetoc/yii order/queue
admini    6975  6865  0 18:25 pts/25   00:00:00 grep --color=auto queue
admini    7751  7331  0 4月13 ?       00:01:19 /usr/local/bin/php /code/yii queue2/listen --interactive=0
admini    7753  7331  0 4月13 ?       00:00:57 /usr/local/bin/php /code/yii queue/listen --interactive=0

## 解释下面的指令：
echo 'Hello World!' > hello-world.txt  清空
echo 'Hello World!' >> hello-world.txt  追加

## 什么是标准输入，标准输出？
使用键盘输入的就是标准输入
输出现实在客户端上显示的就是标准输出
它们是/dev/stdin这个文件和/dev/stdout这个文件。
也就是说所谓的标准输入和标准输出其实就是两个linux下的文件。
echo aaa >/dev/stdout 

## 户用浏览器打开公网上 PHP 网站实时查询数据库展示数据的网页的全过程，有哪些关键进程参与？
1.必须遵守http协议
2.请求header和请求body
3.返回的request请求,浏览器进行解析
4.客户端(浏览器)和web服务器(nginx)进行通信使用http协议,nginx和php-fpm通信使用fast-cgi协议,如果浏览器请求的是.html静态文件,nginx会直接去服务器上找路径文件,返回给客户端,然后浏览器解析;
5.端口 
6.遵守的协议不一样,客户端和web服务器是http; web服务器和php是fast-cgi, php和mysql 使用Socket
