<?php 
    if($_FILES) {
        var_dump($_FILES);die();
    }
?>
<html>
    <head>
        <title>title</title>
    </head>
    <body>
        <form method="post" action="" enctype="multipart/form-data">
            
            <input type="file" name="file" multiple="" />
            
            <input type="file" name="thumbnail" />
            <input type="file" name="header" />
            <input type="submit" value="send" />
        </form>
    </body>
</html>
