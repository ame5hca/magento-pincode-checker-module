<?php

namespace AmeshExtensions\PincodeChecker\Controller\Adminhtml\PincodeChecker;

use Magento\Backend\App\Action;

class MassStatusUpdate extends Action {

    protected $_filter;

    protected $_collectionFactory;
    
    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        \AmeshExtensions\PincodeChecker\Model\ResourceModel\Pincode\CollectionFactory $collectionFactory,
        \Magento\Backend\App\Action\Context $context
        ) {
        $this->_filter            = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute() {
        try{ 
            $statusValue = $this->getRequest()->getParam('status');
            $collection = $this->_filter->getCollection($this->_collectionFactory->create());
            $itemsUpdated = 0;
            foreach ($collection as $item) {
                $item->setStatus($statusValue);
                $item->save();
                $itemsUpdated++;
            }
            $this->messageManager->addSuccess(__('A total of %1 Pincode(s) were updated.', $itemsUpdated));
        }catch(Exception $e){
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('pincodechecker/pincodechecker');
    }
    
     /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('AmeshExtensions_PincodeChecker::view');
    }
}