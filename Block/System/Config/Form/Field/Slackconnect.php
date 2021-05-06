<?php

/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Excellence\Slack\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;


/**
 * Backend system config connect(add to slack) field renderer
 */
class Slackconnect extends \Magento\Config\Block\System\Config\Form\Field {

    public $url = 'https://slack.com/oauth/authorize';
    public $scope = 'incoming-webhook,channels:read,channels:write';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
            \Magento\Framework\App\Request\Http $request,
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigObject
    ) {
        $this->scopeConfigObject = $scopeConfigObject;
        $this->request = $request;
        parent::__construct($context);
       
    }

    /**
     * @param AbstractElement $element
     * @return string
     * @codeCoverageIgnore
     */
    protected function _getElementHtml(AbstractElement $element) {
      
        $this->setType('text');
        $param = $this->request->getParams();
        $code = '';
        if (!empty($param['code'])) {
            $code = $param['code'];
        }
         $this->addClass($code);
         
        $clientId = $this->scopeConfigObject->getValue('slack/setting/clientid');
        $html = '<a  href="#" id="'.$this->getHtmlId().'" name="' . $this->getName() . '"   ' .   $this->serialize( $this->getHtmlAttributes()) . '    onclick="window.open(\'' . $this->url . '?scope=' . $this->scope . '& client_id=' . $clientId . '\' , \'mywindow\' ,\'width=500,height=600,position=fixed , top=50px ,left=300px\');">'
                . '<img alt="Add to Slack" height="40" width="139" src="https://platform.slack-edge.com/img/add_to_slack.png" srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x">'
                . '</a>';
        return $html;
    }

    /**
     * @return mixed
     */
    public function getName() {
        $setupurl=$this->_urlBuilder->getUrl("slack/slack/savetoken/");
        return $setupurl;
    }

    /**
     * Get the Html Id.
     *
     * @return string
     */
    public function getHtmlId() {
         $htmlid = 'addtoslack';
        return $htmlid;
    }

    /**
     * Return the attributes for Html.
     *
     * @return string[]
     */
    public function getHtmlAttributes() {
        return [
            'type',
            'title',
            'class',
            'style',
            'onclick',
            'onchange',
            'disabled',
            'readonly',
            'tabindex',
            'placeholder',
            'data-form-part',
            'data-role',
            'data-action',
            'checked',
        ];
    }

    /**
     * Add a class.
     *
     * @param string $class
     * @return $this
     */
    public function addClass($class) {
        $oldClass = $this->getClass();
        $this->setClass($oldClass . ' ' . $class);
        return $this;
    }

}
