<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

// Users class
require_once BASE_PATH.'/lib/model/Bots.php';
$bots = new Bots();

// Only super admin is allowed to access this page
if ($_SESSION['admin_type'] !== 'super')
{
    // Show permission denied message
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit('401 Unauthorized');
}

// Get Input data from query string
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');
$del_id = filter_input(INPUT_GET, 'del_id');

// Per page limit for pagination.
$pagelimit = 10;

// Get current page.
$page = filter_input(INPUT_GET, 'page');
if (!$page)
{
    $page = 1;
}

// If filter types are not selected we show latest added data first
if (!$filter_col)
{
    $filter_col = 'id';
}
if (!$order_by)
{
    $order_by = 'Desc';
}

//Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();
$select = array('id', 'user_name', 'ip_addr', 'country', 'isp', 'browser', 'os_name','visited_page',
        'country_code','region','city','zipcode','device', 'datetime', 'user_agent','time_spent');

//Start building query according to input parameters.
// If search string
if ($search_string)
{
    $db->where('user_name', '%' . $search_string . '%', 'like');
}

//If order by option selected
if ($order_by)
{
    $db->orderBy($filter_col, $order_by);
}

// Set pagination limit
$db->pageLimit = $pagelimit;

// Get result of the query.
$db->where('blocked', 1);
$rows = $db->arraybuilder()->paginate('users', $page, $select);
$total_pages = $db->totalPages;

include BASE_PATH.'/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Banned Users</h1>
        </div>
    </div>
    <?php include BASE_PATH.'/includes/flash_messages.php'; ?>

    <?php
    if (isset($del_stat) && $del_stat == 1)
    {
        echo '<div class="alert alert-info">Successfully deleted</div>';
    }
    ?>
    <?php foreach ($rows as $row): ?>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" href="#collapse<?php echo $row['id'];?>">
                <div class="row">
                    <div class="col-sm-2">Username: <?php echo $row['user_name'];?></div>
                    <div class="col-sm-2">IP : <?php echo $row['ip_addr'];?></div>
                    <div class="col-sm-6">Visited Page : <?php echo $row['visited_page'];?></div>
                    <div class="col-sm-1">
                        <span class="fi fi-<?php echo $row['country_code'];?>"></span>
                        <?php echo $row['country'];?>
                    </div>

                </div>
            </div>
            <div id="collapse<?php echo $row['id'];?>" class="panel-collapse collapse">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-3">Country: <?php echo $row['country'];?></div>
                            <div class="col-sm-3">Region: <?php echo $row['region'];?></div>
                            <div class="col-sm-3">City: <?php echo $row['city'];?></div>
                            <div class="col-sm-3">Zip Code: <?php echo $row['zipcode'];?></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12">Internet Provider: <?php echo $row['isp'];?></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4">OS: <?php echo $row['os_name'];?></div>
                            <div class="col-sm-4">Device: <?php echo $row['device'];?></div>
                            <div class="col-sm-4">Browser: <?php echo $row['browser'];?></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12">User Agent: <?php echo $row['user_agent'];?></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12">Date & Time: <?php echo $row['datetime'];?></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12">Time spent on website: <?php echo $row['time_spent'];?></div>
                        </div>
                    </li>
                </ul>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-1 ">
                            <a href="controller/unban_user.php?unban_id=<?php echo $row['id']; ?>"
                                class="btn btn-flat btn-success btn-sm"><i class="fas fa-trash"></i>
                                Unban</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <!-- Pagination -->
    <div class=" text-center">
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
                echo '<li' . $li_class . '><a href="users.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
    <!-- //Pagination -->

</div>
<!-- //Main container -->
<?php include BASE_PATH.'/includes/footer.php'; ?>