<?php
/**
 * User: robert.reimi@gmail.com
 * Timestamp: 26/10/13 05:06 PM
 */

class StatusHelper {

    public static $TYPE_PUBLICATION = 'PUBLICATION';
    public static $TYPE_ADVERTISER  = 'ADVERTISER';
    public static $TYPE_JOB  = 'JOB';
    public static $TYPE_ADVERTISING  = 'AVERTISING';
    public static $TYPE_REPORT  = 'REPORT';
    public static $TYPE_USER  = 'USER';
    public static $TYPE_GROUP  = 'GROUP';

    public static function getStatuses($type, $blankCaption = '', $extraOptions = array()) {
        $statuses = array();

        switch ($type) {
            case 'ADVERTISER' :
                $statuses = self::getAdvertiserStatuses($blankCaption);
                break;
            case 'PUBLICATION' :
                $statuses = self::getPublicationStatuses($blankCaption);
                break;
            case 'JOB' :
                $statuses = self::getJobStatuses($blankCaption);
                break;
            case 'AVERTISING' :
                $statuses = self::getAdvertisingStatuses($blankCaption);
                break;
            case 'REPORT' :
                $statuses = self::getReportStatuses($blankCaption);
                break;
            case 'USER' :
                $statuses = self::getUserStatuses($blankCaption);
                break;
            case 'GROUP' :
                $statuses = self::getGroupStatuses($blankCaption);
                break;
        }

        if (count($extraOptions) > 0){
            return array_merge($statuses, $extraOptions);
        }

        return $statuses;
    }

    private static function getAdvertiserStatuses($blankCaption = ''){
        $options = array (
            Publisher::STATUS_PENDING=> Lang::get('content.status_'. Publisher::STATUS_PENDING),
            Publisher::STATUS_APPROVED => Lang::get('content.status_'. Publisher::STATUS_APPROVED),
            Publisher::STATUS_DENIED => Lang::get('content.status_'. Publisher::STATUS_DENIED),
            Publisher::STATUS_SUSPENDED => Lang::get('content.status_'. Publisher::STATUS_SUSPENDED),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private static function getReportStatuses($blankCaption = '') {
        $options = array (
            PublicationReport::STATUS_PENDING => Lang::get('content.status_report_'. PublicationReport::STATUS_PENDING),
            PublicationReport::STATUS_INVALID => Lang::get('content.status_report_'. PublicationReport::STATUS_INVALID),
            PublicationReport::STATUS_VALID => Lang::get('content.status_report_'. PublicationReport::STATUS_VALID),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private static function getUserStatuses($blankCaption = '') {

        $options = array (
            User::STATUS_ACTIVE => Lang::get('content.status_Active'),
            User::STATUS_INACTIVE => Lang::get('content.status_Inactive'),
            User::STATUS_SUSPENDED => Lang::get('content.status_Suspended'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private static function getGroupStatuses($blankCaption = '') {

        $options = array (
            Group::STATUS_ACTIVE => Lang::get('content.status_Active'),
            Group::STATUS_INACTIVE => Lang::get('content.status_Inactive'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private static function getAdvertisingStatuses($blankCaption = '') {

        $options = array (
            'Draft' => Lang::get('content.status_Draft'),
            'Published' => Lang::get('content.status_Published'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private static function getJobStatuses($blankCaption = ''){
        $options = array(
            Job::STATUS_DRAFT => Lang::get('content.status_publication_Draft'),
            Job::STATUS_PUBLISHED => Lang::get('content.status_publication_Published'),
            Job::STATUS_ON_HOLD => Lang::get('content.status_publication_On_Hold'),
            Job::STATUS_SUSPENDED => Lang::get('content.status_publication_Suspended'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private static function getPublicationStatuses($blankCaption = '') {


        //'Draft','Published','On_Hold','Suspended','Finished','Trashed'
        $options = array (
            'Draft' => Lang::get('content.status_publication_Draft'),
            'Published' => Lang::get('content.status_publication_Published'),
            'On_Hold' => Lang::get('content.status_publication_On_Hold'),
            'Finished' => Lang::get('content.status_publication_Finished'),
        );

        if(!Auth::guest()){
            if (Auth::user()->isAdmin()){
                $options['Suspended'] = Lang::get('content.status_publication_Suspended');
            }
        }

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }
}