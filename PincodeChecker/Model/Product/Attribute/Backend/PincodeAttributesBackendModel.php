<?php

/**
 * Catalog product pincode attribute backend model
 */
namespace AmeshExtensions\PincodeChecker\Model\Product\Attribute\Backend;

use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;

class PincodeAttributesBackendModel extends ArrayBackend
{
    /**
     * Set pincode attributes value
     *
     * @param \Magento\Catalog\Model\Product $object
     * @return $this
     */
    public function afterLoad($object)
    {
        $object->setData($this->getAttribute()->getAttributeCode(), $this->getProductAttributeValue($object));
        return $this;
    }

    protected function getProductAttributeValue($product)
    {
        $attributeValue = $product->getResource()->getAttributeRawValue(
            $product->getId(),
            $this->getAttribute()->getAttributeCode(),
            0
        );
        if (is_array($attributeValue)) {
            return $attributeValue;
        }
        return explode(",", $attributeValue);
    }
}
