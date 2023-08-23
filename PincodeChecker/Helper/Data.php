<?php

namespace AmeshExtensions\PincodeChecker\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface;
use AmeshExtensions\PincodeChecker\Setup\Patch\Data\ProductPincodeAttributes;

class Data extends AbstractHelper
{
    /**
     * @var \Prince\PincodeChecker\Model\Pincode\CollectionFactory
     */
    protected $pincodeCollection;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $productFactory;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\ProductFactory $product
     * @param \AmeshExtensions\PincodeChecker\Model\ResourceModel\Pincode\CollectionFactory $pincodeCollection
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \AmeshExtensions\PincodeChecker\Model\ResourceModel\Pincode\CollectionFactory $pincodeCollection,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->pincodeCollection = $pincodeCollection;
        $this->productFactory = $productFactory;
        $this->resultFactory = $resultFactory;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Get collection of pincode
     */
    public function getCollection()
    {
        return $this->pincodeCollection->create();
    }

    /**
     * Get pincode status
     */
    public function getPincodeStatus($pincode)
    {
        $collection = $this->getCollection();
        $collection->addFieldToFilter(
            PincodeInterface::PINCODE,
            array('eq' => $pincode)
        );
        $collection->addFieldToFilter(
            'status',
            array('eq' => 1)
        );

        if($collection->getData()){
            return true;
        }else{
            return false;
        }

    }

    /**
     * Get pincode status by product
     */
    public function checkProductIsAvailableToPincode($id, $pincode)
    {
        $product = $this->productFactory->create()->load($id);
        $divisionsExcluded = $product->getData(
            ProductPincodeAttributes::EXCLUDE_PINCODE_BY_DIVISION_ATTRIBUTE_CODE
        );
        $districtsExcluded = $product->getData(
            ProductPincodeAttributes::EXCLUDE_PINCODE_BY_DISTRICT_ATTRIBUTE_CODE
        );
        $statesExcluded = $product->getData(
            ProductPincodeAttributes::EXCLUDE_PINCODE_BY_STATE_ATTRIBUTE_CODE
        );
        $collection = $this->getCollection();
        $collection->addFieldToFilter(PincodeInterface::PINCODE, ['eq' => $pincode]);
        $collection->addFieldToFilter('status', ['eq' => 1]);


        /** setting null if first array empty */
        if(isset($divisionsExcluded[0]) && empty($divisionsExcluded[0])){
            $divisionsExcluded=null;
        }

        if(isset($districtsExcluded[0]) && empty($districtsExcluded[0])){
            $districtsExcluded=null;
        }

        if(isset($statesExcluded[0]) && empty($statesExcluded[0])){
            $statesExcluded=null;
        }

        if (!empty($divisionsExcluded)) {
            $collection->addFieldToFilter(
                PincodeInterface::DIVISION, ['nin' => $divisionsExcluded]
            );
        }
        if (!empty($districtsExcluded)) {
            $collection->addFieldToFilter(
                PincodeInterface::DISTRICT, ['nin' => $districtsExcluded]
            );
        }
        if (!empty($statesExcluded)) {
            $collection->addFieldToFilter(
                PincodeInterface::STATE, ['nin' => $statesExcluded]
            );
        }
        if ($collection->getSize() > 0) {
            return true;
        }
        return false;

    }

    /**
     * Get pincode status message
     */
    public function getMessage($status)
    {
        if($status){
            $message = $this->getSuccessMessage();
        }else{
            $message = $this->getFailMessage();
        }

        return $message;
    }

    /**
     * Get redirect url
     */
    public function getRedirect()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    /**
     * Check module enable
     */
    public function getIsEnable()
    {
        return $this->scopeConfig->getValue('pincode/general/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get check on addtocart config value
     */
    public function getIsCheckonAddtoCart()
    {
        return $this->scopeConfig->getValue('pincode/general/checkaddtocart', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get success message config value
     */
    public function getSuccessMessage()
    {
        return $this->scopeConfig->getValue('pincode/general/successmessage', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get fail message config value
     */
    public function getFailMessage()
    {
        return $this->scopeConfig->getValue('pincode/general/failmessage', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
