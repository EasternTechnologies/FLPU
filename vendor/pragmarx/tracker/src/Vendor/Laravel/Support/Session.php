<?php

namespace PragmaRX\Tracker\Vendor\Laravel\Support;

use Illuminate\Support\Facades\Input;
use PragmaRX\Tracker\Support\Minutes;
use Session as LaravelSession;

class Session
{
    private $minutes;

    public function __construct()
    {
        LaravelSession::put('tracker.stats.days', $this->getValue('days', 1));

        LaravelSession::put('tracker.stats.page', $this->getValue('pages', 'visits'));

        $this->minutes = new Minutes(60 * 24 * LaravelSession::get('tracker.stats.days'));
    }

    /**
     * @return Minutes
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

    public function getValue($variable, $default = null)
    {
        if (Input::has($variable)) {
            $value = Input::get($variable);
        } else {
            $value = LaravelSession::get('tracker.stats.'.$variable, $default);
        }

        return $value;
    }
}
