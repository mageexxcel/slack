<?php
 
namespace Excellence\Slack\Controller\Adminhtml\Slack;
use Magento\Framework\App\Config\ScopeConfigInterface;
 
class Savetoken extends \Magento\Backend\App\Action 
{
     protected $_scopePool;
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
       $message=$model->saveTokenClientId($data);
       $result = $this->resultJsonFactory->create();
       return $result->setData(['success' => $message]);
     }
    
}
