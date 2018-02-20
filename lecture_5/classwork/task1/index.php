<?php
$array = [];
mt_srand(time() * mt_rand(0, 9999999));
for ($index = 0; $index < 5; $index++) {

    for ($col = 0; $col < 10; $col++) {
        $array[$index][$col] = mt_rand(0, 400);
    }
}
?>
<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <table border="2">
            <?php for ($i = 0; $i < count($array); $i++): ?>
                <tr>
                    <?php $totalCols = count($array[$i]); ?>
                    <?php for ($j = 0; $j < $totalCols; $j++): ?>
                        <td>
                            <?php echo $array[$i][$j]; ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
    </body>
</html>
