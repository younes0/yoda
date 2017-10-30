<?php

namespace Yeb\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class Mocking
{
    public static function addFixturesBuilder(Client $client = null, $prefix)
    {
        $client = $client ?: new Client();

        $client->getEmitter()->on('complete', function(CompleteEvent $e) use ($prefix) {
           
            $hash = md5($e->getRequest()->getUrl());
            $type = $e->getResponse()->getHeader('Content-Type');

            // json
            if (str_contains($type, 'json')) {
                $content = json_encode($e->getResponse()->json(), JSON_PRETTY_PRINT);
                $ext     = 'json';
            
            // default
            } else {
                $content = $e->getResponse()->getBody();
                $ext     = 'response';
            }

            $path = sprintf(
                '%s/resources/fixtures/generated/guzzle.%s.%s.%s', 
                base_path(), $prefix, $hash, $ext
            ); 
            
            file_put_contents($path, $content);
        });

        return $client;
    }

    public static function getMockerClient(Array $options = [], $path = null)
    {
        $client   = new Client($options);
        $iterator = new \FilesystemIterator($path ?: base_path().'/resources/fixtures/');

        // get fixture contents based on request url
        // and intercept request with response before request is sent over the wire.
        $client->getEmitter()->on('before', function(BeforeEvent $e) use ($client, $iterator) {
        
            $hash = md5($e->getRequest()->getUrl());

            foreach ($iterator as $fileinfo) {
                if (str_contains($fileinfo->getFilename(), $hash)) {
                    $contents = file_get_contents($fileinfo->getPathname());
                    break;
                }
            }

            $body = isset($contents) ? Stream::factory($contents) : null;

            $e->intercept(new Response(200, [], $body));
        });
   
        return $client;
    }
}
