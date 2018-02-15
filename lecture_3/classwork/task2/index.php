<html>
    <head>
        <title>title</title>
    </head>
    <body>

        <form action="" method="post">
            Our delimiter is : <input type="text" name="del"  value="<?php if (isset($_POST['del'])): ?><?php echo $_POST['del']; ?><?php endif; ?>" />
            <button type="submit">
                Send
            </button>
        </form>
        <?php
        for ($i = 0; $i < 100; $i++) {

            if ($i == 0) {
                continue;
            }

            if ($i % 3 == 0 or $i % 5 == 0 or $i % 7 == 0) {
                echo $i, "<br />";
            }
        }

        /**
         * 
         * @param int $del
         * @return void
         */
        function generate($del) {
            for ($i = 0; $i < 100; $i++) {

                if ($i == 0) {
                    continue;
                }

                if ($i % $del == 0) {
                    echo $i, "<br />";
                }
            }
        }

        if ($_POST) {
            $del = (int) $_POST['del'];

            generate($del);
        }
        ?>
    </body>
</html>
