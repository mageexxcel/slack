<?php
 
namespace Excellence\Slack\Controller\Adminhtml\Slack;

class Savesetupwizard extends \Magento\Backend\App\Action 
{
     public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Excellence\Slack\Model\SetupwizardFactory $setupwizardFactory , 
        \Magento\Framework\App\Request\Http $request ,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory    
    ) {
        $this->request = $request;
        $this->_setupwizardFactory = $setupwizardFactory;  
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
     }
   public function execute()
    {  
       $data = $this->request->getParams();
       $model= $this->_setupwizardFactory->create();
       $message=$model->setupDone($data);
       $this->messageManager->addSuccess(__('Your Done With Setup!'));
       $this->_redirect('*/slack/setupwizard');
    }
    
}
