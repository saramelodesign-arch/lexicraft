<?php

namespace App\Http\Responses;

use App\Support\Locales;
use Laravel\Fortify\Contracts\LogoutResponse;
use Symfony\Component\HttpFoundation\Response;

final class FortifyLogoutResponse implements LogoutResponse
{
    public function toResponse($request): Response
    {
        $locale = is_string($request->cookie('locale')) && Locales::isSupported($request->cookie('locale'))
            ? $request->cookie('locale')
            : Locales::fallback();

        return redirect()->route('home', ['locale' => $locale]);
    }
}
