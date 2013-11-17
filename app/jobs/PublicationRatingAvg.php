<?php

/**
 * Calcular y guardar rating average para las publicaciones
 *
 * Class PublicationRatingAvg
 */

class PublicationRatingAvg {

    public function fire($job, $pubId) {
        try {
            if ($pubId > 0) {
                Log::debug('Calculando ratingAvg para la publication con id: ' . $pubId);

                // Get average for the current publication
                Publication::calculateRatingAvg($pubId);
            }
        } catch (Exception $ex){
            Log::error('No se pudo calcular ratingAvg para la publicación con id: ' . $pubId);
        }
    }
}