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

            var_dump(sort($numbers));
        }
        ?>
    </body>
</html>
