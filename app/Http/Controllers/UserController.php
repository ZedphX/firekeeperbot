<?php

namespace FireKeeper\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use FireKeeper\Services\UserService;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $service;

    public function __construct()
    {
        $this->service = new UserService;
    }

    /**
     * Store a new User in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->service->create([
            'telegram_id' => $request->telegram_id,
            'alias' => $request->alias,
            'name' => $request->telegramname_id,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return new Response($result);
    }

    /**
     * Update the specified user.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $result = $this->service->update($id, [
            'telegram_id' => $request->telegram_id,
            'alias' => $request->alias,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return new Response($result);
    }

    /**
     * Deletes a User from the database.
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
     * Get a User from the database.
     * 
     * @param int $id
     * @return User
     */
    public function get($id)
    {
        $result = $this->service->get($id);
        return $result;
    }

    /**
     * Get a User from the database by
     * they telegram id.
     * 
     * @param int $id
     * @return User
     */
    public function getByTelgramId($id)
    {
        $result = $this->service->getByTelgramId($id);
        return $result;
    }
}
