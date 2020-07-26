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
            return;
        }

        $ip = $params["ip"];
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

        $_SERVER['REMOTE_ADDR'] = $ip;

        $clientHeaders = @$general['proxy_client_headers'];
        if (is_array($clientHeaders))
        {
            foreach ($clientHeaders as $clientHeader)
            {
                $_SERVER[$clientHeader] = $ip;
            }
        }
        
        log::debug("SERVER: %s", json_encode($_SERVER));
    }
}