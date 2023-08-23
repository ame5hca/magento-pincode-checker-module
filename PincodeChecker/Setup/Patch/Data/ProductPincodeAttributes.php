<?php

namespace AmeshExtensions\PincodeChecker\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use AmeshExtensions\PincodeChecker\Model\Product\Attribute\Backend\PincodeAttributesBackendModel;
use AmeshExtensions\PincodeChecker\Block\Adminhtml\Product\Helper\Form\PincodeDivisionExcluded as InputRenderer;
use Magento\Catalog\Model\Product;
use Zend_Validate_Exception;

/**
 * Class ProductPincodeAttributes
 *
 * @package AmeshExtensions\PincodeChecker\Setup\Patch\Data
 */
class ProductPincodeAttributes implements DataPatchInterface
{
    /**
     * Exclude pincode by division attribute code
     */
    const EXCLUDE_PINCODE_BY_DIVISION_ATTRIBUTE_CODE = 'pincode_division_excluded';

    /**
     * Exclude pincode by district attribute code
     */
    const EXCLUDE_PINCODE_BY_DISTRICT_ATTRIBUTE_CODE = 'pincode_district_excluded';

    /**
     * Exclude pincode by state attribute code
     */
    const EXCLUDE_PINCODE_BY_STATE_ATTRIBUTE_CODE = 'pincode_state_excluded';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * ProductPincodeAttributes constructor.
     *
     * @param  ModuleDataSetupInterface $moduleDataSetup
     * @param  CategorySetupFactory     $categorySetupFactory
     * @return void
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * Attribute create function.
     *
     * @return DataPatchInterface|void
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply()
    {
        $eavSetup = $this->categorySetupFactory->create(
            ['setup' => $this->moduleDataSetup]
        );
        $eavSetup->addAttribute(
            Product::ENTITY,
            self::EXCLUDE_PINCODE_BY_DIVISION_ATTRIBUTE_CODE,
            [
                'type' => 'varchar',
                'frontend' => '',
                'input' => 'text',
                'label' => 'Exclude Pincode By Division',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'backend' => PincodeAttributesBackendModel::class,
                'required' => false,
                'visible' => true,
                'group' => 'General',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'user_defined' => true,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
            ]
        );
        $eavSetup->addAttribute(
            Product::ENTITY,
            self::EXCLUDE_PINCODE_BY_DISTRICT_ATTRIBUTE_CODE,
            [
                'type' => 'varchar',
                'frontend' => '',
                'input' => 'text',
                'label' => 'Exclude Pincode By District',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'backend' => PincodeAttributesBackendModel::class,
                'required' => false,
                'visible' => true,
                'group' => 'General',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'user_defined' => true,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
        $eavSetup->addAttribute(
            Product::ENTITY,
            self::EXCLUDE_PINCODE_BY_STATE_ATTRIBUTE_CODE,
            [
                'type' => 'varchar',
                'frontend' => '',
                'input' => 'text',
                'label' => 'Exclude Pincode By State',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'backend' => PincodeAttributesBackendModel::class,
                'required' => false,
                'visible' => true,
                'group' => 'General',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'user_defined' => true,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
    }

    /**
     * Get the dependencies.
     *
     * @return array|string[]
     */
    public static function getDependencies()
    {
        /**
         * This is dependency to another patch. Dependency should be applied first
         * One patch can have few dependencies
         * Patches do not have versions, so if in old approach with Install/Ugrade data scripts you used
         * versions, right now you need to point from patch with higher version to patch with lower version
         * But please, note, that some of your patches can be independent and can be installed in any sequence
         * So use dependencies only if this important for you
         */
        return [];
    }

    /**
     * Get the aliases.
     *
     * @return array|string[]
     */
    public function getAliases()
    {
        /**
         * This internal Magento method, that means that some patches with time can change their names,
         * but changing name should not affect installation process, that's why if we will change name of the patch
         * we will add alias here
         */
        return [];
    }
}
