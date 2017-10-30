<?php

return array(
    /*
    |---------------------------------------------------------------------
    | Active theme
    |---------------------------------------------------------------------
    |
    */
    'active'     => 'pixeladmin',
    /*
    |---------------------------------------------------------------------
    | Default theme
    |---------------------------------------------------------------------
    |
    */
    'default'    => 'pixeladmin',
    /*
    |---------------------------------------------------------------------
    | Theme class
    |---------------------------------------------------------------------
    |
    */
    'themeClass' => '\\Caffeinated\\Themes\\Theme',
    /*
    |---------------------------------------------------------------------
    | Path configuration
    |---------------------------------------------------------------------
    |
    */
    'paths'      => array(

        /*
        |---------------------------------------------------------------------
        | Theme paths
        |---------------------------------------------------------------------
        |
        | Absolute paths to all directories containing themes.
        | Each theme path should be located in the public path
        |
        */
        'themes'     => array(
            base_path('resources/themes')
        ),
        /*
        |---------------------------------------------------------------------
        | Namespace directory
        |---------------------------------------------------------------------
        |
        | The name/path of the directory containing the namespaces of a theme.
        | This is relative to the theme directory
        |
        */
        'namespaces' => 'namespaces',
        /*
        |---------------------------------------------------------------------
        | Packages directory
        |---------------------------------------------------------------------
        |
        | The name/path of the directory containing the packages of a theme.
        | This is relative to the theme directory
        |
        */
        'packages'   => 'packages',
        /*
        |---------------------------------------------------------------------
        | View directory
        |---------------------------------------------------------------------
        |
        | The name/path of the directory containing the views of a theme.
        | This is relative to the theme directory
        | Default ex: public/themes/{area}/{theme}/views
        |
        */
        'views'      => 'views',
        /*
        |---------------------------------------------------------------------
        | Asset directory
        |---------------------------------------------------------------------
        |
        | The name/path of the directory containing the assets of a theme.
        | This is relative to the theme directory
        |
        */
        'assets'     => 'assets'
    )
);
