<?php

namespace Excellence\Slack\Block\Adminhtml;

class Setupwizard extends \Magento\Backend\Block\Widget\Form\Container
{
    
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
       \Excellence\Slack\Model\SetupwizardFactory $setupwizardFactory ,
        \Excellence\Slack\Model\SlackFactory $slackFactory ,     
       \Magento\Sales\Model\Order\Config $statuslists ,
       \Magento\Framework\Controller\ResultFactory  $ResultFactory     
    ) { 
    $this->resultFactory = $ResultFactory;
         $this->statuslist=$statuslists;
         $this->_setupwizardFactory = $setupwizardFactory;  
         $this->slackFactory = $slackFactory;  
         parent::__construct($context);
    }

    protected function _prepareLayout()
    {
     
    }

   public function statusList(){
         return $this->statuslist->getStatuses();
   }
   
   
   public function getCreateChannel(){
        $model= $this->_setupwizardFactory->create();
        return $model->getCreateChannel();
      
   }
   
   public function getToken(){
      $model= $this->_setupwizardFactory->create();
       return  $model->getToken();
   }
   
   public function getActionUrl(){
      return $setupurl=$this->_urlBuilder->getUrl("slack/slack/savesetupwizard/");
   }
   
   public function getSetupCollection(){
        $model= $this->_setupwizardFactory->create();
       return $model->getCollection();
   }

   public function getConfigUrlSlack(){
      return $configurl=$this->_urlBuilder->getUrl("adminhtml/system_config/edit/section/system_config/edit/section/slack");
   }
  
   
}

