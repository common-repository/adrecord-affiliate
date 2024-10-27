<?php

namespace Jmgraphic\Adrecord;

use \Exception as Exception;
use \Throwable as Throwable;
use \Error as Error;

require(__DIR__ . '/../react-wp-scripts.php');

class App
{
  /**
   * Register the requred hooks for the admin screen
   *
   * @return void
   */
  public static function init()
  {
    add_action('init', [__class__, 'load_text_domain']);
    add_action('admin_menu', [__class__, 'addMenu']);
    add_action('rest_api_init', [__class__, 'register_rest'], 10, 0);

    add_option(JMGRAPHIC_ADRECORD_OPTIONS_KEY, [
      'api_key' => '',
      'user_id' => '',
      'clean_links_enabled' => false,
      'current_programs_market' => 'se'
    ]);

    add_action('wp_dashboard_setup', [__class__, 'my_custom_dashboard_widgets']);
    add_action('wp_footer', [__class__, 'wp_footer']);
  }


  public static function wp_footer()
  {
    if (!is_admin() && !is_feed() && !is_robots() && !is_trackback()) {

      $options = get_option(JMGRAPHIC_ADRECORD_OPTIONS_KEY);
      $user_id = isset($options['user_id']) ? $options['user_id'] : '';
      $clean_links_enabled = isset($options['clean_links_enabled']) ? $options['clean_links_enabled'] : false;
      if ($clean_links_enabled && $user_id) {
        echo clean_link_script($user_id);
      }
    }
  }
  public static function load_text_domain()
  {
    load_plugin_textdomain('adrecord', false, ADRECORD_TEXT_DOMAIN_PATH);
  }
  public static function register_rest()
  {
    register_rest_route(ADRECORD_REST_NAMESPACE, '/get_api_key', [
      [
        'methods' => 'GET',
        'callback' => [__class__, 'get_api_key'],
        // 'permission_callback' => array($this, 'get_options_permission')//Remove to leave open
      ],
    ]);
    register_rest_route(ADRECORD_REST_NAMESPACE, '/set_api_key', [
      [
        'methods' => 'POST',
        'callback' => [__class__, 'set_api_key'],
      ],
    ]);
    register_rest_route(ADRECORD_REST_NAMESPACE, '/set_clean_links_enabled', [
      [
        'methods' => 'POST',
        'callback' => [__class__, 'set_clean_links_enabled'],
      ],
    ]);

    register_rest_route(ADRECORD_REST_NAMESPACE, '/set_current_market', [
      [
        'methods' => 'POST',
        'callback' => [__class__, 'set_current_market'],
      ],
    ]);
  }
  public static function get_api_key(\WP_REST_Request $request)
  {
    return [
      'api_key' => get_option(JMGRAPHIC_ADRECORD_OPTIONS_KEY)['api_key']
    ];
  }
  public static function set_api_key(\WP_REST_Request $request)
  {
    try {
      $params = self::get_params_from_request($request, ['api_key', 'user_id']);
      $api_key = $params['api_key'] ?: '';
      $user_id = $params['user_id'] ? : '';

      return self::update_options([
        'api_key' => $api_key,
        'user_id' => $user_id
      ]);

    } catch (Throwable $e) {
      return new \WP_Error('invalid-argument', $e->getMessage());
    }
  }

  public static function set_clean_links_enabled(\WP_REST_Request $request)
  {
    try {
      $clean_links_enabled = self::get_params_from_request($request, ['clean_links_enabled']);
      return self::update_options([
        'clean_links_enabled' => $clean_links_enabled,
      ]);
    } catch (Throwable $e) {
      return new \WP_Error('invalid-argument', $e->getMessage());
    }
  }
  public static function set_current_market(\WP_REST_Request $request)
  {
    try {
      $current_programs_market = self::get_params_from_request($request, ['current_programs_market']);
      return self::update_options([
        'current_programs_market' => $current_programs_market,
      ]);
    } catch (Throwable $e) {
      return new \WP_Error('invalid-argument', $e->getMessage());
    }
  }


  //Helper method to get body params from request. Validates input.
  public static function get_params_from_request(\WP_REST_Request $request, $params)
  {
    $body = $request->get_body();
    $decoded_body = json_decode($body);
    if (!isset($decoded_body->nonce) || !wp_verify_nonce($decoded_body->nonce, 'wp_rest')) {
      throw new Error(__('Sorry, your nonce was not correct. Please try again.'));
    }

    $decoded_body = json_decode($body);
    $returnValues = [];
    foreach ($params as $param) {
      if (!isset($decoded_body->$param)) {
        throw new Error($param . __('not supplied'));
      }
      $returnValues[$param] = $decoded_body->$param;
    }
    if (count($returnValues) === 1) {
      return array_values($returnValues)[0];
    }

    return $returnValues;
  }

  /**
   * Register an tools/management menu for the admin area
   *
   * @return void
   */
  public static function addMenu()
  {

    $my_page = add_menu_page(
      'Adrecord',
      'Adrecord',
      'manage_options',
      'adrecord-settings',
      [__class__, 'renderSettings'], //callback function
      'data:image/svg+xml;base64,PHN2ZyBiYXNlUHJvZmlsZT0idGlueSIgdmVyc2lvbj0iMS4yIiB2aWV3Qm94PSIwIDAgMTIwIDEyMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KCTxwYXRoIGQ9Im02MC4yODcgNTEuMjE2YzIuNTE1IDguMjIgNS44MzcgMTUuNzg5IDkuOTU4IDIyLjcwNiA3Ljc3MiAxMy4yNTkgMTYuMjk3IDIxLjg4NyAyNS41OSAyNS45MDEtOS42NzQgMTAuNzkyLTIxLjQ0NSAxNi4xODYtMzUuMjgyIDE2LjE4Ni0xMy44OTEgMC0yNS43MTQtNS4zOTQtMzUuNDgxLTE2LjE4NiAzLjIxLTEuNDQ0IDYuMjQyLTMuMzU1IDkuMDktNS43MzEgMi44MzYtMi4zNjQgNS41OTMtNS4yNDIgOC4yNjYtOC42MjggMi42Ni0zLjM5MiA1LjIyOS03LjIzMSA3LjcwOC0xMS41NDIgMy45OS02Ljk2NSA3LjM2OS0xNC41MzUgMTAuMTUxLTIyLjcwNnptNi43NjktNDYuMzc0YzExLjExMiAxLjU5NiAyMC43MDIgNi44OTMgMjguNzc5IDE1Ljg4OSA5LjcyMiAxMS4wNCAxNC41ODkgMjQuMjYgMTQuNTg5IDM5LjY5OSAwIDkuMTgyLTEuNzQxIDE3LjY1OC01LjIxMSAyNS4zOTYtOC45OTctMy4yNC0xNi45MTEtMTAuNjY1LTIzLjc2Mi0yMi4yODMtOS41ODktMTYuMDA3LTE0LjM5NS0zNS41NzgtMTQuMzk1LTU4LjcwMXptLTEzLjg2MiAwYzAgNS41NjgtMC4zMDIgMTEuMDkyLTAuOTA3IDE2LjU3OC0wLjYxNyA1LjQ5My0xLjQ4NCAxMC42MTctMi42MTggMTUuMzc5LTEuMTE2IDQuNzctMi42MTggOS40NDctNC40OCAxNC4wNTEtMS44NzEgNC41OTgtMy45NDggOC44MjQtNi4yNDkgMTIuNjkzLTYuODIzIDExLjMyMi0xNC42MSAxOC42NS0yMy4zOCAyMS45ODctMy40MTktNy42NDMtNS4xMzYtMTYuMDExLTUuMTM2LTI1LjEwMSAwLTE1LjQzOCA0Ljg3OS0yOC42NTkgMTQuNjQ3LTM5LjY5OSAyLjY0NS0yLjg5OSA1LjQ2Mi01LjQ0MiA4LjQzMS03LjYxMiAyLjk2OS0yLjE4OSA2LjA3My0zLjk2IDkuMzExLTUuMzM2IDMuMjM1LTEuMzgxIDYuNjg3LTIuMzU0IDEwLjM4MS0yLjk0eiIgZmlsbD0iIzgyODc4YyIvPgo8L3N2Zz4K'
      // JMGRAPHIC_ADRECORD_URL . '/adrecord-icon-admin.svg'

      // 10 //position.
    );

    // Load the JS conditionally
    add_action('load-' . $my_page, [__class__, 'load_react_js']);

  }

  public static function my_custom_dashboard_widgets()
  {
    global $wp_meta_boxes;

    $options = get_option(JMGRAPHIC_ADRECORD_OPTIONS_KEY);
    if (!empty($options['api_key'])) {
      $page = wp_add_dashboard_widget('custom_help_widget', 'Adrecord', [__class__, 'custom_dashboard']);
      self::load_react_js();
    }

  }

  public static function custom_dashboard()
  {
    echo '<div id="wp-adrecord-dashboard">' . __('Loading', 'adrecord') . '</div>';
  }

  public static function load_react_js()
  {
    add_action('admin_enqueue_scripts', [__class__, 'enqueue_admin_js']);
  }


  public static function enqueue_admin_js()
  {

    $options = get_option(JMGRAPHIC_ADRECORD_OPTIONS_KEY);
    $api_key = isset($options['api_key']) ? $options['api_key'] : '';
    $clean_links_enabled = isset($options['clean_links_enabled']) ? $options['clean_links_enabled'] : '';
    $current_programs_market = isset($options['current_programs_market']) ? $options['current_programs_market'] : '';

    \ReactWPScripts\enqueue_assets(JMGRAPHIC_ADRECORD_PATH, [
      'scripts' => (get_bloginfo('version') >= 5) ? ['wp-i18n'] : [],
      'textDomain' => 'adrecord',
      'textDomainPath' => ADRECORD_TEXT_DOMAIN_PATH,
      'localize' => [
        'name' => 'ADRECORD_GLOBAL',
        'data' => [
          'INITIAL_APIKEY' => $api_key,
          'INITIAL_CLEAN_LINKS' => $clean_links_enabled,
          'CURRENT_PROGRAMS_MARKET' => $current_programs_market,
          'PLUGIN_URL' => JMGRAPHIC_ADRECORD_URL,
          'BASE_URL' => esc_url_raw(rest_url(ADRECORD_REST_NAMESPACE)),
          'nonce' => wp_create_nonce('wp_rest'),
          'language_dict' => language_dictionary(),
        ]
      ]
    ]);
  }
  public static function renderSettings()
  {
    echo '<div class="wrap"> <div id="wp-adrecord"></div> </div>';
  }

  private static function update_options($array)
  {
    $currentOptions = get_option(JMGRAPHIC_ADRECORD_OPTIONS_KEY);
    return update_option(JMGRAPHIC_ADRECORD_OPTIONS_KEY, array_merge($currentOptions, $array));
  }
}
