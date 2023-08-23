<?php

namespace AmeshExtensions\PincodeChecker\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Pincode interface.
 *
 * Pincodes are used for the checking the delivery of the product. 
 * @api
 * @since 100.0.2
 */
interface PincodeInterface extends ExtensibleDataInterface
{    
    /*
     * Table name.
     */
    const TABLE = 'ameshextensions_pincodes';

    /*
     * ID.
     */
    const ENTITY_ID = 'id';

    /*
     * Pincode.
     */
    const PINCODE = 'pincode';

    /*
     * Division Name.
     */
    const DIVISION = 'division';

    /*
     * District Name.
     */
    const DISTRICT = 'district';

    /*
     * State Name.
     */
    const STATE = 'state';

    /*
     * Pincode Status.
     */
    const STATUS = 'status';

    /*
     * Pincode Status.
     */
    const CREATED_AT = 'created_at';


    /**
     * Get pincode record id.
     *
     * @return int ID.
     */
    public function getId();

    /**
     * Set pincode record id.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get pincode.
     *
     * @return string pincode.
     */
    public function getPincode();

    /**
     * Set pincode.
     *
     * @param string $pincode
     * @return $this
     */
    public function setPincode($pincode);

    /**
     * Get pincode division name.
     *
     * @return string division.
     */
    public function getDivision();

    /**
     * Set pincode division.
     *
     * @param string $division
     * @return $this
     */
    public function setDivision($division);

    /**
     * Get pincode district name.
     *
     * @return string district.
     */
    public function getDistrict();

    /**
     * Set pincode district.
     *
     * @param string $district
     * @return $this
     */
    public function setDistrict($district);

    /**
     * Get pincode state.
     *
     * @return string state.
     */
    public function getState();

    /**
     * Set pincode state.
     *
     * @param string $state
     * @return $this
     */
    public function setSate($state);

    /**
     * Get pincode status.
     *
     * @return boolean status.
     */
    public function getStatus();

    /**
     * Set pincode state.
     *
     * @param boolean $state
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get created at.
     *
     * @return string created_at.
     */
    public function getCreatedAt();

    /**
     * Set created at.
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \AmeshExtensions\PincodeChecker\Api\Data\PincodeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \AmeshExtensions\PincodeChecker\Api\Data\PincodeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \AmeshExtensions\PincodeChecker\Api\Data\PincodeExtensionInterface $extensionAttributes
    );
}
