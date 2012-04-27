
if (!window.naan) window.naan = {};

(function (naan) {


    //
    // Wrapper on an AJAX call that checks for error conditions and reports
    // them appropriately.
    //
    naan._ajaxGenericJSON = function(method, rootUri, url, data, callback)
    {
        var params = {
            _METHOD : method,
            data    : data
        };
    
        $.ajax({
            url     : rootUri + url,
            type    : (method == "GET") ? "GET" : "POST",
            data    : params,
            success : function (data) {
                try
                {
                    json = JSON.parse(data);                     
                    if (json.error)
                    {
                        naan.error(json.error.text);
                    }
                    else {
                        try 
                        {
                            callback(json);
                        } 
                        catch (e )
                        {
                            naan.error("Exception in AJAX callback");
                            naan.error("" + e);
                        }
                    }
                }
                catch (e)
                {
                    naan.error("Malformed JSON", data);
                    naan.error("" + e);
                    console.log("Malformed JSON", data);
                    console.log("Exception", e);
                }   
            }
        });
    };

    naan.ajaxGetJSON = function (url, data, callback) {
        naan._ajaxGenericJSON('GET', rootUri, url, data, callback);
    };
    
    naan.ajaxPostJSON = function (url, data, callback) {
        naan._ajaxGenericJSON('POST', rootUri, url, data, callback);
    };
    
    naan.ajaxPutJSON = function (url, data, callback) {
        naan._ajaxGenericJSON('PUT', rootUri, url, data, callback);
    };

    naan.ajaxDeleteJSON = function (url, data, callback) {
        naan._ajaxGenericJSON('DELETE', rootUri, url, data, callback);
    };

})(naan);


$(function() {
    $(".naan-search").each(function() {       
        $(this).attr("href", "http://www.google.com/search?q=" + escape($(this).text()));
        $(this).attr("target", "_blank");
    });
});
