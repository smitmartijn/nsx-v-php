VMware NSX for vSphere PHP Class (nsx-v-php)
===================

Welcome to the project called 'nsx-v-php' - this is a PHP Framework that uses the VMware NSX API to retrieve information about the NSX networking environment and configure aspects of NSX. You can use this to enable NSX integration in an existing PHP appliance or for execution on your command line for quick configuration tasks.

Pull requests or feature requests are welcome!


Current Available Objects:
---------
- Controller
- Edge
- LogicalSwitch
- TransportZone


Quick Example:
---------
```
<?php

  require("nsx-v.class.php");

  /**
   * Instantiate NSX_v_API object and set the login credentials for the NSX Manager
   */
  $nsxv = new NSX_v_API;
  $nsxv->setLoginDetails("my-nsx-manager.domain.local", "admin", "mysecretpassword");

  // Get info on all Logical Switches
  var_dump($nsxv->LogicalSwitch()->Get());
?>
```

Licensing
---------

This project is licensed under the MIT License.

Copyright (c) 2016 Martijn Smit

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
