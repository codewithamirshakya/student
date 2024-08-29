<?php

namespace App\Listeners;

use App\Events\EventCreated;
use App\Models\Student;
use App\Models\User;
use App\Notifications\EventCreatedNotification;
use Google\Client as GoogleClient;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class SendStudentEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventCreated $event): void
    {
        $event = $event->event;
        $students = Student::with(['user'])->where('grade_id', $event->grade_id)->get();

        foreach ($students as $student) {
            $student->user->notify(new EventCreatedNotification($event));
            $this->sendFcmNotification($event, $student->user);
        }
    }

    public function sendFcmNotification($event, $user)
    {
        try {
            $fcm = $user->fcm_token;

            if (!$fcm) {
                return response()->json(['message' => 'User does not have a device token'], 400);
            }

            $title = $event->name;
            $description = $event->description;
            $projectId = config('services.fcm.project_id'); # INSERT COPIED PROJECT ID

            $credentialsFilePath = Storage::path('app/json/file.json');
            $client = new GoogleClient();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();

            $access_token = $token['access_token'];

            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            ];

            $data = [
                "message" => [
                    "token" => $fcm,
                    "notification" => [
                        "title" => $title,
                        "body" => $description,
                    ],
                ]
            ];
            $payload = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                return response()->json([
                    'message' => 'Curl Error: ' . $err
                ], 500);
            } else {
                return response()->json([
                    'message' => 'Notification has been sent',
                    'response' => json_decode($response, true)
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Notification has been failed',
                'response' => null
            ]);
        }
    }
}
