<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class AdminLayoutComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        if ($user) {
            $user->loadMissing('tenants');
        }

        $view->with('currentTenant', current_tenant());
    }
}
