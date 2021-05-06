require([
    "jquery",
    "jquery/ui",
    "mage/translate"
], function (jQuery) {
  
    

    jQuery('#revokeslack').on('click', function () {
        var href = jQuery(this).attr('title');
        if (confirm( jQuery.mage.__('Are you sure you want to revoke the authorization. Doing this will result in magento not able to post updates on slack')) == true) {
        location = href;
        }
    });
})


    