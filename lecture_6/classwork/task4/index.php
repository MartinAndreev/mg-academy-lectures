<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <table>
            <?php
            $file = dirname(__FILE__) . '/../task3/export.csv';

            $fh = fopen($file, 'r');

            while (($data = fgetcsv($fh)) !== FALSE) {
                echo '<tr>';
                foreach ($data as $col) {
                    echo "<td>" . $col . "</td>";
                }
                echo '</tr>';
            }

            fclose($fh);
            ?>
        </table>
    </body>
</html>
