<?php

use Laravel\Jetstream\Features;
use App\Models\Team;
use App\Models\Membership;
use App\Models\TeamInvitation;

return [

    /*
    |--------------------------------------------------------------------------
    | Jetstream Stack
    |--------------------------------------------------------------------------
    |
    | This configuration value informs Jetstream which "stack" you will be
    | using for your application. In general, this value is set for you
    | during installation and will not need to be changed after that.
    |
    */

    'stack' => 'livewire',

    /*
     |--------------------------------------------------------------------------
     | Jetstream Route Middleware
     |--------------------------------------------------------------------------
     |
     | Here you may specify which middleware Jetstream will assign to the routes
     | that it registers with the application. When necessary, you may modify
     | these middleware; however, this default value is usually sufficient.
     |
     */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Jetstream's features are optional. You may disable the features
    | by removing them from this array. You're free to only remove some of
    | these features or you can even remove all of these if you need to.
    |
    */

    'features' => [
        // Features::termsAndPrivacyPolicy(),
        // Features::profilePhotos(),
        // Features::api(),
        Features::teams(['invitations' => true]),
        Features::accountDeletion(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default disk that will be used
    | when storing profile photos for your application's users. Typically
    | this will be the "public" disk but you may adjust this if needed.
    |
    */

    'profile_photo_disk' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Here you can specify your own custom models for Jetstream components.
    | This allows you to use your own models rather than the default ones
    | provided by Jetstream.
    |
    */

    'models' => [
        'team' => Team::class,
        'membership' => Membership::class,
        'team_invitation' => TeamInvitation::class,
    ],

];
