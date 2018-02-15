<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <form method="post" action="">
            N: <input type="number" name="n" />
            X: <input type="number" name="x" />

            <input type="submit" value="send" />
        </form>

        <?php
        if ($_POST) {

            $n = $_POST['n'];
            $x = $_POST['x'];
            $current = 1;

            while (true) {

                if ($current > $x) {
                    break;
                }

                if ($current % $n == 0) {
                    echo 'Skipping ' . $current . '<br />';
                } else {
                    echo 'Current is ' . $current . ' <br />';
                }

                $current++;
            }
        }
        ?>
    </body>
</html>
