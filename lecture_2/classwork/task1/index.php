<?php
if ($_POST) {
    $a = (isset($_POST['a'])) ? $_POST['a'] : 0;
    $b = (isset($_POST['b'])) ? $_POST['b'] : 0;
    $c = (isset($_POST['c'])) ? $_POST['c'] : 0;

    $max = $a;

    if ($b > $max) {
        $max = $b;
    }

    if ($c > $max) {
        $max = $c;
    }
}
?>
<html>
    <head>
        <title>title</title>
        <meta charset="UTF-8" />
    </head>
    <body>
        <div>
            <?php if (isset($max)): ?>
                <h1>
                    <?php echo $max; ?>
                </h1>
            <?php endif; ?>

            <form method="post" action="">
                <div>
                    <label for="a">
                        A is:
                    </label>
                    <input type="text" value="" name="a" /> 
                </div>
                <div>
                    <label for="b">
                        B is:
                    </label>
                    <input type="text" value="" name="b" /> 
                </div>
                <div>
                    <label for="c">
                        C is:
                    </label>
                    <input type="text" value="" name="c" /> 
                </div>

                <div>
                    <button type="submit">
                        Изчисли
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>

