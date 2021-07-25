<?php

namespace mmerlijn\forms\Rules;

use Illuminate\Contracts\Validation\Rule;

class Bsn implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bsn = trim($value);
        if ($bsn) {
            // lijst met nummers die qua check kloppen, maar toch niet geldig zijn
            $aInvalid = array('111111110',
                '999999990',
                '000000000');
            $bsn = strlen($bsn) < 9 ? '0' . $bsn : $bsn;
            if (strlen($bsn) != 9 || !ctype_digit($bsn) || in_array($bsn, $aInvalid)) {
                return false;
            }
            $result = 0;
            $products = range(9, 2);
            $products[] = -1;

            foreach (str_split($bsn) as $i => $char) {
                $result += (int) $char * $products[$i];
            }

            return $result % 11 === 0;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Dit is geen geldig BSN nummer';
    }
}
