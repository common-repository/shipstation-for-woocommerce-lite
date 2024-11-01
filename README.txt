=== ShipStation for WooCommerce (Lite Version) ===
Contributors: rtddev
Donate link: 
Tags: woocommerce, shipstation, shipping, fulfillment
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin helps store owners significantly speed up the shipping process by offering an easy way to seamlessly integrate with ShipStation.

== Description ==

= Note: = This ShipStation for WooCommerce (Lite Version) plugin has limited/restricted functionality. It has been released to allow you to evaluate the ShipStation.com shipping solution. The full-featured [ShipStation for WooCommerce extension](http://docs.woothemes.com/document/shipstation-for-woocommerce/) is available for purchase in the WooCommerce Extension store. 

ShipStation is a web-based shipping solution that streamlines the order fulfillment process for online retailers. With real-time integrations into popular marketplaces and shopping cart platforms, ShipStation handles everything from order import and batch label creation to customer communication. Advanced customization features allow ShipStation to fit businesses with any number of users or locations.

== Installation ==

1. Download/Save Plugin Extension Zip file to your local computer
1. Within WP Admin, Go to Plugins > Add New > Upload
1. Select the plugin zip file from your local computer
1. Click Install Now Button
1. Once install is complete, Click "Activate Plugin" link

== Frequently asked questions ==

**Do I need the full-featured (paid) version of the ShipStation for WooCommerce extension?**

This 'Lite' and free version of the plugin was released primarily to allow you to take advantage of the the ShipStation 30-day free trial and fully evaluate the ShipStation service without requiring you to purchase the full-featured extension. 

It is limited (by design) in functionality, however, you may not need to purchase the full-featured version of the extension/plugin to utilize ShipStation on your online store. 

The largest restriction within this plugin is the Shipping Method selection restriction. If your store utilizes ONLY the native WooCommerce 'flat_rate' shipping method, you will not likely need the full-featured version.

**What are the feature differences between the Lite and the full-featured versions of the plugin?**

With the full-featured version of the plugin, you can:
1. Export multiple order statuses. The Lite version restricts exporting only orders in the 'Processing' state.

2. Export multiple Shipping Methods. The Lite version restricts exporting only orders that use the 'Flat_Rate' shipping method

3. Use the 3rd party Shipment Tracking plugins that provide additional capability within WooCommerce to store detailed shipment tracking information within each order. 

4. Configure alternative 'completed' order status. Helpful if you have a non-standard installation

**What is a WooCommerce Extension?**

Simply described, it's a WordPress plugin that extends or enhances the functionality of WooCommerce through the use of special hooks provided by WooCommerce. 

'Extensions' are implemented and installed the same as plugins.

== Screenshots ==

1. ShipStation Configuration tab inside WooCommerce

== Changelog ==

= 1.0 =
* Initial release of Lite version.

== Upgrade notice ==

None

== Setup, Configuration, and Testing ==

Once plugin is activated, go to **WooCommerce > Settings > ShipStation** tab.

Please refer to the [Setup and Configuration](http://docs.woothemes.com/document/shipstation-for-woocommerce/#setupandconfiguration) section of the ShipStation for WooCommerce documentation.

**Exceptions:**
1. Export > Order Status Selection: 'Processing' is the only order status that will be exported to ShipStation

2. Export > Shipping Methods Selection: 'Flat Rate' is the only shipping method that will be exported to ShipStation

3. Import > Order Status Selection: 'Completed' is the only supported order status

4. Plug-in Detection: Not Supported

= Testing =
Please refer to the TESTING PLUGIN/EXTENSION PRIOR TO SETTING UP IN SHIPSTATION section of [ShipStation for WooCommerce](http://docs.woothemes.com/document/shipstation-for-woocommerce/) documentation.

== Configure WooCommerce ShipStation Extension ==

Please refer to the [Configure WooCommerce ShipStation Extension](http://docs.woothemes.com/document/shipstation-for-woocommerce/#configurewoocommerceshipstationextension) section of the ShipStation for WooCommerce documentation.