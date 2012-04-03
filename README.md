Thebod_VanillaJsConnect
=

Simple module to use vanilla jsconnect with Magento
Supports cross-domain-automatic login and logout

Alpha, not fully tested right now!

License: http://creativecommons.org/licenses/by/3.0/ CC-BY 3.0

How To
------

- Install jsConnect Vanilla Plugin into Vanilla (http://vanillaforums.org/addon/jsconnect-plugin)
- Install Thebod_VanillaJsConnect in Magento
- Setup a new jsConnect connection in Vanilla:
  - authenticate url: http://magento.tld/vanillajsconnect
  - don't fill in login and registration url
- Setup VanillaJsConnect in Magento (System->Configuration->Customer Configuration)
- Include the jscontrol into a Vanilla template: <script type="text/javascript" src="http://magento.tld/vanillajsconnect/index/jscontrol"></script>
- Embed Vanilla into Magento by using <script type="text/javascript" src="http://vanilla.tld/vanilla/plugins/embedvanilla/remote.js"></script>
- At least you can set up authentification methods, menus etc. in Vanilla to let it look nice ;)

WARNING:
You will now automatically logged out of Vanilla if you are not logged in into Magento!
If you have problems to login again deactivate Thebod_VanillaJsConnect in app/etc/modules/Thebod_VanillaJsConnect.xml in Magento!