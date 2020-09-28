<?php


/**
 * Initialize the instance
 */
$options = new CiraPress\Options([
    'id' => 'cirapress34',
]);

/**
 * Section: General
 */

$options->add('section', [
    'id' => 'general',
    'title' => 'General',
]);

$options->add('link', [
    'type' => 'section',
    'title' => 'General',
    'section' => 'general',
    'icon' => 'ri-pencil-ruler-fill',
]);

$options->add('field', [
    'id' => 'text',
    'type' => 'text',
    'title' => 'Text',
    'section' => 'general',
    'default' => 'This is the text field.',
    'attributes' => [
        'placeholder' => 'Insert here ...',
    ]
]);

$options->add('field', [
    'id' => 'textarea',
    'type' => 'textarea',
    'title' => 'Textarea',
    'section' => 'general',
    'default' => 'This is the textarea field.',
    'attributes' => [
        'placeholder' => 'Insert here ...',
    ]
]);

$options->add('field', [
    'id' => 'switch',
    'type' => 'switch',
    'title' => 'Switch',
    'section' => 'general',
    'default' => true,
]);

$options->add('field', [
    'id' => 'select',
    'type' => 'select',
    'title' => 'Select',
    'section' => 'general',
    'default' => 'two',
    'condition' => ['switch', '===', true],
    'choices' => [
        [
            'id' => 'one',
            'title' => 'One'
        ],
        [
            'id' => 'two',
            'title' => 'Two'
        ]
    ]
]);

$options->add('field', [
    'id' => 'number',
    'type' => 'number',
    'title' => 'Number',
    'section' => 'general',
    'default' => 12,
]);

$options->add('field', [
    'id' => 'image',
    'type' => 'image',
    'title' => 'Image',
    'section' => 'general',
    'default' => ''
]);

$options->add('field', [
    'id' => 'mini_input',
    'type' => 'mini-input',
    'title' => 'Mini Input',
    'section' => 'general',
    'icon' => 'ri-font-size-2',
    'attributes' => [
        'placeholder' => 'Hey!',
    ]
]);

$options->add('field', [
    'id' => 'editor',
    'type' => 'editor',
    'title' => 'Editor',
    'section' => 'general',
    'default' => 'hello <i>world</i>',
]);

/**
 * Section: Appearance
 */

$options->add('section', [
    'id' => 'appearance',
    'title' => 'Appearance',
]);

$options->add('link', [
    'type' => 'section',
    'title' => 'Appearance',
    'section' => 'appearance',
    'icon' => 'ri-palette-fill',
]);

$options->add('field', [
    'id' => 'icon',
    'type' => 'icon',
    'section' => 'appearance',
    'title' => 'Icon',
    'default' => 'ri-sun-fill',
]);

$options->add('field', [
    'id' => 'color',
    'type' => 'color',
    'title' => 'Color',
    'section' => 'appearance',
    'default' => '#e0c708',
]);

$options->add('field', [
    'id' => 'radio_image',
    'type' => 'radio-image',
    'title' => 'Radio Image',
    'section' => 'appearance',
    'default' => 'b',
    'choices' => [
        [
            'img' => 'http://localhost:8080/images/gle_c-salt.gif',
            'id' => 'a',
            'title' => 'Layout A'
        ],
        [
            'img' => 'http://localhost:8080/images/gle_c-salt-s.gif',
            'id' => 'b',
            'title' => 'Layout B'
        ],
        [
            'img' => 'http://localhost:8080/images/gle_hncs.gif',
            'id' => 'c',
            'title' => 'Layout C'
        ],
        [
            'img' => 'http://localhost:8080/images/gle_pbc.gif',
            'id' => 'd',
            'title' => 'Layout D'
        ],
        [
            'img' => 'http://localhost:8080/images/gle_s-c-salt.gif',
            'id' => 'e',
        ],                            
    ],
]);

$options->add('field', [
    'id' => 'multi_mini_input',
    'type' => 'multi-mini-input',
    'section' => 'appearance',
    'title' => 'Multi Mini Input',
    'choices' => [
        [
            'id' => 'size',
            'icon' => 'ri-font-size-2',
            'attributes' => [
                'placeholder' => 'Size', 
            ]       
        ],
        [
            'id' => 'hight',
            'attributes' => [
                'placeholder' => 'Hight',
            ]
        ]
    ]
]);

$options->add('field', [
    'id' => 'radio_icon',
    'type' => 'radio-icon',
    'title' => 'Radio Icon',
    'section' => 'appearance',
    'choices' => [
        [
            'id' => 'left',
            'title' => 'Left',
            'icon' => 'ri-align-left'
        ],
        [
            'id' => 'right',
            'title' => 'Right',
            'icon' => 'ri-align-right'
        ],
        [
            'id' => 'center',
            'title' => 'Center',
            'icon' => 'ri-align-center'
        ],
        [
            'id' => 'justify',
            'title' => 'Justify',
            'icon' => 'ri-align-justify'
        ]
    ]
]);

$options->add('field', [
    'id' => 'typography',
    'type' => 'typography',
    'title' => 'Typography',
    'section' => 'appearance',
    'family' => true,
    'variant' => true,
    'size' => true,
    'variant' => true,
    'lineHeight' => true,
    'letterSpacing' => true,
    'wordSpacing' => true,
    'alignment' => true,
    'decoration' => true,
]);

$options->add('field', [
    'id' => 'multicolor',
    'type' => 'multi-color',
    'title' => 'Multi Color',
    'section' => 'appearance',
    'default' => [
        'link'    => '#0088cc',
        'hover'   => '#00aaff',
        'active'  => '#00ffff',                
    ],
    'choices' => [
        [
            'id' => 'link',
            'title' => 'Link'
        ],
        [
            'id' => 'hover',
            'title' => 'Hover'
        ],
        [
            'id' => 'active',
            'title' => 'Active'
        ]
    ]
]);

/**
 * Section: Others
 */

$options->add('section', [
    'id' => 'others',
    'title' => 'Others',
]);

$options->add('link', [
    'type' => 'section',
    'title' => 'Others',
    'section' => 'others',
    'icon' => 'ri-drag-drop-fill',
]);

$options->add('field', [
    'id' => 'repeater',
    'type' => 'repeater',
    'title' => 'Repeater',
    'section' => 'others',
    'fields' => [
        [
            'id' => 'text',
            'type' => 'text',
            'title' => 'Text',
            'default' => 'New Item',
            'attributes' => [
                'placeholder' => 'Insert here ...',
            ]
        ],
        [
            'id' => 'switch',
            'type' => 'switch',
            'title' => 'Switch',
            'default' => false,
        ],
        [
            'id' => 'color',
            'type' => 'color',
            'title' => 'Color',
            'default' => '#555555',
            'condition' => ['switch', '===', true]
        ],
        [
            'id' => 'repeater',
            'type' => 'repeater',
            'title' => 'Repeater',
            'fields' => [
                [
                    'id' => 'text',
                    'type' => 'text',
                    'title' => 'Text',
                    'default' => 'New Item',
                    'attributes' => [
                        'placeholder' => 'Sub repeater!',
                    ]
                ]
            ]
        ]
    ]
]);


$options->add('field', [
    'id' => 'preset',
    'type' => 'preset',
    'title' => 'Preset',
    'section' => 'others',
    'priority' => 0,
    'presets' => [
        [
            'title' => 'Dark',
            'img' => 'http://localhost:8080/wp-content/themes/twentytwenty/screenshot.png',
            'data' => [
                'general' => [
                    'text' => 'Dark theme import successfuly.'
                ]
            ]
        ],
        [
            'title' => 'Minimal',
            'img' => 'http://localhost:8080/wp-content/themes/twentyseventeen/screenshot.png',
            'data' => [
                'general' => [
                    'text' => 'Minimal theme import successfuly.'
                ]
            ]
        ]
    ]
]);


/**
 * Section: Advanced
 */

$options->add('section', [
    'id' => 'advanced',
    'title' => 'Advanced',
]);

$options->add('link', [
    'type' => 'section',
    'title' => 'Advanced',
    'section' => 'advanced',
    'icon' => 'ri-flashlight-fill',
]);

$options->add('field', [
    'id' => 'export',
    'type' => 'export',
    'title' => 'Export',
    'section' => 'advanced',
]);

$options->add('field', [
    'id' => 'import',
    'type' => 'import',
    'title' => 'Import',
    'section' => 'advanced',

]);

$options->add('field', [
    'id' => 'html',
    'type' => 'html',
    'title' => 'html',
    'section' => 'advanced',
    'content' => '<i>hello world!</i>',
    'priority' => 0
]);