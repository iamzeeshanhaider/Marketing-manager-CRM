<?php

namespace App\Http\Controllers;

use Mail;
use DateTime;
use Google_Client;
use App\Models\Email;
use App\Enums\MeetingType;
use App\Models\AccessToken;
use Google\Service\Calendar;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use App\Mail\InvitationEmail;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Response;
use Google\Service\Calendar\EventDateTime;
use Google_Service_Calendar_EventAttendee;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_EventOrganizer;
use Google_Service_Calendar_CreateConferenceRequest;
use Google\Service\Exception as GoogleServiceException;

class CalanderController extends Controller
{

    public function connect()
    {

        $client = new Google_Client();
        $secret = $this->getClientSecret();
        if (!$secret) {
            return redirect()->back()->with('error', 'Company Does not Subscribed for any calender');
        }
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setScopes(Google_Service_Calendar::CALENDAR, 'https://www.googleapis.com/auth/userinfo.email');
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }


    function getClientSecret()
    {
        $company = auth()->user()->companies()->first();
        $calenderType  = $company->calendar->calendar_type;
        if ($calenderType == MeetingType::Google) {
            $clentSecret = $company->calendar;
            return $clentSecret;
        }
        return false;
    }

    public function callback(Request $request)
    {
        $client = new Google_Client();
        $secret = $this->getClientSecret();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessType('offline');
        $client->setRedirectUri(route('google.calendar.callback'));
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->authenticate($request->get('code'));
        $accessToken = $client->getAccessToken();



        $userId = auth()->user()->id;
        $userToken = AccessToken::where('user_id', $userId)->first();
        if (!$userToken) {
            $accessTokenModel = new AccessToken();
            $accessTokenModel->user_id = $userId;
        } else {
            $accessTokenModel =  $userToken;
        }
        $this->setAccessToken($accessTokenModel, $accessToken);

        return redirect(route('google.calendar.events'));
    }

    public function listEvents(Request $request)
    {
        $startDate = date('Y-m-d\TH:i:sP', strtotime($request->input('strat')));
        $endDate = date('Y-m-d\TH:i:sP', strtotime($request->input('end')));
        $emails = Email::where('type', MeetingType::Google)->get();
        $client = $this->getGoogleClient();
        if (!$client) {
            return  redirect(route('google.calendar.connect'));
        }
        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary';

        $events = $service->events->listEvents($calendarId, [
            'timeMin' => $startDate,
            'timeMax' => $endDate,
        ]);
        foreach ($events->getItems() as $event) {
            $meetingLink = null;
            if ($event->getConferenceData() && $event->getConferenceData()->getEntryPoints()) {
                foreach ($event->getConferenceData()->getEntryPoints() as $entryPoint) {
                    if ($entryPoint->getEntryPointType() === 'video' && $entryPoint->getUri()) {
                        $meetingLink = $entryPoint->getUri();
                        break;
                    }
                }
            }

            $attendees = [];
            foreach ($event->getAttendees() as $attendee) {
                $attendees[] = $attendee->getEmail();
            }

            $calendarEvents[] = [
                'id' => $event->getId(),
                'title' => $event->getSummary(),
                'start' => $event->getStart()->dateTime,
                'end' => $event->getEnd()->dateTime,
                'meeting_link' => $meetingLink,
                'attendees' => $attendees,
            ];
        }
        if ($request->ajax()) {
            return Response::json($calendarEvents);
        }
        return view('employee.userbiodata.user-calander', ['emails' => $emails]);
    }

    public function createEvent(Request $request)
    {
        try {
            $startDate = $request->input('start');
            $title = $request->input('title');
            $endDate = $request->input('end');
            $secret = $this->getClientSecret();
            $attendeEmail = $request->input('attendee');
            $emailId = $request->input('email');
            $emailBody = Email::find($emailId)?->body;
            $attendes = explode(",", $attendeEmail);
            foreach ($attendes as $email) {
                $attendeesemail[] = ['email' => $email];
            }
            $attendeesemail[] = ['email' => 'RT-consultant@rizwagroup.com'];
            if ($secret->cc_email)
                $attendeesemail[] = ['email' => $secret->cc_email];
            $token = $this->token();
            $client = new Google_Client();
            $client->setAccessToken($token);
            $service = new Google_Service_Calendar($client);
            $userEmail   = $service->calendars->get('primary')->id;
            $calendarService = new Calendar($client);

            $conferenceData = new Google_Service_Calendar_ConferenceData();
            $conferenceData->setCreateRequest(new Google_Service_Calendar_CreateConferenceRequest([
                'requestId' => mt_rand(),
                'conferenceSolutionKey' => [
                    'type' => 'hangoutsMeet',
                ],
            ]));
            $event = new Google_Service_Calendar_Event([
                'summary' => $request->input('title'),
                'start' => ['dateTime' => $startDate],
                'end' => ['dateTime' => $endDate],
                'conferenceData' => $conferenceData,
                'attendees' => $attendeesemail,
            ]);
            $organizer = new Google_Service_Calendar_EventOrganizer();
            $organizer->setEmail($userEmail);
            $event->setOrganizer($organizer);

            $calendarId = 'primary';
            $event = $calendarService->events->insert($calendarId, $event, array(
                'conferenceDataVersion' => 1,
            ));
            $startTime = $event->start->dateTime;
            $endTime = $event->end->dateTime;
            $details['replyfor'] = $userEmail;
            $details['link'] = $event->getHangoutLink();
            $details['guests'] = $attendes;
            $details['subject'] = $title;
            $details['body'] = $emailBody;
            $details['date'] = $this->createDateTime($startDate, $startTime,  $endTime);
            array_merge(['RT-consultant@rizwagroup.com'], $attendes);
            if ($secret->cc_email) {
                array_merge([$secret->cc_email], $attendes);
            }
            Mail::to($attendes)->send(new InvitationEmail($details));
            return  response()->json(['message' => 'Invitaton Sent', 'status' => 'success']);
        } catch (GoogleServiceException $e) {
            $error = $e->getMessage();
            return  response()->json(['message' => 'Failed to send invitation: ' . $error, 'status' => 'error']);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => 'Failed to send invitation: ' . $error, 'status' => 'error']);
        }
    }


    function updateEvent(Request $request)
    {
        try {
            $eventId = $request->input('event_id');
            $title = $request->input('title');
            $client = $this->getGoogleClient();
            $secret = $this->getClientSecret();
            $newAttendeeEmail = $request->input('attendees');
            $emailId = $request->input('email');
            $attendes = explode(",", $newAttendeeEmail);
            foreach ($attendes as $email) {
                $attendee = new Google_Service_Calendar_EventAttendee();
                $attendee->setEmail($newAttendeeEmail);
                $attendeesemail[] = ['email' => $email];
            }
            $attendeesemail[] = ['email' => 'RT-consultant@rizwagroup.com'];
            if ($secret->cc_email)
                $attendeesemail[] = ['email' => $secret->cc_email];
            $service = new Google_Service_Calendar($client);
            $userEmail   = $service->calendars->get('primary')->id;
            $event = $service->events->get('primary', $eventId);

            $startDateTime = new DateTime($request->input('start'));
            $endDateTime = new DateTime($request->input('end'));
            $startEventDateTime = new EventDateTime();
            $endEventDateTime = clone $startEventDateTime;
            $startEventDateTime->setDateTime($startDateTime->format('Y-m-d\TH:i:sP'));
            $endEventDateTime->setDateTime($endDateTime->format('Y-m-d\TH:i:sP'));
            $event->setSummary($request->input('title'));
            $event->setStart($startEventDateTime);
            $event->setEnd($endEventDateTime);
            $event->setAttendees($attendeesemail);

            $updatedEvent =   $service->events->update('primary', $eventId, $event);
            $emailBody = Email::find($emailId)?->body;
            $startTime = $updatedEvent->start->dateTime;
            $endTime = $updatedEvent->end->dateTime;
            $details['replyfor'] = $userEmail;
            $details['link'] = $event->getHangoutLink();
            $details['guests'] = $attendes;
            $details['subject'] = $title;
            $details['email_body'] = $emailBody;
            $details['date'] = $this->createDateTime($request->input('start'), $startTime,  $endTime);
            array_merge(['RT-consultant@rizwagroup.com'], $attendes);
            if ($secret->cc_email) {
                array_merge([$secret->cc_email], $attendes);
            }
            Mail::to($attendes)->send(new InvitationEmail($details));

            return  response()->json(['message' => 'Event Updated Successfully', 'status' => 'success']);
        } catch (GoogleServiceException $e) {
            $error = $e->getMessage();
            return  response()->json(['message' => 'Failed to Update event: ' . $error, 'status' => 'error']);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => 'Failed to Update event: ' . $error, 'status' => 'error']);
        }
    }

    function deleteEvent(Request $request)
    {
        try {
            $eventId = $request->input('event_id');
            $client = $this->getGoogleClient();
            $service = new Google_Service_Calendar($client);
            $service->events->delete('primary', $eventId);
            return  response()->json(['message' => 'Event Deleted Successfully', 'status' => 'success']);
        } catch (GoogleServiceException $e) {
            $error = $e->getMessage();
            return  response()->json(['message' => 'Failed to delete event: ' . $error, 'status' => 'error']);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => 'Failed to delete event: ' . $error, 'status' => 'error']);
        }
    }



    function getGoogleClient()
    {
        $userId = auth()->user()->id;
        $userToken = AccessToken::where('user_id', $userId)->first();
        if (!$userToken) {
            return false;
        }
        $accessToken =  $this->getToken($userToken);

        $client = new Google_Client();
        $client->setAccessType('offline');
        $secret = $this->getClientSecret();
        if (!$secret) {
            return ['status' => 'error', 'message' => 'Client Secret Does not Exist'];
        }
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setAccessToken($accessToken);
        if ($client->isAccessTokenExpired()) {
            $accessToken = $client->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
            if (array_key_exists('error', $accessToken)) {
                return false;
            }
            $accessToken = $client->getAccessToken();
            $client->setAccessToken($accessToken);
            $this->setAccessToken($userToken, $accessToken);
        }
        return  $client;
    }

    function token()
    {
        $userId = auth()->user()->id;
        $userToken = AccessToken::where('user_id', $userId)->first();
        if (!$userToken) {
            return false;
        }
        $accessToken =  $this->getToken($userToken);

        $client = new Google_Client();
        $client->setAccessType('offline');
        $secret = $this->getClientSecret();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setAccessToken($accessToken);
        if ($client->isAccessTokenExpired()) {
            $accessToken = $client->fetchAccessTokenWithRefreshToken($accessToken['refresh_token']);
            if (array_key_exists('error', $accessToken)) {
                return false;
            }
            $accessToken = $client->getAccessToken();
            $client->setAccessToken($accessToken);
            $this->setAccessToken($userToken, $accessToken);
        }
        return $accessToken['access_token'];
    }



    function getToken($userToken)
    {
        $accessToken['access_token'] =   $userToken->access_token;
        $accessToken['refresh_token'] = $userToken->refresh_token;
        $accessToken['expires_in'] = $userToken->expires_in;
        $accessToken['scope'] = $userToken->scope;
        $accessToken['token_type'] = $userToken->token_type;
        $accessToken['created'] = $userToken->created;
        return $accessToken;
    }

    function setAccessToken($userToken, $accessToken)
    {
        $userToken->access_token = $accessToken['access_token'];
        $userToken->refresh_token = $accessToken['refresh_token'];
        $userToken->expires_in = $accessToken['expires_in'];
        $userToken->scope = $accessToken['scope'];
        $userToken->token_type = $accessToken['token_type'];
        $userToken->created = $accessToken['created'];
        $userToken->save();
    }
    function createDateTime($start, $startTimeFormatted, $endTimeFormatted)
    {
        $meetingDateTime = strtotime($start);
        $dayOfWeek = date('l', $meetingDateTime);
        $monthName = date('M', $meetingDateTime);
        $dayOfMonth = date('j', $meetingDateTime);
        $year = date('Y', $meetingDateTime);
        $startTimeFormatted = date('g:ia', strtotime($startTimeFormatted));
        $endTimeFormatted = date('g:ia', strtotime($endTimeFormatted));
        $meetingTimeString = "$dayOfWeek, $monthName $dayOfMonth, $year $startTimeFormatted - $endTimeFormatted";

        return $meetingTimeString;
    }
}
