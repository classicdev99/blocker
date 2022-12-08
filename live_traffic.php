<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

$page = filter_input(INPUT_GET, 'page');
$pagelimit = 10;
$db = getDbInstance();
$select = array('id', 'user_name', 'ip_addr', 'country', 'isp', 'browser', 'os_name','visited_page',
'country_code','region','city','zipcode','device', 'datetime','user_agent','is_bot','is_proxy','time_spent');

if (!$page)
{
    $page = 1;
}

$db->pageLimit = $pagelimit;

$db->orderBy('datetime', 'desc');
$rows = $db->arraybuilder()->paginate('users', $page, $select);
$total_pages = $db->totalPages;
include BASE_PATH.'/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Live Traffic</h1>
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
                    <div class="col-sm-2">
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
                            <div class="col-sm-6">Date & Time: <?php echo $row['datetime'];?></div>
                            <div class="col-sm-6">Time spent on website: <?php echo $row['time_spent'];?></div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-6">Bot: <?php if($row['is_bot']) echo 'YES'; else echo 'NO';?></div>
                            <div class="col-sm-6">Proxy: <?php if($row['is_proxy']) echo 'YES'; else echo 'NO';?></div>
                        </div>
                    </li>
                </ul>

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
                    echo '<li' . $li_class . '><a href="live_traffic.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
                }
                echo '</ul>';
            }
            ?>
    </div>
    <!-- //Pagination -->

</div>
<!-- //Main container -->
<?php include BASE_PATH.'/includes/footer.php'; ?>