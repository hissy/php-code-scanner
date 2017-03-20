<?php
namespace Hissy\PhpCodeScanner\Analyzer;

use Symfony\Component\Finder\SplFileInfo;

/**
 * Class AbstractAnalyzer
 * @package Hissy\PhpCodeScanner\Analyzer
 */
abstract class AbstractAnalyzer
{
    /**
     * @param SplFileInfo $fileInfo
     * @return array An array of token identifiers.
     */
    protected function getTokens(SplFileInfo $fileInfo)
    {
        $source = $fileInfo->getContents();
        return token_get_all($source);
    }

    /**
     * @param array $tokens
     * @param string $function
     * @return array
     */
    protected function findFunction(array $tokens, $function)
    {
        $positions = [];

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if ($token[0] == T_STRING && $token[1] == $function) {
                    $positions[] = $token[2];
                }
            }
        }

        return $positions;
    }

    protected function strpos(SplFileInfo $fileInfo, $needle)
    {
        return strpos($fileInfo->getContents(), $needle);
    }

    /**
     * @param array $tokens
     * @param int $start
     * @param string $name
     * @return mixed|null
     */
    protected function catchVariable(array $tokens, $start = 0, $name = '')
    {
        $token = null;
        for ($i = $start; $i < count($tokens); $i++) {
            if ($tokens[$i] == ';') {
                return $token;
            }
            if (is_array($tokens[$i]) && $tokens[$i][0] == T_VARIABLE) {
                if ($name == '' || $tokens[$i][1] == $name) {
                    $token = $tokens[$i];
                }
            }
        }
    }

    /**
     * @param array $tokens
     * @param int $start
     * @param string $name
     * @return mixed|null
     */
    protected function catchString(array $tokens, $start = 0, $name = '')
    {
        $token = null;
        for ($i = $start; $i < count($tokens); $i++) {
            if ($tokens[$i] == ';') {
                return $token;
            }
            if (is_array($tokens[$i]) && $tokens[$i][0] == T_STRING && $tokens[$i][1] == $name) {
                $token = $tokens[$i];
            }
        }
    }
}