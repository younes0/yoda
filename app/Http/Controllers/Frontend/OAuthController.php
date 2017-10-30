<?php

namespace Yoda\Http\Controllers\Frontend;

use Yeb\Http\Controllers\PageController;
use Request, Response, Alert, Auth;
use League\OAuth1\Client\Server\Server;
use League\OAuth1\Client\Server\Twitter;
use Gladeye\OAuth1\Client\Server\Wordpress;
use Session;

class OAuthController extends PageController
{   
    protected $tokens;

    public function __construct()
    {
        parent::__construct();
        $this->tokens = \App::make('Tokens');
    }

    /**
     * Later: multiple accounts, complete refactor (store key/secret in items)
     */
    public function anyTwitter()
    {
        $tokenId = 'twitter:'.config('yoda.curation.twitterAccount');
        
        return $this->getOauth1Token('Twitter', $tokenId, new Twitter([
            'identifier'   => env('TWITTER_KEY'),
            'secret'       => env('TWITTER_SECRET'),
            'callback_uri' => url('/oauth/twitter'),
        ]));
    }

    public function anyJediwp()
    {
        return $this->getOauth1Token('Jedi Wordpress', 'jediwp', new Wordpress([
            'identifier'   => env('JEDIWP_KEY'),
            'secret'       => env('JEDIWP_SECRET'),
            'base'         => env('JEDIWP_URL'),
            'callback_uri' => url('/oauth/jediwp'),
        ]));
    }

    protected function getOauth1Token($name, $tokenId, Server $server)
    {
        if ( !Request::has('oauth_token') ) {
            $tempCredentials = $server->getTemporaryCredentials();
            Session::put('tempCredentials', serialize($tempCredentials));

            return redirect($server->getAuthorizationUrl($tempCredentials));
        }

        $token = $server->getTokenCredentials(
            unserialize(Session::get('tempCredentials')), 
            Request::get('oauth_token'),
            Request::get('oauth_verifier')
        );

        // store
        $this->tokens->set($tokenId, [
            'identifier' => $token->getIdentifier(),
            'secret'     => $token->getSecret(),
        ]);

        Alert::success($name.' access token updated.')->flash();

        return redirect('/settings');
    }
}
