<?php

namespace rollun\datanadler\Filter;

use Zend\Filter\AbstractFilter;

/**
 * Class RemoveDigits
 * @package rollun\datanadler\Filter
 */
class RemoveDigits extends AbstractFilter
{
    /**
     * Remove all digits from string
     *
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        if (!(is_string($value) || is_array($value))) {
            return $value;
        }

        $value = preg_replace('/\d/', ' ', $value);

        return $value;
    }
}
