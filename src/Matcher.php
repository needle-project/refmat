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

        $this->tokenExpression = sprintf('/%1$s([^%1$s%2$s]*)%2$s/', preg_quote($leftTokenDelimiter), preg_quote($rightTokenDelimiter));
    }

    /**
     * @param array $inputArray
     * @return array mixed
     */
    public function buildSet($inputArray)
    {
        $arrayHelper = new ArrayHelper();
        $tokenReferences = $this->findTokens(json_encode($inputArray));
        $it = 0;
        while (!empty($tokenReferences) && $it < 10) {
            $it++;
            foreach ($tokenReferences as $key => $token) {
                $tokenPath = explode('.', $token);
                if ($arrayHelper->hasKeysInDepth($inputArray, $tokenPath)) {
                    // should replace
                    $value = $arrayHelper->getValueFromDepth($inputArray, $tokenPath);
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
}
