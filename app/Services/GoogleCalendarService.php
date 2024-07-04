<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = $this->getClient();
    }

    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName(config('app.name'));
        $client->setScopes(Google_Service_Calendar::CALENDAR); // Use the Calendar scope
        $client->setAuthConfig(storage_path('keys/client_secret.json'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $redirect_uri = url('/google-calendar/auth-callback');
        $client->setRedirectUri($redirect_uri);
        return $client;
    }

    public function connect()
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect($authUrl);
    }

    public function store()
    {
        $authCode = request('code');
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);

        $credentialsPath = storage_path('keys/client_secret_generated.json');

        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }

        file_put_contents($credentialsPath, json_encode($accessToken));

        return redirect('/google-calendar')->with('message', 'Credentials saved');
    }

    public function getResources()
    {
        // Get the authorized client object and fetch the resources.
        return $this->client->getService('Calendar');
    }
}
