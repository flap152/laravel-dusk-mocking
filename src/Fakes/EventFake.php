<?php

namespace NoelDeMartin\LaravelDusk\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Testing\Fakes\EventFake as BaseEventFake;
//use Spatie\ModelStates\Events\StateChanged;

class EventFake extends BaseEventFake
{
    /**
     * Prepare object for serialization.
     *
     * @return array
     */
    public function __sleep()
    {
        $attributes = array_keys(get_object_vars($this));

        $index = array_search('dispatcher', $attributes);

        if ($index !== false) {
            array_splice($attributes, $index, 1);
        }

        return $attributes;
    }

    /**
     * Restore object after deserialization.
     *
     * @return void
     */
    public function __wakeup()
    {
        $this->dispatcher = App::make(Dispatcher::class);
    }

    /**
     * Determine if an event should be faked or actually dispatched.
     *
     * @param string $eventName
     * @param mixed $payload
     * @return bool
     */
    protected function shouldFakeEvent($eventName, $payload)
    {
        if (! class_exists('Spatie\ModelStates\Events\StateChanged')){
            return parent::shouldFakeEvent($eventName, $payload);
        }
        // is it about Spatie Changed? if not, return parent
        if ($eventName != 'Spatie\ModelStates\Events\StateChanged') {
            return parent::shouldFakeEvent($eventName, $payload);
        }
//        ray('it is about state change');
        // So it's about Spatie State Change.
        // Are we faking it explicitly? then we should fake it
        if (Arr::has($this->eventsToFake, 'Spatie\ModelStates\Events\StateChanged')) return true;
        // Are Model Events faked? then we should fake StateChange, otherwise no.
        $modelEventFaked = Arr::has( get_class_methods(Model::getEventDispatcher()), ['assertDispatched']);
//        ray(['modeelevnetfake'=>$modelEventFaked]);
        return $modelEventFaked;
    }

//return collect($this->eventsToFake)
//->filter(function ($event) use ($eventName, $payload) {
//                return $event instanceof Closure
//                            ? $event($eventName, $payload)
//                            : $event === $eventName;
//            })
//->isNotEmpty();
}
