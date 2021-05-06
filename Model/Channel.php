<?php
namespace Excellence\Slack\Model;
class Channel extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'excellence_slack_channel';

    protected function _construct()
    {
        $this->_init('Excellence\Slack\Model\ResourceModel\Channel');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    
    
}
