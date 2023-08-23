<?php

namespace AmeshExtensions\PincodeChecker\Api\Data;

/**
 * Pincode search result interface.
 *
 * Pincode is used to check the delivery of products.
 * @api
 * @since 100.0.2
 */
interface PincodeSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Gets collection items.
     *
     * @return \AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface[] Array of collection items.
     */
    public function getItems();

    /**
     * Sets collection items.
     *
     * @param \AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
