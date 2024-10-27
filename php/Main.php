<?php

namespace Jmgraphic\Adrecord;

class Main
{
  /**
   * Singleton instance
   * 
   * @var null|Main
   */
  protected static $instance = null;

  /**
   * Create a new singleton instance
   * 
   * @return Main
   */
  public static function instance()
  {
    if (!is_a(Main::$instance, Main::class)) {
      Main::$instance = new Main;
    }

    return Main::$instance;
  }

  /**
   * Bootstrap the plugin
   * 
   * @return void
   */
  protected function __construct()
  {
    $this->includes();
    $this->hooks();
  }

  /**
   * Include/require files
   *
   * @return void
   */
  protected function includes()
  {
    require_once(JMGRAPHIC_ADRECORD_PATH . '/php/ClearLinkScript.php');
    require_once(JMGRAPHIC_ADRECORD_PATH . '/php/LanguageDictionary.php');
    require_once(JMGRAPHIC_ADRECORD_PATH . '/php/App.php');
  }

  /**
   * Register actions & filters
   *
   * @return void
   */
  protected function hooks()
  {
    register_activation_hook(JMGRAPHIC_ADRECORD_FILE, [$this, 'activation']);
    register_deactivation_hook(JMGRAPHIC_ADRECORD_FILE, [$this, 'deactivation']);

    App::init();
  }

  /**
   * Fires on plugin activation
   *
   * @return void
   */
  public function activation()
  {

  }

  /**
   * Fires on plugin deactivation
   *
   * @return void
   */
  public function deactivation()
  {

  }
}