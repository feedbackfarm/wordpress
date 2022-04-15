<?php
/**
* Plugin Name: Feedback Farm
* Plugin URI: https://www.feedback.farm/
* Description: Gathering customer feedback should be easy and affordable. Feedback is one of the most useful metric while building an application. Easily embed the wordpress plugin to start getting user feedback.
* Version: 1.0
* Author: Charles-Olivier Demers
* Author URI: https://www.twitter.com/co_demers
**/

function feedback_farm_enqueue_script() {
    wp_register_script( 'feedback-farm', "https://feedback.farm/farm-2.1.0.js", [], null, true);
    wp_enqueue_script('feedback-farm');

    wp_enqueue_script( 'script', plugin_dir_url( __FILE__ )  . '/js/farm.js', array ( 'jquery' ), null, true);
}
add_action('wp_enqueue_scripts', 'feedback_farm_enqueue_script');

function feedback_farm_setting_page() {
    $page_title = 'Feedback Farm Settings';
    $menu_title = 'Feedback Farm';
    $capability = 'manage_options';
    $slug = 'feedback_farm';
    $callback = 'feedback_farm_callback';

    add_submenu_page(
        'plugins.php',
        'Feedback Farm Settings',
        'Feedback Farm',
        'manage_options',
        'feedback_farm',
        'feedback_farm_callback'
    );
}
add_action('admin_menu', 'feedback_farm_setting_page');


function feedback_farm_callback() {
?>
  <div class="wrap">
    <h1>Feedback Farm</h1>
    <p>Documentation available <a target="_blank" href="https://shy-universe-cf4.notion.site/Embed-Widget-In-A-Wordpress-website-ee5d756fcaab42629afedc264f075839">here</a></p>
    <form method="post" action="options.php">
        <?php
            settings_fields('feedback_farm');
            do_settings_sections('feedback_farm');
            submit_button();
        ?>
    </form>
  </div>
<?php
}

add_action('admin_init', 'feedback_farm_settings_page_sections');
add_action('admin_init', 'feeback_farm_settings_fields');
function feedback_farm_settings_page_sections() {
    add_settings_section(
        'feedback_farm',
        'General',
        'feedback_farm_setting_callback',
        'feedback_farm'
    );
}

function feeback_farm_settings_fields() {
    add_settings_field(
        'feedback_farm_projectId',
        'Project Id',
        'feedback_farm_projectId',
        'feedback_farm',
        'feedback_farm'
    );

    add_settings_field(
        'feedback_farm_selected_menu',
        'Assigned Menu',
        'feedback_farm_selected_menu_field',
        'feedback_farm',
        'feedback_farm'
    );
    
    register_setting('feedback_farm', 'feedback_farm_projectId');
    register_setting('feedback_farm', 'feedback_farm_selected_menu');
}

function feedback_farm_selected_menu_field() {
    $menus = wp_get_nav_menus();
    $selected_menu = get_option('feedback_farm_selected_menu');

    echo "<select name=\"feedback_farm_selected_menu\" id=\"feedback_farm_selected_menu\">";
    echo "<option value=\"\">Manual</option>";
    foreach ($menus as $menu) {
        echo "<option value=\"".esc_attr($menu->slug)."\"" .
            selected(esc_attr($selected_menu), esc_attr($menu->slug), false) .
            ">".esc_html($menu->name)."</option>";
    }

    echo "</select>";
    echo "<p class=\"description\">The plugin will add a \"Give feedback\" button to the selected menu. If you select the option manual, you will need to add <code>id=\"feedback-farm\" data-feedback-farm-projectid=\"YOUR_PROJECT_ID\"</code> to any HTML element in your app that should trigger the widget.</p>";
}

function feedback_farm_projectId() {
    echo '<input name="feedback_farm_projectId" id="feedback_farm_projectId" type="text" value="' .
        esc_attr(get_option('feedback_farm_projectId')) .
        '" />';
    echo '<p class="description">You can get your project Id from <a href=\"https://feedback.farm/app/projects\" target=\"_blank\">your project page</a>.</p>';
}

function add_feedback_farm_nav_menu_item($items, $args) {
    $selected_menu = get_option('feedback_farm_selected_menu', 0);
    $projectId = get_option('feedback_farm_projectId');

    if(!empty($selected_menu) && $args->menu->slug === $selected_menu) {
        $items .= "<li class=\"menu-item\"><a href=\"#\" id=\"feedback-farm\" data-feedback-farm-projectid=\"".esc_attr($projectId)."\">Give feedback</a></li>";
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_feedback_farm_nav_menu_item', 10, 2);

function feedback_farm_setting_callback() {}
?>