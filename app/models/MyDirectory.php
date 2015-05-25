<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class MyDirectory extends Eloquent {

    protected $table = 'my_directory';

    public $timestamps = false;

    const STATUS_ENTRY_EXISTS = 'entry_exists';
    const STATUS_SQL_ERROR = 'sql_error';
    const STATUS_OK = 'OK';
    const STATUS_NO_RECORD_FOUND = 'record_not_found';

    public static function scopeOfUser($query, $userId){
        $query->where('my_directory.user_id', '=', intval($userId));
    }

    public static function addToMyDirectory($userId, $publisherId) {

        $publisher = Publisher::find(intval($publisherId));

        if ($publisher == null || $publisher->status_publisher != Publisher::STATUS_APPROVED) {
            return self::STATUS_NO_RECORD_FOUND;
        }

        $entries = MyDirectory::where('user_id', $userId)->where('publisher_id', $publisherId)->get();
        if ($entries->count() > 0) {
            return self::STATUS_ENTRY_EXISTS;
        }

        //TODO check user??
        //TODO force current session user??
        $myDirectory = new MyDirectory();
        $myDirectory->user_id = $userId;
        $myDirectory->publisher_id = $publisherId;

        try {
            $myDirectory->save();
        } catch (\Exception $ex) {
            //TODO log here
            return self::STATUS_SQL_ERROR;
        }


        return self::STATUS_OK;
    }

    public static function removeFromMyDirectory($userId, $publisherId) {
        if (empty($publisherId)) {
            return self::STATUS_NO_RECORD_FOUND;
        }

        $entries = MyDirectory::where('user_id', intval($userId))->where('publisher_id', $publisherId)->get();
        if ($entries->count() > 0) {
            foreach ($entries as $entry) {
                $entry->forceDelete();
            }
            return self::STATUS_OK;
        }
        return self::STATUS_NO_RECORD_FOUND;
    }
}