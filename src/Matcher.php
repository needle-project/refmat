<?php
namespace NeedleProject\RefMat;

use NeedleProject\Common\Helper\ArrayHelper;

class Matcher
{
    /**
     * @var null|string
     */
    private $tokenExpression = null;

    /**
     * @var null|string
     */
    private $leftTokenDelimiter = null;

    /**
     * @var null|string
     */
    private $rightTokenDelimiter = null;

    /**
     * @var null|string
     */
    private $pathSeparator = null;

    /**
     * @var null|int
     */
    private $retryTimes = null;

    /**
     * @var null|ArrayHelper
     */
    private $arrayHelper = null;

    /**
     * Matcher constructor.
     *
     * @param string $leftTokenDelimiter
     * @param string $rightTokenDelimiter
     * @param string $pathSeparator
     * @param int    $retryTimes    The number of loop retries until it quits trying
     *                              This occurs when a reference points to another reference in reverse order
     * @param bool   $strict    Whether should throw an exception when not all tokens have been replaced
     */
    public function __construct(
        $leftTokenDelimiter = '[[',
        $rightTokenDelimiter = ']]',
        $pathSeparator = '.',
        $retryTimes = 3,
        $strict = false
    ) {
        $this->tokenExpression = sprintf(
            '/%1$s([^%1$s%2$s]*)%2$s/',
            preg_quote($leftTokenDelimiter),
            preg_quote($rightTokenDelimiter)
        );
        $this->leftTokenDelimiter = $leftTokenDelimiter;
        $this->rightTokenDelimiter = $rightTokenDelimiter;
        $this->pathSeparator = $pathSeparator;
        $this->retryTimes = $retryTimes;
    }

    /**
     * @return \NeedleProject\Common\Helper\ArrayHelper|null
     */
    protected function getArrayHelper()
    {
        if (is_null($this->arrayHelper)) {
            $this->arrayHelper = new ArrayHelper();
        }
        return $this->arrayHelper;
    }

    /**
     * @param array $inputArray
     * @return array mixed
     */
    public function buildSet($inputArray)
    {
        $tokenReferences = $this->findTokens(json_encode($inputArray));
        $retryCounter = 0;
        while (!empty($tokenReferences) && $retryCounter < $this->retryTimes) {
            $retryCounter++;
            foreach ($tokenReferences as $key => $token) {
                $tokenPath = explode($this->pathSeparator, $token);
                if ($this->getArrayHelper()->hasKeysInDepth($inputArray, $tokenPath)) {
                    // should replace
                    $value = $this->getArrayHelper()
                        ->getValueFromDepth($inputArray, $tokenPath);
                    $replacedString = $this->replaceToken(
                        $token,
                        json_encode($value),
                        json_encode($inputArray)
                    );
                    unset($tokenReferences[$key]);
                    $inputArray = json_decode($replacedString, true);
                }
            }
        }
        return $inputArray;
    }

    /**
     * Find tokens based on regexp
     * @param string $inputString
     * @return array
     */
    private function findTokens($inputString)
    {
        preg_match_all($this->tokenExpression, $inputString, $results);
        if (!isset($results[1])) {
            return [];
        }
        return $results[1];
    }

    /**
     * Replace all tokens in the string (json_encode the array)
     * @param string $token
     * @param string $stringValue
     * @param string $inputString
     * @return string
     */
    private function replaceToken($token, $stringValue, $inputString)
    {
        return preg_replace(
            sprintf(
                '/\"%1$s%2$s%3$s\"/',
                preg_quote($this->leftTokenDelimiter),
                preg_quote($token),
                preg_quote($this->rightTokenDelimiter)
            ),
            $stringValue,
            $inputString
        );
    }
}
