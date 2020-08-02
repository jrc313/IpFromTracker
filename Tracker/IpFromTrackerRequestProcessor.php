<?php

namespace Piwik\Plugins\IpFromTracker\Tracker;

use Exception;
use Piwik\Config;
use Piwik\Log;
use Piwik\Tracker\Request;
use Piwik\Tracker\RequestProcessor;
use Piwik\Plugins\IpFromTracker\SystemSettings;

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

        $ip = $this->decodeIp($params["ip"]);
        
        if (filter_var($ip, FILTER_VALIDATE_IP))
        {
            $this->setIpInHeaders($ip);
        }
        else
        {
            log::debug("Invalid IP address: %s", $ip);
        }
    }

    private function decodeIp(string $ip)
    {
        $settings = new SystemSettings();
        $isIpEncrypted = $settings->isIpEncrypted->getValue();
        if (!$isIpEncrypted)
        {
            return $ip;
        }

        $ipParts = explode("$", $ip, 2);
        if (count($ipParts) !== 2)
        {
            return $ip;
        }

        try
        {
            $iv = $ipParts[0];
            $encrypted = $ipParts[1];
            $key = $settings->aesKey->getValue();
            $cipher = $settings->cipher->getValue();
            
            $decrypted = openssl_decrypt(base64_decode($encrypted), $cipher, base64_decode($key), OPENSSL_RAW_DATA, base64_decode($iv));
            if ($decrypted === false)
            {
                log::error("Unable to decrypt IP: %s", $ip);
                return $ip;
            }
            log::debug("Decrypted IP: %s", $decrypted);
            return $decrypted;
        }
        catch (Exception $e)
        {
            log::error("Unable to decrypt IP: %s", $e->getMessage());
            return $ip;
        }
        
    }

    private function setIpInHeaders(string $ip)
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