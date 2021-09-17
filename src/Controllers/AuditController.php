<?php

namespace Muathye\Audit\Controllers;

class AuditController extends BaseController
{
    public function developer()
    {
        return response(
            [
                'success' => true,
                'data' => [
                    'home_page' => 'https://github.com/muath-ye'
                ],
                200
            ]
        );
    }
}