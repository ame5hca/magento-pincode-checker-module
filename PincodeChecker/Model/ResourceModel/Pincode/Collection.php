<?php

namespace AmeshExtensions\PincodeChecker\Model\ResourceModel\Pincode;

use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use AmeshExtensions\PincodeChecker\Model\Pincode as PincodeModel;
use AmeshExtensions\PincodeChecker\Model\ResourceModel\Pincode as PincodeResourceModel;

/**
 * Pincode collection
 *
 * @api
 * @author AmeshExtensions
 * @since 100.0.2
 */
class Collection extends AbstractCollection
{
    /**
     * Id field name
     *
     * @var string
     */
    protected $_idFieldName = PincodeInterface::ENTITY_ID;

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'pincode_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'pincode_collection';

    /**
     * Model initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            PincodeModel::class,
            PincodeResourceModel::class
        );
    }
}
