<?php
class WxLog { public static function DEBUG($sp2af324) { \Illuminate\Support\Facades\Log::debug($sp2af324); } public static function WARN($sp2af324) { \Illuminate\Support\Facades\Log::warning($sp2af324); } public static function ERROR($sp2af324) { $spa5a100 = debug_backtrace(); $sp3381f8 = '['; foreach ($spa5a100 as $sp7b7024 => $spcb4459) { if (array_key_exists('file', $spcb4459)) { $sp3381f8 .= ',file:' . $spcb4459['file']; } if (array_key_exists('line', $spcb4459)) { $sp3381f8 .= ',line:' . $spcb4459['line']; } if (array_key_exists('function', $spcb4459)) { $sp3381f8 .= ',function:' . $spcb4459['function']; } } $sp3381f8 .= ']'; \Illuminate\Support\Facades\Log::error($sp3381f8 . $sp2af324); } public static function INFO($sp2af324) { \Illuminate\Support\Facades\Log::info($sp2af324); } }