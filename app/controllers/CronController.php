<?php

class CronController extends BaseController {

    static $PUBLICATION_CACHE_CRON = 'publication_cache';

    /** Cron job = Executed each 10 min - cron/publication-cache
     *  Invalidate publication cache where is necessary
     **/
	public function getPublicationCache() {
        $job = CronJobs::where('cron_code', self::$PUBLICATION_CACHE_CRON)->first();

        $lastExec = $job->updated_at;

        // Activate publications - Change status to Published for publications that have from_date today and status On_Hold
        $publications = Publication::where(function($query) use ($lastExec)
        {
            $query->orWhere('updated_at', '>=', $lastExec)
                ->orWhere('deleted_at', '>=', $lastExec)
            ;
        })->get();

        foreach ($publications as $publication) {
            Cache::forget(CacheHelper::$PUBLICATION . $publication->id);
        }

        if (count($publications) > 0){
            Cache::forget(CacheHelper::$PUBLICATIONS_MOST_RECENT);
            Cache::forget(CacheHelper::$PUBLICATIONS_MOST_VISITED);
        }

        $job->touch(); //update last execution
    }

    public function getUpdatePublishers(){
        $publishers = Publisher::get();
        foreach ($publishers as $publisher) {
            if (!$publisher->getMainContact()) {
                $publisher->save();
                echo 'generated publisher main contact for: ' . $publisher->id . '<br/>';
            }
        }
        die('done');
    }

}