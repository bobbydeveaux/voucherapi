<?php

namespace DVO\Entity\Voucher;

use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;
use Davegardnerisme\Cruftflake;

class VoucherGateway
{
    protected $_cf;
    protected $_pool;
    protected $_column_family;

    public function __construct(CruftFlake\CruftFlake $cruftflake)
    {
        $this->_cf            = $cruftflake;
        $this->_pool          = new ConnectionPool('DVO');
        $this->_column_family = new ColumnFamily($this->_pool, 'Vouchers');
    }

    /**
     * Get vouchers
     *
     * @return array
     * @author 
     **/
    public function getAllVouchers()
    {
        $vouchers = iterator_to_array($this->_column_family->get_range(), false);
    
        return $vouchers;
    }

    /**
     * insert voucher
     *
     * @return void
     * @author 
     **/
    public function insertVoucher(\DVO\Entity\Voucher $voucher)
    {
        $val = $this->_cf->generateId();
        $this->_column_family->insert($val, array(
                                        'code'        => $voucher->getCode(),
                                        'description' => $voucher->getDescription()
        ));
    }
}
