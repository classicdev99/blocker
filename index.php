<?php

header("refresh: 5;");

      
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';
require_once 'helpers/helpers.php';
require_once BASE_PATH.'/lib/model/Bots.php';

$bots = new Bots();

$pagelimit = 10;

// Get current page.
$page = filter_input(INPUT_GET, 'page');
if (!$page)
{
    $page = 1;
}

//Get DB instance. function is defined in config.php
$db = getDbInstance();
checkBlockedCIDR();
$select = array('id', 'user_name','ip_addr', 'visited_page', 'country','country_code','blocked','datetime');
// $db = getDbInstance();
// $rows = $db->arraybuilder()->paginate('users', $page, $select);
// $total_pages = $db->totalPages;
$db->pageLimit = $pagelimit;
$db->orderBy('datetime', 'desc');
// Get result of the query.
$rows = $db->arraybuilder()->paginate('users', $page, $select);
$total_pages = $db->totalPages;

doit();
include_once('includes/header.php');
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="10%">User Name</th>
                <th width="15%">IP ADDRESS</th>
                <th width="40%">VISITED PAGE</th>
                <th width="10%">COUNTRY</th>
                <th width="15%">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_name']) ?? ''; ?></td>
                <td><?php echo htmlspecialchars($row['ip_addr'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['visited_page'] ?? ''); ?></td>
                <td><span class="fi fi-<?php echo $row['country_code'];?>">
                    </span> <?php echo htmlspecialchars($row['country'] ?? ''); ?>
                </td>
                <td>
                    <a href="live_traffic.php?user_id=<?php echo $row['id']; ?>" class="btn btn-primary">View</a>
                    <a href="#"
                        class="btn btn-danger block_btn <?php if($row['blocked']== 1) echo 'disabled'; else  echo '';?>"
                        data-toggle="modal" data-target="#confirm-block-<?php echo $row['id']; ?>">Ban</a>
                    <a href="#"
                        class="btn btn-success unblock_btn <?php if($row['blocked']== 0) echo 'disabled'; else  echo '';?> "
                        data-toggle="modal" data-target="#confirm-unblock-<?php echo $row['id']; ?>">Unban</a>
                </td>
            </tr>
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="confirm-block-<?php echo $row['id']; ?>" role="dialog">
                <div class="modal-dialog">
                    <form action="controller/block_user.php" method="POST">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="block_id" id="block_id" value="<?php echo $row['id']; ?>">
                                <p>Are you sure you want to block this user?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default pull-left">Yes</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="confirm-unblock-<?php echo $row['id']; ?>" role="dialog">
                <div class="modal-dialog">
                    <form action="controller/unblock_user.php" method="POST">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="unblock_id" id="unblock_id"
                                    value="<?php echo $row['id']; ?>">
                                <p>Are you sure you want to unblock this user?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default pull-left">Yes</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- //Delete Confirmation Modal -->
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center">
        <?php
        if (!empty($_GET)) {
            // We must unset $_GET[page] if previously built by http_build_query function
            unset($_GET['page']);
            // To keep the query sting parameters intact while navigating to next/prev page,
            $http_query = "?" . http_build_query($_GET);
        } else {
            $http_query = "?";
        }
        // Show pagination links
        if ($total_pages > 1) {
            echo '<ul class="pagination text-center">';
            for ($i = 1; $i <= $total_pages; $i++) {
                ($page == $i) ? $li_class = ' class="active"' : $li_class = '';
                echo '<li' . $li_class . '><a href="index.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">


            <!-- /.panel -->
        </div>
        <!-- /.col-lg-8 -->
        <div class="col-lg-4">

            <!-- /.panel .chat-panel -->
        </div>
        <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

<?php include_once('includes/footer.php'); ?>