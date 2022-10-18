<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Using class based composers...
        View::composer(
            [
                'pages.admin.approval.tab-header',
            ],
            'App\Http\ViewComposer\TabApprovalComposer'
        );

        View::composer(
            [
                'layouts.admin.main.header-menu',
            ],
            'App\Http\ViewComposer\NavbarAdminComposer'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
