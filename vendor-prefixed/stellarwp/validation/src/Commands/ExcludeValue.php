<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by learndash on 22-September-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace StellarWP\Learndash\StellarWP\Validation\Commands;

/**
 * Returning this value from the __invoke method of a ValidationRule will stop all validation rules and exclude the
 * value from the validated dataset.
 *
 * @since 1.2.0
 */
class ExcludeValue
{

}
