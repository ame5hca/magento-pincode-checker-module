<?php

namespace AmeshExtensions\PincodeChecker\Model\Import;

use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use Magento\Framework\App\ResourceConnection;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterface;

class Pincode extends AbstractEntity
{
    const PINCODE = 'pincode';

    const TABLE = 'ameshextensions_pincodes';

    const EMPTY_PINCODE_ERROR_CODE = 'EmptyPincode';

    protected $_permanentAttributes = [
        PincodeInterface::PINCODE,        
        PincodeInterface::DIVISION,
        PincodeInterface::DISTRICT,
        PincodeInterface::STATE,
        PincodeInterface::STATUS
    ];

    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;

    protected $groupFactory;

    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
        PincodeInterface::PINCODE,       
        PincodeInterface::DIVISION,
        PincodeInterface::DISTRICT,
        PincodeInterface::STATE,
        PincodeInterface::STATUS
    ];

    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;

    protected $_validators = [];


    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;

    protected $_resource;

    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Customer\Model\GroupFactory $groupFactory        
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;        
        $this->initMessageTemplates();
    }
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return PincodeInterface::PINCODE;
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        $pincode = false;

        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;       
        if (!isset($rowData[PincodeInterface::PINCODE]) || empty($rowData[PincodeInterface::PINCODE])) {
            $this->addRowError(self::EMPTY_PINCODE_ERROR_CODE, $rowNum);
            return false;
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }


    /**
     * Create pincode entry f4rom raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }

        return true;
    }
    /**
     * Save pincode
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }
    /**
     * Replace pincode
     *
     * @return $this
     */
    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }
    /**
     * Deletes pincode data from raw data.
     *
     * @return $this
     */
    public function deleteEntity()
    {
        $listpincode = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowTtile = $rowData[PincodeInterface::PINCODE];
                    $listpincode[] = $rowTtile;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($listpincode) {
            $this->deleteEntityFinish(array_unique($listpincode),PincodeInterface::TABLE);
        }
        return $this;
    }
 /**
     * Save and replace newsletter subscriber
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        $listpincode = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(self::EMPTY_PINCODE_ERROR_CODE, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }
               
                $rowTtile= $rowData[PincodeInterface::PINCODE];
                $listpincode[] = $rowTtile;
                $entityList[$rowTtile][] = [
                  PincodeInterface::PINCODE => $rowData[PincodeInterface::PINCODE],                  
                  PincodeInterface::DIVISION => $rowData[PincodeInterface::DIVISION],
                  PincodeInterface::DISTRICT => $rowData[PincodeInterface::DISTRICT],
                  PincodeInterface::STATE => $rowData[PincodeInterface::STATE],
                  PincodeInterface::STATUS => $rowData[PincodeInterface::STATUS]
                ];
            }
            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                if ($listpincode) {
                    if ($this->deleteEntityFinish(array_unique($listpincode), PincodeInterface::TABLE)) {
                        $this->saveEntityFinish($entityList, PincodeInterface::TABLE);
                    }
                }
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityFinish($entityList, PincodeInterface::TABLE);
            }
        }
        return $this;
    }
    /**
     * Save product prices.
     *
     * @param array $priceData
     * @param string $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData, $table)
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityIn = [];
            foreach ($entityData as $id => $entityRows) {
                    foreach ($entityRows as $row) {
                        $entityIn[] = $row;
                    }
            }
            if ($entityIn) {
                $this->_connection->insertOnDuplicate($tableName, $entityIn,[
                PincodeInterface::PINCODE
            ]);
            }
        }
        return $this;
    }
    protected function deleteEntityFinish(array $listpincode, $table)
    {
        if ($table && $listpincode) {
                try {
                    $this->countItemsDeleted += $this->_connection->delete(
                        $this->_connection->getTableName($table),
                        $this->_connection->quoteInto('pincode IN (?)', implode(",", $listpincode))
                    );
                    return true;
                } catch (\Exception $e) {
                    return false;
                }

        } else {
            return false;
        }
    }

    /**
     * Init Error Messages
     */
    private function initMessageTemplates()
    {
        $this->addMessageTemplate(
            self::EMPTY_PINCODE_ERROR_CODE,
            __('Pincode is empty.')
        );
    }
}