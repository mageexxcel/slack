<?php
namespace Excellence\Slack\Model\ResourceModel\Channel;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Excellence\Slack\Model\Channel','Excellence\Slack\Model\ResourceModel\Channel');
    }
}
