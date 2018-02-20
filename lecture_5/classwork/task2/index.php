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
                       value="<?php echo (isset($_POST['fields'])) 
                       ? (int) $_POST['fields'] : '' ?>" name="fields" />
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

        <?php if ($_POST && isset($_POST['entries']) && count($_POST['entries']) > 0): ?>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Lastname</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php var_dump($_POST);?>
                    <?php foreach ($_POST['entries'] as $entry): ?>
                        <tr>
                            <td>
                                <?php echo $entry['name']; ?>
                            </td>
                            <td>
                                <?php echo $entry['lastname']; ?>
                            </td>
                            <td>
                                <?php echo $entry['email']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </body>
</html>
