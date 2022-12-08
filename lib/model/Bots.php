<?php
class Bots
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
    }
    
    /**
     * Set friendly columns\' names to order tables\' entries
     */
    public function setOrderingValues()
    {
        $ordering = [
            'id' => 'ID',
            'user_name' => 'User Name',
            'ip_addr' => 'Ip Address',
            'country' => 'Country',
            'isp' => 'ISP',
            'browser' => 'Browser',
            'os_name' => 'Operating System',
            'referer' => 'Referers',
            'visited_page' => 'Visited Page',
            'blocked' => 'Blocked',
            'country_code' => 'Country Code',
            'region' => 'region',
            'city' => 'city',
            'zipcode' => 'zipcode',
            'device' => 'device',
            'datetime' => 'datetime',
            'is_proxy' => 'is_proxy',
            'is_bot' => 'is_bot',
            'user_agent' => 'user_agent',
            'time_spent' => 'time_spent'
        ];

        return $ordering;
    }
}
?>