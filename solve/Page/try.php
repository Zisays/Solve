<link rel="stylesheet" href="solve/page/error.css">
<table class="table">
    <thead>
    <tr>
        <th colspan="2">错误提示</th>
    </tr>
    </thead>
    <tbody>
    <?php
    echo "<tr><td style='width: 10%;'>错误编号</td><td>" . $e->getCode() . "</td></tr>";
    echo "<tr><td style='width: 10%;'>错误信息</td><td>" . $e->getMessage() . "</td></tr>";
    echo "<tr><td style='width: 10%;'>错误文件</td><td>" . $e->getFile() . "</td></tr>";
    echo "<tr><td style='width: 10%;'>错误行号</td><td>" . $e->getLine() . "</td></tr>";
    if (!empty($this->sql)) {
        echo "<tr><td style='width: 10%;'>预处理 SQL</td><td>" . $this->sql . "</td></tr>";
    } elseif (!empty($this->updateArray)) {
        echo "<tr><td style='width: 10%;'>预处理 SQL 参数：</td><td>";
        echo '<pre>';
        print_r($this->updateArray);
        echo '</pre>';
        echo "</td></tr>";
    } elseif (!empty($this->whereArray)) {
        echo "<tr><td style='width: 10%;'>预处理 SQL 参数：</td><td>";
        echo '<pre>';
        print_r($this->whereArray);
        echo '</pre>';
        echo "</td></tr>";
    } else {
        echo "<tr><td style='width: 10%;'>预处理 SQL 参数：</td><td></td></tr>";
    }
    $v = $e->getTrace();
    if (count($v) > 0) {
        echo "<tr><td rowspan ='" . count($v) . "' style='width: 10%;'>堆栈跟踪（数组）</td>";
        for ($i = 1; $i <= count($v); $i++) {
            if ($i == 1) {
                echo '<td>';
            } else {
                echo '</tr><td>';
            }
            echo '【' . $i . '】';
            if (!empty($v[$i - 1]['file'])) {
                echo '文件位置：' . $v[$i - 1]['file'] . '（ ' . $v[$i - 1]['line'] . ' 行）<br/><br/>';
            }
            if (!empty($v[$i - 1]['class'])) {
                echo '当前的类名：' . $v[$i - 1]['class'] . '<br/><br/>';
            }
            if (!empty($v[$i - 1]['type'])) {
                echo '当前调用的类型：' . $v[$i - 1]['type'] . '<br/><br/>';
            }
            if (!empty($v[$i - 1]['function'])) {
                echo '当前的函数名：' . $v[$i - 1]['function'] . '<br/><br/>';
            }
            echo '参数：';
            echo '<pre>';
            print_r($v[$i - 1]['args']);
            echo '</pre>';
            echo '</td>';
            echo "</tr>";
        }
    }
    echo "<tr><td style='width: 10%;'>堆栈跟踪（字符串）</td><td>";
    echo '<pre>';
    print_r($e->getTraceAsString());
    echo '</pre>';
    echo "</td></tr>";
    echo "<tr><td style='width: 10%;'>上一个错误</td><td>" . $e->getPrevious() . "</td></tr>";
    ?>
    </tbody>
</table><br/>