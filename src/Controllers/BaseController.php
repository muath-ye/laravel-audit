<?php

namespace Muathye\Audit\Controllers;

use Muathye\Audit\Audit;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Laravel\Telescope\Telescope;

// phpcs:ignoreFile
if (class_exists('Illuminate\Routing\Controller')) {

    class BaseController extends Controller
    {
        protected $audit;

        public function __construct(Request $request, Audit $audit)
        {
            $this->audit = $audit;

            $this->middleware(function ($request, $next) {
                if (class_exists(Telescope::class)) {
                    Telescope::stopRecording();
                }
                return $next($request);
            });
        }
    }

} else {

    class BaseController
    {
        protected $audit;

        public function __construct(Request $request, Audit $audit)
        {
            $this->audit = $audit;
        }
    }
}
