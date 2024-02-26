<?php

use GuzzleHttp\Client;

function tstprep_procesio_call() {


//        /**
//         * TODO
//         * 1. send the new DATA to procesio https://webapi.procesio.app/api/webhooks/launch/b0e32976-1379-4387-96b1-a472185af5fc
//         * 2. add a wordpress notice letting the user know he will get an update once the process is done
//         *
//         * 3. f7f9bc19-a7b8-4f22-a9bc-c16993256e3f should be an ENV variable
//         */
//        $data = [
//            'question_id' => $this->post_id,
//            'tts_script'  => $data_array['to_be_translated'],
//        ];
//
//        $client = new Client([ 'base_uri' => 'https://webapi.procesio.app' ]);
//
//        $response = $client->request('POST', '/api/webhooks/launch/f7f9bc19-a7b8-4f22-a9bc-c16993256e3f', [
//            'json' => $data
//        ]);
//
//        $body = $response->getBody();


}

add_action( 'wp_ajax_tstprep_procesio_call', 'tstprep_procesio_call' );
add_action( 'wp_ajax_nopriv_tstprep_procesio_call', 'tstprep_procesio_call' );
