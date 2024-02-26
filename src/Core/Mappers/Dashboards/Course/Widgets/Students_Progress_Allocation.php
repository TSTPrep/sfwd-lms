<?php
/**
 * Students progress allocation widget.
 *
 * @since 4.9.0
 *
 * @package LearnDash\Core
 */

namespace LearnDash\Core\Mappers\Dashboards\Course\Widgets;

use LearnDash\Core\Template\Dashboards\Widgets\Interfaces;
use LearnDash\Core\Template\Dashboards\Widgets\Traits\Supports_Post;
use LearnDash\Core\Template\Dashboards\Widgets\Types\DTO\Values_Item;
use LearnDash\Core\Template\Dashboards\Widgets\Types\Values;
use Learndash_DTO_Validation_Exception;
use StellarWP\Learndash\StellarWP\DB\DB;

/**
 * Students progress allocation widget.
 *
 * @since 4.9.0
 */
class Students_Progress_Allocation extends Values implements Interfaces\Requires_Post {
	use Supports_Post;

	/**
	 * Number of students enrolled in the course. Default 0.
	 *
	 * @since 4.9.0
	 *
	 * @var int
	 */
	private $students_number_total = 0;

	/**
	 * Number of students who completed the course. Default 0.
	 *
	 * @since 4.9.0
	 *
	 * @var int
	 */
	private $students_number_completed = 0;

	/**
	 * Number of students who are in progress. Default 0.
	 *
	 * @since 4.9.0
	 *
	 * @var int
	 */
	private $students_number_in_progress = 0;

	/**
	 * Number of students who have not started the course. Default 0.
	 *
	 * @since 4.9.0
	 *
	 * @var int
	 */
	private $students_number_not_started = 0;

	/**
	 * Loads required data.
	 *
	 * @since 4.9.0
	 *
	 * @return void
	 */
	protected function load_data(): void {
		$this->load_progress_allocation();

		$items_data = [
			[
				'label'     => __( 'Completed', 'learndash' ),
				'value'     => $this->get_percentage( $this->students_number_completed ) . '%',
				'sub_label' => sprintf(
					// translators: %s: number of students.
					_n( '%s student', '%s students', $this->students_number_completed, 'learndash' ),
					$this->students_number_completed
				),
			],
			[
				'label'     => __( 'In Progress', 'learndash' ),
				'value'     => $this->get_percentage( $this->students_number_in_progress ) . '%',
				'sub_label' => sprintf(
					// translators: %s: number of students.
					_n( '%s student', '%s students', $this->students_number_in_progress, 'learndash' ),
					$this->students_number_in_progress
				),
			],
			[
				'label'     => __( 'Not Started', 'learndash' ),
				'value'     => $this->get_percentage( $this->students_number_not_started ) . '%',
				'sub_label' => sprintf(
					// translators: %s: number of students.
					_n( '%s student', '%s students', $this->students_number_not_started, 'learndash' ),
					$this->students_number_not_started
				),
			],
		];

		$items = [];

		foreach ( $items_data as $item ) {
			try {
				$items[] = new Values_Item( $item );
			} catch ( Learndash_DTO_Validation_Exception $e ) {
				continue;
			}
		}

		$this->set_items( $items );
	}

	/**
	 * Loads the progress allocation data.
	 *
	 * @since 4.9.0
	 *
	 * @return void
	 */
	private function load_progress_allocation(): void {
		$enrolled_students = $this->get_enrolled_student_ids();

		if ( empty( $enrolled_students ) ) {
			return;
		}

		$this->students_number_total = count( $enrolled_students );

		foreach ( $enrolled_students as $student_id ) {
			$student_progress = (array) learndash_user_get_course_progress( $student_id, $this->get_post()->ID );

			if ( ! isset( $student_progress['status'] ) ) {
				$this->students_number_not_started++; // no progress.
				continue;
			}

			switch ( $student_progress['status'] ) {
				case 'completed':
					$this->students_number_completed++;
					break;
				case 'in_progress':
					$this->students_number_in_progress++;
					break;
				default:
					$this->students_number_not_started++; // no progress.
					break;
			}
		}
	}

	/**
	 * Returns the percentage based on the total number of students.
	 *
	 * @since 4.9.0
	 *
	 * @param int $value The value to calculate the percentage.
	 *
	 * @return float
	 */
	private function get_percentage( int $value ): float {
		if ( 0 === $this->students_number_total ) {
			return 0;
		}

		return round( ( $value / $this->students_number_total ) * 100, 2 );
	}

	/**
	 * Returns the enrolled students' IDs.
	 *
	 * @since 4.9.0
	 *
	 * @return array<int>
	 */
	private function get_enrolled_student_ids(): array {
		return array_unique(
			array_merge(
				$this->get_directly_enrolled_student_ids(),
				$this->get_group_based_enrolled_student_ids()
			)
		);
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
