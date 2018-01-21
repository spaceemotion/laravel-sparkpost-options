<?php

namespace Spaceemotion\LaravelSparkPostOptions;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Mail\TransportManager;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class SparkPostConfigProvider extends ServiceProvider
{
    /**
     * Extends the transport manager with our configurable transport
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving(TransportManager::class, function (TransportManager $transport) {
            $transport->extend('sparkpost', function ($app) {
                // Unified version of the createSparkpostTransport and guzzle methods
                $config = $app['config']->get('services.sparkpost', []);
                $guzzle = new HttpClient(Arr::add(
                    Arr::get($config, 'guzzle', []),
                    'connect_timeout',
                    60
                ));

                // Return our transport extension instead
                return new SparkpostConfigTransport(
                    $guzzle,
                    $config['secret'],
                    Arr::get($config, 'options', [])
                );
            });
        });
    }
}
