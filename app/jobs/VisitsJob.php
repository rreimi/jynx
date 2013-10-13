<?php

class VisitsJob {

    public function fire($job, $data)
    {
        //$data;
        //
       file_put_contents(base_path() . "/hola.txt", "holaaa");

        //adas
        //Log::error('hola 2');

        //si algo falla
        //$job->release();

        //limpa el job de la cola
        $job->delete();
    }

}