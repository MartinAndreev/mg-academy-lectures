<?php
define('FILES_PATH', dirname(__FILE__) . '/../task1/files/');

$files = scandir(FILES_PATH);

if (isset($_GET['file']) && $_GET['file'] != '' && $_POST) {
    $file = $_GET['file'];

    if (file_exists(FILES_PATH . $file))
        file_put_contents(FILES_PATH . $file, $_POST['text']);
}
?>
<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <ul>
            <?php
            foreach ($files as $file) {
                if (!is_file(FILES_PATH . $file)) {
                    continue;
                }
                $filesize = filesize(FILES_PATH . $file);
                echo '<li><a href="index.php?file=' . $file . '">' . $file . ' (' . $filesize . ' bytes)' . '</a> </li>';
            }
            ?>
        </ul>
        <?php
        if (isset($_GET['file']) && $_GET['file'] != '') :
            $fileName = $_GET['file'];
            $file = file_get_contents(FILES_PATH . $fileName);
            ?>
            <form action="" method="post">
                <textarea name="text" id="" cols="30" rows="10"><?php echo $file; ?></textarea>
                <button type="submit">Save</button>
            </form>
            <?php
        endif;
        ?>
    </body>
</html>
