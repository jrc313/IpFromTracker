# Matomo IpFromTracker Plugin

## Description

This plugin allows the visitor's IP address to be set by the JS tracker.

Use the following syntax to specify the address in the tracker embed code. This should be called before `trackPageView`.

```js
_paq.push(['IpFromTracker::setIp', '127.0.0.1']);
```


## Encrypting IP Addresses

IP addresses can be encrypted to prevent them from being displayed in clear text in the page source.

Turn on encryption in the General Settings Administration menu then specify the key size and enter a Base64 encoded version of the key.

When passing the encrypted IP address via the JS Tracker you will need to include Base64 encoded representations of both the the IV and the encrypted IP. The plugin expects these to be provided as a single string separated with a `$` symbol. e.g.

```js
let iv = "WzqpPhzST/XL5C2op9z3oA==";
let encryptedIp = "iANmpj17cSO110e0vaOEjA==";

_paq.push(['IpFromTracker::setIp', iv + '$' + encryptedIp])
```

or 

```js
_paq.push(['IpFromTracker::setIp', 'WzqpPhzST/XL5C2op9z3oA==$iANmpj17cSO110e0vaOEjA=='])
```