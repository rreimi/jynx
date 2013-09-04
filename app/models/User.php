<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    const ROLE_ADMIN="Admin";

    const ROLE_PUBLISHER="Publisher";

    const ROLE_BASIC="Basic";

    const STATUS_ACTIVE="Active";

    const STATUS_INACTIVE="Inactive";

    const STATUS_SUSPENDED="Suspended";

    protected $softDelete = true;

    protected $fillable = array('full_name', 'email',
        'role', 'status');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @retur        $data = array( 'postActivation' => 'show' );n string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

    public function publisher(){
        return $this->hasOne('Publisher');
    }

    public function scopeToApprove($query){
        return $query->where('is_publisher',1)->where('role',self::ROLE_BASIC);
    }

    public function isAdmin(){
        return $this->role==self::ROLE_ADMIN;
    }

    public function isBasic(){
        return $this->role==self::ROLE_BASIC;
    }

    public function isPublisher(){
        return $this->role==self::ROLE_PUBLISHER;
    }

    public function scopeAdminEmailList($query){
        return $query->where('role',self::ROLE_ADMIN);
    }

}