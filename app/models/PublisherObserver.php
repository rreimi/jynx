<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rreimi
 * Date: 3/17/14
 * Time: 4:26 AM
 * To change this template use File | Settings | File Templates.
 */

class PublisherObserver {

    /**
     * Update the main contact with the publisher information
     */
    public function saved($publisher)
    {
        $contact = $publisher->getMainContact();

        if ($contact == null) {
            $contact = new Contact();
        }

        $contact->full_name = $publisher->seller_name;
        $contact->email = $publisher->user->email;
        $contact->phone = $publisher->phone1;
        $contact->other_phone = $publisher->phone2;
        $contact->state_id = $publisher->state_id;
        $contact->address = $publisher->address;
        $contact->city = $publisher->city;
        $contact->publisher_id = $publisher->id;
        $contact->is_main = '';
        $contact->save();
    }

}