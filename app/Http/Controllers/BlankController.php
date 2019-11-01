<?php

namespace App\Http\Controllers;

class BlankController extends Controller
{
    /**
     * Show the application splash screen.
     *
     * @return Response
     */
    public function show()
    {
        return view('blank');
    }
}
