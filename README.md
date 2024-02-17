# WordPress Plugin - Sales Tracker

## Overview

Sales Tracker is a WordPress plugin designed to store sales information by providing robust sales tracking capabilities. This readme file provides instructions for installing and testing the Sales Tracker plugin.

## Installation

Follow these steps to install Sales Tracker on your WordPress website:

1. **Download the Plugin:**

   -  Download the Sales Tracker plugin from the [GitHub repository](https://github.com/theMasudRana/sales-tracker/archive/refs/tags/v1.0.0.zip).
   -  The plugin file you need is `sales-tracker-1.0.0.zip`.

2. **Upload to WordPress:**

   -  Log in to your WordPress admin dashboard.
   -  Navigate to **Plugins > Add New**.
   -  Click on the **Upload Plugin** button.
   -  Choose the `sales-tracker-1.0.0.zip` file you downloaded.
   -  Click **Install Now** and then **Activate Plugin**.
   -  **Set WordPress Permalink Settings to Post name**

3. **Manual Installation:**
   -  If you prefer manual installation, unzip the plugin folder.
   -  Upload the entire folder to the `/wp-content/plugins/` directory.
   -  Activate the plugin through the **Plugins** menu in WordPress.

## Configuration

Once you have activated the Sales Tracker plugin, follow these steps to configure it:

1. Navigate to **Dashboard > Sales Tracker** in your WordPress admin dashboard.
2. All Sales: page to show the all the items in admin dashboard.
3. Settings page: See available shortcodes to show the dashboard and tracking form in the frontend.

## Testing

To ensure that Sales Tracker is working correctly, perform the following tests:

1. **Basic Functionality:**

   -  Create a page for Sales Tracker Dashboard with this shortcode [sales_tracker_dashboard]
   -  Create a page for Sales Tracker Form with this shortcode [sales_tracker_form]
   -  Create some sales using the form and check if they are showing in the sales tracker dashboard properly or not.
   -  (Note: I have disabled this requirements [Using cookie, prevent users from multiple submissions within 24 hours.
      ] code so that the plugin can be tested wit multiple data entry.)

2. **Compatibility:**

   -  Test Sales Tracker with different themes but make sure the set the page container width to min or 1120px for great UX
   -  Recommended Astra WordPress theme

## Reporting Issues

If you encounter any issues or have suggestions for improvement, please report them on the [GitHub repository](https://github.com/theMasudRana/sales-tracker/issues).

## Support and Documentation

For additional help or documentation, please contact with me at mr.masudrana00@gmail.com or Skype: mr.masudrana00

## License

Sales Tracker is released under the [GPL-2.0](https://opensource.org/licenses/GPL-2.0) license. See the [LICENSE](https://github.com/theMasudRana/sales-tracker/blob/main/LICENSE) file for more details.
