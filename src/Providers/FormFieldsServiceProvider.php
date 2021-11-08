<?php

namespace Avast\Formfields\Providers;

use Illuminate\Support\ServiceProvider;
use Avast\Formfields\FormFields\AttributedGalleryFormField;
use Avast\Formfields\FormFields\KeyValueFormField;
use Avast\Formfields\FormFields\ValuesListFormField;

class FormFieldsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views/', 'avast-formfields');
    }

    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {
         $this->app->resolving('TCG\\Voyager\\Voyager', function ($voyager, $app) {
            $voyager->addFormField(AttributedGalleryFormField::class);
            $voyager->addFormField(KeyValueFormField::class);
            $voyager->addFormField(ValuesListFormField::class);
        });

        $this->app->bind(
            'TCG\Voyager\Http\Controllers\VoyagerBaseController',
            'Avast\Formfields\Http\Controllers\FormFieldsController'
        );

        $this->app->bind(
            'TCG\Voyager\Http\Controllers\VoyagerMediaController',
            'Avast\Formfields\Http\Controllers\FormFieldsController'
        );
    }
}
