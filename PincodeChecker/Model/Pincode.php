<?php

namespace AmeshExtensions\PincodeChecker\Model;

use Magento\Framework\Api\AttributeValueFactory;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Pincode extends AbstractExtensibleModel implements PincodeInterface
{
    const CACHE_TAG = 'pincode_c';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );        
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\AmeshExtensions\PincodeChecker\Model\ResourceModel\Pincode::class);
    }

    /**
     * Get pincode.
     *
     * @return string pincode.
     */
    public function getPincode() {
        return $this->getData(PincodeInterface::PINCODE);
    }

    /**
     * Set pincode.
     *
     * @param string $pincode
     * @return $this
     */
    public function setPincode($pincode) {
        return $this->setData(PincodeInterface::PINCODE, $pincode);
    }

    /**
     * Get pincode division name.
     *
     * @return string division.
     */
    public function getDivision(){
        return $this->getData(PincodeInterface::DIVISION);
    }

    /**
     * Set pincode division.
     *
     * @param string $division
     * @return $this
     */
    public function setDivision($division){
        return $this->setData(PincodeInterface::DIVISION, $division);
    }

    /**
     * Get pincode district name.
     *
     * @return string district.
     */
    public function getDistrict(){
        return $this->getData(PincodeInterface::DISTRICT);
    }

    /**
     * Set pincode district.
     *
     * @param string $district
     * @return $this
     */
    public function setDistrict($district) {
        return $this->setData(PincodeInterface::DISTRICT, $district);
    }

    /**
     * Get pincode state.
     *
     * @return string state.
     */
    public function getState(){
        return $this->getData(PincodeInterface::STATE);
    }

    /**
     * Set pincode state.
     *
     * @param string $state
     * @return $this
     */
    public function setSate($state){
        return $this->setData(PincodeInterface::STATE, $state);
    }

    /**
     * Get pincode status.
     *
     * @return boolean status.
     */
    public function getStatus(){
        return $this->getData(PincodeInterface::STATUS);
    }

    /**
     * Set pincode state.
     *
     * @param boolean $state
     * @return $this
     */
    public function setStatus($status){
        return $this->setData(PincodeInterface::STATUS, $status);
    }

    /**
     * Get created at.
     *
     * @return string created_at.
     */
    public function getCreatedAt(){
        return $this->getData(PincodeInterface::CREATED_AT);
    }

    /**
     * Set created at.
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt){
        return $this->setData(PincodeInterface::CREATED_AT, $createdAt);
    }

    /**
     * {@inheritdoc}
     *
     * @return \AmeshExtensions\PincodeChecker\Api\Data\PincodeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     *
     * @param \AmeshExtensions\PincodeChecker\Api\Data\PincodeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \AmeshExtensions\PincodeChecker\Api\Data\PincodeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

}
