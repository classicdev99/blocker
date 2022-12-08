<?php
session_start();
require_once 'config/config.php';

include BASE_PATH.'/includes/header.php';
?>
<div id="page-" class="col-md-4 col-md-offset-4">
    <form class="form signupform" method="POST" action="controller/register.php">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Please Register</div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">username</label>
                    <input type="text" name="user_name" class="form-control" required="required">
                </div>
                <div class="form-group">
                    <label class="control-label">password</label>
                    <input type="password" name="password" class="form-control" required="required">
                </div>
                <?php if (isset($_SESSION['failure'])): ?>
                <div class="alert alert-danger alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php
					echo $_SESSION['failure'];
					unset($_SESSION['failure']);
					?>
                </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-success signupField">Register</button>
            </div>
        </div>
    </form>
</div>
<?php include BASE_PATH.'/includes/footer.php'; ?>