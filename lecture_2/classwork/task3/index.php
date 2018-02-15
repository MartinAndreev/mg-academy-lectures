<html>
    <head>
        <title>Task 3</title>
    </head>
    <body>
        <form action="" method="get">
            <label>
                <input type="checkbox" name="has_eggs" value="1" <?php if (isset($_GET['has_eggs'])): ?>checked=""<?php endif; ?> />
                Did we find eggs?
                <button type="submit">Save</button>
            </label>

            <strong>
                <?php if (isset($_GET['has_eggs'])): ?>
                    He came back with 6 milks
                <?php else: ?>
                    He came back with 1 milk
                <?php endif; ?>
            </strong>
        </form>
    </body>
</html>
