<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <form action="" method="get">

            <div>
                <label>Rows</label>
                <input type="text" name="rows" />
            </div>

            <div>
                <label>Cols</label>
                <input type="text" name="cols" />
            </div>

            <button type="submit">
                Send
            </button>
        </form>

        <?php
        if ($_GET && isset($_GET['rows']) && isset($_GET['cols'])) {

            $rows = $_GET['rows'];
            $cols = $_GET['cols'];

            $message = '';

            if ($rows < 0 || $rows > 15) {
                $message .= 'Rows must be between 1 and 15 <br />';
            }

            if ($cols < 0 || $cols > 15) {
                $message .= 'Cols must be between 1 and 15 <br />';
            }

            if ($message != '') {
                ?>
                <div>
                    <?php echo $message; ?>
                </div>
                <?php
            } else {
                ?>
                <table border="3">
                    <?php
                    for ($i = 0; $i < $rows; $i++):
                        ?>
                        <tr id="row-<?php echo $i; ?>">
                            <?php for ($j = 0; $j < $cols; $j++): ?>
                                <td>
                                    <?php echo $j; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </table>
                <?php
            }
        }
        ?>

    </body>
</html>
