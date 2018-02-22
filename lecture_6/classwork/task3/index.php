<?php
if (isset($_POST['entries']) && count($_POST['entries']) > 0) {
    $directory = dirname(__FILE__) . '/';

    $file = $directory . 'export.csv';

    $fh = fopen($file, 'w');

    foreach ($_POST['entries'] as $entry) {
        fputcsv($fh, $entry, ',');
    }

    fclose($fh);
}
?>
<html>
    <head>
        <title>title</title>

        <style type="text/css">
            div {
                margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 10px;
            }

            label {
                margin-right: 20px;
            }

            input {
                margin-right: 20px;
            }

            table thead th {
                border-bottom: 2px solid #eee; padding: 5px;
            }
        </style>
    </head>
    <body>
        <form method="post" action="">

            <div>
                <label>
                    Enter a number of fields
                </label>
                <input type="text" 
                       value="<?php echo (isset($_POST['fields'])) ? (int) $_POST['fields'] : '' ?>" name="fields" />
            </div>

            <?php
            if ($_POST && isset($_POST['fields'])) {
                $number = (int) $_POST['fields'];

                for ($i = 0; $i < $number; $i++) {
                    ?>
                    <div>
                        <label>Name</label> 
                        <input type="text" 
                               name="entries[<?php echo $i; ?>][name]" 
                               value="<?php echo (isset($_POST['entries'][$i]['name']) ? $_POST['entries'][$i]['name'] : ''); ?>" /> 

                        <label>Lastname</label> 
                        <input type="text" 
                               name="entries[<?php echo $i; ?>][lastname]" 
                               value="<?php echo (isset($_POST['entries'][$i]['lastname']) ? $_POST['entries'][$i]['lastname'] : ''); ?>" /> 

                        <label>Email</label> 
                        <input type="text" 
                               name="entries[<?php echo $i; ?>][email]" 
                               value="<?php echo (isset($_POST['entries'][$i]['email']) ? $_POST['entries'][$i]['email'] : ''); ?>" /> 
                    </div>
                    <?php
                }
            }
            ?>

            <button type="submit">Send</button>
        </form>
    </body>
</html>