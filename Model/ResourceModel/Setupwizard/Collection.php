<?php
namespace Excellence\Slack\Model\ResourceModel\Setupwizard;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Excellence\Slack\Model\Setupwizard','Excellence\Slack\Model\ResourceModel\Setupwizard');
    }
}
