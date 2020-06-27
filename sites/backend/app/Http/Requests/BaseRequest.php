<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

/**
 * Class BaseRequest
 * @package App\Http\Requests
 */
class BaseRequest extends FormRequest
{
    /**
     * Accepted date format of a date request parameter.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d';

    /**
     * Default page size.
     *
     * @var int
     */
    private const LIMIT = 10;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * The same as `get` method of request
     * but just parsed the value to integer.
     * Returns `null` if the parameter trying
     * to get does not exists.
     *
     * @param string $key
     * @return int|null
     */
    protected function getInt(string $key): ?int
    {
        if ($this->get($key) !== null) {
            return intval($this->get($key));
        }

        return null;
    }

    /**
     * Returns a rule 'exists'.
     *
     * @param string $tableName
     * @param bool|null $notDeleted
     * @param string $column
     * @return Exists
     */
    protected function ruleExists(string $tableName, ?bool $notDeleted = true, string $column = 'id'): Exists
    {
        if ($notDeleted) {
            return Rule::exists($tableName, $column)->whereNull('deleted_at');
        } elseif ($notDeleted === false) {
            return Rule::exists($tableName, $column)->whereNotNull('deleted_at');
        } else {
            return Rule::exists($tableName, $column);
        }
    }

    /**
     * Gets the page size.
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->get('limit') === null ? self::LIMIT : $this->getInt('limit');
    }

    /**
     * Gets the sorted property.
     *
     * @return null|string
     */
    public function getSort(): ?string
    {
        return $this->get('sort');
    }

    /**
     * Gets how the property will be sorted.
     *
     * @return null|string
     */
    public function getSortBy(): ?string
    {
        return $this->get('sortBy');
    }

    /**
     * Gets the base rules for lists.
     *
     * @param array $sortables
     * @return array
     */
    protected function getBaseListRules(array $sortables): array
    {
        return [
            'limit' => [
                'integer',
            ],
            'sortBy' => [
                Rule::in($sortables),
                'required_with:sort',
            ],
            'sort' => [
                'required_with:sortBy',
                Rule::in(['asc', 'desc']),
            ],
        ];
    }

    /**
     * Parses the request to Carbon.
     *
     * @param string $request
     * @param bool|null $isStartOfDay
     * @return Carbon
     */
    protected function toDate(string $request, ?bool $isStartOfDay = null): Carbon
    {
        if ($isStartOfDay) {
            // Resets to the start of the day
            return Carbon::createFromFormat($this->dateFormat, $request)->startOfDay();
        } elseif ($isStartOfDay === false) {
            // Resets to the end of the day
            return Carbon::createFromFormat($this->dateFormat, $request)->endOfDay();
        } else {
            // No reset
            return Carbon::createFromFormat($this->dateFormat, $request);
        }
    }

    /**
     * Adds validations to check the integrity of the start and end values for the given attribute.
     * This method is for fields that are not required, for required fields, best attach the validations
     * directly to the rules method.
     *
     * eg.
     *  The attribute `num_of_players` has fields `start` and `end`
     *  then invoke this method to validate the integrity of these fields.
     *
     * @param string $attribute
     * @param bool $isDate
     * @return void
     */
    protected function addStartEndValidation(string $attribute, bool $isDate = false): void
    {
        /**
         * Checker if the other counterpart exists.
         * Only add the validation if the other part exists.
         *
         * @param $input
         * @param $attribute
         * @param $checkedPart
         * @return bool
         */
        $counterpartExists = function ($input, $attribute, $checkedPart): bool {
            if (strpos($attribute, '.') !== false) {
                // Field is an array
                $field = $input;

                foreach (explode('.', $attribute) as $index) {
                    if (!isset($field[$index])) {
                        // Index does not exists, no need to add validation
                        return false;
                    } else {
                        // Update field
                        $field = $field[$index];
                    }
                }

                // Check for the counterpart if it exists
                return isset($field[$checkedPart]);
            } else {
                // Check for the counter if it exists
                return isset($input[$attribute][$checkedPart]);
            }
        };

        // Default rules for the integer values
        $ruleStart = 'lte:' . $attribute . '.end';
        $ruleEnd = 'gte:' . $attribute . '.start';

        if ($isDate) {
            // Validation rules for dates
            $ruleStart = 'before_or_equal:' . $attribute . '.end';
            $ruleEnd = 'after_or_equal:' . $attribute . '.start';
        }

        // Add validation for the start part
        $this->validator->sometimes(
            $attribute . '.start',
            $ruleStart,
            function ($input) use ($attribute, $counterpartExists): bool {
                return $counterpartExists($input, $attribute, 'end');
            }
        );

        // Add validation for the end part
        $this->validator->sometimes(
            $attribute . '.end',
            $ruleEnd,
            function ($input) use ($attribute, $counterpartExists): bool {
                return $counterpartExists($input, $attribute, 'start');
            }
        );
    }
}
