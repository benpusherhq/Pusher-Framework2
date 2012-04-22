
if (!window.naan) window.naan = {};

(function (naan) {

    /*
        Add a visible error notification.  Displays it under the 
        mini-dashboard.
        
        For use with INTERNAL ERRORS.  Not intended for normal user
        notification.
     */
    naan.error = function(message) {
        console.log("Error", message);
        if (message.match(/^<[A-Za-z]+\s*\/?>/))
            $("#naan-error-list").append( $("<li/>").html(message) );
        else
            $("#naan-error-list").append( $("<li/>").text(message) );
        $("#naan-error-section").show();   
    };


    /*
        Add a visible success notification.  Displays it temporarily under the 
        mini-dashboard.
     */
    naan.success = function(message) {
        var $elem = $("<li/>").text(message);

        $("#naan-success-list").append($elem);
        window.setTimeout( function() {
            $elem.fadeOut(function() { $elem.remove(); });
        }, 4000);
        
        $("#naan-success-section").slideDown('slow');   
        
        window.clearTimeout(naan._successTimer);
        naan._successTimer = window.setTimeout( function() {
            $("#naan-success-section").slideUp('slow');
        }, 3000);
    };
    naan._successTimer = null;

    /*
        Add a visible success notification.  Displays it temporarily under the 
        mini-dashboard.
     */
    naan.info = function(message) {
        var $elem = $("<li/>").text(message);

        $("#naan-info-list").append($elem);
        window.setTimeout( function() {
            $elem.fadeOut(function() { $elem.remove(); });
        }, 4000);    
        $("#naan-info-section").slideDown('slow');   
        
        window.clearTimeout(naan._infoTimer);
        naan._infoTimer = window.setTimeout( function() {
            $("#naan-info-section").slideUp('slow');
        }, 3000);
    };
    naan._infoTimer = null;

})(naan);
