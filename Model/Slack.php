<?php
namespace Excellence\Slack\Model;
class Slack extends \Excellence\Oms\Model\AbstractModel
{  
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
       \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigObject,     
       \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Psr\Log\LoggerInterface $logger,     
        array $data = []
    ) {
         $this->_resourceConfig = $resourceConfig;
          $this->logger = $logger;
          $this->scopeConfigObject = $scopeConfigObject;
          parent::__construct($data);
    }
   
   
    
    /**
     *  get access key and token of Trello 
     */
    public function getKeyToken() {

        $access = array();
        $access['token'] = $this->scopeConfigObject->getValue('slack/setting/token');
        return $access;
    }

    /*
     * get the all list of slack team members(user)
     */

    public function getUserList($url) {
        $result = $this->initCurl($url);
        return $result;
    }

    /* @param $value is array   $value['exclude_archived']
     * @pram $url string url (action api)
     * get the List of all open channels( if exclude_archived = 1) 
     * return json string  //getChannelList
     */

    public function getBoardChannelList($value=array()) {
        $url='https://slack.com/api/channels.list';
        $result = $this->initCurl($url, $value);
        return $result;
    }

    /* 
     * @param $value is array   $value['name']
     */

    public function postCreateChannel($value=array()) {
        $url = 'https://slack.com/api/channels.create';
        $result = $this->initCurl($url, $value ,$type='post');
        return $result;
    }

    /*
     * post message on channel
     * @parar value , type array , value (channel ,text) requried , username ,attachments etc optional
     * // postMessage
     */

    public function createPost($value=array()) {
        $url = 'https://slack.com/api/chat.postMessage';
        $result = $this->initCurl($url, $value ,$type='post');
        return $result;
    }
    /*
     * use to get channel information using channel id 
     * @param array :   value['chennel']
     */
    public function getChannelInfo($value=array()){
        $url='https://slack.com/api/channels.info';
        $result = $this->initCurl($url, $value ,$type='post');
        return $result;
    }
   
}
