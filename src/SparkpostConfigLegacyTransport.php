<?php

namespace Spaceemotion\LaravelSparkPostOptions;

use Illuminate\Mail\Transport\SparkPostTransport;
use Swift_Mime_Message;

class SparkpostConfigLegacyTransport extends SparkPostTransport
{
    use ConfigurableTransport;

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->sendWithOptions($message, function () use ($message, &$failedRecipients) {
            return parent::send($message, $failedRecipients);
        });
    }
}
