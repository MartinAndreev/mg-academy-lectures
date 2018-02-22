<?php
define('CURRENT_DIR', dirname(__FILE__) . '/');
define('FILES_DIR', CURRENT_DIR . 'files/');

if (!is_dir(FILES_DIR)) {
    mkdir(FILES_DIR,0755);
}

if ($_POST) {

    $text = $_POST['text'];
    $fileName = 'file.txt';

    while (file_exists(FILES_DIR . $fileName)) {
        $parts = pathinfo($fileName);

        $fileCurrentName = $parts['filename'];
        $extention = $parts['extension'];

        $fileName = $fileCurrentName . '.' . mt_rand(0, 100) . '.' . $extention;
    }

    file_put_contents(FILES_DIR . $fileName, $text);
}
?>
<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <form action="" method="post">
            <textarea name="text" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="save" />
        </form>
    </body>
</html>
