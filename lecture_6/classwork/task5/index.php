<?php 
ini_set('display_errors', 'on');
error_reporting(E_ALL);
?>
<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="image" />
            <input type="submit" value="Send"/>
        </form>

        <?php
        if ($_FILES && isset($_FILES['image'])) {
            $file = $_FILES['image'];

            $errors = [];
            $size = filesize($file['tmp_name']);
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

            if ($size > 2000000000) {
                $errors[] = 'File is to large';
            }

            if (!in_array($ext, ['png', 'gif', 'jpg'])) {
                $errors[] = 'File is not an image';
            }

            if ($file['error'] != UPLOAD_ERR_OK) {
                $errors[] = 'Uplaod error found.';
            }

            if (count($errors) > 0) {
                echo implode("<br />", $errors);
            } else {
                $dir = dirname(__FILE__) . '/';
                echo $dir;
                var_dump($file);
                $result = move_uploaded_file($dir . $file['name'], $file['tmp_name']);

                var_dump($result);
            }
        }
        ?>
    </body>
</html>
