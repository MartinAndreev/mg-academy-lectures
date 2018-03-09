<?php load_header($data); ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="margin-top: 50px;">
            <form action="index.php?action=register" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            Register
                        </h2>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="email" class="control-label">
                                Email
                            </label>
                            <input type="text" name="email" id="email" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">
                                Password
                            </label>
                            <input type="password" name="password" id="password" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">
                                Password confirm
                            </label>
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control" />
                        </div>
                         <div class="form-group">
                            <label for="name" class="control-label">
                                Name
                            </label>
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                         <div class="form-group">
                            <label for="lastname" class="control-label">
                                Lastname
                            </label>
                            <input type="lastname" name="lastname" id="lastname" class="form-control" />
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