<?php
/**
 * User: jose.troconis@androb.com
 * Timestamp: 26/04/14 01:06 AM
 */

class RoleHelper {

    public static function getEditableRoles() {
        $roles = array();

        switch (Auth::user()->role) {
            case User::ROLE_ADMIN :
                $roles[] = User::ROLE_ADMIN;
                $roles[] = User::ROLE_SUBADMIN;
            case User::ROLE_SUBADMIN :
                $roles[] = User::ROLE_PUBLISHER;
                $roles[] = User::ROLE_BASIC;
        }

        return $roles;
    }

}