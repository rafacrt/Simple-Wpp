<?php
/*
Plugin Name: Simple Wpp
Plugin URI: https://github.com/rafacrt/Simple-Wpp
Description: A simple floating WhatsApp button for WordPress. Easy to use and customize!
Version: 1.0
Author: Rafael Medeiros
Author URI: https://tecnorafa.dev
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: simple-wpp
Domain Path: /languages
*/

if (!defined('ABSPATH')) exit;

// Ativação do plugin
function simple_wpp_activate() {
    add_option('simple_wpp_number', '');
    add_option('simple_wpp_message', '');
}
register_activation_hook(__FILE__, 'simple_wpp_activate');

// Adiciona botão ao front-end
function simple_wpp_button() {
    $number = esc_attr(get_option('simple_wpp_number'));
    $message = urlencode(get_option('simple_wpp_message'));

    if ($number) {
        echo '<a href="https://wa.me/' . esc_attr($number) . '?text=' . esc_attr($message) . '" 
        class="simple-wpp-float" target="_blank" rel="noopener noreferrer" 
        style="position:fixed;bottom:20px;right:20px;z-index:999;">
            <img src="' . esc_url(plugins_url('whatsapp-icon.png', __FILE__)) . '" alt="' . esc_attr__('Contact via WhatsApp', 'simple-wpp') . '" style="width:60px;height:60px;">
        </a>';
    }
}
add_action('wp_footer', 'simple_wpp_button');

// Adiciona página de configuração
function simple_wpp_menu() {
    add_options_page(
        __('Simple Wpp Settings', 'simple-wpp'),
        'Simple Wpp',
        'manage_options',
        'simple-wpp',
        'simple_wpp_settings_page'
    );
}
add_action('admin_menu', 'simple_wpp_menu');

// Página de configurações
function simple_wpp_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Simple Wpp Settings', 'simple-wpp'); ?></h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('simple_wpp_settings');
                do_settings_sections('simple-wpp');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registra as configurações
function simple_wpp_register_settings() {
    register_setting('simple_wpp_settings', 'simple_wpp_number', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ]);

    register_setting('simple_wpp_settings', 'simple_wpp_message', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ]);

    add_settings_section('simple_wpp_main_section', '', null, 'simple-wpp');

    add_settings_field('simple_wpp_number', __('WhatsApp Number (with country code)', 'simple-wpp'), function() {
        $value = esc_attr(get_option('simple_wpp_number'));
        echo '<input type="text" name="simple_wpp_number" value="' . $value . '" placeholder="5511999999999" style="width:300px;" />';
    }, 'simple-wpp', 'simple_wpp_main_section');

    add_settings_field('simple_wpp_message', __('Default Message', 'simple-wpp'), function() {
        $value = esc_attr(get_option('simple_wpp_message'));
        echo '<input type="text" name="simple_wpp_message" value="' . $value . '" style="width:300px;" />';
    }, 'simple-wpp', 'simple_wpp_main_section');
}
add_action('admin_init', 'simple_wpp_register_settings');
