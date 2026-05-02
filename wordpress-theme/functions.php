<?php
/**
 * TSF Monitor Theme — functions.php
 * Smart & Sustainable Mining Lab, Laurentian University
 *
 * @author Md Rashed Azad Chowdhury <rashed06cse@gmail.com>
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── Theme Constants ────────────────────────────────────────────
define( 'TSF_VERSION', '1.0.0' );
define( 'TSF_DIR', get_template_directory() );
define( 'TSF_URI', get_template_directory_uri() );

// ── Theme Setup ────────────────────────────────────────────────
function tsf_setup() {
    load_theme_textdomain( 'tsf-monitor', TSF_DIR . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', ['search-form','comment-form','comment-list','gallery','caption','style','script'] );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support( 'customize-selective-refresh-widgets' );
    register_nav_menus(['primary' => __('Primary Menu', 'tsf-monitor')]);
}
add_action( 'after_setup_theme', 'tsf_setup' );

// ── Enqueue Assets ─────────────────────────────────────────────
function tsf_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'tsf-fonts',
        'https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;600&display=swap',
        [],
        null
    );
    // Main stylesheet
    wp_enqueue_style( 'tsf-style', get_stylesheet_uri(), ['tsf-fonts'], TSF_VERSION );

    // Chart.js for data visualization
    wp_enqueue_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js', [], '4.4.0', true );

    // Theme JS
    wp_enqueue_script( 'tsf-main', TSF_URI . '/js/main.js', ['chartjs'], TSF_VERSION, true );

    // Pass REST API data to frontend
    wp_localize_script( 'tsf-main', 'tsfData', [
        'restUrl' => esc_url_raw( rest_url('tsf/v1/') ),
        'nonce'   => wp_create_nonce('wp_rest'),
        'siteUrl' => get_site_url(),
    ]);
}
add_action( 'wp_enqueue_scripts', 'tsf_enqueue_assets' );

// ── Custom REST API Endpoints ──────────────────────────────────
function tsf_register_rest_routes() {
    // GET sensor readings (last N records)
    register_rest_route( 'tsf/v1', '/readings', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'tsf_get_readings',
        'permission_callback' => '__return_true', // Public read for demo; restrict in production
        'args'                => [
            'limit' => [
                'default'           => 20,
                'sanitize_callback' => 'absint',
            ],
            'sensor_id' => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ],
    ]);

    // POST new sensor reading (authenticated)
    register_rest_route( 'tsf/v1', '/readings', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'tsf_post_reading',
        'permission_callback' => 'tsf_api_permission',
    ]);

    // GET latest status summary
    register_rest_route( 'tsf/v1', '/status', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'tsf_get_status',
        'permission_callback' => '__return_true',
    ]);
}
add_action( 'rest_api_init', 'tsf_register_rest_routes' );

// API permission: require valid nonce or application password
function tsf_api_permission( WP_REST_Request $request ) {
    return current_user_can('edit_posts') || wp_verify_nonce( $request->get_header('X-WP-Nonce'), 'wp_rest' );
}

// GET /tsf/v1/readings
function tsf_get_readings( WP_REST_Request $request ) {
    global $wpdb;
    $table = $wpdb->prefix . 'tsf_readings';
    $limit = $request->get_param('limit');
    $sensor_id = $request->get_param('sensor_id');

    if ( $sensor_id ) {
        $rows = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table WHERE sensor_id = %s ORDER BY recorded_at DESC LIMIT %d",
            $sensor_id, $limit
        ));
    } else {
        $rows = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table ORDER BY recorded_at DESC LIMIT %d",
            $limit
        ));
    }

    return rest_ensure_response( $rows );
}

// POST /tsf/v1/readings
function tsf_post_reading( WP_REST_Request $request ) {
    global $wpdb;
    $table = $wpdb->prefix . 'tsf_readings';
    $body  = $request->get_json_params();

    $inserted = $wpdb->insert( $table, [
        'sensor_id'   => sanitize_text_field( $body['sensor_id'] ?? '' ),
        'water_level' => floatval( $body['water_level'] ?? 0 ),
        'pore_pressure' => floatval( $body['pore_pressure'] ?? 0 ),
        'seepage_rate'  => floatval( $body['seepage_rate'] ?? 0 ),
        'temperature'   => floatval( $body['temperature'] ?? 0 ),
        'turbidity'     => floatval( $body['turbidity'] ?? 0 ),
        'recorded_at'   => current_time('mysql'),
    ]);

    if ( ! $inserted ) {
        return new WP_Error('db_error', 'Could not save reading', ['status' => 500]);
    }

    return rest_ensure_response(['id' => $wpdb->insert_id, 'status' => 'recorded']);
}

// GET /tsf/v1/status
function tsf_get_status( WP_REST_Request $request ) {
    global $wpdb;
    $table = $wpdb->prefix . 'tsf_readings';
    $latest = $wpdb->get_row("SELECT * FROM $table ORDER BY recorded_at DESC LIMIT 1");

    if ( ! $latest ) {
        return rest_ensure_response(['status' => 'no_data']);
    }

    // Simple threshold logic — extend for real ICOLD criteria
    $wl = floatval($latest->water_level);
    $pp = floatval($latest->pore_pressure);
    $status = 'safe';
    if ( $wl > 85 || $pp > 120 ) $status = 'warning';
    if ( $wl > 95 || $pp > 150 ) $status = 'critical';

    return rest_ensure_response([
        'status'      => $status,
        'latest'      => $latest,
        'updated_at'  => $latest->recorded_at,
    ]);
}

// ── Database Table on Theme Activation ────────────────────────
function tsf_create_tables() {
    global $wpdb;
    $table = $wpdb->prefix . 'tsf_readings';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        sensor_id   VARCHAR(50) NOT NULL DEFAULT 'SENSOR-01',
        water_level DECIMAL(8,3) DEFAULT NULL COMMENT 'metres above datum',
        pore_pressure DECIMAL(8,3) DEFAULT NULL COMMENT 'kPa',
        seepage_rate  DECIMAL(8,3) DEFAULT NULL COMMENT 'L/min',
        temperature   DECIMAL(5,2) DEFAULT NULL COMMENT 'Celsius',
        turbidity     DECIMAL(8,2) DEFAULT NULL COMMENT 'NTU',
        recorded_at   DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY sensor_idx (sensor_id),
        KEY time_idx   (recorded_at)
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
add_action( 'after_switch_theme', 'tsf_create_tables' );

// ── Custom Post Type: TSF Reports ─────────────────────────────
function tsf_register_cpts() {
    register_post_type( 'tsf_report', [
        'labels' => [
            'name'          => __('TSF Reports', 'tsf-monitor'),
            'singular_name' => __('TSF Report', 'tsf-monitor'),
            'add_new_item'  => __('Add New Report', 'tsf-monitor'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-chart-area',
        'supports'     => ['title','editor','author','thumbnail','excerpt','custom-fields'],
        'rewrite'      => ['slug' => 'tsf-reports'],
    ]);
}
add_action( 'init', 'tsf_register_cpts' );

// ── Widgets ────────────────────────────────────────────────────
function tsf_register_sidebars() {
    register_sidebar([
        'name'          => __('Dashboard Sidebar', 'tsf-monitor'),
        'id'            => 'dashboard-sidebar',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action( 'widgets_init', 'tsf_register_sidebars' );

// ── Security Hardening ─────────────────────────────────────────
remove_action( 'wp_head', 'wp_generator' );             // Hide WP version
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
add_filter( 'the_generator', '__return_empty_string' );
