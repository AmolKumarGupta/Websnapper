<?php 

if (! function_exists('hashget')) {
    function hashget(string $str, bool $decode = false): string {
        if ($decode === false) {
            return base64_encode("websnapper-{$str}");
        }

        $decodedStr = base64_decode($str);
        return str_replace("websnapper-", "", $decodedStr);
    }
}