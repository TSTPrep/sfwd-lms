<?php
/**
 * A base class for all views.
 *
 * @since 4.9.0
 *
 * @package LearnDash\Core
 */

namespace LearnDash\Core\Template;

/**
 * A base class for all views.
 *
 * @since 4.9.0
 */
abstract class View {
	/**
	 * View slug.
	 *
	 * @since 4.9.0
	 *
	 * @var string
	 */
	protected $view_slug;

	/**
	 * Context.
	 *
	 * @since 4.9.0
	 *
	 * @var array<string, mixed>
	 */
	protected $context;

	/**
	 * Template.
	 *
	 * @since 4.9.0
	 *
	 * @var ?Template
	 */
	protected $template;

	/**
	 * Whether the view is for an admin page.
	 *
	 * @since 4.9.0
	 *
	 * @var bool
	 */
	protected $is_admin;

	/**
	 * Constructor.
	 *
	 * @since 4.9.0
	 *
	 * @param string       $view_slug View slug.
	 * @param array<mixed> $context   Context.
	 * @param bool         $is_admin  Whether the view is for an admin page. Default false.
	 */
	public function __construct( string $view_slug, array $context = [], bool $is_admin = false ) {
		$this->view_slug = $view_slug;
		$this->is_admin  = $is_admin;

		$this->context = array_merge(
			$context,
			array(
				'user' => wp_get_current_user(),
			)
		);
	}

	/**
	 * Gets the view HTML.
	 *
	 * @since 4.9.0
	 *
	 * @return string
	 */
	public function get_html(): string {
		$template = new Template( $this->view_slug, $this->context, $this->is_admin, $this );

		$this->set_template( $template );

		return $template->get_content();
	}

	/**
	 * Gets the template object.
	 *
	 * @since 4.9.0
	 *
	 * @return Template|null
	 */
	public function get_template(): ?Template {
		return $this->template;
	}

	/**
	 * Sets the template object.
	 *
	 * @since 4.9.0
	 *
	 * @param Template $template The template object.
	 *
	 * @return View
	 */
	public function set_template( Template $template ): View {
		$this->template = $template;

		return $this;
	}
}
