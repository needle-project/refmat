<?php
namespace NeedleProject\RefMat;

use NeedleProject\RefMat\Exception\NotFoundException;

class ArrayHelper
{
    /**
     * Verify if a key exist in depth
     *
     * @param array $array  - The array in which to search
     * @param array $keys - an array with the linear items treated as depth.
     *                      Ex: array('first_level','second_level','third_level')
     * @todo    Refactor in a "non-breaking" mather - to many breaks
     *          Improve design
     * @return bool
     */
    public function hasKeysInDepth($array, $keys)
    {
        if (!is_array($array) || empty($array)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Cannot search keys in depth, expected a non-empty haystack array, got %s or empty array.",
                    gettype($array)
                )
            );
        }
        if (!is_array($keys) || empty($keys)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Cannot search keys in depth, expected a non-empty keys array, got %s or empty array.",
                    gettype($array)
                )
            );
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
            if (!is_array($haystack)) {
                $result = false;
                break;
            }
        }
        return $result;
    }

    /**
     * Get a value in depth of an array
     * @param $array - The array in which to search
     * @param $keys - an array with the linear items treated as depth.
     *                 Ex: array('first_level','second_level','third_level')
     * @return string
     * @todo    Refactor in a "non-breaking" mather - to many breaks
     *          Improve design
     * @throws \Exception
     */
    public function getValueFromDepth($array, $keys)
    {
        if (!is_array($array) || empty($array)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Cannot get value in depth, expected a non-empty haystack array, got %s or empty array.",
                    gettype($array)
                )
            );
        }
        if (!is_array($keys) || empty($keys)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Cannot get value in depth, expected a non-empty keys array, got %s or empty array.",
                    gettype($array)
                )
            );
        }
        $result = '';
        $haystack = $array;
        $depth = count($keys);
        for ($i = 0; $i < $depth; $i++) {
            $isLast = ($i + 1) === $depth;
            $needle = $keys[$i];
            if ($isLast && array_key_exists($needle, $haystack)) {
                $result = $haystack[$needle];
                break;
            }
            if (!isset($haystack[$needle]) || !is_array($haystack[$needle])) {
                throw new NotFoundException(
                    sprintf(
                        "Could not find requested value in %s for given path %s",
                        json_encode($array),
                        implode(' -> ', $keys)
                    )
                );
            }
            $haystack = $haystack[$needle];
        }
        return $result;
    }
}
