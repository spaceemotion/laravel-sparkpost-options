<?php

namespace Spaceemotion\LaravelSparkPostOptions;

use Illuminate\Mail\Mailable;

trait ConfigurableTransport
{
    /**
     * Sends the given message with custom options, if applicable.
     *
     * @param $message
     * @param callable $callback
     * @return mixed
     */
    protected function sendWithOptions($message, callable $callback)
    {
        $configHeader = $message->getHeaders()->get(SparkPostConfigProvider::CONFIG_HEADER);

        // Don't change anything we haven't set any options
        if ($configHeader === null) {
            return $callback();
        }

        // Otherwise set options before sending and reset them after
        $original = $this->getOptions();

        try {
            $decoded = json_decode($configHeader->getFieldBody(), true);
            $this->setOptions(array_merge($original, $decoded));

            return $callback();
        } finally {
            $this->setOptions($original);
        }
    }

    /**
     * Attaches the given options to the mailable via the SMTP header.
     * This will later be properly extracted when sending the mail.
     *
     * @param Mailable $mailable
     * @param array $options
     */
    public static function attach(Mailable $mailable, array $options)
    {
        $mailable->withSwiftMessage(function ($message) use ($options) {
            $message->getHeaders()->addTextHeader(SparkPostConfigProvider::CONFIG_HEADER, json_encode($options));
        });
    }
}
