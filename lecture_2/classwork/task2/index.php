<?php
if ($_POST) {
    $number = (isset($_POST['number'])) ? $_POST['number'] : 0;

    $message = '';
    $error = '';
    if ($number >= 1 && $number <= 3) {
        $message = 'It is winter';
    } elseif ($number >= 4 && $number <= 6) {
        $message = 'It is spring';
    } elseif ($number >= 7 && $number <= 9) {
        $message = 'It is summer';
    } elseif ($number >= 10 && $number <= 12) {
        $message = 'It is autumn';
    } else {
        $error = 'Enter a valid number betwen 1 and 12';
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

            <?php if (isset($message) && $message != ''): ?>
                <p>
                    <?php echo $message; ?>
                </p>
            <?php endif; ?>

            <?php if (isset($error) && $error != ''): ?>
                <p>
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>

            <form method="post" action="">
                <div>
                    <label for="a">
                        Enter a number between 1 and 12:
                    </label>
                    <input type="text" value="" name="number" /> 
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

