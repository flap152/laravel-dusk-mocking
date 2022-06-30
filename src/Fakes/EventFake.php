<?php

namespace NoelDeMartin\LaravelDusk\Fakes;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Testing\Fakes\EventFake as BaseEventFake;
use Serializable;

class EventFake extends BaseEventFake /*implements Serializable*/
{
    /**
     * Prepare object for serialization.
     *
     * @return array
     */
    public function x__sleep()
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

    public function __serialize(): array
    {
        $attr = [
            'eventsToFake' => $this->eventsToFake,
            'events' => $this->events,
            'dispatcher' =>  get_class($this->dispatcher),
        ];
        ray($this);ray($attr);
        return  $attr;
    }

    /**
     * Restore object after deserialization.
     *
     * @return void
     */
    public function x__wakeup()
    {   //ray($this->eventsToFake);ray($this->events);
        $this->dispatcher = App::make(Dispatcher::class);
    }

    public function __unserialize(array $data): void
    {
        $this->eventsToFake = unserialize( $data['eventsToFake']);// ?? [];
        $this->events = unserialize($data['events']); // ?? [];
        $this->dispatcher = App::make(/*$data['dispatcher'] ??*/ Dispatcher::class);
        ray($data);ray($this);
    }
}
