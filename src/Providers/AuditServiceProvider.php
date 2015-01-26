<?php
/**
 * This file is part of the DreamFactory Services Platform(tm) SDK For PHP
 *
 * DreamFactory Services Platform(tm) <http://github.com/dreamfactorysoftware/dsp-core>
 * Copyright 2012-2014 DreamFactory Software, Inc. <support@dreamfactory.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace DreamFactory\Library\Fabric\Auditing\Providers;

use DreamFactory\Library\Fabric\Auditing\Services\AuditingService;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Register the auditing service as a provider with Laravel.
 *
 * To use the "Audit" facade for this provider, you need to add the service provider to
 * your the providers array in your app/config/app.php file:
 *
 *  'providers' => array(
 *
 *      ... Other Providers Above ...
 *      'DreamFactory\Library\Fabric\Auditing\Providers\AuditServiceProvider',
 *
 *  ),
 */
class AuditServiceProvider extends ServiceProvider
{
    //********************************************************************************
    //* Public Methods
    //********************************************************************************

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //  Register object into instance container
        $this->app->bindShared(
            'dfe.auditing',
            function ( $app )
            {
                return new AuditingService();
            }
        );

        //  Create an alias for use
        $this->app->booting(
            function ()
            {
                AliasLoader::getInstance()->alias( 'Audit', 'DreamFactory\\Library\\Fabric\\Auditing\\Facades\\Audit' );
            }
        );
    }

}
