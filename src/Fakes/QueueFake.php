<?php

namespace NoelDeMartin\LaravelDusk\Fakes;

use Illuminate\Support\Facades\App;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Testing\Fakes\QueueFake as ParentQueueFake;

class QueueFake extends ParentQueueFake
{
//    protected $diskRoots = [];
//    protected $connections = [];

    /**
     * Replace the given disk with a local testing disk.
     *
     * @param  string|null  $disk
     *
     * @return void
     */
//    public function fake($connection = null)
//    {
//        $connection = $connection ?: Config::get('queue.default');
//
////        (new Filesystem)->cleanDirectory(
////            $root = storage_path('framework/testing/disks/'.$disk)
////        );
//
//        $this->connections[$connection] = $root;
//        $this->disks[$disk] = App::make('filesystem')->createLocalDriver(['root' => $root]);
//    }

    /**
     * Checks if the current disk being faked.
     *
     * @return bool
     */
//    public function isFaking($disk)
//    {
//        return isset($this->disks[$disk]);
//    }

    /**
     * Get a filesystem instance.
     *
     * @param  string  $disk
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
//    public function queue($connection = null)
//    {
//        $connection = $connection ?: Config::get('queue.default');
//
//        return isset($this->connections[$connection])
//            ? $this->connections[$connection]
//            : App::make('queue')->connection($connection);
//    }

    /**
     * Dynamically call the default disk instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
//    public function __call($method, $parameters)
//    {
//        return $this->queue()->$method(...$parameters);
//    }

    /**
     * Prepare object for serialization.
     *
     * @return array
     */
    public function __sleep()
    {
        return ['jobs'];
    }

    /**
     * Restore object after deserialization.
     *
     * @return void
     */
    public function __wakeup()
    {
//        foreach ($this->connections as $key => $connection) {
//            $this->connections[$connection] = App::make('queue')/*->createLocalDriver(['root' => $root])*/;
//        }
    }
}
