<link rel="stylesheet" href="solve/Page/error.css">
<table class="table">
    <tbody>
    <tr>
        <td>
            <?php
            if (is_bool($data)) {
                var_dump($data);
            } elseif (is_null($data)) {
                var_dump(NULL);
            } else {
                echo '<pre>';
                print_r($data);
                echo '</pre>';
            }
            ?>
        </td>
    </tr>
    </tbody>
</table><br/>