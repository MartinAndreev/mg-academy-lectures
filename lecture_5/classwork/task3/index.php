<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <form method="post" action="">
            <textarea name="text"></textarea>
            <input type="submit" value="send" />
        </form>

        <?php
        if ($_POST && isset($_POST['text']) && $_POST['text'] != '') {
            $text = $_POST['text'];
            $numbers = (strpos($text, ' ')) ? explode(' ', $text) : [$text];

            $unique = array_unique($numbers);
            $total = [];

            foreach ($numbers as $number) {
                if (isset($total[$number])) {
                    $total[$number] += 1;
                } else {
                    $total[$number] = 1;
                }
            }
            
            var_dump($total, $unique);
        }
        ?>
    </body>
</html>
