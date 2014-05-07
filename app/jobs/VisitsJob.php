<?php

/**
 * Job para el contador de visitas de las publicaciones
 *
 * Class VisitsJob
 */

class VisitsJob {

    public function fire($job, $data) {
        $id = intval($data['publication_id']);
        try {
            if ($id > 0) {
                Log::debug('Incrementando las visitas para la publicacion : ' . $id);

                /* Increment visits counter */
                $publication = Publication::find($data['publication_id']);

                if ($publication != null) {
                    $publication->increment('visits_number');
                    // INIT - Create log of publication
                    $pubVisit = new PublicationVisit();
                    $pubVisit->publication_id = $data['publication_id'];
                    $pubVisit->save();
                }
            }

            $job->delete();
        } catch (Exception $ex){
            Log::error('No se pudo ejecutar visitsJob para la publicación con id: ' . $id);
            $job->delete();

            //Attemps its not supported right now
//            if ($job->attempts() > 3) {
//                Log::error('Job fuera de la cola por limite de intentos');
//                $job->delete();
//            } else {
//                $job->release();
//            }
        }
    }
}