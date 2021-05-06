<?php
namespace Excellence\Slack\Observer;
use Magento\Framework\Event\ObserverInterface;


class MyObserver implements ObserverInterface
{  
   
   
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigObject
    
    ) 
    {
        $this->scopeConfigObject = $scopeConfigObject;
    }
   
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $channelid = $this->scopeConfigObject->getValue('slack/notification/channel');
        $webhookurl = $this->scopeConfigObject->getValue('slack/setting/webhook');
        $order = $observer->getEvent()->getOrder();
        $item = array();
        foreach ($order->getAllVisibleItems() as  $items) {
          $item =  $items->getData();
         
        }
        $sku = $item['sku'];
        $name = $item['name'];
        $price = $item['price'];
        $qty = $item['qty_ordered'];
        $channel  = $channelid;
        $bot_name = 'Webhook';
        $icon     = ':alien:';
        $message  = 'Order #'.$item['order_id'].'placed by '.$name;
        $attachments = array([
            'pretext'  => 'Product',
            'color'    => '#ff6600',
            'fields'   => array(
               
                [
                    'title' => 'Name',
                    'value' => $name,
                    'short' => true
                ],
                [
                    'title' => 'Price',
                    'value' => $price,
                    'short' => true
                ],
                [
                    'title' => 'Sku',
                    'value' => $sku,
                    'short' => true
                ],
                [
                  'title' => 'Qty',
                  'value' => $qty,
                  'short' => true
              ]
               
            )
        ]);
        $attachments = array([
            'pretext'  => 'Product',
            'color'    => '#ff6600',
            'fields'   => array(
               
                [
                    'title' => 'Name',
                    'value' => $name,
                    'short' => true
                ],
                [
                    'title' => 'Price',
                    'value' => $price,
                    'short' => true
                ],
                [
                    'title' => 'Sku',
                    'value' => $sku,
                    'short' => true
                ],
                [
                  'title' => 'Qty',
                  'value' => $qty,
                  'short' => true
              ]
               
            )
        ]);
        
        $data = array(
            'channel'     => $channel,
            'username'    => $bot_name,
            'text'        => $message,
            'icon_emoji'  => $icon,
            'attachments' => $attachments
        );
        $data_string = json_encode($data);

        $ch = curl_init($webhookurl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );

        //Execute CURL
        $result = curl_exec($ch);

        return $result;     


    }

}	
