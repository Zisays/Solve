<link rel="stylesheet" href="solve/Page/error.css">
<table class="table">
    <thead>
    <tr>
        <th colspan="2">错误提示</th>
    </tr>
    </thead>
    <tbody>
    <?php
    echo "<tr><td style='width: 10%;'>错误级别</td><td>" . $errno . "</td></tr>";
    echo "<tr><td style='width: 10%;'>错误信息</td><td>" . $errstr . "</td></tr>";
    echo "<tr><td style='width: 10%;'>错误文件</td><td>" . $errfile . "</td></tr>";
    echo "<tr><td style='width: 10%;'>错误行号</td><td>" . $errline . "</td></tr>";
    ?>
    </tbody>
</table><br/>