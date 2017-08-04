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
        return $this->searchArray($inputArray);
    }

    protected function searchArray($array)
    {
        $foundAll = true;
        foreach ($array as $key => $value) {
            if (is_array($value) || !$this->isToken($value)) {
                continue;
            }
            if ($tokenValue = $this->findToken($value, $array)) {
                $array[$key] = $tokenValue;
                continue;
            }
            $foundAll = false;
        }
        if (!$foundAll) {
            return $this->searchArray($array);
        }
        return $array;
    }

    /**
     * @param mixed $item
     * @return bool
     */
    protected function isToken($item)
    {
        return (substr($item, 0, $this->computedLeftLength) === $this->leftTokenDelimiter
                && substr($item, -1 * $this->computedRightLength) === $this->rightTokenDelimiter);
    }

    protected function extractToken($item)
    {
        return substr($item, $this->computedLeftLength, $this->computedRightLength * -1);
    }

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