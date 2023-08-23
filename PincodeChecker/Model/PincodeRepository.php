<?php

namespace AmeshExtensions\PincodeChecker\Model;

use AmeshExtensions\PincodeChecker\Api\PincodeRepositoryInterface;
use AmeshExtensions\PincodeChecker\Model\Spi\PincodeResourceInterface;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterfaceFactory;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeSearchResultInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PincodeRepository implements PincodeRepositoryInterface
{
    /**
     * @var PincodeResourceInterface
     */
    private $pincodeResource;

    /**
     * @var PincodeInterfaceFactory
     */
    private $pincodeFactory;

    /**
     * @var PincodeSearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        PincodeResourceInterface $pincodeResource,
        PincodeInterfaceFactory $pincodeFactory,
        PincodeSearchResultInterfaceFactory $searchResultFactory,
        CollectionProcessorInterface $collectionProcessor       
    ) {
        $this->pincodeResource = $pincodeResource;
        $this->pincodeFactory = $pincodeFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionProcessor = $collectionProcessor;                
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResult = $this->searchResultFactory->create();
        $this->collectionProcessor->process($searchCriteria, $searchResult);
        $searchResult->setSearchCriteria($searchCriteria);
        return $searchResult;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $entity = $this->pincodeFactory->create();
        $this->pincodeResource->load($entity, $id);
        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function delete(PincodeInterface $entity)
    {
        try {
            $this->pincodeResource->delete($entity);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete the pincode.'), $e);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function save(PincodeInterface $entity)
    {
        try {
            $this->pincodeResource->save($entity);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save the pincode.'), $e);
        }

        return $entity;
    }
}
