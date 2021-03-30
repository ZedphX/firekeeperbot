<?php

namespace FireKeeper\Http\Controllers;

use FireKeeper\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use FireKeeper\Services\ReminderService;

class ReminderController extends Controller
{
    /**
     * @var ReminderService
     */
    protected $service;

    public function __construct()
    {
        $this->service = new ReminderService;
    }

    /**
     * Store a new Reminder in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->service->create([
            'user_telegram_id' => $request->user_telegram_id,
            'message' => $request->message,
        ]);

        return new Response($result);
    }

    /**
     * Deletes a Reminder from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->delete($id);
        return new Response($result);
    }

    /**
     * Get a Reminder by id.
     *
     * @param int $id
     * @return \FireKeeper\Models\Reminder
     */
    public function get($id)
    {
        $reminder = $this->service->get($id);
        return $reminder;
    }

    /**
     * Send Reminders.
     *
     * @param int $limit
     * @return void
     */
    public function sendReminders($limit = 100)
    {
        $this->service->sendReminders($limit);
    }
}
