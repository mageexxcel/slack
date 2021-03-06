define(['jquery'],function(jQuery){
 
    var authurl = 'https://slack.com/oauth/authorize';
    var authscope = 'incoming-webhook,channels:read,channels:write,chat:write:bot,users:read';
    var accessurl = 'https://slack.com/api/oauth.access';
    
    
    if (window.opener != null && !window.opener.closed) {

        var client_id = window.opener.document.getElementById("slack_setting_clientid").value;
        var secret_key = window.opener.document.getElementById("slack_setting_secretkey").value;
        var action_url = window.opener.document.getElementById("addtoslack").getAttribute("name");
      
        /*
         * get parameter code
         */
        var urlParams;
        (window.onpopstate = function() {
            var match,
                    pl = /\+/g, // Regex for replacing addition symbol with a space
                    search = /([^&=]+)=?([^&]*)/g,
                    decode = function(s) {
                        return decodeURIComponent(s.replace(pl, " "));
                    },
                    query = window.location.search.substring(1);

            urlParams = {};
            while (match = search.exec(query))
                urlParams[decode(match[1])] = decode(match[2]);
        })();
        var code_value = urlParams['code'];

        if ((code_value != '') && (client_id != '') && (secret_key != '')) {
            jQuery.ajax({
                url: accessurl,
                type: "get",
                data: {client_id: client_id, client_secret: secret_key, code: code_value},
                success: function(response) {
                   console.log(response.access_token);
                    /*
                     * show slack token/start setupslack field after authentication done and hide Add to Slack button 
                     */
                 console.log(client_id);
                 console.log(secret_key);
                   
                    window.opener.jQuery("#slack_setting_token").val(response.access_token);
                    window.opener.jQuery("#row_slack_setting_startsetupwizard").show();
                    window.opener.jQuery('#row_slack_setting_setuprevoke').show();
                    window.opener.jQuery("#row_slack_setting_connect").hide();
                    jQuery.ajax({
                        url: action_url,
                        type: 'get',
                        dataType: 'json',
                        data: {response_token: response , client_id: client_id, client_secret: secret_key},
                        success: function(data) {
                            window.close();
                        },
                        error: function(err) {

                        }
                    })

                },
                error: function(xhr) {
                    //Do Something to handle error
                }
            })
        }

    }
});