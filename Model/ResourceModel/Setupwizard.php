<?php
namespace Excellence\Slack\Model\ResourceModel;
class Setupwizard extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('excellence_slack_setupwizard','id');
    }
}
