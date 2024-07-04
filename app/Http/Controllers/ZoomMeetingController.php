<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Enums\MeetingType;
use App\Mail\UpdateZoomInvitation;
use App\Mail\ZoomInvitationEmail;
use App\Models\AccessToken;
use App\Models\Attendee;
use App\Models\Company;
use Mail;
use App\Models\Email;
use Illuminate\Support\Facades\Response;
use Exception;

class ZoomMeetingController extends Controller
{
    public function redirectToZoomProvider()
    {
        return redirect()->away('https://zoom.us/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.zoom.client_id'),
            'redirect_uri' => config('services.zoom.redirect'),
        ]));
    }

    public function handleZoomCallback(Request $request)
    {
        $code = $request->query('code');
        $response = Http::asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('services.zoom.redirect'),
            'client_id' => config('services.zoom.client_id'),
            'client_secret' => config('services.zoom.client_secret'),
        ]);

        if ($response->successful()) {
            $accessToken = $response->json();
            $token = auth()->user()->accessToken;
            if (!$token)
                $token = new AccessToken();
            $token->user_id = auth()->user()->id;
            $token->expires_in = "";
            $token->scope = "";
            $token->token_type = "Bearer";
            $token->created = date('Y-m-d');
            $token->access_token = $accessToken['access_token'];
            $token->refresh_token = $accessToken['refresh_token'];
            $token->save();
            return redirect(route('zoom.calendar.meetings'));
        } else {
            return response()->json(['error' => 'Failed to exchange authorization code for access token'], $response->status());
        }
    }


    function zoomMeetings(Request $request)
    {
        $accessToken = $this->getZoomAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://api.zoom.us/v2/users/me/meetings');
        if ($response->successful()) {
            $meetings = $response->json();
            $meetings = $meetings['meetings'];
            $events = array_map(function ($meeting) use ($accessToken) {
                $start = new \DateTime($meeting['start_time']);
                $duration = new \DateInterval('PT' . $meeting['duration'] . 'M');
                $end = (clone $start)->add($duration);
                $attendees = Attendee::where('meeting_id', $meeting['id'])->value('attendee');
                return [
                    'title' => $meeting['topic'],
                    'start' => $start->format('c'),
                    'end' => $end->format('c'),
                    'link' => $meeting['join_url'],
                    'id' => $meeting['id'],
                    'attendees' => explode(",", $attendees),
                ];
            }, $meetings);
            $emails = Email::where('type', MeetingType::Zoom)->get();
            if ($request->ajax()) {
                return Response::json($events);
            }
            return view('employee.userbiodata.zoom-calander', ['emails' => $emails]);
        } else {
            Session::flash('error', 'Failed to retrieve meetings');
            return redirect()->back();
        }
    }
    function createMeeting(Request $request)
    {
        try {
            $accessToken = $this->getZoomAccessToken();
            if (!$accessToken)
                return response()->json(['message' => 'Invalid Access Token', 'status' => 'error']);
            $secret = $this->getClientSecret();
            if (!$secret) {
                return response()->json(['message' => 'Company Does not Subscribed for any calender', 'status' => 'error']);
            }
            $attendeEmail = $request->input('attendee');
            $emailId = $request->input('email');
            $attendes = explode(",", $attendeEmail);
            $meetingData = [
                'topic' => $request->input('title'),
                'type' => 2,
                'start_time' => $request->input('start_time'),
                'duration' => $request->input('duration'),
                'timezone' => 'UTC',
                'agenda' => $request->input('title'),
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => true,
                    'waiting_room' => true,
                ],
            ];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.zoom.us/v2/users/me/meetings', $meetingData);
            if ($response->successful()) {
                $company = Company::find(\App\Models\Company::first()->id);
                $attendeEmail = $request->input('attendee');
                $emailBody = Email::find($emailId)?->body;
                $meeting = $response->json();
                $details['id'] = $meeting['id'];
                $details['password'] = $meeting['password'];
                $details['join_url'] = $meeting['join_url'];
                $details['start_time'] = $meeting['start_time'];
                $details['topic'] = $meeting['topic'];
                $details['logo'] = $company->getLogo();
                $details['company'] = $company->name;
                $details['email_body'] = $emailBody;
                array_push($attendes, "RT-consultant@rizwagroup.com");
                if ($secret->cc_email) {
                    array_push($attendes, $secret->cc_email);
                }
                $attendes = array_filter(array_map('trim', $attendes));
                Attendee::create(['meeting_id' => $meeting['id'], 'attendee' => implode(",", $attendes)]);


                Mail::to($attendes)->send(new ZoomInvitationEmail($details));
                return response()->json(['message' => 'Invitaton Sent', 'status' => 'success']);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $statusError = "";
            if (isset($meeting) && isset($meeting['id'])) {
                $status =   $this->deleteMeeting($accessToken, $meeting['id']);
                if (!$status)
                    $statusError = ", meeting couldn't be deleted";
            }
            return response()->json(['message' => 'Failed to send invitation: ' . $error . $statusError, 'status' => 'error']);
        }
    }

    function getZoomAccessToken()
    {
        $accessToken = auth()->user()->accessToken;
        if ($accessToken) {
            $token = $this->refreshToken($accessToken->refresh_token);
            return $token;
        } else {
            $this->redirectToZoomProvider();
        }
    }
    public function refreshToken($refreshToken)
    {
        $response = Http::asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => config('services.zoom.client_id'),
            'client_secret' => config('services.zoom.client_secret'),
        ]);

        if ($response->successful()) {
            $token = $response->json();
            $accessToken = auth()->user()->accessToken;
            $accessToken->access_token = $token['access_token'];
            $accessToken->refresh_token = $token['refresh_token'];
            $accessToken->save();
            return $token['access_token'];
        } else {
            throw new Exception('Failed to refresh Zoom token: ' . $response->status());
        }
    }
    function getClientSecret()
    {
        $company = Company::find(\App\Models\Company::first()->id);
        $calenderType  = $company->calendar->calendar_type;
        if ($calenderType == MeetingType::Google || $calenderType == MeetingType::Zoom) {
            $clentSecret = $company->calendar;
            return $clentSecret;
        }
        return false;
    }

    function deleteMeeting($accessToken, $meetingId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->delete('https://api.zoom.us/v2/meetings/' . $meetingId);
            return true;
        } catch (Exception $deleteException) {
            return false;
        }
    }


    function deleteZoomMeeting(Request $request)
    {
        try {
            $meetingId = $request->input('meeting_id');
            $token =  $this->getZoomAccessToken();
            $status =   $this->deleteMeeting($token, $meetingId);
            if ($status) {
                Attendee::find($meetingId)->delete();
                return  response()->json(['message' => 'Event Deleted Successfully', 'status' => 'success']);
            } else {
                return  response()->json(['message' => 'Something went wrong Please Contact Support', 'status' => 'error']);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => 'Failed to delete event: ' . $error, 'status' => 'error']);
        }
    }

    public function updateZoomMeeting(Request $request)
    {
        try {
            $meetingId = $request->input('meeting_id');
            $newAttendeeEmail = $request->input('attendee');
            $emailId = $request->input('email');
            Attendee::updateOrCreate(
                ['meeting_id' => $meetingId],
                ['attendee' => $newAttendeeEmail]
            );
            $attendes = explode(",", $newAttendeeEmail);
            $accessToken = $this->getZoomAccessToken();
            $secret = $this->getClientSecret();
            if (!$accessToken) {
                return response()->json(['message' => 'Invalid Access Token', 'status' => 'error']);
            }
            // Fetch the current meeting details
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->get('https://api.zoom.us/v2/meetings/' . $meetingId);

            if (!$response->successful()) {
                $error = $response->json()['message'] ?? 'Unknown error';
                throw new \Exception($error);
            }
            $currentMeeting = $response->json();
            $meetingData = [
                'topic' => $request->input('title', $currentMeeting['topic']),
                'type' => $currentMeeting['type'],
                'start_time' => $request->input('start_time', $currentMeeting['start_time']),
                'duration' => $request->input('duration', $currentMeeting['duration']),
                'timezone' => 'UTC',
                'agenda' => $request->input('title', $currentMeeting['agenda']),
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => true,
                    'waiting_room' => true,
                ],
            ];
            $updateResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->patch('https://api.zoom.us/v2/meetings/' . $meetingId, $meetingData);

            if ($updateResponse->successful()) {
                $company = Company::find(\App\Models\Company::first()->id);
                $emailBody = Email::find($emailId)?->body;
                $details['id'] = $meetingId;
                $details['password'] = $currentMeeting['password'];
                $details['join_url'] = $currentMeeting['join_url'];
                $details['start_time'] = $meetingData['start_time'];
                $details['topic'] = $meetingData['topic'];
                $details['logo'] = $company->getLogo();
                $details['company'] = $company->name;
                $details['email_body'] = $emailBody;
                array_push($attendes, "RT-consultant@rizwagroup.com");
                if ($secret->cc_email) {
                    array_push($attendes, $secret->cc_email);
                }
                $attendes = array_filter(array_map('trim', $attendes));




                Mail::to($attendes)->send(new UpdateZoomInvitation($details));
                return response()->json(['message' => 'Meeting updated successfully', 'status' => 'success']);
            } else {
                $error = $updateResponse->json()['message'] ?? 'Unknown error';
                throw new \Exception($error);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => 'Failed to update meeting: ' . $error, 'status' => 'error']);
        }
    }
}
