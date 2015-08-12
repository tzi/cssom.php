<?php

namespace CSSOM;

class Parser
{

    public function parseCSSTextRules($cssText)
    {
        $cssTextRules = [];
        $level = 0;
        $caret = 0;
        while ($caret < strlen($cssText)) {
            if ($cssText[$caret] == '}') {
                if ($level == 0) {
                    throw new \RuntimeException('Closing brackets without opening one.');
                }
                $level--;
                if ($level == 0) {
                    $cssTextRules[] = substr($cssText, 0, $caret + 1);
                    $cssText = substr($cssText, $caret + 1);
                    $caret = 0;
                }
            } else if ($cssText[$caret] == '{') {
                $level++;
            }

            $caret++;
        }
        if (trim($cssText) != '') {
            throw new \RuntimeException('Missing closing brackets.');
        }
        return $cssTextRules;
    }

    public function parseCSSSelector($cssText)
    {
        return substr($cssText, 0, strpos($cssText, '{'));
    }

    public function parseCSSStyle($cssText)
    {
        $start = strpos($cssText, '{') + 1;
        return substr($cssText, $start, strpos($cssText, '}') - $start);
    }
}