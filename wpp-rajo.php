<?php
/**
* Plugin Name: WPP Rajo
* Plugin URI: http://rajo.com.br
* Description: Este plugin adiciona o whatsapp ao site.
* Version: 1.4.1
* Author: Rafael Medeiros
* Author URI: http://rajo.com.br
**/

function add_whatsapp_button() {
    $whatsapp_number = get_option('whatsapp_number');
    ?>
    <div class="floating_btn-wpp">
        <a class="link-wpp" target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo $whatsapp_number; ?>" style='text-decoration: none'>
            <div class="contact_icon-wpp">
                <i class="fab fa-whatsapp my-float"></i>
            </div>
        </a>
    </div>
    <?php
}

add_action('wp_body_open', 'add_whatsapp_button');

function add_whatsapp_button_styles() {
    // rest of the function
}
add_action('wp_head', 'add_whatsapp_button_styles');

function whatsapp_plugin_menu() {
    add_menu_page(
        'Botão do WhatsApp Configurações', // page_title
        'Botão do WhatsApp', // menu_title
        'manage_options', // capability
        'whatsapp-button', // menu_slug
        'whatsapp_plugin_options_page', // function
        'dashicons-admin-generic', // icon_url
        20 // position
    );
}

function whatsapp_plugin_options_page() {
    ?>
    <div class="wrap">
        <h1>Configurações do Whatsapp</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('whatsapp_options');
            do_settings_sections('whatsapp_options');
            submit_button('Salvar');
            ?>
        </form>
    </div>
    <?php
}

function whatsapp_settings_init() {
    register_setting('whatsapp_options', 'whatsapp_number');

    add_settings_section(
        'whatsapp_settings_section',
        'Configuração do botão do Whatsapp',
        '',
        'whatsapp_options'
    );

    add_settings_field(
        'whatsapp_number',
        'Número do Whatsapp com DDD',
        'whatsapp_number_field_render',
        'whatsapp_options',
        'whatsapp_settings_section'
    );
}

function whatsapp_number_field_render() {
    $whatsapp_number = get_option('whatsapp_number');
    echo "<input type='text' name='whatsapp_number' value='$whatsapp_number'>";
}

function whatsapp_button_enqueue_scripts() {
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' );
    wp_enqueue_style( 'whatsapp-button', plugin_dir_url( __FILE__ ) . 'whatsapp-button.css', array(), '1.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'whatsapp_button_enqueue_scripts' );


add_action('admin_menu', 'whatsapp_plugin_menu');
add_action('admin_init', 'whatsapp_settings_init');
