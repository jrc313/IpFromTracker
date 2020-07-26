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
                if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ip))
                {
                    return "&ip=" + ip;
                }
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