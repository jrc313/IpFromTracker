(function () {

    function init() {
        var ip = "";
        Matomo.IpFromTracker = {
            setIp: function(newIp)
            {
                ip = newIp;
            }
        }
        
        Matomo.addPlugin("IpFromTracker", {
            log: function()
            {
                return "&ip=" + encodeURIComponent(ip);
            }
        });
    }

    if ('object' === typeof window.Matomo) {
        init();
    } else {
        // tracker might not be loaded yet
        if ('object' !== typeof window.matomoPluginAsyncInit) {
            window.matomoPluginAsyncInit = [];
        }

        window.matomoPluginAsyncInit.push(init);
    }

})();