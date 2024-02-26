<?php
/**
 * AJAX module provider class.
 *
 * @since 4.8.0
 *
 * @package LearnDash\Core
 */

namespace LearnDash\Core\Modules\AJAX;

use StellarWP\Learndash\lucatume\DI52\ServiceProvider;

/**
 * Service provider class for AJAX modules.
 *
 * @since 4.8.0
 */
class Provider extends ServiceProvider {
	/**
	 * Register service providers.
	 *
	 * @since 4.8.0
	 *
	 * @return void
	 */
	public function register(): void {
		$this->container->singleton( Search_Posts::class );

		$this->hooks();
	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 4.8.0
	 *
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'wp_ajax_' . Search_Posts::$action, $this->container->callback( Search_Posts::class, 'handle_request' ) );
	}
}
