<?php

namespace App\Http\Controllers;

use App\Email;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Email $email)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $email->composeContactEmail($data);

        return ['success' => true];
    }
}
