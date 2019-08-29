<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClearController extends Controller
{
    //show clear invoice
    public function show()
    {
        return view('clear');
    }

    //show clear select
    public function show_select()
    {
        return view('select');
    }
    public function show_blank()
    {
        return view('blank');
    }
}
