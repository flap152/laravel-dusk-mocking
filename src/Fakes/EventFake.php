<?php

namespace NoelDeMartin\LaravelDusk\Fakes;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\EventFake as BaseEventFake;
use Serializable;

class EventFake extends BaseEventFake /*implements Serializable*/
{

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * Prepare object for serialization.
     *
     * @return array
     */
    public function __sleep()
    {
        $attributes = array_keys(get_object_vars($this));
//        ray($attributes);
        $index = array_search('dispatcher', $attributes);

        if ($index !== false) {
            array_splice($attributes, $index, 1);
        }
//        $this->dispatcher = null;
//            ray($attributes);
        return $attributes;
    }

//    public function __serialize(): array
//    {
//        $attr = [
//            'eventsToFake' => $this->eventsToFake,
//            'events' => $this->events,
//            'dispatcher' => get_class($this->dispatcher),
//        ];
//        ray($this);
//        ray($attr);
//        return $attr;
//    }

    /**
     * Restore object after deserialization.
     *
     * @return void
     */
    public function __wakeup()
    {   //ray($this->eventsToFake);ray($this->events);
        $disp = new \Illuminate\Events\Dispatcher(app());//$disp = App::make(Dispatcher::class);
        $this->dispatcher = $disp;
        Model::setEventDispatcher( $disp);

    }

//    public function __unserialize(array $data): void
//    {
////        $this->eventsToFake = unserialize( $data['eventsToFake']);// ?? [];
//        $this->eventsToFake = uss($data['eventsToFake']);// ?? [];
//        $this->events = uss/*unserialize*/ ($data['events']); // ?? [];
////        $this->dispatcher = App::make(/*$data['dispatcher'] ??*/ Dispatcher::class);
//        if ($data['dispatcher'] ?? '' == EventFake::class) {
//            $this->dispatcher = new EventFake(new Dispatcher(app()), $this->eventsToFake);
//        } else {
//            $this->dispatcher = new Dispatcher(app());
//        }
//        ray($data);
//        ray($this);
//    }

//    public static function fake($eventsToFake = [])
//    {
//        $real = Model::getEventDispatcher();
//        static::swap($fake = new \Illuminate\Support\Testing\Fakes\EventFake(static::getFacadeRoot(), $eventsToFake));
//
//        Model::setEventDispatcher($real);
//        Cache::refreshEventDispatcher();
//
//        return $fake;
//    }


//    function uss($php)
//    {
//        ray($php);
//        $classes = [
//            EventFake::class => function (EventFake $val) { /*ray($val);*/
//                return Arr::except($val->getEvents(), []);
//            },
////            default => function(EventFake $val){ ray($val); return Arr::except($val->getEvents(), [] ); } ,
//        ];
//        if (is_array($php)) {
//            foreach ($php as $key => $value) {
//                $temp = $this->uss($value);
//                if ($temp != $value)
//                    $php[$key] = $temp;
//            }
//            return $php;
//        }
//        if (is_object($php)) {
////            ray($php);
////            $treatment = match($php::class){
////                EventFake::class => function(/*EventFake*/ $val){ /*ray($val);*/ return Arr::except($val->getEvents(), [] ); } ,
////                default => function(EventFake $val){ /*ray($val);*/ return Arr::except($val->getEvents(), [] ); }
////                default => function($val){ /*ray($val);*/ return []; },
////            };
////            $res = $treatment($php);
//            $res = json_decode(json_encode($php, JSON_PRETTY_PRINT, 5), true);
//            ray($res);
//            return $res; // unserialize(serialize($php), ['allowed_classes'=> false]);
//
//        }
//        if (!is_string($php))
//            return $php;
//        if (Str::startsWith($php, '{')) {
//            $ret = (str_replace('\\', '_', $php));
//            $ret = json_decode($ret, true);
//            return $this->uss($ret);
//        }
//        if (base64_encode(base64_decode($php, true)) === $php) {
//            $ret = (base64_decode($php));
//            return $this->uss($ret);
//        }
//        if (strlen($php) > 10) {
//            try {
//                $ret = unserialize($php, ['allowed_classes' => false]);
//            } catch (Exception $e) {
//                ray($e);
//                return $php;
//            }
//            if ($ret === false) return $php;
//            return $this->uss($ret);
//        }
//        return $php;
//    }


}
