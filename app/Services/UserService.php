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
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

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
            if (!empty($user->name)) $user->name = $data['name'];
            if (!empty($user->email)) $user->email = $data['email'];
            if (!empty($user->password)) $user->password = Hash::make($data['password']);

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
}
