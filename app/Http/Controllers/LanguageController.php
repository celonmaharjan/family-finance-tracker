<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    public function switch(string $locale)
    {
        // Validate the locale
        $availableLocales = ['en', 'ne'];
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale'); // Fallback to default
        }

        // Store the selected locale in the session
        Session::put('locale', $locale);

        // Redirect back to the previous page
        return Redirect::back();
    }
}
