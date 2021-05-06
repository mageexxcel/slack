<?php

namespace Excellence\Slack\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;


/**
 * Backend system config connect(add to slack) field renderer
 */
class Inventory extends \Magento\Config\Block\System\Config\Form\Field {

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
      
        $this->setType('select');
        $this->addClass("input-text");
        $token = $this->scopeConfigObject->getValue('slack/setting/token');
        $channelListUrl= $this->scopeConfigObject->getValue('slack/notification/inventory');
       // $value = $this->scopeConfigObject->getValue('slack/setting/setupdone');
        $html = '<select  id="'.$this->getHtmlId().'" class="channellistinventory" name="groups[notification][fields][inventory][value]"><option value="">---Please Select---</option></select>';
        echo '<script type="text/javascript">
                  require([
          "jquery",
          "mage/translate"
         ], function(jQuery) {
                  var channelurl = "https://slack.com/api/conversations.list";  
                  var tokenvalue =  "' . $token . '";
                  var selected =    "' . $channelListUrl . '"; 
                
           jQuery.ajax({
             url: channelurl,
             method: "POST",
             dataType: "json",
             data: {token: tokenvalue, scope: "channels:read", exclude_archived: "1"},
             async: false,
             success: function(data) {
                 var channeldata = data.channels;
                 var channels = jQuery.map(channeldata, function(el) {
                     return el;
                 });
                 jQuery(".channellistinventory").each(function() {
                     jQuery(this).find("option:gt(1)").remove();
                 });
                 var open_count = 0;
                 for (var i = 0; i < channels.length; i++) {
                  if( channels[i].id == selected ){
                  jQuery(".channellistinventory").append("<option value=" + channels[i].id + " selected>" + channels[i].name + "</option>");
                 
                  }else {
                  jQuery(".channellistinventory").append("<option value=" + channels[i].id + ">" + channels[i].name + "</option>");
                 
                   }
                         open_count++;
                 }
                                
                 jQuery("#own-loading").hide();
             },
             error: function(err) {
                 
                 jQuery("#own-loading").hide();
             }
    
         })
       });
           </script>';
        return $html;
    }

    /**
     * Get the Html Id.
     *
     * @return string
     */
    public function getHtmlId() {
         $htmlid = 'inventory';
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
