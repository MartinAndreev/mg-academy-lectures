<?php
require_once 'action.php';
?>
<!doctype html>
<html>
    <head>
        <title>My registration form</title>
        <meta charset="UTF-8" />
    </head>
    <body>
        <style type="text/css">
            label {
                width: 150px; display: inline-block;
                line-height: 26px; vertical-align: middle;
            }

            input, select {
                line-height: 26px; height: 26px;
                padding-left: 5px; padding-right: 5px;
            }

            button {
                background: #eee; color: #000; border-radius: 0px;
                border: 1px solid #999;
            }

            div {
                margin-bottom: 10px;
            }

            .error p {
                color: #f00;
            }
        </style>

        <?php if (isset($message)): ?>
            <div class="error">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div>

            <form method="post" action="">

                <div>
                    <label for="username">
                        Потребителско име
                    </label>
                    <input type="text" <?php if (isset($_POST['username'])): ?>value="<?php echo $_POST['username']; ?>"<?php endif; ?> name="username" id="username" />
                </div>

                <div>
                    <label for="password">
                        Парола
                    </label>
                    <input type="password" name="password" id="password" />
                </div>

                <div>
                    <label for="password-confirm">
                        Потвърди паролата
                    </label>
                    <input type="password" name="password-confirm" id="password-confirm" />
                </div>

                <div>
                    <label for="email">
                        Емайл адрес
                    </label>
                    <input type="text" <?php if (isset($_POST['email'])): ?>value="<?php echo $_POST['email']; ?>"<?php endif; ?> name="email" id="email" />
                </div>

                <div>
                    <label for="name">
                        Име
                    </label>
                    <input type="text" <?php if (isset($_POST['name'])): ?>value="<?php echo $_POST['name']; ?>"<?php endif; ?> name="name" id="name" />
                </div>

                <div>
                    <label for="lastname">
                        Фамилия
                    </label>
                    <input type="text" <?php if (isset($_POST['lastname'])): ?>value="<?php echo $_POST['lastname']; ?>"<?php endif; ?> name="lastname" id="lastname" />
                </div>

                <div>
                    <label for="phone">
                        Телефон
                    </label>
                    <input type="text" <?php if (isset($_POST['phone'])): ?>value="<?php echo $_POST['phone']; ?>"<?php endif; ?> name="phone" id="phone" />
                </div>

                <div>
                    <label>
                        Пол
                    </label>
                    <label for="sex-m">
                        <input type="radio" <?php if (isset($_POST['sex']) && $_POST['sex'] == 'm'): ?>checked=""<?php endif; ?> id="sex-m" name="sex" value="m" /> Мъж
                    </label>
                    <label for="sex-f">
                        <input type="radio" <?php if (isset($_POST['sex']) && $_POST['sex'] == 'f'): ?>checked=""<?php endif; ?> id="sex-f" name="sex" value="f" /> Жена
                    </label>
                </div>

                <div>
                    <label for="country">
                        Държава
                    </label>
                    <select name="country" id="country">
                        <option value="">Моля, изберете</option>
                        <option value="bg" <?php if (isset($_POST['country']) && $_POST['country'] == 'bg'): ?>selected=""<?php endif; ?>>България</option>
                        <option value="other" <?php if (isset($_POST['country']) && $_POST['country'] == 'other'): ?>selected=""<?php endif; ?>>Друго</option>
                    </select>
                </div>

                <div>
                    <label>
                        <input type="checkbox" value="1" name="terms" /> Съгласявам се
                    </label>
                </div>

                <input type="hidden" name="test" value="1" />

                <div>
                    <button type="submit">
                        Изпрати
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>
