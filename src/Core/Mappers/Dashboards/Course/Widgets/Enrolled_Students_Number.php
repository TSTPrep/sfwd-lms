<?php
/**
 * Enrolled students number widget.
 *
 * @since 4.9.0
 *
 * @package LearnDash\Core
 */

namespace LearnDash\Core\Mappers\Dashboards\Course\Widgets;

use LearnDash\Core\Template\Dashboards\Widgets\Interfaces;
use LearnDash\Core\Template\Dashboards\Widgets\Traits\Supports_Post;
use LearnDash\Core\Template\Dashboards\Widgets\Types\Value;
use StellarWP\Learndash\StellarWP\DB\DB;

/**
 * Enrolled students number widget.
 *
 * @since 4.9.0
 */
class Enrolled_Students_Number extends Value implements Interfaces\Requires_Post {
	use Supports_Post;

	/**
	 * Loads required data.
	 *
	 * @since 4.9.0
	 *
	 * @return void
	 */
	protected function load_data(): void {
		$this->set_label(
			__( 'Enrolled Students', 'learndash' )
		);

		$this->set_value(
			$this->get_enrolled_students_number()
		);
	}

	/**
	 * Returns the number of enrolled students.
	 *
	 * @since 4.9.0
	 *
	 * @return int
	 */
	private function get_enrolled_students_number(): int {
		$unique_student_ids = array_unique(
			array_merge(
				$this->get_directly_enrolled_student_ids(),
				$this->get_group_based_enrolled_student_ids()
			)
		);

		return count( $unique_student_ids );
	}

	/**
	 * Returns the ids of directly enrolled students.
	 *
	 * @since 4.9.0
	 *
	 * @return int[]
	 */
	private function get_directly_enrolled_student_ids(): array {
		return learndash_get_course_users_access_from_meta( $this->get_post()->ID );
	}

	/**
	 * Returns the ids of students enrolled via groups.
	 *
	 * @since 4.9.0
	 *
	 * @return int[]
	 */
	private function get_group_based_enrolled_student_ids(): array {
		$course_groups_ids = learndash_get_course_groups( $this->get_post()->ID );

		if ( empty( $course_groups_ids ) ) {
			return [];
		}

		// Count users that belong to any of the course groups.

		$enrolled_users_sql = DB::table( 'usermeta' )
			->select( 'user_id' )
			->whereIn(
				'meta_key',
				array_map(
					function( $course_group_id ) {
						return "learndash_group_users_{$course_group_id}";
					},
					$course_groups_ids
				)
			)
			->groupBy( 'user_id' )
			->getSQL();

		return DB::get_col( $enrolled_users_sql );
	}
}
