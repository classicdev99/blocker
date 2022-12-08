<?php
class BannedIps
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
            'ip' => 'IP Address',
            'reason' => 'Reason'
        ];

        return $ordering;
    }
}
?>