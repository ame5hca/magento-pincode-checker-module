<?php

namespace AmeshExtensions\PincodeChecker\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Zend_Validate_Exception;

/**
 * Class CustomerLastCheckedPincodeAttribute
 *
 * @package AmeshExtensions\PincodeChecker\Setup\Patch\Data
 */
class CustomerLastCheckedPincodeAttribute implements DataPatchInterface
{
    /**
     * Customer last checked pincode attribute code
     */
    const CUSTOMER_LAST_CHECKED_PINCODE_ATTRIBUTE_CODE = 'last_checked_pincode';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupfactory;

    /**
     * CustomerMobileNumberAttribute constructor.
     *
     * @param  ModuleDataSetupInterface $moduleDataSetup
     * @param  CustomerSetupFactory     $customerSetupfactory
     * @return void
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupfactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupfactory = $customerSetupfactory;
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
        $customerSetup = $this->customerSetupfactory->create(
            ['setup' => $this->moduleDataSetup]
        );
        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::CUSTOMER_LAST_CHECKED_PINCODE_ATTRIBUTE_CODE,
            [
                'type' => 'varchar',
                'label' => 'Last Checked Pincode',
                'input' => 'text',
                'source' => '',
                'backend' => ArrayBackend::class,
                'required' => false,
                'visible' => true,
                'sort_order' => 100,
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_on_front' => true,
                'unique' => false,
                'system' => false,
                'apply_to' => ''
            ]
        );

        $attribute = $customerSetup->getEavConfig()->getAttribute(
            Customer::ENTITY,
            self::CUSTOMER_LAST_CHECKED_PINCODE_ATTRIBUTE_CODE
        );

        $attribute->setData('used_in_forms', [])
            ->setData("is_used_for_customer_segment", true)
            ->setData("is_system", 0)
            ->setData("is_user_defined", 1)
            ->setData("is_visible", 1)
            ->setData("sort_order", 100);
        $attribute->save();
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
