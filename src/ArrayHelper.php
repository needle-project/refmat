<?php
namespace NeedleProject\RefMat;

class ArrayHelper
{
    /**
     * Verify if a key exist in depth
     * @param $array - The array in which to search
     * @param $keys - an array with the linear items treated as depth. Ex: array('first_level','second_level','third_level')
     * @return bool
     */
    public function hasKeysInDepth($array, $keys)
    {
        if (is_null($array) || !is_array($array)) {
            return false;
        }
        $result = false;
        $haystack = $array;
        $depth = count($keys);
        for ($i = 0; $i < $depth; $i++) {
            $isLast = ($i + 1) === $depth;
            $needle = $keys[$i];
            if ($isLast && array_key_exists($needle, $haystack)) {
                $result = true;
                break;
            }
            if (!isset($haystack[$needle])) {
                $result = false;
                break;
            }
            $haystack = $haystack[$needle];
        }
        return $result;
    }

    /**
     * Get a value in depth of an array
     * @param $array - The array in wich to search
     * @param $keys - an array with the linear items treated as depth. Ex: array('first_level','second_level','third_level')
     * @todo - Refactor to non-static, convert from recursive to linear to avoid maximum level of recursion limit
     * @return string
     * @throws \Exception
     */
    public static function getValueFromDepth($array, $keys)
    {
        /** If the array is null */
        if (is_null($array) || empty($keys)) {
            throw new \Exception('Empty array or empty search keys!');
        }
        $depth = count($keys);
        $firstItem = $array[$keys[0]];
        if ($depth == 1) {
            return $firstItem;
        } else {
            $array = $array[$keys[0]];
            unset($keys[0]);
            $keys = array_values($keys);
            return self::getValueFromDepth($array, $keys);
        }
    }
}