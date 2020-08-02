<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\IpFromTracker;

use Piwik\Settings\Setting;
use Piwik\Settings\FieldConfig;
use Piwik\Validators\NotEmpty;

/**
 * Defines Settings for IpFromTracker.
 *
 * Usage like this:
 * $settings = new SystemSettings();
 * $settings->key->getValue();
 */
class SystemSettings extends \Piwik\Settings\Plugin\SystemSettings
{
    /** @var Setting */
    public $isIpEncrypted;

    /** @var Setting */
    public $cipher;

    /** @var Setting */
    public $aesKey;

    protected function init()
    {
        $this->title = "IP From Tracker Encryption";

        // System setting --> Turn on encrypted IPs
        $this->isIpEncrypted = $this->createIsIpEncryptedSetting();

        // System setting --> Specify encryption cipher
        $this->cipher = $this->createCipherSetting();

        // System setting --> Specify key
        $this->aesKey = $this->createAesKeySetting();

    }

    private function createIsIpEncryptedSetting()
    {
        return $this->makeSetting('isEncrypted', $default = false, FieldConfig::TYPE_BOOL, function (FieldConfig $field)
        {
            $field->title = 'IP Addresses are encrypted';
            $field->uiControl = FieldConfig::UI_CONTROL_CHECKBOX;;
            $field->description = 'If checked, IPs provided via the tracker are assumed to be encrypted with AES CBC algorithm and will be decrypted with the provided key';
        });
    }

    private function createCipherSetting()
    {
        
        return $this->makeSetting('cipher', $default = "aes-256-cbc", FieldConfig::TYPE_STRING, function (FieldConfig $field)
        {
            $field->title = 'AES key size';
            $field->condition = 'isEncrypted';
            $field->uiControl = FieldConfig::UI_CONTROL_SINGLE_SELECT;
            $field->availableValues = array('aes-128-cbc' => '128 Bit', 'aes-256-cbc' => '256 Bit');
            $field->description = 'Choose the key size used to encrypt the IPs';
        });
    }

    private function createAesKeySetting()
    {
        return $this->makeSetting('aesKey', $default = '', FieldConfig::TYPE_STRING, function (FieldConfig $field)
        {
            $field->title = 'Key';
            $field->condition = 'isEncrypted';
            $field->uiControl = FieldConfig::UI_CONTROL_TEXTAREA;
            $field->description = 'Base64 encoded key to decrypt the provided IP address';
        });
    }
}
