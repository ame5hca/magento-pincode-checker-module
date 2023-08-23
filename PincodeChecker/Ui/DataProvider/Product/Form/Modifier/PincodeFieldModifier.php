<?php

declare(strict_types=1);

namespace AmeshExtensions\PincodeChecker\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\ArrayManager;
use AmeshExtensions\PincodeChecker\Setup\Patch\Data\ProductPincodeAttributes;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use AmeshExtensions\PincodeChecker\Model\Pincode as PincodeModel;
use Magento\Framework\App\Cache\Type\Block as BlockCache;
use AmeshExtensions\PincodeChecker\Model\ResourceModel\Pincode\CollectionFactory as PincodeCollectionFactory;

/**
 * Different pincode fields modifier
 *
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 101.0.0
 */
class PincodeFieldModifier extends AbstractModifier
{
    /**
     * Pincode divisions cache id
     */
    const PINCODE_DIVISIONS_CACHE_ID = 'PINCODE_DIVISIONS';

    /**
     * Pincode districts cache id
     */
    const PINCODE_DISTRICTS_CACHE_ID = 'PINCODE_DISTRICTS';

    /**
     * Pincode states cache id
     */
    const PINCODE_STATES_CACHE_ID = 'PINCODE_STATES';

    protected $pincodeCollectionFactory;

    /**
     * @var LocatorInterface
     * @since 101.0.0
     */
    protected $locator;

    /**
     * @var ArrayManager
     * @since 101.0.0
     */
    protected $arrayManager;

    /**
     * @var CacheInterface
     */
    private $cacheManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param LocatorInterface $locator    
     * @param ArrayManager $arrayManager
     * @param SerializerInterface $serializer 
     */
    public function __construct(
        LocatorInterface $locator,        
        ArrayManager $arrayManager,
        PincodeCollectionFactory $pincodeCollectionFactory,
        SerializerInterface $serializer = null    
    ) {
        $this->locator = $locator;        
        $this->pincodeCollectionFactory = $pincodeCollectionFactory;        
        $this->arrayManager = $arrayManager;
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(SerializerInterface::class);        
    }

    /**
     * Retrieve cache interface
     *
     * @return CacheInterface
     * @deprecated 101.0.3
     */
    private function getCacheManager(): CacheInterface
    {
        if (!$this->cacheManager) {
            $this->cacheManager = ObjectManager::getInstance()
                ->get(CacheInterface::class);
        }
        return $this->cacheManager;
    }

    /**
     * @inheritdoc
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        $meta = $this->customizePincodeDivisionExcludeField($meta);
        $meta = $this->customizePincodeDistrictExcludeField($meta);
        $meta = $this->customizePincodeStateExcludeField($meta);

        return $meta;
    }


    /**
     * @inheritdoc
     * @since 101.0.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Customize exclude pincode by distict field
     *
     * @param array $meta
     * @return array
     * @throws LocalizedException
     * @since 101.0.0
     */
    protected function customizePincodeDivisionExcludeField(array $meta)
    {
        $fieldCode = ProductPincodeAttributes::EXCLUDE_PINCODE_BY_DIVISION_ATTRIBUTE_CODE;
        return $this->customizeField($meta, $fieldCode);
    }

    /**
     * Customize exclude pincode by distict field
     *
     * @param array $meta
     * @return array
     * @throws LocalizedException
     * @since 101.0.0
     */
    protected function customizePincodeDistrictExcludeField(array $meta)
    {
        $fieldCode = ProductPincodeAttributes::EXCLUDE_PINCODE_BY_DISTRICT_ATTRIBUTE_CODE;
        return $this->customizeField($meta, $fieldCode);
    }

    /**
     * Customize exclude pincode by state field
     *
     * @param array $meta
     * @return array
     * @throws LocalizedException
     * @since 101.0.0
     */
    protected function customizePincodeStateExcludeField(array $meta)
    {
        $fieldCode = ProductPincodeAttributes::EXCLUDE_PINCODE_BY_STATE_ATTRIBUTE_CODE;
        return $this->customizeField($meta, $fieldCode);
    }

    private function customizeField(array $meta, $fieldCode)
    {
        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');
        $fieldIsDisabled = $this->locator->getProduct()->isLockedAttribute($fieldCode);

        if (!$elementPath) {
            return $meta;
        }
        $value = $this->prepareFieldLayoutData($fieldCode, $fieldIsDisabled);        
        $meta = $this->arrayManager->merge($containerPath, $meta, $value);

        return $meta;
    }

    private function prepareFieldLayoutData($fieldCode, $fieldIsDisabled)
    {
        $layoutData = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => false,
                        'required' => false,
                        'dataScope' => '',
                        'breakLine' => false,
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'component' => 'Magento_Ui/js/form/components/group',
                        'disabled' => $this->locator->getProduct()->isLockedAttribute($fieldCode),
                    ],
                ],
            ],
            'children' => [
                $fieldCode => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => 'field',
                                'component' => 'Magento_Ui/js/form/element/ui-select',
                                'filterOptions' => true,
                                'chipsEnabled' => true,
                                'disableLabel' => true,
                                'levelsVisibility' => '1',
                                'disabled' => $fieldIsDisabled,
                                'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                                'options' => $this->getPincodeAttributeOptions($fieldCode),                                
                                'config' => [
                                    'dataScope' => $fieldCode,
                                    'sortOrder' => 10,
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];

        return $layoutData;
    }

    private function getPincodeAttributeOptions($fieldCode)
    {
        switch ($fieldCode) {
            case ProductPincodeAttributes::EXCLUDE_PINCODE_BY_DIVISION_ATTRIBUTE_CODE:
                return $this->getPincodeDivisionsList();
                break;
            case ProductPincodeAttributes::EXCLUDE_PINCODE_BY_DISTRICT_ATTRIBUTE_CODE:
                return $this->getPincodeDistrictsList();
                break;
            case ProductPincodeAttributes::EXCLUDE_PINCODE_BY_STATE_ATTRIBUTE_CODE:
                return $this->getPincodeStatesList();
                break;            
            default:
                return [];
                break;
        }
    }

    /**
     * Retrieve pincode division list
     *
     * @param string|null $filter
     * @return array
     * @throws LocalizedException
     * @since 101.0.0
     */
    protected function getPincodeDivisionsList($filter = null)
    {
        $storeId = (int) $this->locator->getStore()->getId();

        $cachedDivisionsList = $this->getCacheManager()
            ->load($this->getPincodeDivisionsListCacheId($storeId, (string) $filter));
        if (!empty($cachedDivisionsList)) {
            return $this->serializer->unserialize($cachedDivisionsList);
        }

        $divisionsList = $this->retrievePincodeData('division');

        $this->getCacheManager()->save(
            $this->serializer->serialize($divisionsList),
            $this->getPincodeDivisionsListCacheId($storeId, (string) $filter),
            [
                PincodeModel::CACHE_TAG,
                BlockCache::CACHE_TAG
            ]
        );

        return $divisionsList;
    }

    /**
     * Retrieve pincode district list
     *
     * @param string|null $filter
     * @return array
     * @throws LocalizedException
     * @since 101.0.0
     */
    protected function getPincodeDistrictsList($filter = null)
    {
        $storeId = (int) $this->locator->getStore()->getId();

        $cachedDistrictsList = $this->getCacheManager()
            ->load($this->getPincodeDistrictsListCacheId($storeId, (string) $filter));
        if (!empty($cachedDistrictsList)) {
            return $this->serializer->unserialize($cachedDistrictsList);
        }

        $districtsList = $this->retrievePincodeData('district');

        $this->getCacheManager()->save(
            $this->serializer->serialize($districtsList),
            $this->getPincodeDistrictsListCacheId($storeId, (string) $filter),
            [
                PincodeModel::CACHE_TAG,
                BlockCache::CACHE_TAG
            ]
        );

        return $districtsList;
    }

    /**
     * Retrieve pincode states list
     *
     * @param string|null $filter
     * @return array
     * @throws LocalizedException
     * @since 101.0.0
     */
    protected function getPincodeStatesList($filter = null)
    {
        $storeId = (int) $this->locator->getStore()->getId();

        $cachedStatesList = $this->getCacheManager()
            ->load($this->getPincodeStatesListCacheId($storeId, (string) $filter));
        if (!empty($cachedStatesList)) {
            return $this->serializer->unserialize($cachedStatesList);
        }

        $statesList = $this->retrievePincodeData('state');

        $this->getCacheManager()->save(
            $this->serializer->serialize($statesList),
            $this->getPincodeStatesListCacheId($storeId, (string) $filter),
            [
                PincodeModel::CACHE_TAG,
                BlockCache::CACHE_TAG
            ]
        );

        return $statesList;
    }

    /**
     * Get cache id for pincode divisions list.
     *
     * @param int $storeId
     * @param string $filter
     * @return string
     */
    private function getPincodeDivisionsListCacheId(int $storeId, string $filter = '') : string
    {
        return self::PINCODE_DIVISIONS_CACHE_ID
            . '_' . (string) $storeId
            . '_' . $filter;
    }

    /**
     * Get cache id for pincode districts list.
     *
     * @param int $storeId
     * @param string $filter
     * @return string
     */
    private function getPincodeDistrictsListCacheId(int $storeId, string $filter = '') : string
    {
        return self::PINCODE_DISTRICTS_CACHE_ID
            . '_' . (string) $storeId
            . '_' . $filter;
    }

    /**
     * Get cache id for pincode states list.
     *
     * @param int $storeId
     * @param string $filter
     * @return string
     */
    private function getPincodeStatesListCacheId(int $storeId, string $filter = '') : string
    {
        return self::PINCODE_STATES_CACHE_ID
            . '_' . (string) $storeId
            . '_' . $filter;
    }

    /**
     * Retrieve tree of categories with attributes.
     *
     * @param int $storeId
     * @param array $shownCategoriesIds
     * @return array|null
     * @throws LocalizedException
     */
    private function retrievePincodeData($attribute = null) : ?array
    {
        $resultData = [];
        $collection = $this->pincodeCollectionFactory->create();
        $collection->addFieldToSelect($attribute);
        $collection->getSelect()->group($attribute);
        foreach ($collection as $item) {
            $resultData[] = [
                'label' => $item->getData($attribute), 
                'value' => $item->getData($attribute)
            ];
        }

        return $resultData;
    }
}
