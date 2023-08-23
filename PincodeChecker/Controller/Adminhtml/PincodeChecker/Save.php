<?php

namespace AmeshExtensions\PincodeChecker\Controller\Adminhtml\PincodeChecker;

use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action;
use AmeshExtensions\PincodeChecker\Api\PincodeRepositoryInterface;
use AmeshExtensions\PincodeChecker\Api\Data\PincodeInterfaceFactory;
use AmeshExtensions\PincodeChecker\Model\PincodeDataProvider\Api;

class Save extends Action
{
    protected $dataPersistor;

    protected $pincodeRepository;

    protected $pincodeModelFactory;

    protected $pincodeApi;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,        
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        PincodeRepositoryInterface $pincodeRepository,
        PincodeInterfaceFactory $pincodeModelFactory,
        Api $pincodeApi
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->pincodeRepository = $pincodeRepository;
        $this->pincodeModelFactory = $pincodeModelFactory;
        $this->pincodeApi = $pincodeApi;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('id');
            $pincode = $this->pincodeRepository->get($id);            
            if (!$pincode->getId() && $id) {
                $this->messageManager->addError(__('This Pincode no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $pincodeDetails = $this->pincodeApi->getPincodeDetails($data['pincode']);
            $finalData = array_merge($pincodeDetails, $data);
            $pincodeModel = $this->pincodeModelFactory->create();
            $pincodeModel->setData($finalData);
        
            try {
                $this->pincodeRepository->save($pincodeModel);
                $this->messageManager->addSuccess(__('You saved the Pincode.'));
                $this->dataPersistor->clear('ameshextensions_pincode');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $pincode->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Pincode.'));
            }
        
            $this->dataPersistor->set('ameshextensions_pincode', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
