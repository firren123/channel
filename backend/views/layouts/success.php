<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <style type="text/css">
        /*空*/
        .emptybox{width:500px;height:80px; line-height:28px; text-align:center;background:#FFFBF7; border:1px solid #e2e2e2; margin:300px auto; padding-top:20px; font-size:14px;}
        .emptybox span{font-weight:bold;}
        .emptybox .num{color:#FF5D5B;}
        .emptybox a,.emptybox a:hover{padding:0 5px; color:#d4761e; text-decoration:underline;}
        .emptybox p span{font-weight:normal;}
    </style>
</head>
<body>

<div class="emptybox">

    <span>
        <?php
        if ($message) {
            echo $message;
        } else {
            echo $error;
        }
        ?>
        &nbsp;&nbsp;&nbsp;&nbsp;

    </span>
    <p class="jump">
        页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
    </p>
<!--    <p><span>5</span>秒后&nbsp;&nbsp;<a href="#">返回首页</a>或者<a href="#">查看订单详情</a></p>-->
</div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>
</html>
