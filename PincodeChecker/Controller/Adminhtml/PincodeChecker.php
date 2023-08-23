<?php

namespace AmeshExtensions\PincodeChecker\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class PincodeChecker extends Action
{

    const ADMIN_RESOURCE = 'AmeshExtensions_PincodeChecker::top_level';

    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu('AmeshExtensions_PincodeChecker::top_level')
            ->addBreadcrumb(__('AmeshExtensions'), __('AmeshExtensions'))
            ->addBreadcrumb(__('Pincode Checker'), __('Pincode Checker'));
        return $resultPage;
    }
}
