<?php
/**
 * Function to generate random string.
 */


 
function randomString($n) {

	$generated_string = "";

	$domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

	$len = strlen($domain);

	// Loop to create random string
	for ($i = 0; $i < $n; $i++) {
		// Generate a random index to pick characters
		$index = rand(0, $len - 1);

		// Concatenating the character
		// in resultant string
		$generated_string = $generated_string . $domain[$index];
	}

	return $generated_string;
}

/**
 *
 */
function getSecureRandomToken() {
	$token = bin2hex(openssl_random_pseudo_bytes(16));
	return $token;
}

/**
 * Clear Auth Cookie
 */
function clearAuthCookie() {

	unset($_COOKIE['series_id']);
	unset($_COOKIE['remember_token']);
	setcookie('series_id', null, -1, '/');
	setcookie('remember_token', null, -1, '/');
}
/**
 *
 */
function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function paginationLinks($current_page, $total_pages, $base_url) {

	if ($total_pages <= 1) {
		return false;
	}

	$html = '';

	if (!empty($_GET)) {
		// We must unset $_GET[page] if previously built by http_build_query function
		unset($_GET['page']);
		// To keep the query sting parameters intact while navigating to next/prev page,
		$http_query = "?" . http_build_query($_GET);
	} else {
		$http_query = "?";
	}

	$html = '<ul class="pagination text-center">';

	if ($current_page == 1) {

		$html .= '<li class="disabled"><a>First</a></li>';
	} else {
		$html .= '<li><a href="' . $base_url . $http_query . '&page=1">First</a></li>';
	}

	// Show pagination links

	//var i = (Number(data.page) > 5 ? Number(data.page) - 4 : 1);

	if ($current_page > 5) {
		$i = $current_page - 4;
	} else {
		$i = 1;
	}

	for (; $i <= ($current_page + 4) && ($i <= $total_pages); $i++) {
		($current_page == $i) ? $li_class = ' class="active"' : $li_class = '';

		$link = $base_url . $http_query;

		$html = $html . '<li' . $li_class . '><a href="' . $link . '&page=' . $i . '">' . $i . '</a></li>';

		if ($i == $current_page + 4 && $i < $total_pages) {

			$html = $html . '<li class="disabled"><a>...</a></li>';

		}

	}

	if ($current_page == $total_pages) {
		$html .= '<li class="disabled"><a>Last</a></li>';
	} else {

		$html .= '<li><a href="' . $base_url . $http_query . '&page=' . $total_pages . '">Last</a></li>';
	}

	$html = $html . '</ul>';

	return $html;
}

/**
 * to prevent xss
 */
function xss_clean($string){
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function getIPAddress() {  
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}  

function getIPInfo($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode,
                        "region"         => @$ipdat->geoplugin_regionName,
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}


function getIPAPI($ip = NULL)
{
    $ispQuery = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
    if($ispQuery && $ispQuery['status'] == 'success') {
        return $ispQuery;
    } else {
        return '';
    }
}
function getISP($ip = NULL){
    $ispQuery = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
    if($ispQuery && $ispQuery['status'] == 'success') {
        return $ispQuery['isp'];
    } else {
        return '';
    }
}

function getCity($ip = NULL){
    $ispQuery = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
    if($ispQuery && $ispQuery['status'] == 'success') {
        return $ispQuery['city'];
    } else {
        return '';
    }
}

function getRegion($ip = NULL){
    $ispQuery = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
    if($ispQuery && $ispQuery['status'] == 'success') {
        return $ispQuery['region'];
    } else {
        return '';
    }
}

function getZipcode($ip = NULL){
    $ispQuery = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
    if($ispQuery && $ispQuery['status'] == 'success') {
        return $ispQuery['zip'];
    } else {
        return '';
    }
}

function getOS() { 

	$user_agent = $_SERVER['HTTP_USER_AGENT'];

    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {

	$user_agent = $_SERVER['HTTP_USER_AGENT'];

    $browser        = "Unknown Browser";

    $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                     );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}

function checkBlockedUser($username = ''){
    require_once './config/config.php';
    $db = getDbInstance();
    $blocked_user = $db->where("user_name", $username)->where('blocked', 1)->get('users');

    if($db->count > 0)
        return true;
    return false;
    
}

function checkBlockedGuest($ip = ''){
    require_once './config/config.php';
    $db = getDbInstance();
    $blocked_user = $db->where("ip_addr", $ip)->where('blocked', 1)->get('users');

    if($db->count > 0)
        return true;
    return false;
    
}

function checkBlockedIP(){
    require_once './config/config.php';
    $db = getDbInstance();
    $ip = getIPAddress(); 
    
    $blocked_ip = $db->where("ip", $ip)->get('banned_ip');
    if($db->count > 0)
        return true;
    return false;
}
function ip_in_range( $ip, $range ) {
	if ( strpos( $range, '/' ) == false ) {
		$range .= '/32';
	}
	// $range is in IP/CIDR format eg 127.0.0.1/24
	list( $range, $netmask ) = explode( '/', $range, 2 );
	$range_decimal = ip2long( $range );
	$ip_decimal = ip2long( $ip );
	$wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
	$netmask_decimal = ~ $wildcard_decimal;
	return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
}
function checkIPinRange($cidr, $ip) {
    $range = array();
    $cidr = explode('/', $cidr);
    $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
    $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);

    return ip_in_range($ip, $range);
  }


function checkBlockedCIDR(){
    require_once './config/config.php';
    $db = getDbInstance();
    $ip = getIPAddress(); 
    
    $cidrs = $db->get('banned_cidr');
    foreach($cidrs as $cidr){
        if(ip_in_range($ip, $cidr['cidr']))
            return true;
    }
    return false;
}

function setInterval($func = null, $interval = 0, $times = 0){
    if( ($func == null) || (!function_exists($func)) ){
      throw new Exception('We need a valid function.');
    }
  
    /*
    usleep delays execution by the given number of microseconds.
    JavaScript setInterval uses milliseconds. microsecond = one 
    millionth of a second. millisecond = 1/1000 of a second.
    Multiplying $interval by 1000 to mimic JS.
    */
  
    $seconds = $interval * 1000;
    /*
    If $times > 0, we will execute the number of times specified.
    Otherwise, we will execute until the client aborts the script.
    */
  
    if($times > 0){
      
      $i = 0;
      
      while($i < $times){
          call_user_func($func);
          $i++;
          usleep( $seconds );
      }
    } else {
      
      while(true){
          call_user_func($func); // Call the function you've defined.
          usleep( $seconds );
      }
    }
  }
  
  function doit(){
    $db = getDbInstance();
    
    if($_SESSION['current_id'] != -1)
    {
        $d = new DateTime();
        $time_spent = $_SESSION['begin_time']->diff($d)->format('%h:%i:%s');
        $update_user = array(
            'time_spent'=> $time_spent
        );
        $db->where('id',$_SESSION['current_id'])->update('users', $update_user);
    }
  }

?>