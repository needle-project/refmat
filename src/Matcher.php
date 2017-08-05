<?php
namespace NeedleProject\RefMat;

class Matcher
{
    private $leftTokenDelimiter;
    private $rightTokenDelimiter;
    private $computedLeftLength = 0;
    private $computedRightLength = 0;

    public function __construct($leftTokenDelimiter = '[[', $rightTokenDelimiter = ']]')
    {
        $this->leftTokenDelimiter = $leftTokenDelimiter;
        $this->rightTokenDelimiter = $rightTokenDelimiter;
        $this->computedLeftLength = strlen($leftTokenDelimiter);
        $this->computedRightLength = strlen($rightTokenDelimiter);
    }

    public function buildSet($inputArray)
    {
        return $this->searchArray($inputArray, $inputArray);
    }

    private $count = 0;

    /**
     * Indetify and apply tokens
     * @param $array
     * @param $baseArray
     * @todo - at the moment, if no token is found it will cause infinite loop (limited by nesting level)
     * @return mixed
     */
    protected function searchArray($array, $baseArray)
    {
        $this->count++;
        if ($this->count == 10) {
            return $array;
        }
        $foundAll = true;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->searchArray($value, $baseArray);
                continue;
            }
            if (!$this->isToken($value)) {
                continue;
            }
            if ($tokenValue = $this->findToken($value, $baseArray)) {
                $array[$key] = $tokenValue;
                continue;
            }
            $foundAll = false;
        }
        if (!$foundAll && $this->count < 10) {
            return $this->searchArray($array, $baseArray);
        }
        return $array;
    }

    /**
     * Validate if the current value is a token representation
     * @param string $item
     * @return bool
     */
    protected function isToken($item)
    {
        return (substr($item, 0, $this->computedLeftLength) === $this->leftTokenDelimiter
                && substr($item, -1 * $this->computedRightLength) === $this->rightTokenDelimiter);
    }

    /**
     * Extract the delimiter from the token representation
     * @param string $item
     * @return string
     */
    protected function extractToken($item)
    {
        return substr($item, $this->computedLeftLength, $this->computedRightLength * -1);
    }

    /**
     * @param string $tokenValue
     * @param array $haystack
     * @return bool|string
     */
    protected function findToken($tokenValue, $haystack)
    {
        $tokenValue = $this->extractToken($tokenValue);
        if (isset($haystack[$tokenValue])) {
            return $haystack[$tokenValue];
        }
        if (strpos($tokenValue, '.') !== false) {
            $depthKeys = explode('.', $tokenValue);
            if (is_array($haystack) && ArrayHelper::hasKeysInDepth($haystack, $depthKeys)) {
                return ArrayHelper::getValueFromDepth($haystack, $depthKeys);
            }
        }
        return false;
    }
}
