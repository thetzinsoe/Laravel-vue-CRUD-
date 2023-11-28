<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function outputminga()
    {
        $message = "MIN GA LAR PAR!";
        return compact('message');
    }
}
