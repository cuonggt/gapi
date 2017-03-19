<?php

if (! function_exists('api_response')) {

    /**
     * Get an ApiResponse instance.
     *
     * @param  array  $data
     * @param  int  $status
     * @param  array  $headers
     * @return \Gtk\Gapi\ApiResponse
     */
    function api_response($data = [], $status = 200, $headers = [])
    {
        $api_response = app('api-response');

        if (func_num_args() == 0) {
            return $api_response;
        }

        return $api_response->setStatusCode($status)->withArray($data, $headers);
    }

}
