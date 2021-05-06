<?php

namespace Excellence\Slack\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerRegisterSuccess implements ObserverInterface {

 

    public function __construct(
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigObject,
            \Excellence\Slack\Model\SlackFactory $slackFactory,
            \Excellence\Slack\Model\ChannelFactory $channelFactory,
            \Magento\Customer\Model\Session $customerSession
    ) {
        $this->scopeConfigObject = $scopeConfigObject;
       $this->slackFactory   = $slackFactory;
        $this->channelFactory = $channelFactory;
        $this->customerSession = $customerSession;
       
    }

    

    public function execute(Observer $observer) {
      $channelid = $this->scopeConfigObject->getValue('slack/notification/channel');
        if($channelid){
         $customer = $observer->getEvent()->getCustomer();
         $firstName=$customer->getFirstname();
         $lastName=$customer->getLastName();
         $email=$customer->getEmail();
         $message="New Customer Registered"."\n"."Customer Name : "." ".$firstName . "  " . $lastName . " " . "\n" . "email : " . $email;
        $channel  = $channelid;
        $bot_name = 'Webhook';
        $icon     = ':alien:';
        $message  = 'Registration';

        $attachments = array([
          'fallback' => 'Customer Registration',
          'pretext'  => 'Customer Registration',
          'color'    => '#ff6600',
          'fields'   => array(
              [
                  'title' => 'First Name',
                  'value' => $firstName,
                  'short' => true
              ],
              [
                  'title' => 'Last Name',
                  'value' => $lastName,
                  'short' => true
              ],
              [
                'title' => 'Email',
                'value' => $email,
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

      $ch = curl_init('https://hooks.slack.com/services/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');
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
     
}
