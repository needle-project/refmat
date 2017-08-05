<?php
namespace NeedleProject\RefMat;

class ArrayHelper
{
    /**
     * Verify if a key exist in depth
     * @param $array - The array in wich to search
     * @param $keys - an array with the linear items treated as depth. Ex: array('first_level','second_level','third_level')
     * @todo - Refactor to non-static, convert from recursive to linear to avoid maximum level of recursion limit
     * @return bool
     */
    public function hasKeysInDepth($array, $keys)
    {
        /** If the array is null */
        if (is_null($array) || !is_array($array)) {
            return false;
        }
        $depth = count($keys);
        $firstItem = array_key_exists($keys[0], $array);
        if ($depth == 1) {
            return $firstItem;
        } else {
            if ($firstItem === true) {
                $array = $array[$keys[0]];
                unset($keys[0]);
                $keys = array_values($keys);
                return self::hasKeysInDepth($array, $keys);
            } else {
                return false;
            }
        }
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