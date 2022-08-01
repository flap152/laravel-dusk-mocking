<?php

namespace NoelDeMartin\LaravelDusk;

use Exception;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use NoelDeMartin\LaravelDusk\Fakes\EventFake;
use NoelDeMartin\LaravelDusk\Fakes\QueueFake;
use Serializable;
use Symfony\Component\HttpFoundation\Response;
use NoelDeMartin\LaravelDusk\Fakes\StorageFake;
use function json_last_error;
use function json_last_error_msg;

//use Laravel\SerializableClosure\SerializableClosure;

abstract class Driver
{
    /**
     * The array of active facade mocks.
     *
     * @var array
     */
    protected $mocks = [];

    /**
     * The array of fake facade classes.
     *
     * @var array
     */
    protected $fakes = [];

    /**
     * Storage fake must remain the same instance because it can
     * be faked more than once (when testing multiple disks).
     *
     * @var NoelDeMartin\LaravelDusk\Fakes\StorageFake|null
     */
    protected $storageFake = null;

    /**
     * Start mocking facades.
     *
     * @return void
     */
    public function start()
    {
        $this->load();// ray($this->mocks);ray($this->fakes);
        /**
         * @var Facade $facade
         * @var  $mock
         */
        foreach ($this->mocks as $facade => $mock) {
//            ray($facade);ray(/*json_encode*/( $mock));
//            if (! is_a($mock, ))
            $facade::swap($mock);
            if (!$this->isADispatcher($mock)) {
                ray('not a Dispatcher');
            }
            switch ($facade) {
                case Event::class:
//                    if (! $this->isADispatcher($mock)){
//                        var_dump($mock); //$mock = $mock->dispatcher;
//                        ray('not a Dispatcher');
//                    } else {
                    $facade::swap($mock);
                    Model::setEventDispatcher(new Dispatcher(app()));
//                    }
//                    Model::setEventDispatcher($mock);
                    break;
            }
        }
    }

    public function isADispatcher($mock)
    {
        $res = is_a($mock, \Illuminate\Contracts\Events\Dispatcher::class) ||
            (is_object($mock) && method_exists($mock, 'dispatch'));
        return $res;
    }

    /**
     * Save facades state.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    public function save(Response $response)
    {
        $this->persist($response);
    }

    /**
     * Replace facade instance with a mock.
     *
     * @param string $facade
     * @param mixed[] ...$arguments
     * @return void
     */
    public function mock(string $facade, ...$arguments)
    {
        $mock = $this->createMock($facade, ...$arguments);
        $real = Model::getEventDispatcher();
//        $real = new Dispatcher(app());

        $facade::swap($mock);
//        Model::setEventDispatcher($real);
        $this->mocks[$facade] = $mock;

        switch ($facade) {
            case Event::class:
                if ($real) { // may be null in tests or other situation: robustnenss
                    Model::setEventDispatcher($real);
                }
//                Model::setEventDispatcher(App::make(Dispatcher::class));
//                Model::setEventDispatcher($mock);
                break;
        }
    }

    /**
     * Register new facade fake.
     *
     * @param string $facade
     * @param string $fake
     * @return void
     */
    public function registerFake(string $facade, string $fake)
    {
        $this->fakes[$facade] = $fake;
    }

    /**
     * Determine if a facade is being mocked.
     *
     * @param string $facade
     * @return bool
     */
    public function has(string $facade)
    {
        return isset($this->mocks[$facade]);
    }

    /**
     * Get facade mock.
     *
     * @param string $facade
     * @return mixed
     */
    public function get(string $facade)
    {
        return $this->has($facade) ? $this->mocks[$facade] : null;
    }

    /**
     * Determine if a facade fake is registered.
     *
     * @param string $facade
     * @return bool
     */
    public function hasFake(string $facade)
    {
        return isset($this->fakes[$facade]);
    }

    /**
     * Get registered fake.
     *
     * @param string $facade
     * @return string | null
     */
    public function getFake(string $facade)
    {
        return $this->hasFake($facade) ? $this->fakes[$facade] : null;
    }

    /**
     * Serialize a facade mock.
     *
     * @param string $facade
     * @return string
     */
    public function serialize(string $facade)
    {
        try {/*ray($facade);ray(*/
            $m = $this->mocks[$facade]/*)*/
            ;
            $ser = serialize($m)/*->serialize()*/
            ;
//            $ser =  sserialize($m)/*->serialize()*/;
//            /*$ser = serialize($this->mocks[$facade]);*/ray($ser);
        } catch (Exception $exception) {
            ray($m, $exception);
            //$ser = serialize(null);
            $ser = json_encode($this->mocks[$facade], 0, 2);
            ray(json_last_error(), json_last_error_msg());
                }
        ray($ser);
        return base64_encode($ser);
    }

    /**
     * Unserialize a facade mock.
     *
     * @param string $serializedMock
     * @return mixed
     */
    public function unserialize(string $serializedMock)
    { //ray($serializedMock);
//        $ser = base64_decode( $serializedMock);ray($ser);
//        if ($this->isjson($ser)) {ray(json_decode($ser));
//            return json_decode($ser);
//        }ray(__LINE__);
        $mock = uss($serializedMock);
        //ray($mock);
        return $mock; //  unserialize($ser);
    }

    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE
            && $string != '{}';
    }

    /**
     * Create a facade mock.
     *
     * @param $facade   string
     * @param mixed[] ...$arguments
     * @return mixed
     */
    protected function createMock(string $facade, ...$arguments)
    {   //ray("creating $facade");ray($this->fakes);
        if (isset($this->fakes[$facade])) {
            return new $this->fakes[$facade](...$arguments);
        }

        switch ($facade) {
            case Storage::class:
                $storageFake = $this->getStorageFake();

                $storageFake->fake(...$arguments);

                return $storageFake;
            case Event::class:
//                 App::make(Dispatcher::class)
                return new EventFake($facade::getFacadeRoot(), ...$arguments);
            case Queue::class:
                return new QueueFake($facade::getFacadeRoot(), ...$arguments);
            default:
//                Log::debug('facade is not mentiond- case default');
                ray('facade is not mentioned - case default' . $facade);
                try {
                    $facade::fake(...$arguments);
                } catch (Exception $e) {
                    $facade::mock(...$arguments);
                }

                return $facade::getFacadeRoot();
        }
    }

    /**
     * Lazy-load the storage fake.
     *
     * @return NoelDeMartin\LaravelDusk\Fakes\StorageFake
     */
    protected function getStorageFake()
    {
        if (is_null($this->storageFake)) {
            $this->storageFake = new StorageFake;
        }

        return $this->storageFake;
    }

    /**
     * Load data from storage.
     *
     * @return void
     */
    abstract protected function load();

    /**
     * Persists data.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    abstract protected function persist(Response $response);
}
