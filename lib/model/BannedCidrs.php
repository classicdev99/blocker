<?php
class BannedCidrs
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
            'cidr' => 'CIDR Format',
            'reason' => 'Reason'
        ];

        return $ordering;
    }
}
?>