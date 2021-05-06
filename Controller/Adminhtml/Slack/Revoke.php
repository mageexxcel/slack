<?php

namespace Excellence\Slack\Controller\Adminhtml\Slack;

class Revoke extends \Magento\Backend\App\Action {

    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Excellence\Slack\Model\SetupwizardFactory $setupwizardFactory
    ) {
        $this->_setupwizardFactory = $setupwizardFactory;
        parent::__construct($context);
    }

    public function execute() {
        $model = $this->_setupwizardFactory->create();
        $model->setupRemove();
        return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
    }

}
