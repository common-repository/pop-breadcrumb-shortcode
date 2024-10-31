<?php

/*
Plugin Name: Pop Breadcrumb Shortcode
Description: Simple Breadcrumb Shortcode is an easy to use plugin, paste the shortcode, and it will appear on your pages
Version: 1.4
Plugin URI:  https://wordpress.org/plugins/pop-breadcrumb-shortcode
Author: Jérôme OLLIVIER
Author URI: https://www.jerome-freelance.com/
Tags: breadcrumb, shortcode
Requires at least: 4.9
Tested up to: 6.1
Stable tag: 3.11.1
License: GPLv2 or later
Requires PHP: 7.1
Screenshot 1 : /assets/images/screenshot.png
Copyright: {2023} {Jérôme OLLIVIER} {email: hello@jerome-freelance.fr}
    This program is a free software; you can redistribute it and/or modify it under the termes of the GNU General Public License,
    version 2, or later, as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful, but WHITOUT ANY WARRANTLY; without event the implied warranty
    of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
    See the GNU General Public License for more details.

    You shoud have received a copy of the GNU General Public License along with this program; if not, write to the 
    Free Sofware Foundatuon, Inc., 51 Franklin St, First Floor, Boston, MA 02110-1301 USA

*/


function ecpt_make_breadcrumb()
{
    global $post;

    if (!is_home()) {

        $fil = '';
        $fil .= '<a href="' . get_bloginfo('wpurl') . '">';
        $fil .= __('Home', 'mybreadcrumb');
        $fil .= '</a> > ';

        $parents = array_reverse(get_ancestors($post->ID, 'page'));
        foreach ($parents as $parent) {
            $fil .= '<a href="' . get_permalink($parent) . '">';
            $fil .= get_the_title($parent);
            $fil .= '</a> > ';
        }
        $fil .= $post->post_title;
        return $fil;
    }
}

add_shortcode('mybreadcrumb', 'ecpt_make_breadcrumb');

/********************
**** Back Office ****
 *******************/

// Adding CSS in Admin

function my_admin_theme_style()
{
    wp_enqueue_style('my-admin-theme', plugins_url('assets/simple_breadcrumb.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'my_admin_theme_style');
add_action('login_enqueue_scripts', 'my_admin_theme_style');

// Adding sidebar in Admin

function simple_breadcrumb_sidebar()
{
    add_menu_page('Simple Breadcrumb', 'Simple BreadCrumb', 'manage_options', 'social-media', 'simple_breadcrumb_page', 'dashicons-shortcode');
}

add_action('admin_menu', 'simple_breadcrumb_sidebar');


// Admin page creation

function simple_breadcrumb_section_readme()
{
    // Adding a section
    add_settings_section('simple_breadcrumb_section', '', null, 'simple-breadcrumb');

    // Fields creation 
    add_settings_field("simple_breadcrumb_field", "Text in Admin", "simple_breadcrumb_field_one", "simple-breadcrumb", "simple_breadcrumb_section");

    // Fields registration
    register_setting("simple_breadcrumb_section", "simple_breadcrumb_field");
}

// HTML page code
function simple_breadcrumb_page()
{ ?>
    <div class="container">
        <h1>Simple BreadCrumb</h1>
        <p>Simply add the shortcode [mybreadcrumb] in your page</p>
        <img src="<?PHP echo plugin_dir_url(__FILE__) . 'assets/images/simple_breadcrumb_screenshot.png'; ?>" class="image">
        <p>It is advisable to put it in a template so that it is displayed on all pages (except the home page).</p>
    </div>
<?php }


// Internationalization

function my_plugin_load_textdomain() {
    load_plugin_textdomain( 'mybreadcrumb', false, basename( dirname( __FILE__ ) ) . '/languages/' );
  }
  add_action( 'plugins_loaded', 'my_plugin_load_textdomain' );
  ?>
