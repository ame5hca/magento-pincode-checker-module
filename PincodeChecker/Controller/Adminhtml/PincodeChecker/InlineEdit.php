<?php

namespace AmeshExtensions\PincodeChecker\Controller\Adminhtml\PincodeChecker;

use Magento\Backend\App\Action;
use AmeshExtensions\PincodeChecker\Api\PincodeRepositoryInterfaceFactory;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterfaceFactory;

class InlineEdit extends Action
{
    protected $jsonFactory;

    protected $pincodeRepositoryFactory;

    protected $pincodeFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        PincodeRepositoryInterfaceFactory $pincodeRepositoryFactory,
        PincodeInterfaceFactory $pincodeFactory
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->pincodeRepositoryFactory = $pincodeRepositoryFactory;
        $this->pincodeFactory = $pincodeFactory;
    }

    /**
     * Inline edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        
        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {                
                foreach (array_keys($postItems) as $modelid) {
                    $pincodeRepository = $this->pincodeRepositoryFactory->create();
                    $pincode = $pincodeRepository->get($modelid);                    
                    try {
                        $pincodeModel = $this->pincodeFactory->create();
                        $pincodeModel->setData(array_merge($pincode->getData(), $postItems[$modelid]));
                        $pincodeRepo = $this->pincodeRepositoryFactory->create();
                        $pincodeRepo->save($pincodeModel);
                    } catch (\Exception $e) {
                        $messages[] = "[Pincode ID: {$modelid}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }
        
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
