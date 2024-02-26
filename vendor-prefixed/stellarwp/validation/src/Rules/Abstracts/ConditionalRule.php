<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by learndash on 22-September-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace StellarWP\Learndash\StellarWP\Validation\Rules\Abstracts;

use StellarWP\Learndash\StellarWP\FieldConditions\ComplexConditionSet;
use StellarWP\Learndash\StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\Learndash\StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Learndash\StellarWP\FieldConditions\SimpleConditionSet;
use StellarWP\Learndash\StellarWP\Validation\Config;
use StellarWP\Learndash\StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Learndash\StellarWP\Validation\Contracts\ValidationRule;

abstract class ConditionalRule implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @var ConditionSet
     */
    protected $conditions;

    /**
     * @param ConditionSet|Condition[] $conditions
     */
    public function __construct($conditions)
    {
        if ($conditions instanceof ConditionSet) {
            $this->conditions = $conditions;
        } else {
            $this->conditions = new ComplexConditionSet(...$conditions);
        }
    }

    /**
     * Supports a simple syntax for defining conditions. Example:
     * - ruleId:field1,value1;field2,value2
     *
     * Each rule is assumed to be a basic condition with an equals operator.
     *
     * @since 1.2.0
     */
    public static function fromString(string $options = null): ValidationRule
    {
        if (empty($options)) {
            Config::throwInvalidArgumentException(static::class . ' rule requires at least one condition');
        }

        $rules = explode(';', $options);

        $conditionSet = new SimpleConditionSet();
        foreach ($rules as $rule) {
            $rule = explode(',', $rule);

            if (count($rule) !== 2) {
                Config::throwInvalidArgumentException(static::class . ' rule requires one field name and one value');
            }

            $conditionSet->and($rule[0], '=', $rule[1]);
        }

        return new static($conditionSet);
    }

    /**
     * {@inheritdoc}
     *
     * @since 1.2.0
     */
    public function serializeOption()
    {
        return $this->conditions->jsonSerialize();
    }
}
