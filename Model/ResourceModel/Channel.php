<?php
namespace Excellence\Slack\Model\ResourceModel;
class Channel extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('excellence_slack_channel','entity_id');
    }
}
