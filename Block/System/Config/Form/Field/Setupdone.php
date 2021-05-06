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
class Setupdone extends \Magento\Config\Block\System\Config\Form\Field {

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
     \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigObject       
    ) {
         $this->scopeConfigObject = $scopeConfigObject;
         parent::__construct($context);
       
    }

    /**
     * @param AbstractElement $element
     * @return string
     * @codeCoverageIgnore
     */
    protected function _getElementHtml(AbstractElement $element) {
      
        $this->setType('text');
        $this->addClass("input-text");
        $value = $this->scopeConfigObject->getValue('slack/setting/setupdone');
        $html = '<input  id="'.$this->getHtmlId().'"  value="'. $value . '" disabled>';
        return $html;
    }

    /**
     * Get the Html Id.
     *
     * @return string
     */
    public function getHtmlId() {
         $htmlid = 'setupdone';
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
