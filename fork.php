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
