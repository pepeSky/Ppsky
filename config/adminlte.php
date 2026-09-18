<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Administration ppSky',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => null,
    'logo_img' => 'vendor/adminlte/dist/img/ppsky.png',
    'logo_img_class' => 'flex img-responsive',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AdminLTE',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light-info elevation-2',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => '/',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        /*
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        */

        // *** Sites

        ['header' => 'MIS RUTAS'],

        [
            'text'        => 'Dashboard',
            'url'         => 'admin',
            'icon'        => 'fas fa-fw fa-tachometer-alt',
            'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text'    => 'Páginas',
            'icon'    => 'far fa-fw fa-swatchbook',
            'submenu' => [
                [
                    'text'        => 'pepeSky',
                    'url'         => 'https://www.pepesky.test',
                    'icon'        => 'fas fa-fw fa-blog',
                ],
                [
                    'text'        => 'manTcic Life',
                    'url'         => 'https://www.mantcic-life.test',
                    'icon'        => 'fas fa-fw fa-blog',
                ],
                [
                    'text'        => 'Patio Sofista',
                    'url'         => 'https://www.patio-sofista.test',
                    'icon'        => 'fas fa-fw fa-blog',
                ],
                [
                    'text'        => "dad's system",
                    'url'         => '#',
                    'icon'        => 'fas fa-fw fa-blog',
                ],

            ],
        ],


        ['header' => 'ENTRADAS'],

        // Sistema
        [
            'text'    => 'Sistema',
            'icon'    => 'fa-fw fab fa-battle-net',
            'submenu' => [
                [
                    'text' => 'Actores',
                    'route'  => 'admin.users.index',
                    'icon' => 'fas fa-users fa-fw',
                    'active' => ['admin/users*'],
                ],
                [
                    'text'    => 'Procesos',
                    'route'    => 'admin.units.index',
                    'icon'    => 'fa-fw far fa-dot-circle',
                    'active' => ['admin/units*'],
                ],
                [
                    'text'    => 'Herramientas',
                    'route'    => 'admin.roles.index',
                    'icon'    => 'far fa-fw fa-toolbox',
                    'active' => ['admin/roles*'],
                ],
                [
                    'text'    => 'Documentación',
                    'route'    => 'admin.roles.index',
                    'icon'    => 'fas fa-fw fa-file',
                    'active' => ['admin/roles*'],
                ],
                [
                    'text' => 'Categorias',
                    'route'  => 'admin.categories.index',
                    'icon' => 'fab fa-fw fa-buffer',
                    'active' => ['admin/categories*'],
                ],
                [
                    'text' => 'Etiquetas',
                    'route'  => 'admin.tags.index',
                    'icon' => 'fas fa-fw fa-tags',
                    'active' => ['admin/tags*'],
                ],

            ],
        ],

        // Administrador
        [
            'text'    => 'Administrador',
            'icon'    => 'fas fa-fw fa-user-tie',
            'submenu' => [
                [
                    'text' => 'Bitácora',
                    'route'    => 'admin.posts.index',
                    'icon' => 'fas fa-fw fa-pen-fancy',
                    'active' => ['admin/posts*'],
                ],
                [
                    'text' => 'Entidades',
                    'route'    => 'admin.entities.index',
                    'icon' => 'fas fa-fw fa-users-cog',
                    'active' => ['admin/entities*'],
                ],
                [
                    'text' => 'Actividades',
                    'route'    => 'admin.activities.index',
                    'icon' => 'fa-fw fas fa-wave-square',
                    'active' => ['admin/activities*'],
                ],
                [
                    'text' => 'Valorizaciones',
                    'route'    => 'admin.ideas.index',
                    'icon' => 'fa-fw far fa-cash-register',
                    'active' => ['admin/ideas*'],
                ],

            ],
        ],

        // Modulos
        [
            'text'    => 'Modulos',
            'icon' => 'fas fa-fw fa-cubes',
            'submenu' => [
                [
                    'text' => 'Estudios',
                    'route'  => 'admin.studies.index',
                    'icon'    => 'fa-fw fas fa-chalkboard-teacher',
                    'active' => ['admin/studies*'],
                ],
                [
                    'text' => 'Blog',
                    'route'  => 'admin.blogs.index',
                    'icon' => 'far fa-fw fa-blog',
                    'active' => ['admin/blogs*'],
                ],
                [
                    'text' => 'Desarrollo',
                    'route'  => 'admin.developments.index',
                    'icon' => 'far fa-fw fa-code',
                    'active' => ['admin/developments*'],
                ],
                [
                    'text' => 'Administración',
                    'route'  => 'admin.managements.index',
                    'icon' => 'far fa-fw fa-user-tie',
                    'active' => ['admin/managements*'],
                ],
                [
                    'text' => 'Contabilidad',
                    'route'  => 'admin.accountings.index',
                    'icon' => 'fa-fw fas fa-cash-register',
                    'active' => ['admin/accountings*'],
                ],
                [
                    'text' => 'Mantenimiento',
                    'route'  => 'admin.mantenances.index',
                    'icon'    => 'fa-fw fas fa-toolbox',
                    'active' => ['admin/mantenances*'],
                ],
                [
                    'text' => 'Salud',
                    'route'  => 'admin.healts.index',
                    'icon' => 'fa-fw fas fa-heartbeat',
                    'active' => ['admin/healts*'],
                ],
                [
                    'text' => 'Almacen',
                    'route'  => 'admin.warehouses.index',
                    'icon' => 'fa-fw fas fa-warehouse',
                    'active' => ['admin/warehouses*'],
                ],
                [
                    'text' => 'Venta',
                    'route'  => 'admin.stores.index',
                    'icon'    => 'fa-fw fas fa-store',
                    'active' => ['admin/stores*'],
                ],
                [
                    'text' => 'Diseño',
                    'route'  => 'admin.designs.index',
                    'icon' => 'fa-fw fas fa-bezier-curve',
                    'active' => ['admin/designs*'],
                ],


            ],
        ],


        ['header' => 'MODULOS '],

        // *** Blog
        [
            'text'    => 'Blog',
            'icon'    => 'far fa-fw fa-blog',
            'submenu' => [
                [
                    'text' => 'Blogs',
                    'route'  => 'admin.tasks.index',
                ],
                [
                    'text' => 'Ideas',
                    'url'  => '#',
                ],
                [
                    'text' => 'Titles',
                    'url'  => '#',
                ],
            ],
        ],

        // *** Developments
        [
            'text'    => 'Desarrollos',
            'icon'    => 'far fa-fw fa-code',
            'submenu' => [
                [
                    'text' => 'Development',
                    'route'  => 'admin.developments.index',
                ],
                [
                    'text' => 'Tasks',
                    'route'  => 'admin.tasks.index',
                ],
                [
                    'text' => 'Ideas',
                    'url'  => '#',
                ],
                [
                    'text' => 'Titles',
                    'url'  => '#',
                ],
            ],
        ],

        // *** Studies
        [
            'text'    => 'Estudios',
            'icon'    => 'fa-fw fas fa-chalkboard-teacher',
            'submenu' => [
                [
                    'text' => 'Subjects',
                    'route'  => 'admin.subjects.index',
                ],
                [
                    'text' => 'Academies',
                    'url'  => '#',
                ],
            ],
        ],

        // *** Management
        [
            'text'    => 'Administración',
            'icon'    => 'far fa-fw fa-user-tie',
            'submenu' => [
                [
                    'text' => 'Planning',
                    'url'  => '#',
                ],
                [
                    'text' => 'Organization',
                    'url'  => '#',
                ],
                [
                    'text' => 'Direction',
                    'url'  => '#',
                ],
                [
                    'text' => 'Control',
                    'url'  => '#',
                ],
            ],
        ],

        // *** Accounting
        [
            'text'    => 'Contabilidad',
            'icon'    => 'fa-fw fas fa-cash-register',
            'submenu' => [
                [
                    'text'  => 'Economic Facts',
                    'route' => 'admin.economic-facts.index',
                ],
                [
                    'text' => 'Diary Books',
                    'url'  => '#',
                ],
                [
                    'text' => 'Ledgers',
                    'url'  => '#',
                ],
                [
                    'text' => 'Balance Sheets',
                    'url'  => '#',
                ],
            ],
        ],

        // *** Maintenance
        [
            'text'    => 'Mantenimiento',
            'icon'    => 'fa-fw fas fa-toolbox',
            'submenu' => [
                [
                    'text' => 'Location',
                    'url'  => '#',
                ],
                [
                    'text' => 'Assets',
                    'url'  => '#',
                ],
                [
                    'text' => 'Activities',
                    'url'  => '#',
                ],
                [
                    'text' => 'Confiability',
                    'url'  => '#',
                ],
            ],
        ],

        // *** Store
        [
            'text'    => 'Tienda',
            'icon'    => 'fa-fw fas fa-store',
            'submenu' => [
                [
                    'text' => 'Presupuestos',
                    'url'  => '#',
                ],
                [
                    'text' => 'Cotizaciones',
                    'url'  => '#',
                ],
                [
                    'text' => 'Campañas',
                    'url'  => '#',
                ],
            ],
        ],

        // *** Healt
        [
            'text'    => 'Salud',
            'icon' => 'fa-fw fas fa-heartbeat',
            'submenu' => [
                [
                    'text' => 'Location',
                    'url'  => '#',
                ],
                [
                    'text' => 'Assets',
                    'url'  => '#',
                ],
                [
                    'text' => 'Activities',
                    'url'  => '#',
                ],
            ],
        ],

        // *** Warehouse
        [
            'text'    => 'Almacen',
            'icon' => 'fa-fw fas fa-warehouse',
            'submenu' => [
                [
                    'text' => 'Location',
                    'url'  => '#',
                ],
                [
                    'text' => 'Assets',
                    'url'  => '#',
                ],
                [
                    'text' => 'Activities',
                    'url'  => '#',
                ],
                [
                    'text' => 'Confiability',
                    'url'  => '#',
                ],
            ],
        ],

           // *** Designs
        [
            'text'    => 'Diseño',
            'icon' => 'fa-fw fas fa-bezier-curve',
            'submenu' => [
                [
                    'text' => 'Tarjetas',
                    'url'  => '#',
                ],
                [
                    'text' => 'Assets',
                    'url'  => '#',
                ],
                [
                    'text' => 'Activities',
                    'url'  => '#',
                ],
                [
                    'text' => 'Confiability',
                    'url'  => '#',
                ],
            ],
        ],




        ['header' => 'PRIORIDADES'],

        [
            'text'       => 'importante',
            'icon_color' => 'red',
            'url'        => '#',
        ],
        [
            'text'       => 'precaución',
            'icon_color' => 'yellow',
            'url'        => '#',
        ],
        [
            'text'       => 'información',
            'icon_color' => 'cyan',
            'url'        => '#',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => true,
];
