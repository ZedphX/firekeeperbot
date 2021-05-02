<?php

namespace FireKeeper\Services;

use FireKeeper\Models\TelegramUser;
use WeStacks\TeleBot\Objects\Update;
use Illuminate\Support\Facades\Config;

class TelegramUserService
{

    /**
     * Creates a new Telegram User and store it in the database.
     * Returns user's id or false.
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $user = new TelegramUser;
        $user->telegram_id = $data['telegram_id'];
        $user->alias = $data['alias'];
        $user->locale = $data['locale'];

        return $user->save() ? $user->id : false;
    }

    /**
     * Updates a telegram user's information.
     *
     * @param int $id
     * @param array $data
     * @return int|false
     */
    public function update($id, $data)
    {
        $user = TelegramUser::find($id);

        if ($user) {
            if (!empty($user->telegram_id)) $user->telegram_id = $data['telegram_id'];
            if (!empty($user->alias)) $user->alias = $data['alias'];
            if (!empty($user->locale)) $user->locale = $data['locale'];

            return $user->save() ? $user->id : false;
        }

        return false;
    }

    /**
     * Deletes a Telegram User from the database.
     * 
     * @param int $id
     * @return int|false
     */
    public function delete($id)
    {
        $result = TelegramUser::find($id)->delete();
        return $result;
    }

    /**
     * Get a Telegram User from the database.
     * 
     * @param int $id
     * @return TelegramUser
     */
    public function get($id)
    {
        $reminder = TelegramUser::find($id);
        return $reminder;
    }

    /**
     * Get a Telegram User from the database by
     * they telegram id.
     * 
     * @param int $id
     * @return TelegramUser
     */
    public function getByTelgramId($id)
    {
        $user = TelegramUser::where('telegram_id', '=', $id)->first();
        return $user;
    }

    /**
     * Get an Update's Telegram User from the database.
     * If the Telegram User does not exist it is created with the Update information.
     * 
     * @param Update $update
     * @return TelegramUser
     */
    public function getUserFromUpdate(Update $update)
    {
        if (isset($update->callback_query)) $updateUser = $update->callback_query->from;
        elseif (isset($update->inline_query)) $updateUser = $update->inline_query->from;
        else $updateUser = $update->message->from;

        $user = $this->getByTelgramId($updateUser->id);
        if (!$user) {
            $defaultAlias = Config::get('constants.default_alias');
            $languageCode = substr($updateUser->language_code, 0, 2);

            $this->create([
                'telegram_id' => $updateUser->id,
                'alias' => $defaultAlias,
                'locale' => in_array($languageCode, array_keys(Config::get('constants.supported_languages')))
                    ? $languageCode : 'en',
            ]);

            $user = $this->getByTelgramId($updateUser->id);
        }

        return $user;
    }
}
