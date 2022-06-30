<?php

namespace NoelDeMartin\LaravelDusk\Drivers;

use NoelDeMartin\LaravelDusk\Driver;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionDriver extends Driver
{
    /**
     * Name of the session key used to store mocks.
     *
     * @var string
     */
    const KEY_NAME = 'Dusk-Mocking';

    /**
     * Load mocks from storage.
     *
     * @return void
     */
    protected function load()
    {
        $data = Session::get(self::KEY_NAME, json_encode(['mocks' => [],'fakes' => []]));//ray($data);
        $data = json_decode($data, true);

        foreach ($data['mocks'] as $facade => $serializedMock) { //ray($facade);ray($serializedMock);
            $unserialized = $this->unserialize($serializedMock);
            if ($unserialized) $this->mocks[$facade] = $unserialized;
        }

        $this->fakes = $data['fakes'];
    }

    /**
     * Persist mocks.
     *
     * @param  \Symfony\Component\HttpFoundation\Response   $response
     * @return void
     */
    protected function persist(Response $response)
    {
        $serializedMocks = [];
        foreach (array_keys($this->mocks) as $facade) {
            $serializedMocks[$facade] = $this->serialize($facade);
        }
        ray($this->mocks);
//        ray($serializedMocks);
        Session::put(
            static::KEY_NAME,
            json_encode([
                'mocks' => $serializedMocks,
                'fakes' => $this->fakes,
            ])
        );
    }
}
