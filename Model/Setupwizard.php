<?php

namespace Excellence\Slack\Model;

use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Setupwizard extends \Magento\Framework\Model\AbstractModel {

    const CACHE_TAG = 'excellence_slack_setupwizard';

    protected $_fileFactory;

    public function __construct(
    \Magento\Framework\Model\Context $context, 
    \Magento\Framework\Registry $registry,
    \Excellence\Slack\Model\ResourceModel\Setupwizard $resource, 
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigObject,
    \Excellence\Slack\Model\ResourceModel\Setupwizard\Collection $resourceCollection, 
    \Magento\Config\Model\ResourceModel\Config $resourceConfig,
    \Excellence\Slack\Model\ChannelFactory $channelFactory, 
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    FileFactory $fileFactory,
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \Magento\Framework\Filesystem $filesystem,
     \Excellence\Slack\Model\SlackFactory $slackFactory,        
    \Psr\Log\LoggerInterface $logger, array $data = []
    ) {
        $this->_resourceConfig = $resourceConfig;
        $this->logger = $logger;
        $this->scopeConfigObject = $scopeConfigObject;
        $this->channelFactory = $channelFactory;
        $this->_storeManager = $storeManager;
        $this->_fileFactory = $fileFactory;
        $this->_filesystem = $filesystem;
        $this->_objectManager= $objectManager;
        $this->slackFactory = $slackFactory;
        parent::__construct(
                $context, $registry, $resource, $resourceCollection, $data
        );
    }

    public function createPdf($orderId, $id, $incrementId, $type, $pdfName) {
       $channelModel = $this->channelFactory->create();
        if ($active) {
            $modelid = $channelModel->getCollection()->addFieldToFilter('order_id', $orderId)->getFirstItem()->getData();
          
            if (!empty($modelid)) {
                $channelId = $modelid['channel_id'];
                if (trim($type) == 'creditmemo') {
                    $pdfUrl = $this->getCreditmemoPdf($id);
                } elseif (trim($type) == 'shipment') {
                    $pdfUrl = $this->getShipmentPdf($id);
                } elseif (trim($type) == 'invoice') {
                  $pdfUrl = $this->getInvoicePdf($id);
                }
                

                $value['text'] = $pdfName . " generated for #" . $incrementId . "\n" . $pdfUrl;
             
                $value['username'] = $pdfName;
                $value['channel'] = $channelId;
                $this->slackFactory->create()->createPost($value);
             
            }
        }
       
    }

    public function getInvoicePdf($invoiceId, $download = false) {

       $baseurl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'invoice';
      
        if ($invoiceId) {
           $invoice = $this->_objectManager->get('Magento\Sales\Model\Order\Invoice')->load($invoiceId);
           if ($invoice) {
               $pdf = $this->_objectManager->get(
                   'Magento\Sales\Model\Order\Pdf\Invoice'
               )->getPdf(
                   [$invoice]
               );
               
                if (!file_exists($baseurl)) {
                $mediaDir = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
                $invoiceDir = '/invoice';
                $mediaDir->create($invoiceDir);
            }
            $myModuleDir = BP . '/pub/media/invoice/'.$invoiceId.'invoice.pdf';
            file_put_contents($myModuleDir , $pdf->render());
           $pdfUrl = $baseurl .'/'. $invoiceId.'invoice.pdf';
            return $pdfUrl;
           }
       }
    }

    public function getShipmentPdf($shipmentId, $download = false) {
     
       $baseurl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'shipment';
        if ($shipmentId) {
           $shipment = $this->_objectManager->get('Magento\Sales\Model\Order\Shipment')->load($shipmentId);
         
           if ($shipment) {
               $pdf = $this->_objectManager->get(
                   'Magento\Sales\Model\Order\Pdf\Shipment'
               )->getPdf(
                   [$shipment]
               );
               
                if (!file_exists($baseurl)) {
                $mediaDir = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
                $invoiceDir = '/shipment';
                $mediaDir->create($invoiceDir);
            }
            
            $myModuleDir = BP . '/pub/media/shipment/'.$shipmentId.'shipment.pdf';
            file_put_contents($myModuleDir , $pdf->render());
             $pdfUrl = $baseurl .'/'. $shipmentId.'shipment.pdf';
             return $pdfUrl;
         
           }
       }
        
   }

     public function getCreditMemoPdf($creditmemoId, $download = false) {
       
       $baseurl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'creditmemo';
       if ($creditmemoId) {
           $creditmemo = $this->_objectManager->get('Magento\Sales\Model\Order\Creditmemo')->load($creditmemoId);
       
           if ($creditmemo) {
               $pdf = $this->_objectManager->get(
                   'Magento\Sales\Model\Order\Pdf\Creditmemo'
               )->getPdf(
                   [$creditmemo]
               );
               
                if (!file_exists($baseurl)) {
                $mediaDir = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
                $invoiceDir = '/creditmemo';
                $mediaDir->create($invoiceDir);
            }
            
            $myModuleDir = BP . '/pub/media/creditmemo/'.$creditmemoId.'creditmemo.pdf';
            file_put_contents($myModuleDir , $pdf->render());
            $pdfUrl = $baseurl .'/'. $creditmemoId.'creditmemo.pdf';
            return $pdfUrl;
           }
       } 
        
    }
    
    public function checkActiveEnable() {

        $is_enable = $this->scopeConfigObject->getValue('slack/advance/enable');
        $isActive = $this->scopeConfigObject->getValue('slack/setting/setupdone');
        $return = (($isActive == 'Yes') && ($is_enable == 1)) ? true : false;
        return $return;
    }

    protected function _construct() {
        $this->_init('Excellence\Slack\Model\ResourceModel\Setupwizard');
    }

    public function getIdentities() {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /*
     * setupDone for setupwizard configuration 
     * @param  array of values of all form files (associative array) 
     */

    public function setupDone($data) {
        /*
         * save checkbox value in store config table (core_config_data)
         */
       
        if (!empty($data['createchannel'])) {
            $this->_resourceConfig->saveConfig('slack/slack/createchannel', $data['createchannel'], \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        } else {
            $this->_resourceConfig->saveConfig('slack/slack/createchannel', 0, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        };
        /*
         * tirm this array remove extra value like  [form_key] => i0Th8G3UYh25c1R8
         */
        if (array_key_exists('key', $data)) {
            array_shift($data);
        }
        if (array_key_exists('form_key', $data)) {
            array_shift($data);
        }
        if (array_key_exists('createchannel', $data)) {
            array_pop($data);
        }

        $ass_array = array();
        /*
         * convert array to multidimation array of two value , key (order_stat) and value (trello_list_id)
         */
        $i = 0;
        foreach ($data as $key => $value) {
            $ass_array[$i]['order_state'] = $key;
            $ass_array[$i]['slack_channel_id'] = $value;
            $i++;
        }

        $setup_data = $this->getCollection();
        $order_item = $setup_data->getData();

        /*
         * get the collection and check exist record or not if exist the update record otherwise insert
         */
        $k = 0;
        if ($order_item) {
            if (sizeof($order_item) != sizeof($ass_array)) {
                array_shift($order_item);
            }

            foreach ($order_item as $item) {
                $order_state = $item['order_state'];
                $id = $item['id'];

                $ass = $ass_array[$k];
                $this->load($id)->addData($ass);
                $this->save();
                $k++;
            }
        } else {
            foreach ($ass_array as $ass) {
                $this->setData($ass);
                $this->save();
            }
        }


        /*
         * set value setupdone (in core_config_data table ) Yes after setupwizard done
         */
        $this->_resourceConfig->saveConfig('slack/setting/setupdone', 'Yes', \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
    }

    public function getCreateChannel() {
        $value = $this->scopeConfigObject->getValue('slack/notification/channel');
        return $value;
    }

    public function getToken() {
        $value = $this->scopeConfigObject->getValue('slack/setting/token');
        return $value;
    }

    public function saveTokenClientId($data) {

        $token = $data['response_token']['access_token'];
        $clientId = $data['client_id'];
        $secretKey = $data['client_secret'];
        $team_name = $data['response_token']['team_name'];
        $team_id = $data['response_token']['team_id'];
        $channel = $data['response_token']['incoming_webhook']['channel'];
        $channel_id = $data['response_token']['incoming_webhook']['channel_id'];
        $incoming_webhook_url = $data['response_token']['incoming_webhook']['url'];

        $this->_resourceConfig->saveConfig('slack/setting/token', $token, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/setting/clientid', $clientId, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/setting/secretkey', $secretKey, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/setting/teamname', $team_name, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/setting/teamid', $team_id, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/setting/channel', $channel, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/setting/channeid', $channel_id, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/setting/incomingwebhookurl', $incoming_webhook_url, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        return true;
    }

    public function setupRemove() {
        $this->_resourceConfig->saveConfig('slack/setting/token', '', \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/setting/setupdone', 'No', \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $this->_resourceConfig->saveConfig('slack/slack/createchannel', 0, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        $collection = $this->getCollection();
        foreach ($collection as $item) {
            $item->delete();
        }

        return true;
    }

}

