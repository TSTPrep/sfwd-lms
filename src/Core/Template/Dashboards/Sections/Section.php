<?php
/**
 * A dashboard section.
 *
 * @since 4.9.0
 *
 * @package LearnDash\Core
 */

namespace LearnDash\Core\Template\Dashboards\Sections;

use InvalidArgumentException;
use LogicException;
use LearnDash\Core\Template\Dashboards\Widgets\Widget;
use LearnDash\Core\Template\Dashboards\Widgets\Widgets;

/**
 * A dashboard section.
 *
 * @since 4.9.0
 */
class Section {
	/**
	 * The maximum number of columns in a section.
	 *
	 * @since 4.9.0
	 *
	 * @var int
	 */
	protected $max_columns = 12;

	/**
	 * The sections of the section.
	 *
	 * @since 4.9.0
	 *
	 * @var Sections
	 */
	protected $sections;

	/**
	 * The widgets of the section.
	 *
	 * @since 4.9.0
	 *
	 * @var Widgets
	 */
	protected $widgets;

	/**
	 * Title. Default is empty string.
	 *
	 * @since 4.9.0
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Hint text. Default is empty string. It supports HTML tags returned by {@see self::get_hint_supported_html_tags()}.
	 *
	 * @since 4.9.0
	 *
	 * @var string
	 */
	protected $hint = '';

	/**
	 * Size. Default is max column size (full width).
	 *
	 * @since 4.9.0
	 *
	 * @var int
	 */
	protected $size;

	/**
	 * Constructor.
	 *
	 * @since 4.9.0
	 */
	public function __construct() {
		$this->sections = new Sections();
		$this->widgets  = new Widgets();
		$this->size     = $this->max_columns;
	}

	/**
	 * Creates a new instance of the class.
	 *
	 * @since 4.9.0
	 *
	 * @return self
	 */
	public static function create(): self {
		return new self();
	}

	/**
	 * Returns the supported HTML tags for the hint text.
	 *
	 * @since 4.9.0
	 *
	 * @return array<array<mixed>> The supported HTML tags.
	 */
	public static function get_hint_supported_html_tags(): array {
		/**
		 * Filters the supported HTML tags for the hint text.
		 *
		 * @since 4.9.0
		 *
		 * @param array<array<mixed>> $tags The supported HTML tags.
		 *
		 * @return array<array<mixed>> The supported HTML tags.
		 */
		return apply_filters(
			'learndash_dashboard_section_hint_supported_html_tags',
			[
				'a'      => [
					'href'   => [],
					'target' => [],
					'rel'    => [],
				],
				'br'     => [],
				'b'      => [],
				'strong' => [],
			]
		);
	}

	/**
	 * Sets the widgets of the section.
	 *
	 * @since 4.9.0
	 *
	 * @param Widgets $widgets Widgets.
	 *
	 * @throws LogicException If the sections are not empty.
	 *
	 * @return self
	 */
	public function set_widgets( Widgets $widgets ): self {
		if ( $this->sections->is_not_empty() ) {
			throw new LogicException( 'The sections must be empty if you want to set widgets.' );
		}

		$this->widgets = $widgets;

		return $this;
	}

	/**
	 * Adds the widget.
	 *
	 * @since 4.9.0
	 *
	 * @param Widget $widget Widget.
	 *
	 * @throws LogicException If the sections are not empty.
	 *
	 * @return self
	 */
	public function add_widget( Widget $widget ): self {
		if ( $this->sections->is_not_empty() ) {
			throw new LogicException( 'The sections must be empty if you want to set widgets.' );
		}

		$this->get_widgets()->push( $widget );

		return $this;
	}

	/**
	 * Sets the sections of the section.
	 *
	 * @param Sections $sections Sections.
	 *
	 * @throws LogicException If the widgets are not empty.
	 *
	 * @return self
	 */
	public function set_sections( Sections $sections ): self {
		if ( $this->widgets->is_not_empty() ) {
			throw new LogicException( 'The widgets must be empty if you want to set sections.' );
		}

		$this->sections = $sections;

		return $this;
	}

	/**
	 * Adds the section.
	 *
	 * @param Section $section Section.
	 *
	 * @throws LogicException If the widgets are not empty.
	 *
	 * @return self
	 */
	public function add_section( Section $section ): self {
		if ( $this->widgets->is_not_empty() ) {
			throw new LogicException( 'The widgets must be empty if you want to set sections.' );
		}

		$this->get_sections()->push( $section );

		return $this;
	}

	/**
	 * Returns sections.
	 *
	 * @since 4.9.0
	 *
	 * @return Sections
	 */
	public function get_sections(): Sections {
		return $this->sections;
	}

	/**
	 * Returns widgets.
	 *
	 * @since 4.9.0
	 *
	 * @return Widgets
	 */
	public function get_widgets(): Widgets {
		return $this->widgets;
	}

	/**
	 * Returns true if the section contains sections.
	 *
	 * @since 4.9.0
	 *
	 * @return bool
	 */
	public function has_sections(): bool {
		return $this->sections->is_not_empty();
	}

	/**
	 * Returns true if the section contains widgets.
	 *
	 * @since 4.9.0
	 *
	 * @return bool
	 */
	public function has_widgets(): bool {
		return $this->widgets->is_not_empty();
	}

	/**
	 * Sets the title of the section.
	 *
	 * @since 4.9.0
	 *
	 * @param string $title Title.
	 *
	 * @return self
	 */
	public function set_title( string $title ): self {
		$this->title = $title;

		return $this;
	}

	/**
	 * Sets the hint text of the section.
	 *
	 * @since 4.9.0
	 *
	 * @param string $hint Hint text.
	 *
	 * @return self
	 */
	public function set_hint( string $hint ): self {
		$this->hint = $hint;

		return $this;
	}

	/**
	 * Returns the hint text of the section.
	 *
	 * @since 4.9.0
	 *
	 * @return string
	 */
	public function get_hint(): string {
		return $this->hint;
	}

	/**
	 * Returns the title of the section.
	 *
	 * @since 4.9.0
	 *
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * Returns the size of the section.
	 *
	 * @since 4.9.0
	 *
	 * @return int
	 */
	public function get_size(): int {
		return $this->size;
	}

	/**
	 * Sets the size of the section.
	 *
	 * @since 4.9.0
	 *
	 * @param int $size Size.
	 *
	 * @throws InvalidArgumentException If the size is greater than the column number.
	 *
	 * @return self
	 */
	public function set_size( int $size ): self {
		if ( $size < 1 ) {
			throw new InvalidArgumentException( 'The size cannot be less than 1.' );
		}

		if ( $size > $this->max_columns ) {
			throw new InvalidArgumentException(
				sprintf( 'The size cannot be greater than the maximum column number: %d.', $this->max_columns )
			);
		}

		$this->size = $size;

		return $this;
	}
}
