<?php

/* Intercepts events and invalidate cache */

class PublicationObserver {

    public function onPublicationChange($publication) {
        $publication->touch();
        //Cache::forget(CacheHelper::$PUBLICATION.$publication->id());
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param  Illuminate\Events\Dispatcher  $events
    * @return array
    */
    public function subscribe($events) {
        /* Publication */
        //$events->listen('publication.save', 'PublicationObserver@onPublicationSave');
//        $events->listen('publication.addImage', 'PublicationObserver@onPublicationAddImage');
//        $events->listen('publication.deleteImage', 'PublicationObserver@onPublicationDeleteImage');
        $events->listen('publication.change', 'PublicationObserver@onPublicationChange');

        //$events->listen('user.logout', 'CacheHandler@onUserLogout');
    }

}