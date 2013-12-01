<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

$api = $app['controllers_factory'];

$api->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$api->get('/', function () { return 'API home page'; });

$api->get('/entries', function () use ($app) {
    $entries = $app['entry_api']->getEntries();
    return $app->json($entries, 200);
});


$api->post('/entries', function (Request $request) use ($app) {
    $url = $request->request->get('url');

    $entry = $app['entry_api']->createEntryFromUrl($url);

    return $app->json($entry, 201);
});

return $api;
