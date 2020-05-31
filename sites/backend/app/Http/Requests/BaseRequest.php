<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Parses the request to Carbon.
     *
     * @param string $request
     * @param bool $resetTime
     * @return Carbon
     */
    protected function toDate(string $request, bool $resetTime = true): Carbon
    {
        if ($resetTime) {
            return Carbon::createFromFormat($this->dateFormat, $request)->startOfDay();
        } else {
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
