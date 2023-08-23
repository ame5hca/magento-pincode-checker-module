<?php

namespace AmeshExtensions\PincodeChecker\Controller\Adminhtml\PincodeChecker;

use AmeshExtensions\PincodeChecker\Controller\Adminhtml\PincodeChecker;
use AmeshExtensions\PincodeChecker\Api\PincodeRepositoryInterface;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterfaceFactory;

class Edit extends PincodeChecker
{
    protected $resultPageFactory;

    protected $pincodeRepository;

    protected $pincodeModelFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        PincodeRepositoryInterface $pincodeRepository,
        PincodeInterfaceFactory $pincodeModelFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->pincodeRepository = $pincodeRepository;
        $this->pincodeModelFactory = $pincodeModelFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $pincode = $this->pincodeModelFactory->create();
        // 2. Initial checking
        if ($id) {
            $pincode = $this->pincodeRepository->get($id);
            if (!$pincode->getId()) {
                $this->messageManager->addError(__('This Pincode no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('ameshextensions_pincode', $pincode);
        
        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Pincode') : __('New Pincode'),
            $id ? __('Edit Pincode') : __('New Pincode')
        );
        $pageTitle = ($pincode !=null && $pincode->getId()) ? __('Edit Pincode #').$pincode->getId() : __('New Pincode');
        $resultPage->getConfig()->getTitle()->prepend(__('Pincodes'));
        $resultPage->getConfig()->getTitle()->prepend($pageTitle);
        return $resultPage;
    }
}
