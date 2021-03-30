<?php

namespace FireKeeper\Services;

use FireKeeper\Models\User;

class UserService
{

    /**
     * Creates a new User and store it in the database.
     * Returns user's id or false.
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $user = new User;
        $user->telegram_id = $data['telegram_id'];
        $user->alias = $data['alias'];
        $user->name = $data['name'];
        $user->email = !empty($data['email']) ? $data['email'] : null;
        $user->password = !empty($data['password']) ? $data['password'] : null;

        return $user->save() ? $user->id : false;
    }

    /**
     * Updates a user's information.
     *
     * @param int $id
     * @param array $data
     * @return int|false
     */
    public function update($id, $data)
    {
        $user = User::find($id);

        if ($user) {
            if (!empty($user->telegram_id)) $user->telegram_id = $data['telegram_id'];
            if (!empty($user->alias)) $user->alias = $data['alias'];
            if (!empty($user->name)) $user->name = $data['name'];
            if (!empty($user->email)) $user->email = $data['email'];
            if (!empty($user->password)) $user->password = $data['password'];

            return $user->save() ? $user->id : false;
        }

        return false;
    }

    /**
     * Deletes a User from the database.
     * 
     * @param int $id
     * @return int|false
     */
    public function delete($id)
    {
        $result = User::find($id)->delete();
        return $result;
    }

    /**
     * Get a User from the database.
     * 
     * @param int $id
     * @return User
     */
    public function get($id)
    {
        $reminder = User::find($id);
        return $reminder;
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
        $user = User::where('telegram_id', '=', $id)->first();
        return $user;
    }
}
