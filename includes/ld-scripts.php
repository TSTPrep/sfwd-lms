<?php
/**
 * Scripts & Styles
 *
 * @since 2.1.0
 *
 * @package LearnDash\Scripts
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueues styles for front-end.
 *
 * Fires on `wp_enqueue_scripts` hook.
 *
 * @global array $learndash_assets_loaded An array of loaded styles and scripts.
 *
 * @since 2.1.0
 */
function learndash_load_resources()
{
    global $learndash_assets_loaded;

    wp_enqueue_style(
        'learndash_style',
        LEARNDASH_LMS_PLUGIN_URL . 'assets/css/style' . learndash_min_asset() . '.css',
        array(),
        LEARNDASH_SCRIPT_VERSION_TOKEN
    );
    wp_style_add_data('learndash_style', 'rtl', 'replace');
    $learndash_assets_loaded['styles']['learndash_style'] = __FUNCTION__;

    wp_enqueue_style(
        'sfwd_front_css',
        LEARNDASH_LMS_PLUGIN_URL . 'assets/css/front' . learndash_min_asset() . '.css',
        array(),
        LEARNDASH_SCRIPT_VERSION_TOKEN
    );
    wp_style_add_data('sfwd_front_css', 'rtl', 'replace');
    $learndash_assets_loaded['styles']['sfwd_front_css'] = __FUNCTION__;

    if (!is_admin()) {
        wp_enqueue_style(
            'jquery-dropdown-css',
            LEARNDASH_LMS_PLUGIN_URL . 'assets/css/jquery.dropdown.min.css',
            array(),
            LEARNDASH_SCRIPT_VERSION_TOKEN
        );
        wp_style_add_data('jquery-dropdown-css', 'rtl', 'replace');
        $learndash_assets_loaded['styles']['jquery-dropdown-css'] = __FUNCTION__;
    }

    $filepath = SFWD_LMS::get_template('learndash_pager.css', null, null, true);
    if (!empty($filepath)) {
        wp_enqueue_style('learndash_pager_css', learndash_template_url_from_path($filepath), array(), LEARNDASH_SCRIPT_VERSION_TOKEN);
        wp_style_add_data('learndash_pager_css', 'rtl', 'replace');
        $learndash_assets_loaded['styles']['learndash_pager_css'] = __FUNCTION__;
    }

    $filepath = SFWD_LMS::get_template('learndash_pager.js', null, null, true);
    if (!empty($filepath)) {
        wp_enqueue_script('learndash_pager_js', learndash_template_url_from_path($filepath), array('jquery'), LEARNDASH_SCRIPT_VERSION_TOKEN, true);
        $learndash_assets_loaded['scripts']['learndash_pager_js'] = __FUNCTION__;
    }

    $filepath = SFWD_LMS::get_template('learndash_template_style.css', null, null, true);
    if (!empty($filepath)) {
        wp_enqueue_style('learndash_template_style_css', learndash_template_url_from_path($filepath), array(), LEARNDASH_SCRIPT_VERSION_TOKEN);
        wp_style_add_data('learndash_template_style_css', 'rtl', 'replace');
        $learndash_assets_loaded['styles']['learndash_template_style_css'] = __FUNCTION__;
    }

    $filepath = LEARNDASH_LMS_PLUGIN_URL . 'assets/js/learndash-payments' . learndash_min_asset() . '.js';
    if (!empty($filepath)) {
        wp_register_script('learndash-payments', $filepath, array('jquery'), LEARNDASH_SCRIPT_VERSION_TOKEN, true);
        $learndash_assets_loaded['scripts']['learndash-payments'] = __FUNCTION__;
        wp_localize_script(
            'learndash-payments',
            'learndash_payments',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'messages' => array(
                    'successful_transaction' => is_user_logged_in()
                        ? esc_html__('Your transaction was successful.', 'learndash')
                        : esc_html__('Your transaction was successful. Please log in to access your content.', 'learndash'),
                ),
            )
        );
    }

    $filepath = LEARNDASH_LMS_PLUGIN_URL . 'assets/js/learndash-password-strength-meter.js';
    if (!empty($filepath)) {
        wp_register_script('learndash-password-strength-meter', $filepath, array('jquery', 'password-strength-meter'), LEARNDASH_SCRIPT_VERSION_TOKEN, true);
        $learndash_assets_loaded['scripts']['learndash-password-strength-meter'] = __FUNCTION__;
    }

    /** This filter is documented in includes/ld-misc-functions.php */
    if (true === apply_filters('learndash_responsive_video', true, get_post_type(), get_the_ID())) {
        $filepath = SFWD_LMS::get_template('learndash_lesson_video.css', null, null, true);
        if (!empty($filepath)) {
            wp_enqueue_style('learndash_lesson_video', learndash_template_url_from_path($filepath), array(), LEARNDASH_SCRIPT_VERSION_TOKEN);
            $learndash_assets_loaded['styles']['learndash_lesson_video'] = __FUNCTION__;
        }
    }

    if (!isset($learndash_assets_loaded['scripts']['learndash_template_script_js'])) {
        // First check if the theme has the file learndash/learndash_template_script.js or learndash_template_script.js file.
        $filepath = SFWD_LMS::get_template('learndash_template_script.js', null, null, true);
        if (!empty($filepath)) {
            wp_enqueue_script('learndash_template_script_js', learndash_template_url_from_path($filepath), array('jquery'), LEARNDASH_SCRIPT_VERSION_TOKEN, true);
            $learndash_assets_loaded['scripts']['learndash_template_script_js'] = __FUNCTION__;

            $data = array();
            $data['ajaxurl'] = admin_url('admin-ajax.php');
            $data = array('json' => wp_json_encode($data));
            wp_localize_script('learndash_template_script_js', 'sfwd_data', $data);
        }
    }

    // This will be dequeued via the get_footer hook if the button was not used.
    if (!is_admin()) {
        wp_enqueue_script('jquery-dropdown-js', LEARNDASH_LMS_PLUGIN_URL . 'assets/js/jquery.dropdown.min.js', array('jquery'), LEARNDASH_SCRIPT_VERSION_TOKEN, true);
        $learndash_assets_loaded['scripts']['jquery-dropdown-js'] = __FUNCTION__;
    }


    /**
     * TSTPREP added for the tooltip
     */
    if (!is_admin()) {
        wp_enqueue_script('floating-ui-core', '//cdn.jsdelivr.net/npm/@floating-ui/core@1.6.1', ['jquery'], LEARNDASH_SCRIPT_VERSION_TOKEN, true);
        wp_enqueue_script('floating-ui-dom', '//cdn.jsdelivr.net/npm/@floating-ui/dom@1.6.4', ['floating-ui-core'], LEARNDASH_SCRIPT_VERSION_TOKEN, true);
    }

}

/**
 * Filters LearnDash resources load priority.
 *
 * @param string $priority Resources load priority.
 */
add_action('wp_enqueue_scripts', 'learndash_load_resources', apply_filters('learndash_load_resources_priority', '10'));

/**
 * Dequeues scripts.
 *
 * @global array $learndash_assets_loaded
 * @global array $learndash_shortcode_used
 * @global array $learndash_post_types
 */
function learndash_unload_resources()
{
    global $learndash_shortcode_used;
    global $learndash_assets_loaded;

    // If we are showing a known LD post type then leave it all.
    global $learndash_post_types;
    if ((is_singular($learndash_post_types)) || (false !== $learndash_shortcode_used)) {
        return;
    }

    if ((isset($learndash_assets_loaded['scripts'])) && (!empty($learndash_assets_loaded['scripts']))) {
        foreach ($learndash_assets_loaded['scripts'] as $script_tag => $function_loaded) {
            // We *should* check these scripts to ensure we dequeue only ones set to load in the footer. Oh well.
            wp_dequeue_script($script_tag);
        }
    }
}

add_action('wp_print_footer_scripts', 'learndash_unload_resources', 1);

/**
 * TSTPREP process the API call
 */

use GuzzleHttp\Client;

// Handle the AJAX request
function feedback_type_correct_sent_1()
{

    // Check if the values are set and retrieve them
    if (isset($_POST['student_text'])) {

        // Now you can use $correct_text and $student_text for your logic
        // For example, just to check the values, you might want to send them back
        $data = [
            'essay' => stripslashes($_POST['student_text']),
            'task' => '',
            'demo' => false,
            'connection_id' => 10,
        ];

    } else {
        // Send an error response back if the expected data isn't set
        wp_send_json_error(['message' => 'Data not received.']);
    }

    $client = new Client(['base_uri' => 'https://TSTPrep-tstprep-writing.hf.space/']);
    $response = $client->request('POST', 'correct_sent', [
        'headers' => [
            'Authorization' => "Bearer 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",
            'Content-Type' => 'application/json',
        ],
        'json' => $data,
    ]);


    /**
     * Just return the $response of the request?
     */
    wp_send_json_success(json_decode($response->getBody()));
    wp_die();
}

add_action('wp_ajax_feedback_type_correct_sent', 'feedback_type_correct_sent_1');
add_action('wp_ajax_nopriv_feedback_type_correct_sent', 'feedback_type_correct_sent_1');
