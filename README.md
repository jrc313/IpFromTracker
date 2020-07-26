# Matomo IpFromTracker Plugin

## Description

This plugin allows the visitor's IP address to be set by the JS tracker.

Use the following syntax to specify the address in the tracker embed code. This should be called before `trackPageView`.

```js
_paq.push(['IpFromTracker::setIp', '127.0.0.1']);
```
