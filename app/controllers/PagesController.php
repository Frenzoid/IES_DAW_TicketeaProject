<?php

namespace liveticket\app\controllers;

use liveticket\core\Response;

class PagesController
{
    public function about()
    {
        Response:: renderView (
            'about',
            [
            ]
        );
    }

    public function notFound()
    {
        header ('HTTP/1.1 404 Not Found', true, 404);
        Response:: renderView ('404');
    }

    public function conflict(){
        header ('HTTP/1.1 404 Conflict', true, 409);
        Response:: renderView ('409');
    }
}