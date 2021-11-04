<?php
// default route (homepage)
const default_ctrl = "home";

// CORS
const allowed_origins = ['http://localhost:8080','*'];

// optional: custom 404
const default_404 = 'notFound';

\Neoan3\Core\Event::listen('Core::Exception', function ($ev){
//    var_dump($ev);
    redirect('home');
});
\Neoan3\Core\Event::listen('Core\Api::error', function ($ev){
    header('');
});


