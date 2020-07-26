<?php

namespace Piwik\Plugins\IpFromTracker\Tracker;

use Piwik\Config;
use Piwik\Log;
use Piwik\Tracker\Request;
use Piwik\Tracker\RequestProcessor;

/**
 * Class IpFromTrackerRequestProcessor
 * @package Piwik\Plugins\IpFromTracker\Tracker
 */
class IpFromTrackerRequestProcessor extends RequestProcessor
{
    public function manipulateRequest(Request $request)
    {
        $params = $request->getParams();
        if (!isset($params["ip"]))
        {
            log::debug("IP parameter does not exist");
            return;
        }

        $ip = $params["ip"];
        log::debug("Got IP: %s", $ip);

        if (filter_var($ip, FILTER_VALIDATE_IP))
        {
            $this->setIpInHeaders($ip);
        }
        else
        {
            log::debug("Invalid IP address: %s", $ip);
        }
    }

    private function setIpInHeaders($ip)
    {
        $general = Config::getInstance()->General;
        $clientHeaders = @$general['proxy_client_headers'];
        if (!is_array($clientHeaders)) {
            $clientHeaders = array();
        }

        $_SERVER['REMOTE_ADDR'] = $ip;
        foreach (@$general['proxy_client_headers'] as $proxyHeader)
        {
            $_SERVER[$proxyHeader] = $ip;
        }

        log::debug("SERVER: %s", json_encode($_SERVER));
    }
}