## 查看fork相关的进程
 - 运行 fork.php
 - ps aux | grep fork.php
admini   32278 32089  0 18:52 pts/23   00:00:00 php b.php
admini   32279 32278  0 18:52 pts/23   00:00:00 php b.php
admini   32379 32282  0 18:52 pts/24   00:00:00 grep --color=auto b.php

其中32278是父进程,32279是子进程

