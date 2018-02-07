<?php
$squareSize = 4;
$p          = $squareSize * 4;
$s          = $squareSize * $squareSize;
$sizeInPX   = $squareSize * 37.7952755905;
?>
<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <p>
            My square size is <?php echo $squareSize; ?>cm
        </p>
        <p>
            My P is <?php echo $p; ?>cm
        </p>
        <p>
            My S is <?php echo $s; ?>cm
        </p>

        <h4>
            Square representation
        </h4>

        <div class="square" style="width: <?php echo $sizeInPX; ?>px; height: <?php echo $sizeInPX; ?>px; background: #eee;"></div>
    </body>
</html>
