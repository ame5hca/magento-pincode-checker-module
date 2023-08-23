<?php

namespace AmeshExtensions\PincodeChecker\Api;

/**
 * Pincode repository interface.
 *
 * Pincodes are used for the checking the delivery of the product. 
 * @api
 * @since 100.0.2
 */
interface PincodeRepositoryInterface
{
    /**
     * List all the pincodes that matches the search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria The search criteria.
     * @return \AmeshExtensions\PincodeChecker\Api\Data\PincodeSearchResultInterface Pincode search result interface.
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Loads a specific pincode data.
     *
     * @param int $id Pincode id.
     * @return \AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface Pincode interface.
     */
    public function get($id);

    /**
     * Deletes a specified pincode data.
     *
     * @param \AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface $entity The pincode.
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface $entity);

    /**
     * Performs persist operations for a specified pincode.
     *
     * @param \AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface $entity The pincode.
     * @return \AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface pincode interface.
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface $entity);
}
