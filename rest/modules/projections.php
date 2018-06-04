<?php

Flight::route('POST /projection/create', function () {
    $request = Flight::request();
    $projectionDb = new Projection();
    $projection = [
        'movie_name' => $request->data->movie_name,
        'hall_name' => $request->data->hall_name,
        'cinema_id' => $request->data->cinema_id,
    ];

    $res = $projectionDb->add_projection($projection);
    Flight::json($res);
});

Flight::route('GET /projections/all/@id', function ($id) {
    $projectionDb = new Projection();
    $res = $projectionDb->get_all_projections($id);
    Flight::json($res);
});

FLIGHT::route('DELETE /projection/@id', function ($id) {
    $movieDb = new Projection();
    $movieDb->delete_projection($id);
});
