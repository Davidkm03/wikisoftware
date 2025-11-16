<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Wiki Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all configuration options specific to the Software Wiki
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Upload Settings
    |--------------------------------------------------------------------------
    */

    'upload' => [
        'max_size' => env('WIKI_MAX_UPLOAD_SIZE', 10240), // KB
        'allowed_types' => explode(',', env('WIKI_ALLOWED_FILE_TYPES', 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,svg,zip')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    'pagination' => [
        'per_page' => env('WIKI_ITEMS_PER_PAGE', 15),
    ],

    /*
    |--------------------------------------------------------------------------
    | Document States
    |--------------------------------------------------------------------------
    */

    'document_states' => [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Roles
    |--------------------------------------------------------------------------
    */

    'roles' => [
        'admin' => 'Administrator',
        'editor' => 'Editor',
        'viewer' => 'Viewer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Predefined Categories
    |--------------------------------------------------------------------------
    | These categories will be seeded during initial setup
    */

    'default_categories' => [
        [
            'name' => 'Training Manuals',
            'slug' => 'training-manuals',
            'description' => 'Training materials and educational resources for team members',
            'icon' => 'academic-cap',
            'color' => 'blue',
        ],
        [
            'name' => 'Onboarding Documentation',
            'slug' => 'onboarding-documentation',
            'description' => 'Documentation for new team member onboarding process',
            'icon' => 'user-group',
            'color' => 'green',
        ],
        [
            'name' => 'De-Briefs',
            'slug' => 'de-briefs',
            'description' => 'Post-project reports and client transition documentation',
            'icon' => 'document-text',
            'color' => 'purple',
        ],
        [
            'name' => 'Guides',
            'slug' => 'guides',
            'description' => 'Technical guides and process documentation',
            'icon' => 'book-open',
            'color' => 'indigo',
        ],
        [
            'name' => 'Proposals',
            'slug' => 'proposals',
            'description' => 'Commercial and technical proposals',
            'icon' => 'briefcase',
            'color' => 'yellow',
        ],
        [
            'name' => 'Checklists',
            'slug' => 'checklists',
            'description' => 'Quality assurance and process checklists',
            'icon' => 'clipboard-check',
            'color' => 'pink',
        ],
        [
            'name' => 'Briefs',
            'slug' => 'briefs',
            'description' => 'Project summaries and overview documents',
            'icon' => 'document-duplicate',
            'color' => 'teal',
        ],
        [
            'name' => 'Planners',
            'slug' => 'planners',
            'description' => 'Project planning and timeline documents',
            'icon' => 'calendar',
            'color' => 'orange',
        ],
        [
            'name' => 'Audits',
            'slug' => 'audits',
            'description' => 'Code audits and quality assessments',
            'icon' => 'shield-check',
            'color' => 'red',
        ],
        [
            'name' => 'Other',
            'slug' => 'other',
            'description' => 'Miscellaneous documents and resources',
            'icon' => 'folder',
            'color' => 'gray',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Common Tags
    |--------------------------------------------------------------------------
    | Suggested tags for document classification
    */

    'suggested_tags' => [
        'Frontend',
        'Backend',
        'DevOps',
        'Security',
        'Performance',
        'Testing',
        'Database',
        'API',
        'UI/UX',
        'Mobile',
        'Best Practices',
        'Tutorial',
        'Reference',
        'Troubleshooting',
        'Architecture',
    ],

    /*
    |--------------------------------------------------------------------------
    | Editor Configuration
    |--------------------------------------------------------------------------
    */

    'editor' => [
        'type' => 'tinymce', // or 'quill'
        'toolbar' => 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | code',
        'plugins' => 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Configuration
    |--------------------------------------------------------------------------
    */

    'search' => [
        'min_query_length' => 3,
        'max_results' => 50,
    ],

];
