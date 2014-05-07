<?php

class LoggerJob {

    public function fire($job, $data) {

        var_dump($data);

    }

    public function log($job, $dataLog) {
        try {
            Log::debug('Starting logjob');

            $entities = $dataLog['entities'];

            $logJob = new LogJob();
            $logJob->operation = $dataLog['operation'];
            $logJob->from_user_id = $dataLog['userAdminId'];
            $finalData = array();
            foreach ($entities as $entity){
                if (is_object($entity)){
                    $finalData[] = $entity->getOriginal();
                } elseif (is_array($entity)){
                    $finalData[] = $entity;
                }

                switch (get_class($entity)) {
                    case 'User':
                        $logJob->to_user_id = $entity->id;
                        break;
                    case 'Publisher':
                        $logJob->to_publisher_id = $entity->id;
                        break;
                    case 'Publication':
                        $logJob->to_publication_id = $entity->id;
                        break;
                    case 'PublicationImage':
                        $logJob->to_publication_id = $entity->publication_id;
                        $logJob->to_publication_image_id = $entity->id;
                        break;
                    case 'PublicationReport':
                        $logJob->to_publication_id = $entity->publication_id;
                        $logJob->to_report_id = $entity->id;
                        break;
                    case 'Advertising':
                        $logJob->to_advertising_id = $entity->id;
                        break;
                    case 'PublicationRating':
                        $logJob->to_publication_id = $entity->publication_id;
                        $logJob->to_rating_id = $entity->id;
                        break;
                }
            }
            $logJob->final_value = json_encode($finalData);

            if ($dataLog['method'] == 'edit'){
                $logJob->previous_value = json_encode($dataLog['previousData']);
            }

            $logJob->save();

        } catch (Exception $ex){
            Log::error('No se pudo ejecutar LoggerJob para los datos ' . json_encode($dataLog));
            Log::error('Mensaje: ' . $ex->getMessage());
            if ($job->attempts() > 3) {
                Log::error('Job fuera de la cola por limite de intentos');
                $job->delete();
            } else {
                $job->release();
            }
        }
        Log::debug('Ending logjob');
    }
}