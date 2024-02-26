<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by learndash on 22-September-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace StellarWP\Learndash\StellarWP\FieldConditions\Concerns;

use StellarWP\Learndash\StellarWP\FieldConditions\Config;
use StellarWP\Learndash\StellarWP\FieldConditions\Contracts\Condition;

trait HasLogicalOperator
{
    /**
     * @var 'and'|'or'
     */
    protected $logicalOperator;

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function setLogicalOperator(string $operator)
    {
        if ( ! in_array($operator, Condition::LOGICAL_OPERATORS, true)) {
            throw Config::throwInvalidArgumentException(
                "Invalid logical operator: $operator. Must be one of: " . implode(
                    ', ',
                    Condition::LOGICAL_OPERATORS
                )
            );
        }

        $this->logicalOperator = $operator;
    }

    /**
     * @since 1.0.0
     */
    public function getLogicalOperator(): string
    {
        return $this->logicalOperator;
    }
}
