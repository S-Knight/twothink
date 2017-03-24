{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>页面跳转中</title>
    <style type="text/css">
        body,
        html {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        * {
            padding: 0;
            margin: 0;
        }

        body {
            background: #fff;
            font-family: "Microsoft Yahei", "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 16px;
        }

        .system-message {
            padding: 40px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 86%;
            margin: auto;
            background:rgba(222,240,216,1);
        }

        .system-message h1 {
            font-size: 100px;
            font-weight: normal;
            line-height: 120px;
            margin-bottom: 12px;
        }

        .system-message .jump {
            padding-top: 10px;
            color: rgba(70, 106, 162,1);
        }

        .system-message .jump a {
            color: #333;
        }

        .system-message .success,
        .system-message .error {
            line-height: 1.8em;
            font-size: 36px;
        }

        .system-message .success{
            color: rgba(58,126,96,1);
        }

        .system-message .detail {
            font-size: 12px;
            line-height: 20px;
            margin-top: 12px;
            display: none;
        }

        .tableBox{
            width: inherit;
            height: inherit;
            display: table;
        }

        .tableCell{
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .tag>img{
            height: 80px;
        }
    </style>
</head>

<body>
<div class="tableBox">
    <div class="tableCell">
        <div class="system-message">

            <?php switch ($code) {?>
            <?php case 1:?>
                <p class="tag">
                <img src="/common/img/success.png" alt="success"/>
                </p>
                <p class="success"><?php echo(strip_tags($msg));?></p>
            <?php break;?>
            <?php case 0:?>
                <p class="tag">
                <img src="/common/img/error.png" alt="success"/>
                </p>
                <p class="success"><?php echo(strip_tags($msg));?></p>
            <?php break;?>
            <?php } ?>
            <p class="detail"></p>
            <p class="jump">
                页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function() {
        var wait = document.getElementById('wait');
        var href = document.getElementById('href').href;
        var interval = setInterval(function() {
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