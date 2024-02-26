<?php
/**
 * A base class for all WP frontend views.
 *
 * @since 4.6.0
 *
 * @package LearnDash\Core
 */

/** NOTICE: This code is currently under development and may not be stable.
 *  Its functionality, behavior, and interfaces may change at any time without notice.
 *  Please refrain from using it in production or other critical systems.
 *  By using this code, you assume all risks and liabilities associated with its use.
 *  Thank you for your understanding and cooperation.
 **/

namespace LearnDash\Core\Template\Views;

use LDLMS_Post_Types;
use LearnDash\Core\Template\View as View_Base;
use LearnDash_Custom_Label;

/**
 * A base class for all WP frontend views.
 *
 * @since 4.6.0
 */
abstract class View extends View_Base {
	/**
	 * Constructor.
	 *
	 * @since 4.6.0
	 *
	 * @param string       $view_slug View slug.
	 * @param array<mixed> $context   Context.
	 */
	public function __construct( string $view_slug, array $context = array() ) {
		parent::__construct( $view_slug, $context );

		add_action( 'wp_enqueue_scripts', array( $this, 'manage_assets' ) );
	}

	/**
	 * Gets the breadcrumbs base.
	 *
	 * @since 4.6.0
	 *
	 * @return array<string, string>[]
	 */
	protected function get_breadcrumbs_base(): array {
		$course_slug = LDLMS_Post_Types::get_post_type_slug( LDLMS_Post_Types::COURSE );

		$breadcrumbs = [
			[
				'url'   => learndash_post_type_has_archive( $course_slug ) ? (string) get_post_type_archive_link( $course_slug ) : '',
				'label' => LearnDash_Custom_Label::get_label( 'courses' ),
				'id'    => 'courses',
			],
		];

		/**
		 * Filters the breadcrumbs base.
		 *
		 * @since 4.6.0
		 *
		 * @param array<string, string>[] $breadcrumbs The breadcrumbs base.
		 * @param string                  $view_slug   The view slug.
		 * @param View                    $view        The view object.
		 *
		 * @ignore
		 */
		return (array) apply_filters(
			'learndash_template_views_breadcrumbs_base',
			$breadcrumbs,
			$this->view_slug,
			$this
		);
	}

	/**
	 * Manages assets.
	 *
	 * @since 4.6.0
	 *
	 * @return void
	 */
	public function manage_assets(): void {
		wp_dequeue_style( 'learndash_template_style_css' );
	}
}
