<?php

namespace Spaceemotion\LaravelSparkPostOptions;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Transport\SparkPostTransport;
use Swift_Mime_SimpleMessage;
use Swift_Mime_Message;

class SparkpostConfigTransport extends SparkPostTransport
{
    /**
     * The header used by SparkPost to set the transaction options
     */
    const CONFIG_HEADER = 'X-MSYS-API';


    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $configHeader = $message->getHeaders()->get(static::CONFIG_HEADER);

        // Don't change anything we haven't set any options
        if ($configHeader === null) {
            return parent::send($message, $failedRecipients);
        }

        // Otherwise set options before sending and reset them after
        $original = $this->getOptions();

        try {
            $decoded = json_decode($configHeader->getFieldBody(), true);
            $this->setOptions(array_merge($original, $decoded));

            return parent::send($message, $failedRecipients);
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
        $mailable->withSwiftMessage(function (Swift_Message $message) use ($options) {
            $message->getHeaders()->addTextHeader(static::CONFIG_HEADER, json_encode($options));
        });
    }
}
