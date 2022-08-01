<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use NoelDeMartin\LaravelDusk\Fakes\EventFake;

//if (! function_exists('uss')) {
//    /**
//     * Unserializes stuff
//     *
//     * @return mixed
//     */
//    function uss($php)
//    {ray($php);
//        $classes = [
//            EventFake::class => function(EventFake $val){ /*ray($val);*/ return Arr::except($val->getEvents(), [] ); } ,
////            default => function(EventFake $val){ ray($val); return Arr::except($val->getEvents(), [] ); } ,
//        ];
//        if (is_array($php)) {
//            foreach ($php as $key => $value) {
//                $temp = uss($value);
//                if ($temp != $value)
//                    $php[$key] = $temp;
//            }
//            return $php;
//        }
//        if (is_object($php)){
//            ray($php);
////            $treatment = match($php::class){
////                EventFake::class => function(/*EventFake*/ $val){ /*ray($val);*/ return Arr::except($val->getEvents(), [] ); } ,
////                default => function(EventFake $val){ /*ray($val);*/ return Arr::except($val->getEvents(), [] ); }
////                default => function($val){ /*ray($val);*/ return []; },
////            };
////            $res = $treatment($php);
////            $res = json_decode(json_encode($php, JSON_PRETTY_PRINT, 5), true);
////            ray($res);
////            return $res; // unserialize(serialize($php), ['allowed_classes'=> false]);
//            return $php; // unserialize(serialize($php), ['allowed_classes'=> false]);
//
//        }
//        if (! is_string($php) )
//            return $php;
//        if (Str::startsWith($php, '{')) {
//            $ret = (str_replace('\\','_',  $php));
//            $ret = json_decode($ret,true);
//            return uss($ret);
//        }
//        if ( base64_encode(base64_decode($php, true)) === $php){
//            $ret = (base64_decode($php));
//            return uss($ret);
//        }
//        if (strlen($php) > 10){
//            try {
//                $ret = unserialize($php, ['allowed_classes' => false]);
//            } catch (Exception $e) {
//                ray($e);ray($e->getTrace());
//                return $php;
//            }
//            if ($ret === false) return $php;
//            return uss($ret);
//        }
//        return $php;
//    }
//}

