<?php load_header($data); ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="margin-top: 50px;">
            <form action="<?php echo $data['action']; ?>" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            <?php echo $data['title']; ?>
                        </h2>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="book" class="control-label">
                                Book
                            </label>
                            <input <?php if (isset($data['defaults']['book'])): ?>value="<?php echo $data['defaults']['book']; ?>"<?php endif; ?> type="text" name="book" id="book" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="isbn" class="control-label">
                                isbn
                            </label>
                            <input <?php if (isset($data['defaults']['isbn'])): ?>value="<?php echo $data['defaults']['isbn']; ?>"<?php endif; ?> type="text" name="isbn" id="isbn" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="author" class="control-label">
                                author
                            </label>
                            <input <?php if (isset($data['defaults']['author'])): ?>value="<?php echo $data['defaults']['author']; ?>"<?php endif; ?> type="text" name="author" id="author" class="form-control" />
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            Register
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <?php if (isset($data['errors']) && is_array($data['errors']) && count($data['errors']) > 0): ?>
        <div class="alert alert-danger">
            <?php foreach ($data['errors'] as $error): ?>
                <p>
                    <?php echo $error; ?>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php load_footer($data); ?>

