<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | The configuration settings array is passed directly to HTMLPurifier.
    |
    | Feel free to add / remove / customize these attributes as you wish.
    |
    | Documentation: http://htmlpurifier.org/live/configdoc/plain.html
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Config
    |--------------------------------------------------------------------------
    |
    | This option controls the default configuration that will be used by
    | the Purify instance. This name must match one of the keys in
    | the "configs" array below.
    |
    */

    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Configs
    |--------------------------------------------------------------------------
    |
    | The configuration settings array is passed directly to HTMLPurifier.
    |
    | Feel free to add / remove / customize these attributes as you wish.
    |
    | Documentation: http://htmlpurifier.org/live/configdoc/plain.html
    |
    */

    'configs' => [

        'default' => [
            'Core.Encoding' => 'utf-8',
            'Cache.SerializerPath' => storage_path('app/purify'),
            'HTML.Doctype' => 'XHTML 1.0 Strict',
            'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span,img[width|height|alt|src],figure[class],table,tr,th,td',
            'HTML.ForbiddenElements' => '',
            'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Definitions
    |--------------------------------------------------------------------------
    |
    | The class name of the HTML definitions you wish to apply to every
    | Purify instance. This class must implement the interface:
    |
    | \Stevebauman\Purify\Definitions\Definition
    |
    */

    'definitions' => \App\Purify\CkEditorDefinition::class,

    /*
    |--------------------------------------------------------------------------
    | Serializer
    |--------------------------------------------------------------------------
    |
    | The serializer configuration.
    |
    */

    'serializer' => [
        'disk' => 'local',
        'path' => 'purify',
        'cache' => \Stevebauman\Purify\Cache\FilesystemDefinitionCache::class,
    ],

];
