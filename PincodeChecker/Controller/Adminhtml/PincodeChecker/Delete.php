<?php

namespace AmeshExtensions\PincodeChecker\Controller\Adminhtml\PincodeChecker;

use AmeshExtensions\PincodeChecker\Controller\Adminhtml\PincodeChecker;
use AmeshExtensions\PincodeChecker\Api\PincodeRepositoryInterface;

class Delete extends PincodeChecker
{
    protected $pincodeRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        PincodeRepositoryInterface $pincodeRepository
    ) {
        parent::__construct($context, $coreRegistry);
        $this->pincodeRepository = $pincodeRepository;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();        
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $pincode = $this->pincodeRepository->get($id);
                $this->pincodeRepository->delete($pincode);                
                // display success message
                $this->messageManager->addSuccess(__('You have deleted the pincode.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a Pincode to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
