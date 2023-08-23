<?php

namespace AmeshExtensions\PincodeChecker\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface;
use AmeshExtensions\PincodeChecker\Model\Spi\PincodeResourceInterface;

class Pincode extends AbstractDb implements PincodeResourceInterface
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'pincode_resource';

    protected function _construct()
    {
        $this->_init(PincodeInterface::TABLE, PincodeInterface::ENTITY_ID);
    }
}
