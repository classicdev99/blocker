<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once BASE_PATH.'/lib/model/BannedCidrs.php';
require_once BASE_PATH.'/lib/model/BannedIps.php';
$ips = new BannedIps();
$cidrs = new BannedCidrs();

if($_SESSION['admin_type']!='super'){
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit("401 Unauthorized");
}

$db = getDbInstance();
$select_ips = array('id', 'ip', 'reason');
$select_cidrs = array('id', 'cidr', 'reason');

$pagelimit = 10;

$page_ip= filter_input(INPUT_GET, 'page');
if (!$page_ip)
{
    $page_ip = 1;
}

$page_cidr= filter_input(INPUT_GET, 'page');
if (!$page_cidr)
{
    $page_cidr = 1;
}

$db->orderBy('id', 'desc');
$ip_rows = $db->arraybuilder()->paginate('banned_ip', $page_ip, $select_ips);

$ip_total_pages = $db->totalPages;
$db->orderBy('id', 'desc');
$cidr_rows = $db->arraybuilder()->paginate('banned_cidr', $page_cidr, $select_cidrs);

$cidr_total_pages = $db->totalPages;

include BASE_PATH.'/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">IP/CIDR Blocking</h1>
        </div>

    </div>
    <?php include BASE_PATH.'/includes/flash_messages.php'; ?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div>
                        <div>
                            <h3>Ban IP Address</h3>
                        </div>
                        <div>
                            <form class="form-horizontal" action="controller/add_ip.php" method="post">
                                <div class="form-group">
                                    <label class="control-label">IP Address: </label>
                                    <input name="ip" class="form-control" type="text" value="" required="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Reason: </label>
                                    <input name="reason" class="form-control" type="text" value="">
                                </div>
                                <div>
                                    <button class="btn btn-block btn-flat btn-danger" name="ban-ip"
                                        type="submit">Ban</button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h3>IP Bans</h3>
                        </div>
                    </div>
                    <div>
                        <table id="dt-basicbans" class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40%">IP Address</th>
                                    <th width="50%">Reason</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ip_rows as $ip_row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ip_row['ip'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($ip_row['reason'] ?? ''); ?></td>
                                    <td>
                                        <a href="controller/unban_ip.php?unban_id=<?php echo $ip_row['id']; ?>"
                                            class="btn btn-flat btn-success btn-sm"><i class="fas fa-trash"></i>
                                            Unban</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-1">
                </div>
                <div class="col-md-5">
                    <div>
                        <div>
                            <h3>CIDR Format</h3>
                        </div>
                        <div>
                            <form class="form-horizontal" action="controller/add_cidr.php" method="post">
                                <div class="form-group">
                                    <label class="control-label">CIDR Format: </label>
                                    <input name="cidr" class="form-control" type="text" value="" required="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Reason: </label>
                                    <input name="reason" class="form-control" type="text" value="">
                                </div>
                                <div>
                                    <button class="btn btn-block btn-flat btn-danger" name="ban-ip"
                                        type="submit">Ban</button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h3>CIDR Bans</h3>
                        </div>
                    </div>
                    <div>
                        <table id="dt-basicbans" class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40%">CIDR Format</th>
                                    <th width="50%">Reason</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cidr_rows as $cidr_row): ?>
                                <tr>

                                    <td><?php echo htmlspecialchars($cidr_row['cidr'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($cidr_row['reason'] ?? ''); ?></td>
                                    <td>
                                        <a href="controller/unban_cidr.php?unban_id=<?php echo $cidr_row['id']; ?>"
                                            class="btn btn-flat btn-success btn-sm"><i class="fas fa-trash"></i>
                                            Unban</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<!-- //Main container -->
<?php include BASE_PATH.'/includes/footer.php'; ?>