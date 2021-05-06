<?php
 
namespace Excellence\Slack\Controller\Adminhtml\Slack;
 
class Setupwizard extends \Magento\Backend\App\Action
{
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
      $resultPage = $this->resultPageFactory->create();
      return $resultPage;
    }
}
