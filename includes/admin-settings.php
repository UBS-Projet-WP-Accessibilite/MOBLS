<?php
/**
 * Administration settings for the accessibility widget.
 *
 * @package A11yWidget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Normalize a list of feature slugs.
 *
 * @param mixed $items Slugs to sanitize.
 *
 * @return string[]
 */
if ( ! function_exists( 'a11y_widget_normalize_feature_slugs' ) ) {
    function a11y_widget_normalize_feature_slugs( $items ) {
        if ( ! is_array( $items ) ) {
            $items = array( $items );
        }

        $normalized = array();

        foreach ( $items as $slug ) {
            $slug = sanitize_key( $slug );

            if ( '' === $slug ) {
                continue;
            }

            $normalized[ $slug ] = true;
        }

        return array_keys( $normalized );
    }
}

/**
 * Option name helper for disabled features.
 *
 * @return string
 */
function a11y_widget_get_disabled_features_option_name() {
    return 'a11y_widget_disabled_features';
}

/**
 * Option name helper for the "force all features" toggle.
 *
 * @return string
 */
function a11y_widget_get_force_all_features_option_name() {
    return 'a11y_widget_force_all_features';
}

/**
 * Option name helper for the custom feature layout.
 *
 * @return string
 */
function a11y_widget_get_feature_layout_option_name() {
    return 'a11y_widget_feature_layout';
}

/**
 * Option name helper for the custom sub-feature layout.
 *
 * @return string
 */
function a11y_widget_get_subfeature_layout_option_name() {
    return 'a11y_widget_subfeature_layout';
}

/**
 * Option name helper for the custom section order.
 *
 * @return string
 */
function a11y_widget_get_section_order_option_name() {
    return 'a11y_widget_section_order';
}

/**
 * Option name helper for the launcher logo scale.
 *
 * @return string
 */
function a11y_widget_get_launcher_logo_scale_option_name() {
    return 'a11y_widget_launcher_logo_scale';
}

/**
 * Option name helper for configurable profile presets.
 *
 * @return string
 */
function a11y_widget_get_profile_presets_option_name() {
    return 'a11y_widget_profile_presets';
}

/**
 * Feature choices that can be composed into admin-configurable comfort profiles.
 *
 * @return array<string, array<string, string>>
 */
function a11y_widget_get_profile_feature_choices() {
    return array(
        'luminosite-reglages'              => array(
            'label' => __( 'Luminosité et contrastes', 'a11y-widget' ),
            'group' => __( 'Vision', 'a11y-widget' ),
        ),
        'vision-migraine'                  => array(
            'label' => __( 'Confort visuel', 'a11y-widget' ),
            'group' => __( 'Vision', 'a11y-widget' ),
        ),
        'vision-daltonisme-deuteranopie'   => array(
            'label' => __( 'Daltonisme : deutéranopie', 'a11y-widget' ),
            'group' => __( 'Vision', 'a11y-widget' ),
        ),
        'vision-daltonisme-protanopie'     => array(
            'label' => __( 'Daltonisme : protanopie', 'a11y-widget' ),
            'group' => __( 'Vision', 'a11y-widget' ),
        ),
        'vision-daltonisme-tritanopie'     => array(
            'label' => __( 'Daltonisme : tritanopie', 'a11y-widget' ),
            'group' => __( 'Vision', 'a11y-widget' ),
        ),
        'cognitif-reading-guide'           => array(
            'label' => __( 'Guide de lecture et structure', 'a11y-widget' ),
            'group' => __( 'Lecture', 'a11y-widget' ),
        ),
        'cognitif-dyslexie'                => array(
            'label' => __( 'Aides à la lecture dyslexie', 'a11y-widget' ),
            'group' => __( 'Lecture', 'a11y-widget' ),
        ),
        'lecture-texte-seul'               => array(
            'label' => __( 'Mode texte seul', 'a11y-widget' ),
            'group' => __( 'Lecture', 'a11y-widget' ),
        ),
        'lecture-structure-page'           => array(
            'label' => __( 'Structure de page', 'a11y-widget' ),
            'group' => __( 'Lecture', 'a11y-widget' ),
        ),
        'lecture-masquer-images'           => array(
            'label' => __( 'Masquer les images', 'a11y-widget' ),
            'group' => __( 'Lecture', 'a11y-widget' ),
        ),
        'cognitif-dictionnaire-glossaire'  => array(
            'label' => __( 'Dictionnaire / glossaire', 'a11y-widget' ),
            'group' => __( 'Cognitif', 'a11y-widget' ),
        ),
        'cognitif-reduire-distractions'    => array(
            'label' => __( 'Réduire les distractions', 'a11y-widget' ),
            'group' => __( 'Cognitif', 'a11y-widget' ),
        ),
        'moteur-boutons'                   => array(
            'label' => __( 'Boutons plus visibles', 'a11y-widget' ),
            'group' => __( 'Moteur', 'a11y-widget' ),
        ),
        'moteur-curseur'                   => array(
            'label' => __( 'Curseur personnalisé', 'a11y-widget' ),
            'group' => __( 'Moteur', 'a11y-widget' ),
        ),
        'epilepsie-protection'             => array(
            'label' => __( 'Réduction des déclencheurs visuels', 'a11y-widget' ),
            'group' => __( 'Mouvement', 'a11y-widget' ),
        ),
        'audition-text-to-speech'          => array(
            'label' => __( 'Lecture à voix haute', 'a11y-widget' ),
            'group' => __( 'Audio / vidéo', 'a11y-widget' ),
        ),
        'braille-contracte'                => array(
            'label' => __( 'Braille visuel contracté', 'a11y-widget' ),
            'group' => __( 'Braille visuel', 'a11y-widget' ),
        ),
        'braille-decontracte'              => array(
            'label' => __( 'Braille visuel non contracté', 'a11y-widget' ),
            'group' => __( 'Braille visuel', 'a11y-widget' ),
        ),
    );
}

/**
 * Default configurable comfort profiles.
 *
 * @return array<string, array<string, mixed>>
 */
function a11y_widget_get_profile_preset_defaults() {
    return array(
        'reading' => array(
            'enabled'  => true,
            'label'    => __( 'Lecture confortable', 'a11y-widget' ),
            'hint'     => __( 'Guide de lecture, espacement, patterns et lecture audio de confort.', 'a11y-widget' ),
            'features' => array( 'cognitif-reading-guide', 'cognitif-dyslexie', 'audition-text-to-speech' ),
        ),
        'visual'  => array(
            'enabled'  => true,
            'label'    => __( 'Contraste renforcé', 'a11y-widget' ),
            'hint'     => __( 'Luminosité, contraste et atténuation visuelle comme point de départ.', 'a11y-widget' ),
            'features' => array( 'luminosite-reglages', 'vision-migraine' ),
        ),
        'focus'   => array(
            'enabled'  => true,
            'label'    => __( 'Concentration', 'a11y-widget' ),
            'hint'     => __( 'Réduction des distractions et aide au suivi de lecture.', 'a11y-widget' ),
            'features' => array( 'cognitif-reading-guide', 'cognitif-reduire-distractions' ),
        ),
        'text'    => array(
            'enabled'  => true,
            'label'    => __( 'Texte seul', 'a11y-widget' ),
            'hint'     => __( 'Page recentrée sur le texte, avec images et médias masqués.', 'a11y-widget' ),
            'features' => array( 'lecture-texte-seul', 'lecture-masquer-images', 'cognitif-reduire-distractions' ),
        ),
        'motor'   => array(
            'enabled'  => true,
            'label'    => __( 'Navigation facilitée', 'a11y-widget' ),
            'hint'     => __( 'Boutons plus visibles et curseur renforcé.', 'a11y-widget' ),
            'features' => array( 'moteur-boutons', 'moteur-curseur' ),
        ),
        'safety'  => array(
            'enabled'  => true,
            'label'    => __( 'Sécurité visuelle', 'a11y-widget' ),
            'hint'     => __( 'Réduction des animations, vidéos automatiques et déclencheurs visuels potentiels.', 'a11y-widget' ),
            'features' => array( 'epilepsie-protection' ),
        ),
    );
}

/**
 * Sanitize configurable comfort profiles.
 *
 * @param mixed $input Raw option value.
 *
 * @return array<string, array<string, mixed>>
 */
function a11y_widget_sanitize_profile_presets( $input ) {
    $defaults        = a11y_widget_get_profile_preset_defaults();
    $feature_choices = a11y_widget_get_profile_feature_choices();
    $allowed_slugs   = array_keys( $feature_choices );

    if ( ! is_array( $input ) ) {
        $input = array();
    }

    $profiles          = array();
    $enabled_profiles  = 0;

    foreach ( $defaults as $profile_key => $default_profile ) {
        $raw_profile = isset( $input[ $profile_key ] ) && is_array( $input[ $profile_key ] )
            ? $input[ $profile_key ]
            : array();

        $label = isset( $raw_profile['label'] ) ? sanitize_text_field( (string) $raw_profile['label'] ) : '';
        $hint  = isset( $raw_profile['hint'] ) ? sanitize_textarea_field( (string) $raw_profile['hint'] ) : '';

        if ( '' === trim( $label ) ) {
            $label = isset( $default_profile['label'] ) ? (string) $default_profile['label'] : $profile_key;
        }

        if ( '' === trim( $hint ) ) {
            $hint = isset( $default_profile['hint'] ) ? (string) $default_profile['hint'] : '';
        }

        $features = isset( $raw_profile['features'] ) ? $raw_profile['features'] : array();

        if ( is_string( $features ) ) {
            $features = preg_split( '/,/', $features );
        }

        if ( ! is_array( $features ) ) {
            $features = array();
        }

        $features = array_values( array_intersect( a11y_widget_normalize_feature_slugs( $features ), $allowed_slugs ) );

        if ( empty( $features ) ) {
            $features = isset( $default_profile['features'] ) && is_array( $default_profile['features'] )
                ? array_values( array_intersect( a11y_widget_normalize_feature_slugs( $default_profile['features'] ), $allowed_slugs ) )
                : array();
        }

        $enabled = ! empty( $raw_profile['enabled'] );

        if ( $enabled ) {
            ++$enabled_profiles;
        }

        $profiles[ $profile_key ] = array(
            'enabled'  => $enabled,
            'label'    => $label,
            'hint'     => $hint,
            'features' => $features,
        );
    }

    if ( 0 === $enabled_profiles && isset( $profiles['reading'] ) ) {
        $profiles['reading']['enabled'] = true;
    }

    return $profiles;
}

/**
 * Retrieve configured comfort profiles.
 *
 * @return array<string, array<string, mixed>>
 */
function a11y_widget_get_profile_presets() {
    return a11y_widget_sanitize_profile_presets(
        get_option(
            a11y_widget_get_profile_presets_option_name(),
            a11y_widget_get_profile_preset_defaults()
        )
    );
}

/**
 * Retrieve profiles exposed to the public widget.
 *
 * @return array<int, array<string, mixed>>
 */
function a11y_widget_get_enabled_profile_presets() {
    $profiles = array();

    foreach ( a11y_widget_get_profile_presets() as $profile_key => $profile ) {
        if ( empty( $profile['enabled'] ) ) {
            continue;
        }

        $profiles[] = array(
            'key'      => sanitize_key( (string) $profile_key ),
            'label'    => isset( $profile['label'] ) ? (string) $profile['label'] : '',
            'hint'     => isset( $profile['hint'] ) ? (string) $profile['hint'] : '',
            'features' => isset( $profile['features'] ) && is_array( $profile['features'] )
                ? a11y_widget_normalize_feature_slugs( $profile['features'] )
                : array(),
        );
    }

    return $profiles;
}

/**
 * Default scale applied to the launcher logo preview.
 *
 * @return float
 */
function a11y_widget_get_launcher_logo_scale_default() {
    return 1.0;
}

/**
 * Sanitize the multiplier applied to the launcher logo preview.
 *
 * @param mixed $value Raw input value.
 *
 * @return float
 */
function a11y_widget_sanitize_launcher_logo_scale( $value ) {
    if ( is_array( $value ) ) {
        $value = reset( $value );
    }

    if ( is_scalar( $value ) ) {
        $value = (string) $value;
    } else {
        $value = '';
    }

    $normalized = str_replace( ',', '.', trim( $value ) );

    if ( '' !== $normalized ) {
        $normalized = rtrim( rtrim( $normalized, '0' ), '.' );
    }

    $allowed = array(
        '1'   => 1.0,
        '1.5' => 1.5,
        '2'   => 2.0,
        '3'   => 3.0,
        '5'   => 5.0,
    );

    if ( isset( $allowed[ $normalized ] ) ) {
        return $allowed[ $normalized ];
    }

    return $allowed['1'];
}

/**
 * Retrieve the sanitized launcher logo scale value.
 *
 * @return float
 */
function a11y_widget_get_launcher_logo_scale() {
    $option = get_option(
        a11y_widget_get_launcher_logo_scale_option_name(),
        a11y_widget_get_launcher_logo_scale_default()
    );

    return a11y_widget_sanitize_launcher_logo_scale( $option );
}

/**
 * Default heading levels used by the reading guide summary.
 *
 * @return string[]
 */
function a11y_widget_get_reading_guide_heading_levels_default() {
    return array( 'h2', 'h3' );
}

/**
 * Option name helper for the reading guide heading levels.
 *
 * @return string
 */
function a11y_widget_get_reading_guide_heading_levels_option_name() {
    return 'a11y_widget_reading_guide_heading_levels';
}

/**
 * Sanitize the heading levels stored for the reading guide summary.
 *
 * @param mixed $input Raw input.
 *
 * @return string[]
 */
function a11y_widget_sanitize_heading_levels( $input ) {
    if ( null === $input ) {
        return a11y_widget_get_reading_guide_heading_levels_default();
    }

    if ( is_string( $input ) ) {
        $input = preg_split( '/,/', $input );
    }

    if ( ! is_array( $input ) ) {
        $input = array();
    }

    $valid     = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
    $sanitized = array();

    foreach ( $input as $value ) {
        if ( is_array( $value ) ) {
            continue;
        }

        $value = strtolower( sanitize_key( $value ) );

        if ( in_array( $value, $valid, true ) ) {
            $sanitized[ $value ] = true;
        }
    }

    $levels = array();

    foreach ( $valid as $heading ) {
        if ( isset( $sanitized[ $heading ] ) ) {
            $levels[] = $heading;
        }
    }

    if ( empty( $levels ) ) {
        return a11y_widget_get_reading_guide_heading_levels_default();
    }

    return $levels;
}

/**
 * Retrieve the sanitized list of heading levels for the reading guide summary.
 *
 * @return string[]
 */
function a11y_widget_get_reading_guide_heading_levels() {
    $stored = get_option(
        a11y_widget_get_reading_guide_heading_levels_option_name(),
        a11y_widget_get_reading_guide_heading_levels_default()
    );

    return a11y_widget_sanitize_heading_levels( $stored );
}

/**
 * Build the CSS selector used to target headings for the reading guide summary.
 *
 * @return string
 */
function a11y_widget_get_reading_guide_heading_selector() {
    $levels = a11y_widget_get_reading_guide_heading_levels();

    if ( empty( $levels ) ) {
        $levels = a11y_widget_get_reading_guide_heading_levels_default();
    }

    $selectors = array();

    foreach ( $levels as $level ) {
        $selectors[] = 'main ' . $level;
    }

    return implode( ', ', $selectors );
}

/**
 * Default CSS selectors used to apply syllable separation.
 *
 * @return string
 */
function a11y_widget_get_reading_guide_syllable_selector_default() {
    return 'main p, main li';
}

/**
 * Option name helper for the reading guide syllable selector list.
 *
 * @return string
 */
function a11y_widget_get_reading_guide_syllable_selector_option_name() {
    return 'a11y_widget_reading_guide_syllable_selectors';
}

/**
 * Sanitize the CSS selectors stored for syllable separation.
 *
 * @param mixed $input Raw input.
 *
 * @return string
 */
function a11y_widget_sanitize_syllable_selectors( $input ) {
    if ( null === $input ) {
        return a11y_widget_get_reading_guide_syllable_selector_default();
    }

    if ( is_array( $input ) ) {
        $parts = $input;
    } else {
        $parts = preg_split( '/,/', (string) $input );
    }

    if ( ! is_array( $parts ) ) {
        return a11y_widget_get_reading_guide_syllable_selector_default();
    }

    $unique  = array();
    $ordered = array();

    foreach ( $parts as $selector ) {
        if ( is_array( $selector ) ) {
            continue;
        }

        $selector = trim( (string) $selector );

        if ( '' === $selector || isset( $unique[ $selector ] ) ) {
            continue;
        }

        $unique[ $selector ] = true;
        $ordered[]           = $selector;
    }

    if ( empty( $ordered ) ) {
        return a11y_widget_get_reading_guide_syllable_selector_default();
    }

    return implode( ', ', $ordered );
}

/**
 * Retrieve the sanitized CSS selectors used for syllable separation.
 *
 * @return string
 */
function a11y_widget_get_reading_guide_syllable_selector() {
    $stored = get_option(
        a11y_widget_get_reading_guide_syllable_selector_option_name(),
        a11y_widget_get_reading_guide_syllable_selector_default()
    );

    return a11y_widget_sanitize_syllable_selectors( $stored );
}

/**
 * Retrieve the list of disabled features stored in the database.
 *
 * @return string[]
 */
function a11y_widget_get_disabled_features() {
    $stored = get_option( a11y_widget_get_disabled_features_option_name(), array() );

    if ( empty( $stored ) ) {
        return array();
    }

    return a11y_widget_normalize_feature_slugs( $stored );
}

/**
 * Determine if all features should be displayed, regardless of customization.
 *
 * @return bool
 */
function a11y_widget_force_all_features_enabled() {
    return (bool) get_option( a11y_widget_get_force_all_features_option_name(), false );
}

/**
 * Retrieve the stored feature layout with sanitized slugs.
 *
 * @return array<string, string[]>
 */
function a11y_widget_get_feature_layout() {
    $layout = get_option( a11y_widget_get_feature_layout_option_name(), array() );

    return a11y_widget_sanitize_feature_layout( $layout );
}

/**
 * Retrieve the stored sub-feature layout with sanitized slugs.
 *
 * @return array<string, string[]>
 */
function a11y_widget_get_subfeature_layout() {
    $layout = get_option( a11y_widget_get_subfeature_layout_option_name(), array() );

    return a11y_widget_sanitize_subfeature_layout( $layout );
}

/**
 * Retrieve the stored section order.
 *
 * @return string[]
 */
function a11y_widget_get_section_order() {
    $order = get_option( a11y_widget_get_section_order_option_name(), array() );

    return a11y_widget_sanitize_section_order( $order );
}

/**
 * Sanitize disabled features before saving the option.
 *
 * @param mixed $input Raw input.
 *
 * @return string[]
 */
function a11y_widget_sanitize_disabled_features( $input ) {
    if ( null === $input ) {
        return array();
    }

    return a11y_widget_normalize_feature_slugs( $input );
}

/**
 * Sanitize the "force all features" option.
 *
 * @param mixed $input Raw input value.
 *
 * @return bool
 */
function a11y_widget_sanitize_force_all_features( $input ) {
    return ! empty( $input );
}

/**
 * Sanitize the custom feature layout option.
 *
 * @param mixed $input Raw input.
 *
 * @return array<string, string[]>
 */
function a11y_widget_sanitize_feature_layout( $input ) {
    if ( ! is_array( $input ) ) {
        return array();
    }

    $layout = array();

    foreach ( $input as $section_slug => $children ) {
        $section_slug = sanitize_title( $section_slug );

        if ( '' === $section_slug ) {
            continue;
        }

        if ( is_string( $children ) ) {
            $children = preg_split( '/,/', $children );
        }

        if ( ! is_array( $children ) ) {
            continue;
        }

        $child_lookup = array();

        foreach ( $children as $child_slug ) {
            if ( is_array( $child_slug ) ) {
                continue;
            }

            $child_slug = sanitize_key( $child_slug );

            if ( '' === $child_slug ) {
                continue;
            }

            $child_lookup[ $child_slug ] = true;
        }

        $layout[ $section_slug ] = array_keys( $child_lookup );
    }

    return $layout;
}

/**
 * Sanitize the custom sub-feature layout option.
 *
 * @param mixed $input Raw input.
 *
 * @return array<string, string[]>
 */
function a11y_widget_sanitize_subfeature_layout( $input ) {
    if ( ! is_array( $input ) ) {
        return array();
    }

    $layout = array();

    foreach ( $input as $feature_slug => $children ) {
        $feature_slug = sanitize_key( $feature_slug );

        if ( '' === $feature_slug ) {
            continue;
        }

        if ( is_string( $children ) ) {
            $children = preg_split( '/,/', $children );
        }

        if ( ! is_array( $children ) ) {
            continue;
        }

        $child_lookup = array();

        foreach ( $children as $child_slug ) {
            if ( is_array( $child_slug ) ) {
                continue;
            }

            $child_slug = sanitize_key( $child_slug );

            if ( '' === $child_slug ) {
                continue;
            }

            $child_lookup[ $child_slug ] = true;
        }

        $layout[ $feature_slug ] = array_keys( $child_lookup );
    }

    return $layout;
}

/**
 * Sanitize the custom section order option.
 *
 * @param mixed $input Raw input.
 *
 * @return string[]
 */
function a11y_widget_sanitize_section_order( $input ) {
    if ( null === $input ) {
        return array();
    }

    if ( is_string( $input ) ) {
        $input = preg_split( '/,/', $input );
    }

    if ( ! is_array( $input ) ) {
        return array();
    }

    $order = array();

    foreach ( $input as $slug ) {
        if ( is_array( $slug ) ) {
            continue;
        }

        $slug = sanitize_title( $slug );

        if ( '' === $slug || isset( $order[ $slug ] ) ) {
            continue;
        }

        $order[ $slug ] = true;
    }

    return array_keys( $order );
}

/**
 * Register plugin settings used by the admin screen.
 */
function a11y_widget_register_settings() {
    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_disabled_features_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_disabled_features',
            'default'           => array(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_force_all_features_option_name(),
        array(
            'type'              => 'boolean',
            'sanitize_callback' => 'a11y_widget_sanitize_force_all_features',
            'default'           => true,
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_feature_layout_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_feature_layout',
            'default'           => array(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_subfeature_layout_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_subfeature_layout',
            'default'           => array(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_section_order_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_section_order',
            'default'           => array(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_launcher_logo_option_name(),
        array(
            'type'              => 'string',
            'sanitize_callback' => 'a11y_widget_sanitize_launcher_logo',
            'default'           => a11y_widget_get_launcher_logo_default(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_launcher_logo_scale_option_name(),
        array(
            'type'              => 'number',
            'sanitize_callback' => 'a11y_widget_sanitize_launcher_logo_scale',
            'default'           => a11y_widget_get_launcher_logo_scale_default(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_background_mode_option_name(),
        array(
            'type'              => 'string',
            'sanitize_callback' => 'a11y_widget_sanitize_background_mode',
            'default'           => a11y_widget_get_background_mode_default(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_reading_guide_heading_levels_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_heading_levels',
            'default'           => a11y_widget_get_reading_guide_heading_levels_default(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_reading_guide_syllable_selector_option_name(),
        array(
            'type'              => 'string',
            'sanitize_callback' => 'a11y_widget_sanitize_syllable_selectors',
            'default'           => a11y_widget_get_reading_guide_syllable_selector_default(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_visual_options_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_visual_options',
            'default'           => a11y_widget_get_visual_options_default(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_accessibility_statement_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_accessibility_statement_options',
            'default'           => a11y_widget_get_accessibility_statement_default(),
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_feedback_collection_option_name(),
        array(
            'type'              => 'boolean',
            'sanitize_callback' => 'a11y_widget_sanitize_feedback_collection_enabled',
            'default'           => false,
        )
    );

    register_setting(
        'a11y_widget_settings',
        a11y_widget_get_feedback_retention_option_name(),
        array(
            'type'              => 'string',
            'sanitize_callback' => 'a11y_widget_sanitize_feedback_retention_days',
            'default'           => '90',
        )
    );

    register_setting(
        'a11y_widget_profile_settings',
        a11y_widget_get_profile_presets_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_profile_presets',
            'default'           => a11y_widget_get_profile_preset_defaults(),
        )
    );

    register_setting(
        'a11y_widget_audit_settings',
        a11y_widget_get_accessibility_statement_option_name(),
        array(
            'type'              => 'array',
            'sanitize_callback' => 'a11y_widget_sanitize_accessibility_statement_options',
            'default'           => a11y_widget_get_accessibility_statement_default(),
        )
    );
}
add_action( 'admin_init', 'a11y_widget_register_settings' );

/**
 * Configuration export schema identifier.
 *
 * @return string
 */
function a11y_widget_get_configuration_export_schema() {
    return 'mobls/a11y-widget-configuration';
}

/**
 * Configuration export schema version.
 *
 * @return int
 */
function a11y_widget_get_configuration_export_schema_version() {
    return 1;
}

/**
 * Configuration options that may be exported and imported.
 *
 * Retours utilisateurs, notes de retour, critères RGAA_Audit et anomalies restent hors
 * périmètre : cette liste ne concerne que la configuration du widget.
 *
 * @return array<string, array<string, mixed>>
 */
function a11y_widget_get_configuration_option_definitions() {
    return array(
        'disabled_features'                => array(
            'option'   => a11y_widget_get_disabled_features_option_name(),
            'get'      => 'a11y_widget_get_disabled_features',
            'sanitize' => 'a11y_widget_sanitize_disabled_features',
        ),
        'force_all_features'              => array(
            'option'   => a11y_widget_get_force_all_features_option_name(),
            'get'      => 'a11y_widget_force_all_features_enabled',
            'sanitize' => 'a11y_widget_sanitize_force_all_features',
        ),
        'feature_layout'                  => array(
            'option'   => a11y_widget_get_feature_layout_option_name(),
            'get'      => 'a11y_widget_get_feature_layout',
            'sanitize' => 'a11y_widget_sanitize_feature_layout',
        ),
        'subfeature_layout'               => array(
            'option'   => a11y_widget_get_subfeature_layout_option_name(),
            'get'      => 'a11y_widget_get_subfeature_layout',
            'sanitize' => 'a11y_widget_sanitize_subfeature_layout',
        ),
        'section_order'                   => array(
            'option'   => a11y_widget_get_section_order_option_name(),
            'get'      => 'a11y_widget_get_section_order',
            'sanitize' => 'a11y_widget_sanitize_section_order',
        ),
        'launcher_logo'                   => array(
            'option'   => a11y_widget_get_launcher_logo_option_name(),
            'get'      => 'a11y_widget_get_launcher_logo',
            'sanitize' => 'a11y_widget_sanitize_launcher_logo',
        ),
        'launcher_logo_scale'             => array(
            'option'   => a11y_widget_get_launcher_logo_scale_option_name(),
            'get'      => 'a11y_widget_get_launcher_logo_scale',
            'sanitize' => 'a11y_widget_sanitize_launcher_logo_scale',
        ),
        'background_mode'                 => array(
            'option'   => a11y_widget_get_background_mode_option_name(),
            'get'      => 'a11y_widget_get_background_mode',
            'sanitize' => 'a11y_widget_sanitize_background_mode',
        ),
        'reading_guide_heading_levels'    => array(
            'option'   => a11y_widget_get_reading_guide_heading_levels_option_name(),
            'get'      => 'a11y_widget_get_reading_guide_heading_levels',
            'sanitize' => 'a11y_widget_sanitize_heading_levels',
        ),
        'reading_guide_syllable_selector' => array(
            'option'   => a11y_widget_get_reading_guide_syllable_selector_option_name(),
            'get'      => 'a11y_widget_get_reading_guide_syllable_selector',
            'sanitize' => 'a11y_widget_sanitize_syllable_selectors',
        ),
        'visual_options'                  => array(
            'option'   => a11y_widget_get_visual_options_option_name(),
            'get'      => 'a11y_widget_get_visual_options',
            'sanitize' => 'a11y_widget_sanitize_visual_options',
        ),
        'profile_presets'                 => array(
            'option'   => a11y_widget_get_profile_presets_option_name(),
            'get'      => 'a11y_widget_get_profile_presets',
            'sanitize' => 'a11y_widget_sanitize_profile_presets',
        ),
        'accessibility_statement'         => array(
            'option'   => a11y_widget_get_accessibility_statement_option_name(),
            'get'      => 'a11y_widget_get_configuration_accessibility_statement_options',
            'sanitize' => 'a11y_widget_sanitize_configuration_accessibility_statement_options',
        ),
        'feedback_collection_enabled'     => array(
            'option'   => a11y_widget_get_feedback_collection_option_name(),
            'get'      => 'a11y_widget_feedback_collection_enabled',
            'sanitize' => 'a11y_widget_sanitize_feedback_collection_enabled',
        ),
        'feedback_retention_days'         => array(
            'option'   => a11y_widget_get_feedback_retention_option_name(),
            'get'      => 'a11y_widget_get_feedback_retention_days',
            'sanitize' => 'a11y_widget_sanitize_feedback_retention_days',
        ),
    );
}

/**
 * Return accessibility statement values allowed in a configuration export.
 *
 * Internal follow-up notes are intentionally excluded.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_configuration_accessibility_statement_options() {
    $statement_options = a11y_widget_get_accessibility_statement_options();
    unset( $statement_options['notes'] );

    return $statement_options;
}

/**
 * Sanitize imported accessibility statement values while preserving internal notes.
 *
 * @param mixed $input Raw imported value.
 *
 * @return array<string, mixed>
 */
function a11y_widget_sanitize_configuration_accessibility_statement_options( $input ) {
    $existing = a11y_widget_get_accessibility_statement_options();

    if ( ! is_array( $input ) ) {
        $input = array();
    }

    $allowed_keys = array(
        'enabled',
        'declaration_url',
        'audit_url',
        'audit_date',
        'audit_scope',
        'audit_status',
        'compliance_rate',
        'auditor',
    );

    foreach ( $allowed_keys as $key ) {
        if ( array_key_exists( $key, $input ) ) {
            $existing[ $key ] = $input[ $key ];
        }
    }

    return a11y_widget_sanitize_accessibility_statement_options( $existing );
}

/**
 * Build the current configuration payload.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_configuration_export_payload() {
    $options = array();

    foreach ( a11y_widget_get_configuration_option_definitions() as $key => $definition ) {
        $getter = isset( $definition['get'] ) ? (string) $definition['get'] : '';

        if ( '' === $getter || ! function_exists( $getter ) ) {
            continue;
        }

        $options[ $key ] = call_user_func( $getter );
    }

    return array(
        'schema'         => a11y_widget_get_configuration_export_schema(),
        'schema_version' => a11y_widget_get_configuration_export_schema_version(),
        'plugin_version' => defined( 'A11Y_WIDGET_VERSION' ) ? A11Y_WIDGET_VERSION : '',
        'exported_at'    => gmdate( 'c' ),
        'site_url'       => home_url(),
        'options'        => $options,
        'excluded'       => array(
            'feedback_items',
            'feedback_internal_notes',
            'audit_internal_notes',
            'rgaa_audit_criteria',
            'rgaa_audit_findings',
        ),
    );
}

/**
 * Configuration admin URL with optional status message.
 *
 * @param string $message Optional message key.
 * @param array  $args    Additional query args.
 *
 * @return string
 */
function a11y_widget_get_configuration_admin_url( $message = '', $args = array() ) {
    $url = admin_url( 'admin.php?page=a11y-widget' );

    if ( '' !== $message ) {
        $args['a11y_config_message'] = sanitize_key( $message );
    }

    if ( ! empty( $args ) ) {
        $url = add_query_arg( $args, $url );
    }

    return $url . '#a11y-widget-settings-config';
}

/**
 * Export widget configuration as JSON.
 */
function a11y_widget_handle_configuration_export() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation d’exporter cette configuration.', 'a11y-widget' ) );
    }

    check_admin_referer( 'a11y_widget_export_configuration' );

    $payload = a11y_widget_get_configuration_export_payload();
    $json    = wp_json_encode( $payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

    if ( ! is_string( $json ) ) {
        wp_die( esc_html__( 'La configuration n’a pas pu être sérialisée.', 'a11y-widget' ) );
    }

    nocache_headers();
    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=a11y-widget-configuration-' . gmdate( 'Y-m-d' ) . '.json' );

    echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    exit;
}
add_action( 'admin_post_a11y_widget_export_configuration', 'a11y_widget_handle_configuration_export' );

/**
 * Import widget configuration from a JSON payload.
 */
function a11y_widget_handle_configuration_import() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation d’importer cette configuration.', 'a11y-widget' ) );
    }

    check_admin_referer( 'a11y_widget_import_configuration' );

    $raw_json = isset( $_POST['a11y_widget_config_json'] )
        ? trim( (string) wp_unslash( $_POST['a11y_widget_config_json'] ) )
        : '';

    if ( '' === $raw_json || strlen( $raw_json ) > 262144 ) {
        wp_safe_redirect( a11y_widget_get_configuration_admin_url( 'import_file_error' ) );
        exit;
    }

    $payload = json_decode( $raw_json, true );

    if (
        ! is_array( $payload )
        || ! isset( $payload['schema'], $payload['schema_version'], $payload['options'] )
        || a11y_widget_get_configuration_export_schema() !== (string) $payload['schema']
        || a11y_widget_get_configuration_export_schema_version() !== (int) $payload['schema_version']
        || ! is_array( $payload['options'] )
    ) {
        wp_safe_redirect( a11y_widget_get_configuration_admin_url( 'import_invalid' ) );
        exit;
    }

    $definitions  = a11y_widget_get_configuration_option_definitions();
    $import_count = 0;

    foreach ( $definitions as $key => $definition ) {
        if ( ! array_key_exists( $key, $payload['options'] ) ) {
            continue;
        }

        $option_name = isset( $definition['option'] ) ? (string) $definition['option'] : '';
        $sanitize    = isset( $definition['sanitize'] ) ? (string) $definition['sanitize'] : '';

        if ( '' === $option_name || '' === $sanitize || ! function_exists( $sanitize ) ) {
            continue;
        }

        $value = call_user_func( $sanitize, $payload['options'][ $key ] );
        update_option( $option_name, $value );
        $import_count++;
    }

    if ( 0 === $import_count ) {
        wp_safe_redirect( a11y_widget_get_configuration_admin_url( 'import_invalid' ) );
        exit;
    }

    wp_safe_redirect(
        a11y_widget_get_configuration_admin_url(
            'imported',
            array( 'a11y_config_count' => (string) $import_count )
        )
    );
    exit;
}
add_action( 'admin_post_a11y_widget_import_configuration', 'a11y_widget_handle_configuration_import' );

/**
 * Admin URL for the guided setup assistant.
 *
 * @param string $message Optional message key.
 *
 * @return string
 */
function a11y_widget_get_setup_assistant_admin_url( $message = '' ) {
    $args = array(
        'page' => 'a11y-widget-setup',
    );

    if ( '' !== $message ) {
        $args['a11y_setup_message'] = sanitize_key( $message );
    }

    return add_query_arg( $args, admin_url( 'admin.php' ) );
}

/**
 * Feature slugs handled by the setup assistant.
 *
 * @return string[]
 */
function a11y_widget_get_setup_assistant_known_feature_slugs() {
    $slugs = array(
        'profils-recommandes',
        'feedback-utilisateur',
        'vision-daltonisme-deuteranopie',
        'vision-daltonisme-protanopie',
        'vision-daltonisme-deuteranomalie',
        'vision-daltonisme-protanomalie',
        'vision-daltonisme-tritanopie',
        'vision-daltonisme-tritanomalie',
        'vision-daltonisme-achromatopsie',
        'vision-migraine',
        'luminosite-reglages',
        'cognitif-reading-guide',
        'lecture-structure-page',
        'cognitif-dyslexie',
        'lecture-texte-seul',
        'lecture-masquer-images',
        'cognitif-dictionnaire-glossaire',
        'cognitif-reduire-distractions',
        'moteur-boutons',
        'moteur-curseur',
        'epilepsie-protection',
        'braille-contracte',
        'braille-decontracte',
        'audition-text-to-speech',
        'declaration-accessibilite',
    );

    /**
     * Filter the feature slugs known by the guided setup assistant.
     *
     * @param string[] $slugs Feature slugs.
     */
    $slugs = apply_filters( 'a11y_widget_setup_assistant_known_feature_slugs', $slugs );

    return a11y_widget_normalize_feature_slugs( $slugs );
}

/**
 * Scenario choices proposed by the guided setup assistant.
 *
 * @return array<string, array<string, mixed>>
 */
function a11y_widget_get_setup_assistant_feature_scope_choices() {
    return array(
        'current'  => array(
            'label'             => __( 'Conserver le périmètre actuel', 'a11y-widget' ),
            'description'       => __( 'Ne modifie pas la liste des fonctionnalités visibles. Utile si le panneau a déjà été ajusté finement.', 'a11y-widget' ),
            'preserve_features' => true,
        ),
        'complete' => array(
            'label'         => __( 'Complet', 'a11y-widget' ),
            'description'   => __( 'Toutes les fonctionnalités restent visibles. À choisir pour un site de démonstration ou une recette complète.', 'a11y-widget' ),
            'force_all'     => true,
            'visible_slugs' => array(),
        ),
        'balanced' => array(
            'label'         => __( 'Usage courant', 'a11y-widget' ),
            'description'   => __( 'Garde les aides les plus utiles au quotidien et masque les démonstrations ou réglages très spécialisés.', 'a11y-widget' ),
            'force_all'     => false,
            'visible_slugs' => array(
                'profils-recommandes',
                'feedback-utilisateur',
                'vision-migraine',
                'luminosite-reglages',
                'cognitif-reading-guide',
                'lecture-structure-page',
                'cognitif-dyslexie',
                'lecture-texte-seul',
                'lecture-masquer-images',
                'cognitif-dictionnaire-glossaire',
                'cognitif-reduire-distractions',
                'moteur-boutons',
                'moteur-curseur',
                'epilepsie-protection',
                'audition-text-to-speech',
                'declaration-accessibilite',
            ),
        ),
        'reading'  => array(
            'label'         => __( 'Lecture et apprentissage', 'a11y-widget' ),
            'description'   => __( 'Priorise le repérage, le texte, la lecture audio de confort et la réduction des distractions.', 'a11y-widget' ),
            'force_all'     => false,
            'visible_slugs' => array(
                'profils-recommandes',
                'feedback-utilisateur',
                'cognitif-reading-guide',
                'lecture-structure-page',
                'cognitif-dyslexie',
                'lecture-texte-seul',
                'lecture-masquer-images',
                'cognitif-dictionnaire-glossaire',
                'cognitif-reduire-distractions',
                'audition-text-to-speech',
                'declaration-accessibilite',
            ),
        ),
    );
}

/**
 * Return the setup assistant scope closest to the current settings.
 *
 * @return string
 */
function a11y_widget_get_current_setup_assistant_scope() {
    if ( a11y_widget_force_all_features_enabled() ) {
        return 'complete';
    }

    $known_slugs = a11y_widget_get_setup_assistant_known_feature_slugs();
    $disabled    = a11y_widget_get_disabled_features();
    $visible     = array_values( array_diff( $known_slugs, $disabled ) );
    $visible     = a11y_widget_normalize_feature_slugs( $visible );
    $choices     = a11y_widget_get_setup_assistant_feature_scope_choices();

    foreach ( $choices as $scope => $choice ) {
        if ( ! empty( $choice['force_all'] ) || ! empty( $choice['preserve_features'] ) ) {
            continue;
        }

        $choice_visible = isset( $choice['visible_slugs'] ) ? a11y_widget_normalize_feature_slugs( $choice['visible_slugs'] ) : array();

        sort( $choice_visible );
        sort( $visible );

        if ( $choice_visible === $visible ) {
            return $scope;
        }
    }

    return 'current';
}

/**
 * Build disabled feature slugs for a setup assistant scope.
 *
 * @param string $scope Scope key.
 *
 * @return string[]
 */
function a11y_widget_get_setup_assistant_disabled_features_for_scope( $scope ) {
    $scope   = sanitize_key( (string) $scope );
    $choices = a11y_widget_get_setup_assistant_feature_scope_choices();

    if ( ! isset( $choices[ $scope ] ) || ! empty( $choices[ $scope ]['force_all'] ) || ! empty( $choices[ $scope ]['preserve_features'] ) ) {
        return array();
    }

    $known_slugs    = a11y_widget_get_setup_assistant_known_feature_slugs();
    $visible_slugs  = isset( $choices[ $scope ]['visible_slugs'] ) ? a11y_widget_normalize_feature_slugs( $choices[ $scope ]['visible_slugs'] ) : array();
    $visible_lookup = array_fill_keys( $visible_slugs, true );
    $disabled       = array();

    foreach ( $known_slugs as $slug ) {
        if ( ! isset( $visible_lookup[ $slug ] ) ) {
            $disabled[] = $slug;
        }
    }

    return $disabled;
}

/**
 * Save settings submitted from the setup assistant.
 */
function a11y_widget_handle_setup_assistant_save() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation de configurer ce widget.', 'a11y-widget' ) );
    }

    check_admin_referer( 'a11y_widget_setup_assistant' );

    $background_mode = isset( $_POST['a11y_widget_setup_background_mode'] )
        ? a11y_widget_sanitize_background_mode( wp_unslash( $_POST['a11y_widget_setup_background_mode'] ) )
        : a11y_widget_get_background_mode_default();
    update_option( a11y_widget_get_background_mode_option_name(), $background_mode );

    $scope   = isset( $_POST['a11y_widget_setup_feature_scope'] ) ? sanitize_key( (string) wp_unslash( $_POST['a11y_widget_setup_feature_scope'] ) ) : 'balanced';
    $choices = a11y_widget_get_setup_assistant_feature_scope_choices();

    if ( ! isset( $choices[ $scope ] ) ) {
        $scope = 'balanced';
    }

    if ( empty( $choices[ $scope ]['preserve_features'] ) ) {
        $force_all = ! empty( $choices[ $scope ]['force_all'] );
        update_option( a11y_widget_get_force_all_features_option_name(), $force_all );
        update_option(
            a11y_widget_get_disabled_features_option_name(),
            a11y_widget_sanitize_disabled_features( a11y_widget_get_setup_assistant_disabled_features_for_scope( $scope ) )
        );
    }

    $statement_url_raw = isset( $_POST['a11y_widget_setup_declaration_url'] )
        ? wp_unslash( $_POST['a11y_widget_setup_declaration_url'] )
        : '';

    if ( is_array( $statement_url_raw ) ) {
        $statement_url_raw = reset( $statement_url_raw );
    }

    $statement_options = a11y_widget_get_accessibility_statement_options();
    $statement_options['enabled'] = ! empty( $_POST['a11y_widget_setup_statement_enabled'] );
    $statement_options['declaration_url'] = esc_url_raw( trim( (string) $statement_url_raw ) );
    update_option(
        a11y_widget_get_accessibility_statement_option_name(),
        a11y_widget_sanitize_accessibility_statement_options( $statement_options )
    );

    $feedback_enabled = ! empty( $_POST['a11y_widget_setup_feedback_enabled'] );
    update_option( a11y_widget_get_feedback_collection_option_name(), $feedback_enabled );

    $feedback_retention_raw = isset( $_POST['a11y_widget_setup_feedback_retention'] )
        ? wp_unslash( $_POST['a11y_widget_setup_feedback_retention'] )
        : a11y_widget_get_feedback_retention_days();

    if ( is_array( $feedback_retention_raw ) ) {
        $feedback_retention_raw = reset( $feedback_retention_raw );
    }

    $feedback_retention = a11y_widget_sanitize_feedback_retention_days( $feedback_retention_raw );
    update_option( a11y_widget_get_feedback_retention_option_name(), $feedback_retention );

    wp_safe_redirect( a11y_widget_get_setup_assistant_admin_url( 'saved' ) );
    exit;
}
add_action( 'admin_post_a11y_widget_save_setup_assistant', 'a11y_widget_handle_setup_assistant_save' );

/**
 * Add the "Accessibilité" menu entry in the WordPress administration.
 */
function a11y_widget_register_admin_menu() {
    add_menu_page(
        __( 'Réglages du widget d’accessibilité', 'a11y-widget' ),
        __( 'Accessibilité', 'a11y-widget' ),
        'manage_options',
        'a11y-widget',
        'a11y_widget_render_admin_page',
        'dashicons-universal-access-alt',
        58
    );

    add_submenu_page(
        'a11y-widget',
        __( 'Réglages du widget', 'a11y-widget' ),
        __( 'Réglages du widget', 'a11y-widget' ),
        'manage_options',
        'a11y-widget',
        'a11y_widget_render_admin_page'
    );

    add_submenu_page(
        'a11y-widget',
        __( 'Assistant de configuration', 'a11y-widget' ),
        __( 'Assistant de configuration', 'a11y-widget' ),
        'manage_options',
        'a11y-widget-setup',
        'a11y_widget_render_setup_assistant_page'
    );

    add_submenu_page(
        'a11y-widget',
        __( 'Profils de confort', 'a11y-widget' ),
        __( 'Profils de confort', 'a11y-widget' ),
        'manage_options',
        'a11y-widget-profiles',
        'a11y_widget_render_profile_presets_page'
    );

    add_submenu_page(
        'a11y-widget',
        __( 'Santé du widget', 'a11y-widget' ),
        __( 'Santé du widget', 'a11y-widget' ),
        'manage_options',
        'a11y-widget-health',
        'a11y_widget_render_health_page'
    );

    add_submenu_page(
        'a11y-widget',
        __( 'Audit et suivi', 'a11y-widget' ),
        __( 'Audit et suivi', 'a11y-widget' ),
        'manage_options',
        'a11y-widget-audit',
        'a11y_widget_render_audit_page'
    );

    add_submenu_page(
        'a11y-widget',
        __( 'Crédits', 'a11y-widget' ),
        __( 'Crédits', 'a11y-widget' ),
        'manage_options',
        'a11y-widget-credits',
        'a11y_widget_render_credits_page'
    );

    add_submenu_page(
        'a11y-widget',
        __( 'Retours utilisateurs', 'a11y-widget' ),
        __( 'Retours utilisateurs', 'a11y-widget' ),
        'manage_options',
        'a11y-widget-feedback',
        'a11y_widget_render_feedback_page'
    );
}
add_action( 'admin_menu', 'a11y_widget_register_admin_menu' );

/**
 * Enqueue styles for the admin settings screen.
 *
 * @param string $hook Current admin page.
 */
function a11y_widget_enqueue_admin_assets( $hook ) {
    if ( false === strpos( (string) $hook, 'a11y-widget' ) ) {
        return;
    }

    wp_enqueue_style(
        'a11y-widget-admin',
        A11Y_WIDGET_URL . 'assets/admin.css',
        array(),
        a11y_widget_get_asset_version( 'assets/admin.css' )
    );

    wp_enqueue_script(
        'a11y-widget-admin',
        A11Y_WIDGET_URL . 'assets/admin.js',
        array(),
        a11y_widget_get_asset_version( 'assets/admin.js' ),
        true
    );
}
add_action( 'admin_enqueue_scripts', 'a11y_widget_enqueue_admin_assets' );

/**
 * Return the local audit and follow-up admin URL.
 *
 * @return string
 */
function a11y_widget_get_audit_admin_url() {
    return admin_url( 'admin.php?page=a11y-widget-audit' );
}

/**
 * Return the RGAA_Audit companion integration status for the audit screen.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_rgaa_audit_integration_status() {
    $admin_url = a11y_widget_get_rgaa_audit_admin_url();
    $version   = defined( 'RGAA_AUDIT_VERSION' ) ? (string) constant( 'RGAA_AUDIT_VERSION' ) : '';
    $detected  = defined( 'RGAA_AUDIT_VERSION' ) || '' !== $admin_url;

    return array(
        'detected'       => $detected,
        'version'        => $version,
        'admin_url'      => $admin_url,
        'link_available' => '' !== $admin_url,
        'mode'           => defined( 'RGAA_AUDIT_VERSION' )
            ? __( 'Extension détectée', 'a11y-widget' )
            : __( 'Lien fourni par filtre ou non détecté', 'a11y-widget' ),
    );
}

/**
 * Return a human-readable label for a widget health level.
 *
 * @param string $level Health level.
 *
 * @return string
 */
function a11y_widget_get_health_level_label( $level ) {
    switch ( sanitize_key( (string) $level ) ) {
        case 'error':
            return __( 'Action requise', 'a11y-widget' );
        case 'warning':
            return __( 'À surveiller', 'a11y-widget' );
        case 'info':
            return __( 'Information', 'a11y-widget' );
        case 'ok':
        default:
            return __( 'OK', 'a11y-widget' );
    }
}

/**
 * Return the severity weight used to summarize health checks.
 *
 * @param string $level Health level.
 *
 * @return int
 */
function a11y_widget_get_health_level_weight( $level ) {
    switch ( sanitize_key( (string) $level ) ) {
        case 'error':
            return 3;
        case 'warning':
            return 2;
        case 'info':
            return 1;
        case 'ok':
        default:
            return 0;
    }
}

/**
 * Collect feature slugs that map to user-facing controls.
 *
 * @param array<int, array<string, mixed>> $sections Widget sections.
 *
 * @return string[]
 */
function a11y_widget_collect_health_feature_slugs( $sections ) {
    $slugs = array();

    if ( ! is_array( $sections ) ) {
        return array();
    }

    foreach ( $sections as $section ) {
        if ( empty( $section['children'] ) || ! is_array( $section['children'] ) ) {
            continue;
        }

        foreach ( $section['children'] as $feature ) {
            if ( empty( $feature['slug'] ) ) {
                continue;
            }

            if ( ! empty( $feature['children'] ) && is_array( $feature['children'] ) ) {
                foreach ( $feature['children'] as $subfeature ) {
                    $subfeature_slug = isset( $subfeature['slug'] ) ? sanitize_key( (string) $subfeature['slug'] ) : '';

                    if ( '' !== $subfeature_slug ) {
                        $slugs[ $subfeature_slug ] = true;
                    }
                }

                continue;
            }

            $feature_slug = sanitize_key( (string) $feature['slug'] );

            if ( '' !== $feature_slug ) {
                $slugs[ $feature_slug ] = true;
            }
        }
    }

    return array_keys( $slugs );
}

/**
 * Build the local health report for the widget administration.
 *
 * This diagnostic is intentionally local-only. It does not call external services and it does
 * not inspect RGAA_Audit criteria, proofs or anomalies.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_widget_health_report() {
    $checks                = array();
    $sections              = a11y_widget_get_sections();
    $feature_slugs         = a11y_widget_collect_health_feature_slugs( $sections );
    $total_features        = count( $feature_slugs );
    $disabled_features     = a11y_widget_get_disabled_features();
    $disabled_lookup       = array_fill_keys( $disabled_features, true );
    $known_disabled_count  = 0;
    $force_all_features    = a11y_widget_force_all_features_enabled();

    foreach ( $feature_slugs as $feature_slug ) {
        if ( isset( $disabled_lookup[ $feature_slug ] ) ) {
            ++$known_disabled_count;
        }
    }

    $visible_features = $force_all_features ? $total_features : max( 0, $total_features - $known_disabled_count );

    $asset_checks = array(
        'assets/widget.js'    => __( 'Script front du widget', 'a11y-widget' ),
        'assets/widget.css'   => __( 'Styles front du widget', 'a11y-widget' ),
        'templates/widget.php' => __( 'Template public du widget', 'a11y-widget' ),
        'assets/admin.js'     => __( 'Script admin', 'a11y-widget' ),
        'assets/admin.css'    => __( 'Styles admin', 'a11y-widget' ),
    );
    $missing_assets = array();

    foreach ( $asset_checks as $relative_path => $asset_label ) {
        $asset_path = A11Y_WIDGET_PATH . ltrim( $relative_path, '/\\' );

        if ( ! file_exists( $asset_path ) || ! is_readable( $asset_path ) || 0 === (int) filesize( $asset_path ) ) {
            $missing_assets[] = $asset_label;
        }
    }

    if ( empty( $missing_assets ) ) {
        $checks[] = array(
            'level' => 'ok',
            'title' => __( 'Assets locaux présents', 'a11y-widget' ),
            'text'  => __( 'Les fichiers front, admin et template attendus sont disponibles dans le plugin.', 'a11y-widget' ),
        );
    } else {
        $checks[] = array(
            'level'        => 'error',
            'title'        => __( 'Assets locaux incomplets', 'a11y-widget' ),
            'text'         => sprintf(
                /* translators: %s: missing asset labels */
                __( 'Fichiers absents ou illisibles : %s.', 'a11y-widget' ),
                implode( ', ', $missing_assets )
            ),
            'action_label' => __( 'Vérifier les fichiers du plugin', 'a11y-widget' ),
        );
    }

    if ( 0 === $total_features ) {
        $checks[] = array(
            'level'        => 'error',
            'title'        => __( 'Aucune fonctionnalité trouvée', 'a11y-widget' ),
            'text'         => __( 'Le modèle de sections ne renvoie aucune fonctionnalité affichable.', 'a11y-widget' ),
            'action_label' => __( 'Contrôler les réglages', 'a11y-widget' ),
            'action_url'   => admin_url( 'admin.php?page=a11y-widget#a11y-widget-settings-features' ),
        );
    } elseif ( 0 === $visible_features ) {
        $checks[] = array(
            'level'        => 'error',
            'title'        => __( 'Toutes les fonctionnalités sont masquées', 'a11y-widget' ),
            'text'         => __( 'Le widget risque de s’ouvrir sans action utile pour les utilisateurs.', 'a11y-widget' ),
            'action_label' => __( 'Réactiver des fonctionnalités', 'a11y-widget' ),
            'action_url'   => admin_url( 'admin.php?page=a11y-widget#a11y-widget-settings-features' ),
        );
    } elseif ( ! $force_all_features && $visible_features < max( 3, (int) floor( $total_features * 0.2 ) ) ) {
        $checks[] = array(
            'level'        => 'warning',
            'title'        => __( 'Très peu de fonctionnalités visibles', 'a11y-widget' ),
            'text'         => sprintf(
                /* translators: 1: visible feature count, 2: total feature count */
                __( '%1$d fonctionnalité(s) visible(s) sur %2$d. Vérifiez que ce périmètre correspond bien au besoin du site.', 'a11y-widget' ),
                $visible_features,
                $total_features
            ),
            'action_label' => __( 'Voir les fonctionnalités', 'a11y-widget' ),
            'action_url'   => admin_url( 'admin.php?page=a11y-widget#a11y-widget-settings-features' ),
        );
    } else {
        $checks[] = array(
            'level' => $force_all_features || 0 === $known_disabled_count ? 'ok' : 'info',
            'title' => __( 'Fonctionnalités affichables', 'a11y-widget' ),
            'text'  => sprintf(
                /* translators: 1: visible feature count, 2: total feature count */
                __( '%1$d fonctionnalité(s) visible(s) sur %2$d dans le panneau public.', 'a11y-widget' ),
                $visible_features,
                $total_features
            ),
        );
    }

    $background_mode = a11y_widget_get_background_mode();
    $background_labels = array(
        'modal'       => __( 'Modal', 'a11y-widget' ),
        'interactive' => __( 'Interactif', 'a11y-widget' ),
    );
    $background_label = isset( $background_labels[ $background_mode ] ) ? $background_labels[ $background_mode ] : $background_mode;

    if ( 'interactive' === $background_mode ) {
        $checks[] = array(
            'level'        => 'warning',
            'title'        => __( 'Mode arrière-plan interactif', 'a11y-widget' ),
            'text'         => __( 'Le site reste manipulable derrière le panneau. Validez ce choix au clavier avant une mise en prod.', 'a11y-widget' ),
            'action_label' => __( 'Changer le comportement', 'a11y-widget' ),
            'action_url'   => admin_url( 'admin.php?page=a11y-widget#a11y-widget-settings-behavior' ),
        );
    } else {
        $checks[] = array(
            'level' => 'ok',
            'title' => __( 'Mode arrière-plan modal', 'a11y-widget' ),
            'text'  => __( 'Le panneau isole le focus et évite les interactions accidentelles avec la page derrière le widget.', 'a11y-widget' ),
        );
    }

    $auto_inject_enabled = (bool) apply_filters( 'a11y_widget_enable_auto', true );

    $checks[] = array(
        'level'        => $auto_inject_enabled ? 'ok' : 'info',
        'title'        => $auto_inject_enabled ? __( 'Injection automatique active', 'a11y-widget' ) : __( 'Injection automatique désactivée', 'a11y-widget' ),
        'text'         => $auto_inject_enabled
            ? __( 'Le widget est injecté via le pied de page WordPress si le thème appelle wp_footer().', 'a11y-widget' )
            : __( 'Le widget devra être placé manuellement, par exemple avec le shortcode [a11y_widget].', 'a11y-widget' ),
        'action_label' => $auto_inject_enabled ? '' : __( 'Ouvrir le site public', 'a11y-widget' ),
        'action_url'   => $auto_inject_enabled ? '' : home_url( '/' ),
    );

    $feedback_enabled   = a11y_widget_feedback_collection_enabled();
    $feedback_retention = a11y_widget_get_feedback_retention_days();

    if ( $feedback_enabled && 0 === $feedback_retention ) {
        $checks[] = array(
            'level'        => 'warning',
            'title'        => __( 'Retours activés sans durée de conservation', 'a11y-widget' ),
            'text'         => __( 'La collecte est active et la conservation est illimitée. Validez ce choix avec l’équipe qui traite les retours.', 'a11y-widget' ),
            'action_label' => __( 'Régler la conservation', 'a11y-widget' ),
            'action_url'   => admin_url( 'admin.php?page=a11y-widget#a11y-widget-settings-feedback' ),
        );
    } elseif ( $feedback_enabled ) {
        $checks[] = array(
            'level' => 'ok',
            'title' => __( 'Retours utilisateurs configurés', 'a11y-widget' ),
            'text'  => sprintf(
                /* translators: %d: retention duration in days */
                __( 'La collecte est active avec une conservation de %d jours.', 'a11y-widget' ),
                $feedback_retention
            ),
        );
    } else {
        $checks[] = array(
            'level' => 'info',
            'title' => __( 'Retours utilisateurs désactivés', 'a11y-widget' ),
            'text'  => __( 'Aucune carte de feedback n’est affichée dans le widget public.', 'a11y-widget' ),
        );
    }

    $statement_options = a11y_widget_get_accessibility_statement_options();
    $statement_enabled = ! empty( $statement_options['enabled'] );
    $statement_url     = isset( $statement_options['declaration_url'] ) ? trim( (string) $statement_options['declaration_url'] ) : '';

    if ( $statement_enabled && '' === $statement_url ) {
        $checks[] = array(
            'level'        => 'warning',
            'title'        => __( 'Déclaration activée sans URL', 'a11y-widget' ),
            'text'         => __( 'Le widget ne devrait afficher la déclaration que lorsqu’une page publique vérifiable est renseignée.', 'a11y-widget' ),
            'action_label' => __( 'Configurer la déclaration', 'a11y-widget' ),
            'action_url'   => admin_url( 'admin.php?page=a11y-widget-audit' ),
        );
    } elseif ( $statement_enabled ) {
        $checks[] = array(
            'level' => 'ok',
            'title' => __( 'Déclaration publique liée', 'a11y-widget' ),
            'text'  => __( 'Une URL de déclaration peut être proposée depuis le widget public.', 'a11y-widget' ),
        );
    } else {
        $checks[] = array(
            'level' => 'info',
            'title' => __( 'Déclaration masquée', 'a11y-widget' ),
            'text'  => __( 'La carte Déclaration d’accessibilité n’est pas affichée dans le widget.', 'a11y-widget' ),
        );
    }

    $rgaa_integration = a11y_widget_get_rgaa_audit_integration_status();
    $rgaa_detected    = ! empty( $rgaa_integration['detected'] );

    $checks[] = array(
        'level'        => $rgaa_detected ? 'ok' : 'info',
        'title'        => $rgaa_detected ? __( 'RGAA_Audit détecté', 'a11y-widget' ) : __( 'RGAA_Audit non détecté', 'a11y-widget' ),
        'text'         => $rgaa_detected
            ? __( 'La liaison admin est disponible, sans lecture des critères, preuves ou anomalies RGAA_Audit par le widget.', 'a11y-widget' )
            : __( 'Le widget fonctionne en mode autonome. Les audits, critères, preuves et anomalies restent hors de ce diagnostic.', 'a11y-widget' ),
        'action_label' => $rgaa_detected && ! empty( $rgaa_integration['admin_url'] ) ? __( 'Ouvrir RGAA_Audit', 'a11y-widget' ) : '',
        'action_url'   => $rgaa_detected && ! empty( $rgaa_integration['admin_url'] ) ? (string) $rgaa_integration['admin_url'] : '',
    );

    $checks[] = array(
        'level' => 'ok',
        'title' => __( 'Diagnostic local uniquement', 'a11y-widget' ),
        'text'  => __( 'Cette page ne transmet aucune donnée à un service externe et ne crée aucune collecte utilisateur supplémentaire.', 'a11y-widget' ),
    );

    $summary_level = 'ok';
    $max_weight    = 0;

    foreach ( $checks as $check ) {
        $level  = isset( $check['level'] ) ? (string) $check['level'] : 'ok';
        $weight = a11y_widget_get_health_level_weight( $level );

        if ( $weight > $max_weight ) {
            $max_weight    = $weight;
            $summary_level = sanitize_key( $level );
        }
    }

    $summary_text = __( 'Aucun point bloquant détecté sur la configuration locale du widget.', 'a11y-widget' );

    if ( 'warning' === $summary_level ) {
        $summary_text = __( 'Le widget est utilisable, avec au moins un point à valider avant mise en prod.', 'a11y-widget' );
    } elseif ( 'error' === $summary_level ) {
        $summary_text = __( 'Un point bloque ou fragilise fortement l’affichage du widget public.', 'a11y-widget' );
    }

    return array(
        'status'       => $summary_level,
        'status_label' => a11y_widget_get_health_level_label( $summary_level ),
        'summary'      => $summary_text,
        'metrics'      => array(
            array(
                'label' => __( 'Version', 'a11y-widget' ),
                'value' => defined( 'A11Y_WIDGET_VERSION' ) ? A11Y_WIDGET_VERSION : __( 'Non renseignée', 'a11y-widget' ),
            ),
            array(
                'label' => __( 'Fonctionnalités visibles', 'a11y-widget' ),
                'value' => sprintf(
                    /* translators: 1: visible feature count, 2: total feature count */
                    __( '%1$d / %2$d', 'a11y-widget' ),
                    $visible_features,
                    $total_features
                ),
            ),
            array(
                'label' => __( 'Mode arrière-plan', 'a11y-widget' ),
                'value' => $background_label,
            ),
            array(
                'label' => __( 'Retours', 'a11y-widget' ),
                'value' => $feedback_enabled ? __( 'Activés', 'a11y-widget' ) : __( 'Désactivés', 'a11y-widget' ),
            ),
            array(
                'label' => __( 'RGAA_Audit', 'a11y-widget' ),
                'value' => $rgaa_detected ? __( 'Détecté', 'a11y-widget' ) : __( 'Mode autonome', 'a11y-widget' ),
            ),
        ),
        'checks'       => $checks,
    );
}

/**
 * Build audit follow-up notices from the stored declaration metadata.
 *
 * @param array<string, mixed> $statement_options Stored statement metadata.
 *
 * @return array<int, array<string, string>>
 */
function a11y_widget_get_audit_followup_notices( $statement_options ) {
    $notices     = array();
    $status      = isset( $statement_options['audit_status'] ) ? sanitize_key( (string) $statement_options['audit_status'] ) : 'not_assessed';
    $audit_url   = isset( $statement_options['audit_url'] ) ? trim( (string) $statement_options['audit_url'] ) : '';
    $audit_date  = isset( $statement_options['audit_date'] ) ? trim( (string) $statement_options['audit_date'] ) : '';
    $rate        = isset( $statement_options['compliance_rate'] ) ? trim( (string) $statement_options['compliance_rate'] ) : '';
    $has_audit_data = ( 'not_assessed' !== $status ) || '' !== $audit_url || '' !== $audit_date || '' !== $rate;

    if ( ! empty( $statement_options['enabled'] ) && empty( $statement_options['declaration_url'] ) ) {
        $notices[] = array(
            'level' => 'warning',
            'title' => __( 'Déclaration activée sans URL publique', 'a11y-widget' ),
            'text'  => __( 'Le widget ne devrait afficher la déclaration que lorsqu’une page publique vérifiable est renseignée.', 'a11y-widget' ),
        );
    }

    if ( $has_audit_data && '' === $audit_url ) {
        $notices[] = array(
            'level' => 'warning',
            'title' => __( 'Données d’audit sans rapport lié', 'a11y-widget' ),
            'text'  => __( 'Ajoutez un lien vers le rapport, l’outil d’audit ou une synthèse vérifiable avant de publier un statut détaillé.', 'a11y-widget' ),
        );
    }

    if ( '' !== $rate && 'not_assessed' === $status ) {
        $notices[] = array(
            'level' => 'warning',
            'title' => __( 'Taux renseigné avec un statut non évalué', 'a11y-widget' ),
            'text'  => __( 'Le taux devrait provenir d’un audit réel et être cohérent avec le statut déclaré.', 'a11y-widget' ),
        );
    }

    if ( '' !== $audit_date ) {
        $timestamp = strtotime( $audit_date . ' 00:00:00' );

        if ( false !== $timestamp ) {
            $now = function_exists( 'current_time' )
                ? (int) current_time( 'timestamp' )
                : time();

            if ( $timestamp > $now ) {
                $notices[] = array(
                    'level' => 'warning',
                    'title' => __( 'Date d’audit dans le futur', 'a11y-widget' ),
                    'text'  => __( 'Vérifiez la date avant d’afficher l’information comme donnée d’audit.', 'a11y-widget' ),
                );
            } elseif ( $timestamp < strtotime( '-1 year', $now ) ) {
                $notices[] = array(
                    'level' => 'info',
                    'title' => __( 'Audit possiblement ancien', 'a11y-widget' ),
                    'text'  => __( 'Prévoyez une revue si le site, le thème ou les contenus ont évolué depuis cet audit.', 'a11y-widget' ),
                );
            }
        }
    }

    if ( empty( $notices ) ) {
        $notices[] = array(
            'level' => 'ok',
            'title' => __( 'Aucune alerte de suivi détectée', 'a11y-widget' ),
            'text'  => __( 'Les informations renseignées sont cohérentes pour un affichage public synthétique.', 'a11y-widget' ),
        );
    }

    return $notices;
}

/**
 * Return the admin URL for the user feedback page.
 *
 * @param array<string, string> $args Optional query arguments.
 *
 * @return string
 */
function a11y_widget_get_feedback_admin_url( $args = array() ) {
    $url = admin_url( 'admin.php?page=a11y-widget-feedback' );

    if ( ! empty( $args ) ) {
        $url = add_query_arg( $args, $url );
    }

    return $url;
}

/**
 * Return the admin URL for one feedback detail view.
 *
 * @param int                   $feedback_id Feedback post ID.
 * @param array<string, string> $args        Optional query arguments.
 *
 * @return string
 */
function a11y_widget_get_feedback_detail_admin_url( $feedback_id, $args = array() ) {
    $args['feedback_id'] = (string) absint( $feedback_id );

    return a11y_widget_get_feedback_admin_url( $args );
}

/**
 * Redirect back to the feedback page with a short status message.
 *
 * @param string                $message Message key.
 * @param array<string, string> $args    Optional query arguments.
 */
function a11y_widget_redirect_feedback_admin( $message, $args = array() ) {
    $args['a11y_feedback_message'] = sanitize_key( $message );

    wp_safe_redirect(
        a11y_widget_get_feedback_admin_url( $args )
    );
    exit;
}

/**
 * Return a stored feedback post if it belongs to the widget.
 *
 * @param int $feedback_id Feedback post ID.
 *
 * @return WP_Post|null
 */
function a11y_widget_get_feedback_item( $feedback_id ) {
    $post = $feedback_id ? get_post( $feedback_id ) : null;

    if ( ! $post || a11y_widget_get_feedback_post_type() !== $post->post_type ) {
        return null;
    }

    if ( 'private' !== get_post_status( $post ) ) {
        return null;
    }

    return $post;
}

/**
 * Build feedback admin redirect arguments from a submitted admin form.
 *
 * @param int $feedback_id Feedback post ID.
 *
 * @return array<string, string>
 */
function a11y_widget_get_feedback_redirect_args_from_post( $feedback_id = 0 ) {
    $redirect_args = array();
    $status_filter = a11y_widget_get_feedback_status_filter_from_post();

    if ( '' !== $status_filter ) {
        $redirect_args['feedback_status'] = $status_filter;
    }

    if ( $feedback_id && ! empty( $_POST['feedback_detail'] ) ) {
        $redirect_args['feedback_id'] = (string) absint( $feedback_id );
    }

    return $redirect_args;
}

/**
 * Return a valid status filter from the current request.
 *
 * @return string
 */
function a11y_widget_get_feedback_status_filter_from_request() {
    $status = isset( $_GET['feedback_status'] ) ? sanitize_key( (string) wp_unslash( $_GET['feedback_status'] ) ) : '';
    $choices = a11y_widget_get_feedback_status_choices();

    return isset( $choices[ $status ] ) ? $status : '';
}

/**
 * Return a valid status filter submitted by an admin form.
 *
 * @return string
 */
function a11y_widget_get_feedback_status_filter_from_post() {
    $status = isset( $_POST['feedback_status_filter'] ) ? sanitize_key( (string) wp_unslash( $_POST['feedback_status_filter'] ) ) : '';
    $choices = a11y_widget_get_feedback_status_choices();

    return isset( $choices[ $status ] ) ? $status : '';
}

/**
 * Build a meta query for feedback status filtering.
 *
 * @param string $status Status filter slug.
 *
 * @return array<int|string, mixed>
 */
function a11y_widget_get_feedback_status_meta_query( $status ) {
    if ( '' === $status ) {
        return array();
    }

    if ( 'new' === $status ) {
        return array(
            'relation' => 'OR',
            array(
                'key'     => '_a11y_widget_feedback_status',
                'value'   => 'new',
                'compare' => '=',
            ),
            array(
                'key'     => '_a11y_widget_feedback_status',
                'compare' => 'NOT EXISTS',
            ),
        );
    }

    return array(
        array(
            'key'     => '_a11y_widget_feedback_status',
            'value'   => $status,
            'compare' => '=',
        ),
    );
}

/**
 * Count feedback entries by workflow status.
 *
 * @return array<string, int>
 */
function a11y_widget_get_feedback_status_counts() {
    $status_choices = a11y_widget_get_feedback_status_choices();
    $counts = array_fill_keys( array_keys( $status_choices ), 0 );
    $counts['all'] = 0;
    $feedback_ids = get_posts(
        array(
            'post_type'      => a11y_widget_get_feedback_post_type(),
            'post_status'    => 'private',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    foreach ( $feedback_ids as $feedback_id ) {
        $status = get_post_meta( (int) $feedback_id, '_a11y_widget_feedback_status', true );

        if ( ! isset( $status_choices[ $status ] ) ) {
            $status = 'new';
        }

        $counts[ $status ]++;
        $counts['all']++;
    }

    return $counts;
}

/**
 * Delete feedback entries matching a private admin query.
 *
 * @param array<string, mixed> $query_args Additional query arguments.
 *
 * @return int
 */
function a11y_widget_delete_feedback_posts( $query_args ) {
    $feedback_ids = get_posts(
        array_merge(
            $query_args,
            array(
                'post_type'      => a11y_widget_get_feedback_post_type(),
                'post_status'    => 'private',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
            )
        )
    );
    $deleted = 0;

    foreach ( $feedback_ids as $feedback_id ) {
        $result = wp_delete_post( (int) $feedback_id, true );

        if ( $result ) {
            $deleted++;
        }
    }

    return $deleted;
}

/**
 * Permanently delete feedback entries older than the configured retention.
 *
 * @return int Number of deleted entries.
 */
function a11y_widget_purge_expired_feedback_posts() {
    $retention = a11y_widget_get_feedback_retention_days();

    if ( 'unlimited' === $retention ) {
        return 0;
    }

    return a11y_widget_delete_feedback_posts(
        array(
            'date_query' => array(
                array(
                    'column'    => 'post_date',
                    'before'    => absint( $retention ) . ' days ago',
                    'inclusive' => false,
                ),
            ),
        )
    );
}

/**
 * Handle feedback status changes from the administration.
 */
function a11y_widget_handle_feedback_status_update() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation de modifier ces retours.', 'a11y-widget' ) );
    }

    $feedback_id = isset( $_POST['feedback_id'] ) ? absint( wp_unslash( $_POST['feedback_id'] ) ) : 0;

    check_admin_referer( 'a11y_widget_update_feedback_status_' . $feedback_id );

    $post = a11y_widget_get_feedback_item( $feedback_id );

    if ( ! $post ) {
        a11y_widget_redirect_feedback_admin( 'invalid_feedback' );
    }

    $statuses = a11y_widget_get_feedback_status_choices();
    $status   = isset( $_POST['feedback_status'] ) ? sanitize_key( (string) wp_unslash( $_POST['feedback_status'] ) ) : 'new';

    if ( ! isset( $statuses[ $status ] ) ) {
        $status = 'new';
    }

    update_post_meta( $feedback_id, '_a11y_widget_feedback_status', $status );
    $redirect_args = a11y_widget_get_feedback_redirect_args_from_post( $feedback_id );

    a11y_widget_redirect_feedback_admin( 'status_updated', $redirect_args );
}
add_action( 'admin_post_a11y_widget_update_feedback_status', 'a11y_widget_handle_feedback_status_update' );

/**
 * Update the private internal note attached to a feedback entry.
 */
function a11y_widget_handle_feedback_note_update() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation de modifier ces retours.', 'a11y-widget' ) );
    }

    $feedback_id = isset( $_POST['feedback_id'] ) ? absint( wp_unslash( $_POST['feedback_id'] ) ) : 0;

    check_admin_referer( 'a11y_widget_update_feedback_note_' . $feedback_id );

    $post = a11y_widget_get_feedback_item( $feedback_id );

    if ( ! $post ) {
        a11y_widget_redirect_feedback_admin( 'invalid_feedback' );
    }

    $note = isset( $_POST['feedback_internal_note'] )
        ? sanitize_textarea_field( (string) wp_unslash( $_POST['feedback_internal_note'] ) )
        : '';

    if ( '' === $note ) {
        delete_post_meta( $feedback_id, '_a11y_widget_feedback_internal_note' );
    } else {
        update_post_meta( $feedback_id, '_a11y_widget_feedback_internal_note', $note );
    }

    a11y_widget_redirect_feedback_admin(
        'note_updated',
        a11y_widget_get_feedback_redirect_args_from_post( $feedback_id )
    );
}
add_action( 'admin_post_a11y_widget_update_feedback_note', 'a11y_widget_handle_feedback_note_update' );

/**
 * Permanently delete a feedback entry from the administration.
 */
function a11y_widget_handle_feedback_delete() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation de supprimer ces retours.', 'a11y-widget' ) );
    }

    $feedback_id = isset( $_POST['feedback_id'] ) ? absint( wp_unslash( $_POST['feedback_id'] ) ) : 0;

    check_admin_referer( 'a11y_widget_delete_feedback_' . $feedback_id );

    $post = a11y_widget_get_feedback_item( $feedback_id );

    if ( ! $post ) {
        a11y_widget_redirect_feedback_admin( 'invalid_feedback' );
    }

    wp_delete_post( $feedback_id, true );
    $redirect_args = a11y_widget_get_feedback_redirect_args_from_post();

    a11y_widget_redirect_feedback_admin( 'deleted', $redirect_args );
}
add_action( 'admin_post_a11y_widget_delete_feedback', 'a11y_widget_handle_feedback_delete' );

/**
 * Permanently delete archived feedback entries.
 */
function a11y_widget_handle_feedback_purge_archived() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation de purger ces retours.', 'a11y-widget' ) );
    }

    check_admin_referer( 'a11y_widget_purge_archived_feedback' );

    $deleted = a11y_widget_delete_feedback_posts(
        array(
            'meta_query' => array(
                array(
                    'key'     => '_a11y_widget_feedback_status',
                    'value'   => 'archived',
                    'compare' => '=',
                ),
            ),
        )
    );

    a11y_widget_redirect_feedback_admin(
        'purged_archived',
        array( 'a11y_feedback_count' => (string) $deleted )
    );
}
add_action( 'admin_post_a11y_widget_purge_archived_feedback', 'a11y_widget_handle_feedback_purge_archived' );

/**
 * Permanently delete feedback entries older than the configured retention.
 */
function a11y_widget_handle_feedback_purge_old() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation de purger ces retours.', 'a11y-widget' ) );
    }

    check_admin_referer( 'a11y_widget_purge_old_feedback' );

    $retention = a11y_widget_get_feedback_retention_days();

    if ( 'unlimited' === $retention ) {
        a11y_widget_redirect_feedback_admin( 'retention_unlimited' );
    }

    $deleted = a11y_widget_purge_expired_feedback_posts();

    a11y_widget_redirect_feedback_admin(
        'purged_old',
        array( 'a11y_feedback_count' => (string) $deleted )
    );
}
add_action( 'admin_post_a11y_widget_purge_old_feedback', 'a11y_widget_handle_feedback_purge_old' );

/**
 * Prefix CSV cells that spreadsheet tools may interpret as formulas.
 *
 * @param mixed $value Raw cell value.
 *
 * @return string
 */
function a11y_widget_escape_feedback_csv_cell( $value ) {
    $value = (string) $value;

    if ( preg_match( '/^\s*[=+\-@]/', $value ) ) {
        return "'" . $value;
    }

    return $value;
}

/**
 * Write one sanitized CSV row.
 *
 * @param resource     $output Output stream.
 * @param array<mixed> $row    Row cells.
 */
function a11y_widget_put_feedback_csv_row( $output, $row ) {
    fputcsv(
        $output,
        array_map( 'a11y_widget_escape_feedback_csv_cell', $row ),
        ';'
    );
}

/**
 * Export stored feedback as a CSV file for internal follow-up.
 */
function a11y_widget_handle_feedback_export() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Vous n’avez pas l’autorisation d’exporter ces retours.', 'a11y-widget' ) );
    }

    check_admin_referer( 'a11y_widget_export_feedback' );

    $rating_choices = a11y_widget_get_feedback_rating_choices();
    $status_choices = a11y_widget_get_feedback_status_choices();
    $status_filter  = a11y_widget_get_feedback_status_filter_from_request();
    $query_args     = array(
        'post_type'      => a11y_widget_get_feedback_post_type(),
        'post_status'    => 'private',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    );
    $meta_query     = a11y_widget_get_feedback_status_meta_query( $status_filter );

    if ( ! empty( $meta_query ) ) {
        $query_args['meta_query'] = $meta_query;
    }

    $feedback_items = get_posts( $query_args );
    $filename_part  = '' !== $status_filter ? '-' . sanitize_file_name( $status_filter ) : '';

    nocache_headers();
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=a11y-widget-retours' . $filename_part . '-' . gmdate( 'Y-m-d' ) . '.csv' );

    $output = fopen( 'php://output', 'w' );

    if ( false === $output ) {
        exit;
    }

    fwrite( $output, "\xEF\xBB\xBF" );
    a11y_widget_put_feedback_csv_row(
        $output,
        array(
            'date',
            'statut',
            'aide_percue',
            'page',
            'commentaire',
            'note_interne',
            'profil',
            'fonctions_actives',
        )
    );

    foreach ( $feedback_items as $feedback_item ) {
        $rating   = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_rating', true );
        $status   = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_status', true );
        $page     = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_page_url', true );
        $profile  = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_profile', true );
        $features = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_features', true );
        $note     = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_internal_note', true );

        if ( ! is_array( $features ) ) {
            $features = array();
        }

        a11y_widget_put_feedback_csv_row(
            $output,
            array(
                get_the_date( 'Y-m-d H:i:s', $feedback_item ),
                isset( $status_choices[ $status ] ) ? $status_choices[ $status ] : $status_choices['new'],
                isset( $rating_choices[ $rating ] ) ? $rating_choices[ $rating ] : __( 'Non renseigné', 'a11y-widget' ),
                (string) $page,
                (string) $feedback_item->post_content,
                (string) $note,
                (string) $profile,
                implode( ', ', array_map( 'sanitize_key', $features ) ),
            )
        );
    }

    fclose( $output );
    exit;
}
add_action( 'admin_post_a11y_widget_export_feedback', 'a11y_widget_handle_feedback_export' );

/**
 * Render the admin page that lets site administrators hide specific features.
 */
function a11y_widget_render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $sections               = a11y_widget_get_sections();
    $disabled               = a11y_widget_get_disabled_features();
    $disabled_lookup        = array_fill_keys( $disabled, true );
    $force_all_features     = a11y_widget_force_all_features_enabled();
    $force_all_option_key   = a11y_widget_get_force_all_features_option_name();
    $layout_option_key      = a11y_widget_get_feature_layout_option_name();
    $subfeature_layout_key  = a11y_widget_get_subfeature_layout_option_name();
    $stored_subfeature_layout = a11y_widget_get_subfeature_layout();
    $section_order_option   = a11y_widget_get_section_order_option_name();
    $section_order_slugs    = array_filter( array_map( 'sanitize_title', wp_list_pluck( $sections, 'slug' ) ) );
    $section_order_value    = implode( ',', $section_order_slugs );
    $heading_option_key     = a11y_widget_get_reading_guide_heading_levels_option_name();
    $heading_levels         = a11y_widget_get_reading_guide_heading_levels();
    $available_headings     = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
    $syllable_option_key    = a11y_widget_get_reading_guide_syllable_selector_option_name();
    $syllable_selectors     = a11y_widget_get_reading_guide_syllable_selector();
    $logo_option_key        = a11y_widget_get_launcher_logo_option_name();
    $logo_variants          = a11y_widget_get_launcher_logo_variants();
    $selected_logo          = a11y_widget_get_launcher_logo();
    $logo_scale_option_key  = a11y_widget_get_launcher_logo_scale_option_name();
    $logo_scale_value       = a11y_widget_get_launcher_logo_scale();
    $logo_scale_choices     = array( 1, 1.5, 2, 3, 5 );
    $logo_preview_scale     = (float) $logo_scale_value;

    if ( $logo_preview_scale <= 0 ) {
        $logo_preview_scale = a11y_widget_get_launcher_logo_scale_default();
    }

    $logo_preview_scale_css = rtrim( rtrim( number_format( $logo_preview_scale, 2, '.', '' ), '0' ), '.' );
    $logo_preview_style     = sprintf(
        '--a11y-launcher-preview-scale: %s;',
        $logo_preview_scale_css
    );
    $background_mode_option = a11y_widget_get_background_mode_option_name();
    $background_mode_value  = a11y_widget_get_background_mode();
    $visual_option_key      = a11y_widget_get_visual_options_option_name();
    $visual_options         = a11y_widget_get_visual_options();
    $visual_presets         = a11y_widget_get_visual_theme_presets();
    $statement_option_key   = a11y_widget_get_accessibility_statement_option_name();
    $statement_options      = a11y_widget_get_accessibility_statement_options();
    $statement_status_choices = a11y_widget_get_accessibility_statement_status_choices();
    $audit_admin_url        = a11y_widget_get_audit_admin_url();
    $rgaa_audit_admin_url   = a11y_widget_get_rgaa_audit_admin_url();
    $feedback_collection_key = a11y_widget_get_feedback_collection_option_name();
    $feedback_collection_enabled = a11y_widget_feedback_collection_enabled();
    $feedback_retention_key = a11y_widget_get_feedback_retention_option_name();
    $feedback_retention_value = a11y_widget_get_feedback_retention_days();
    $feedback_retention_choices = a11y_widget_get_feedback_retention_choices();
    $configuration_export_url = wp_nonce_url(
        add_query_arg(
            array( 'action' => 'a11y_widget_export_configuration' ),
            admin_url( 'admin-post.php' )
        ),
        'a11y_widget_export_configuration'
    );
    $configuration_message_key = isset( $_GET['a11y_config_message'] ) ? sanitize_key( (string) wp_unslash( $_GET['a11y_config_message'] ) ) : '';
    $configuration_import_count = isset( $_GET['a11y_config_count'] ) ? absint( wp_unslash( $_GET['a11y_config_count'] ) ) : 0;
    $configuration_messages = array(
        'imported'          => sprintf(
            /* translators: %d: imported setting count */
            _n( 'Configuration importée. %d réglage mis à jour.', 'Configuration importée. %d réglages mis à jour.', max( 1, $configuration_import_count ), 'a11y-widget' ),
            $configuration_import_count
        ),
        'import_invalid'    => __( 'Le JSON fourni n’est pas compatible avec cette version de l’export.', 'a11y-widget' ),
        'import_file_error' => __( 'Aucun JSON valide n’a été fourni pour l’import.', 'a11y-widget' ),
    );
    $visual_color_fields    = array(
        'primary'          => array(
            'label'       => __( 'Couleur principale', 'a11y-widget' ),
            'description' => __( 'Utilisée pour le bouton, les actions principales et les accents du panneau.', 'a11y-widget' ),
        ),
        'primary_contrast' => array(
            'label'       => __( 'Texte sur couleur principale', 'a11y-widget' ),
            'description' => __( 'Couleur du texte ou des icônes affichés sur la couleur principale.', 'a11y-widget' ),
        ),
        'surface'          => array(
            'label'       => __( 'Surface du panneau', 'a11y-widget' ),
            'description' => __( 'Fond principal du panneau du widget.', 'a11y-widget' ),
        ),
        'surface_elev'     => array(
            'label'       => __( 'Surface secondaire', 'a11y-widget' ),
            'description' => __( 'Fond des en-têtes, pieds de panneau et zones légèrement distinguées.', 'a11y-widget' ),
        ),
        'text'             => array(
            'label'       => __( 'Texte principal', 'a11y-widget' ),
            'description' => __( 'Couleur principale des libellés dans le panneau.', 'a11y-widget' ),
        ),
        'text_subtle'      => array(
            'label'       => __( 'Texte secondaire', 'a11y-widget' ),
            'description' => __( 'Couleur des descriptions et informations moins prioritaires.', 'a11y-widget' ),
        ),
        'border'           => array(
            'label'       => __( 'Bordures', 'a11y-widget' ),
            'description' => __( 'Couleur des séparateurs, bordures et cartes internes.', 'a11y-widget' ),
        ),
    );
    $background_mode_choices = array(
        'modal'       => array(
            'label'       => __( 'Mode modal (site en arrière-plan figé)', 'a11y-widget' ),
            'description' => __( 'Masque l’arrière-plan, verrouille le défilement et piège le focus dans le module.', 'a11y-widget' ),
        ),
        'interactive' => array(
            'label'       => __( 'Mode interactif (site manipulable)', 'a11y-widget' ),
            'description' => __( 'Laisse la page active : pas de masque, pas de verrouillage du défilement et pas de piège du focus.', 'a11y-widget' ),
        ),
    );
    ?>
    <div class="wrap a11y-widget-admin">
        <h1><?php esc_html_e( 'Réglages du widget d’accessibilité', 'a11y-widget' ); ?></h1>
        <p class="a11y-widget-admin__intro">
            <?php esc_html_e( 'Toutes les fonctionnalités sont actives par défaut. Décochez celles que vous souhaitez masquer aux utilisateurs finaux.', 'a11y-widget' ); ?>
        </p>

        <?php if ( isset( $configuration_messages[ $configuration_message_key ] ) ) : ?>
            <div class="notice <?php echo 'imported' === $configuration_message_key ? 'notice-success' : 'notice-error'; ?> is-dismissible">
                <p><?php echo esc_html( $configuration_messages[ $configuration_message_key ] ); ?></p>
            </div>
        <?php endif; ?>

        <nav class="nav-tab-wrapper a11y-widget-admin-tabs" aria-label="<?php echo esc_attr__( 'Sections des réglages du widget', 'a11y-widget' ); ?>">
            <a class="nav-tab" href="#a11y-widget-settings-behavior"><?php esc_html_e( 'Comportement', 'a11y-widget' ); ?></a>
            <a class="nav-tab" href="#a11y-widget-settings-visual"><?php esc_html_e( 'Personnalisation', 'a11y-widget' ); ?></a>
            <a class="nav-tab" href="#a11y-widget-settings-features"><?php esc_html_e( 'Fonctionnalités', 'a11y-widget' ); ?></a>
            <a class="nav-tab" href="#a11y-widget-settings-reading"><?php esc_html_e( 'Lecture', 'a11y-widget' ); ?></a>
            <a class="nav-tab" href="#a11y-widget-settings-statement"><?php esc_html_e( 'Déclaration', 'a11y-widget' ); ?></a>
            <a class="nav-tab" href="#a11y-widget-settings-feedback"><?php esc_html_e( 'Retours', 'a11y-widget' ); ?></a>
            <a class="nav-tab" href="#a11y-widget-settings-config"><?php esc_html_e( 'Configuration', 'a11y-widget' ); ?></a>
        </nav>

        <form method="post" action="options.php">
            <?php settings_fields( 'a11y_widget_settings' ); ?>

            <fieldset id="a11y-widget-settings-behavior" class="a11y-widget-admin-card a11y-widget-admin-background">
                <legend><?php esc_html_e( 'Comportement du site en arrière-plan', 'a11y-widget' ); ?></legend>
                <p class="description">
                    <?php esc_html_e( 'Choisissez si l’ouverture du module doit figer le site ou le laisser accessible.', 'a11y-widget' ); ?>
                </p>
                <?php foreach ( $background_mode_choices as $mode_slug => $mode_data ) :
                    $mode_slug = sanitize_key( $mode_slug );
                    $input_id  = 'a11y-widget-background-mode-' . $mode_slug;
                    $label     = isset( $mode_data['label'] ) ? (string) $mode_data['label'] : '';
                    $help      = isset( $mode_data['description'] ) ? (string) $mode_data['description'] : '';
                    ?>
                    <div class="a11y-widget-admin-background__option">
                        <label for="<?php echo esc_attr( $input_id ); ?>">
                            <input
                                type="radio"
                                name="<?php echo esc_attr( $background_mode_option ); ?>"
                                id="<?php echo esc_attr( $input_id ); ?>"
                                value="<?php echo esc_attr( $mode_slug ); ?>"
                                <?php checked( $background_mode_value, $mode_slug ); ?>
                            />
                            <span class="a11y-widget-admin-background__label"><?php echo esc_html( $label ); ?></span>
                        </label>
                        <?php if ( '' !== trim( $help ) ) : ?>
                            <p class="description"><?php echo esc_html( $help ); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </fieldset>

            <fieldset class="a11y-widget-admin-card a11y-widget-admin-launcher">
                <?php $launcher_legend_id = 'a11y-widget-launcher-logo-legend'; ?>
                <legend id="<?php echo esc_attr( $launcher_legend_id ); ?>"><?php esc_html_e( 'Logo du bouton lanceur', 'a11y-widget' ); ?></legend>
                <p class="description">
                    <?php esc_html_e( 'Choisissez le logo affiché sur le bouton flottant et dans l’en-tête du module.', 'a11y-widget' ); ?>
                </p>
                <div class="a11y-widget-admin-launcher__field">
                    <label for="a11y-widget-launcher-logo-scale"><?php esc_html_e( 'Taille du logo', 'a11y-widget' ); ?></label>
                    <select
                        id="a11y-widget-launcher-logo-scale"
                        name="<?php echo esc_attr( $logo_scale_option_key ); ?>"
                    >
                        <?php foreach ( $logo_scale_choices as $scale_choice ) :
                            $precision     = floor( $scale_choice ) === (float) $scale_choice ? 0 : 1;
                            $display_value = number_format_i18n( $scale_choice, $precision );
                            ?>
                            <option value="<?php echo esc_attr( $scale_choice ); ?>" <?php selected( $logo_scale_value, $scale_choice ); ?>>
                                <?php echo esc_html( sprintf( __( '×%s', 'a11y-widget' ), $display_value ) ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ( empty( $logo_variants ) ) : ?>
                    <p class="description a11y-widget-admin-launcher__empty">
                        <?php esc_html_e( 'Aucune variante de logo n’est disponible pour le moment.', 'a11y-widget' ); ?>
                    </p>
                <?php else : ?>
                    <div
                        class="a11y-widget-admin-launcher__choices"
                        role="group"
                        aria-labelledby="<?php echo esc_attr( $launcher_legend_id ); ?>"
                        data-launcher-choice-group
                    >
                        <?php foreach ( $logo_variants as $logo_slug => $logo_data ) :
                            $input_id = 'a11y-widget-launcher-logo-' . $logo_slug;
                            $label    = isset( $logo_data['label'] ) ? $logo_data['label'] : '';
                            $svg      = isset( $logo_data['svg'] ) ? (string) $logo_data['svg'] : '';

                            if ( function_exists( 'a11y_widget_get_launcher_logo_image_markup' ) ) {
                                $preview_markup = a11y_widget_get_launcher_logo_image_markup( $logo_slug, 'admin' );
                            } else {
                                $preview_markup = $svg;

                                if ( '' !== $preview_markup && function_exists( 'a11y_widget_prepare_logo_svg_markup' ) ) {
                                    $preview_markup = a11y_widget_prepare_logo_svg_markup( $preview_markup, $logo_slug, 'admin' );
                                }
                            }
                            ?>
                            <div class="a11y-widget-admin-launcher__option">
                                <label class="a11y-widget-admin-launcher__label" for="<?php echo esc_attr( $input_id ); ?>">
                                    <input
                                        type="radio"
                                        name="<?php echo esc_attr( $logo_option_key ); ?>"
                                        id="<?php echo esc_attr( $input_id ); ?>"
                                        value="<?php echo esc_attr( $logo_slug ); ?>"
                                        <?php checked( $logo_slug === $selected_logo ); ?>
                                        data-launcher-choice
                                    />
                                    <span class="a11y-widget-admin-launcher__details">
                                        <span class="a11y-widget-admin-launcher__preview" aria-hidden="true" style="<?php echo esc_attr( $logo_preview_style ); ?>"><?php echo $preview_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                        <span class="a11y-widget-admin-launcher__name"><?php echo esc_html( $label ); ?></span>
                                    </span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </fieldset>

            <fieldset id="a11y-widget-settings-visual" class="a11y-widget-admin-card a11y-widget-admin-appearance">
                <legend><?php esc_html_e( 'Personnalisation visuelle', 'a11y-widget' ); ?></legend>
                <p class="description">
                    <?php esc_html_e( 'Personnalisez l’apparence du widget côté site. Le nom et le positionnement de l’application restent inchangés.', 'a11y-widget' ); ?>
                </p>

                <div class="a11y-widget-admin-appearance__presets" role="radiogroup" data-visual-theme-group aria-label="<?php echo esc_attr__( 'Thème visuel du widget', 'a11y-widget' ); ?>">
                    <?php foreach ( $visual_presets as $preset_slug => $preset_data ) :
                        $preset_slug = sanitize_key( $preset_slug );
                        $preset_id   = 'a11y-widget-visual-theme-' . $preset_slug;
                        $preset_label = isset( $preset_data['label'] ) ? (string) $preset_data['label'] : $preset_slug;
                        $preset_description = isset( $preset_data['description'] ) ? (string) $preset_data['description'] : '';
                        ?>
                        <?php
                        $preset_checked = ( isset( $visual_options['theme'] ) ? $visual_options['theme'] : 'mobls' ) === $preset_slug;
                        ?>
                        <label class="a11y-widget-admin-appearance__preset<?php echo $preset_checked ? ' a11y-widget-admin-appearance__preset--checked' : ''; ?>" for="<?php echo esc_attr( $preset_id ); ?>">
                            <input
                                type="radio"
                                id="<?php echo esc_attr( $preset_id ); ?>"
                                name="<?php echo esc_attr( $visual_option_key ); ?>[theme]"
                                value="<?php echo esc_attr( $preset_slug ); ?>"
                                <?php checked( $preset_checked ); ?>
                                data-visual-theme-choice
                            />
                            <span class="a11y-widget-admin-appearance__preset-body">
                                <span class="a11y-widget-admin-appearance__preset-name"><?php echo esc_html( $preset_label ); ?></span>
                                <?php if ( '' !== trim( $preset_description ) ) : ?>
                                    <span class="description"><?php echo esc_html( $preset_description ); ?></span>
                                <?php endif; ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="a11y-widget-admin-appearance__fields">
                    <?php foreach ( $visual_color_fields as $field_key => $field_data ) :
                        $field_key = sanitize_key( $field_key );
                        $field_id  = 'a11y-widget-visual-' . $field_key;
                        $value     = isset( $visual_options[ $field_key ] ) ? (string) $visual_options[ $field_key ] : '';
                        ?>
                        <div class="a11y-widget-admin-appearance__field">
                            <label for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $field_data['label'] ); ?></label>
                            <div class="a11y-widget-admin-appearance__color-control">
                                <input
                                    type="color"
                                    id="<?php echo esc_attr( $field_id ); ?>"
                                    name="<?php echo esc_attr( $visual_option_key ); ?>[<?php echo esc_attr( $field_key ); ?>]"
                                    value="<?php echo esc_attr( $value ); ?>"
                                />
                                <code><?php echo esc_html( $value ); ?></code>
                            </div>
                            <p class="description"><?php echo esc_html( $field_data['description'] ); ?></p>
                        </div>
                    <?php endforeach; ?>

                    <div class="a11y-widget-admin-appearance__field">
                        <label for="a11y-widget-visual-radius"><?php esc_html_e( 'Rayon des coins', 'a11y-widget' ); ?></label>
                        <input
                            type="number"
                            id="a11y-widget-visual-radius"
                            name="<?php echo esc_attr( $visual_option_key ); ?>[radius]"
                            value="<?php echo esc_attr( isset( $visual_options['radius'] ) ? absint( $visual_options['radius'] ) : 16 ); ?>"
                            min="0"
                            max="32"
                            step="1"
                        />
                        <p class="description"><?php esc_html_e( 'Valeur en pixels, limitée à 32 pour éviter des formes difficiles à lire.', 'a11y-widget' ); ?></p>
                    </div>
                </div>
            </fieldset>

            <fieldset id="a11y-widget-settings-features" class="a11y-widget-admin-card a11y-widget-admin-force-all">
                <legend class="screen-reader-text"><?php esc_html_e( 'Affichage des fonctionnalités', 'a11y-widget' ); ?></legend>
                <label for="a11y-widget-force-all">
                    <input type="hidden" name="<?php echo esc_attr( $force_all_option_key ); ?>" value="0" />
                    <input
                        type="checkbox"
                        id="a11y-widget-force-all"
                        name="<?php echo esc_attr( $force_all_option_key ); ?>"
                        value="1"
                        <?php checked( $force_all_features ); ?>
                    />
                    <?php esc_html_e( 'Afficher toutes les fonctionnalités du widget', 'a11y-widget' ); ?>
                </label>
                <p class="description">
                    <?php esc_html_e( 'Lorsque cette option est active, toutes les fonctionnalités sont affichées et la personnalisation ci-dessous est ignorée.', 'a11y-widget' ); ?>
                </p>
            </fieldset>

            <fieldset id="a11y-widget-settings-reading" class="a11y-widget-admin-card a11y-widget-admin-reading-guide">
                <legend><?php esc_html_e( 'Guide de lecture : niveaux de titres', 'a11y-widget' ); ?></legend>
                <p class="description">
                    <?php esc_html_e( 'Choisissez les niveaux de titres inclus dans le sommaire automatique du guide de lecture.', 'a11y-widget' ); ?>
                </p>
                <div class="a11y-widget-admin-reading-guide__choices">
                    <?php foreach ( $available_headings as $heading_level ) : ?>
                        <label>
                            <input
                                type="checkbox"
                                name="<?php echo esc_attr( $heading_option_key ); ?>[]"
                                value="<?php echo esc_attr( $heading_level ); ?>"
                                <?php checked( in_array( $heading_level, $heading_levels, true ) ); ?>
                            />
                            <?php echo esc_html( sprintf( __( 'Titres %s', 'a11y-widget' ), strtoupper( $heading_level ) ) ); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="a11y-widget-admin-reading-guide__field">
                    <label for="a11y-widget-reading-guide-syllable-selector"><?php esc_html_e( 'Zones à syllaber', 'a11y-widget' ); ?></label>
                    <input
                        type="text"
                        id="a11y-widget-reading-guide-syllable-selector"
                        class="regular-text"
                        name="<?php echo esc_attr( $syllable_option_key ); ?>"
                        value="<?php echo esc_attr( $syllable_selectors ); ?>"
                    />
                    <p class="description">
                        <?php esc_html_e( 'Indiquez les sélecteurs CSS, séparés par des virgules, pour définir les éléments concernés par la séparation syllabique.', 'a11y-widget' ); ?>
                    </p>
                </div>
            </fieldset>

            <fieldset id="a11y-widget-settings-statement" class="a11y-widget-admin-card a11y-widget-admin-statement">
                <legend><?php esc_html_e( 'Déclaration d’accessibilité', 'a11y-widget' ); ?></legend>
                <p class="description">
                    <?php esc_html_e( 'Gardez ici seulement ce qui doit rester visible dans le widget. Le suivi détaillé de conformité reste côté RGAA_Audit ou dans le sous-menu Audit et suivi.', 'a11y-widget' ); ?>
                </p>

                <label class="a11y-widget-admin-statement__toggle" for="a11y-widget-statement-enabled">
                    <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[enabled]" value="0" />
                    <input
                        type="checkbox"
                        id="a11y-widget-statement-enabled"
                        name="<?php echo esc_attr( $statement_option_key ); ?>[enabled]"
                        value="1"
                        <?php checked( ! empty( $statement_options['enabled'] ) ); ?>
                    />
                    <?php esc_html_e( 'Afficher la déclaration dans le widget', 'a11y-widget' ); ?>
                </label>

                <div class="a11y-widget-admin-statement__fields">
                    <div class="a11y-widget-admin-statement__field a11y-widget-admin-statement__field--wide">
                        <label for="a11y-widget-statement-declaration-url"><?php esc_html_e( 'URL de la déclaration publiée', 'a11y-widget' ); ?></label>
                        <input
                            type="url"
                            id="a11y-widget-statement-declaration-url"
                            class="regular-text"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[declaration_url]"
                            value="<?php echo esc_attr( isset( $statement_options['declaration_url'] ) ? $statement_options['declaration_url'] : '' ); ?>"
                            placeholder="<?php echo esc_attr( home_url( '/declaration-accessibilite/' ) ); ?>"
                        />
                        <p class="description"><?php esc_html_e( 'Lien public vers la page de déclaration d’accessibilité du site.', 'a11y-widget' ); ?></p>
                    </div>
                </div>

                <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[audit_url]" value="<?php echo esc_attr( isset( $statement_options['audit_url'] ) ? $statement_options['audit_url'] : '' ); ?>" />
                <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[audit_date]" value="<?php echo esc_attr( isset( $statement_options['audit_date'] ) ? $statement_options['audit_date'] : '' ); ?>" />
                <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[audit_status]" value="<?php echo esc_attr( isset( $statement_options['audit_status'] ) ? $statement_options['audit_status'] : 'not_assessed' ); ?>" />
                <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[compliance_rate]" value="<?php echo esc_attr( isset( $statement_options['compliance_rate'] ) ? $statement_options['compliance_rate'] : '' ); ?>" />
                <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[auditor]" value="<?php echo esc_attr( isset( $statement_options['auditor'] ) ? $statement_options['auditor'] : '' ); ?>" />
                <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[audit_scope]" value="<?php echo esc_attr( isset( $statement_options['audit_scope'] ) ? $statement_options['audit_scope'] : '' ); ?>" />
                <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[notes]" value="<?php echo esc_attr( isset( $statement_options['notes'] ) ? $statement_options['notes'] : '' ); ?>" />

                <p class="a11y-widget-admin-statement__tool">
                    <a href="<?php echo esc_url( $audit_admin_url ); ?>" class="button button-secondary">
                        <?php esc_html_e( 'Ouvrir Audit et suivi', 'a11y-widget' ); ?>
                    </a>
                    <span class="description"><?php esc_html_e( 'Les informations détaillées d’audit restent dans l’administration et ne chargent pas le menu du widget.', 'a11y-widget' ); ?></span>
                </p>
            </fieldset>

            <fieldset id="a11y-widget-settings-feedback" class="a11y-widget-admin-card a11y-widget-admin-feedback-settings">
                <legend><?php esc_html_e( 'Retours utilisateurs', 'a11y-widget' ); ?></legend>
                <p class="description">
                    <?php esc_html_e( 'Activez cette collecte uniquement si l’équipe du site traite réellement les retours reçus. Sinon, aucune carte de feedback n’est affichée dans le widget.', 'a11y-widget' ); ?>
                </p>

                <label class="a11y-widget-admin-statement__toggle" for="a11y-widget-feedback-collection-enabled">
                    <input type="hidden" name="<?php echo esc_attr( $feedback_collection_key ); ?>" value="0" />
                    <input
                        type="checkbox"
                        id="a11y-widget-feedback-collection-enabled"
                        name="<?php echo esc_attr( $feedback_collection_key ); ?>"
                        value="1"
                        <?php checked( $feedback_collection_enabled ); ?>
                    />
                    <?php esc_html_e( 'Collecter les retours dans WordPress', 'a11y-widget' ); ?>
                </label>

                <div class="a11y-widget-admin-feedback-settings__field">
                    <label for="a11y-widget-feedback-retention-days"><?php esc_html_e( 'Durée de conservation des retours', 'a11y-widget' ); ?></label>
                    <select
                        id="a11y-widget-feedback-retention-days"
                        name="<?php echo esc_attr( $feedback_retention_key ); ?>"
                    >
                        <?php foreach ( $feedback_retention_choices as $retention_value => $retention_label ) : ?>
                            <option value="<?php echo esc_attr( $retention_value ); ?>" <?php selected( $feedback_retention_value, $retention_value ); ?>>
                                <?php echo esc_html( $retention_label ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description">
                        <?php esc_html_e( 'Cette durée alimente la purge automatique quotidienne et le bouton de purge manuelle des retours expirés.', 'a11y-widget' ); ?>
                    </p>
                </div>

                <p class="a11y-widget-admin-statement__tool">
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=a11y-widget-feedback' ) ); ?>" class="button button-secondary">
                        <?php esc_html_e( 'Ouvrir les retours reçus', 'a11y-widget' ); ?>
                    </a>
                    <span class="description"><?php esc_html_e( 'Les retours sont stockés en privé dans l’administration. Le widget demande un consentement avant l’envoi.', 'a11y-widget' ); ?></span>
                </p>
            </fieldset>

            <p class="a11y-widget-admin__hint">
                <?php esc_html_e( 'Glissez-déposez les catégories et les fonctionnalités pour les réorganiser ou les déplacer.', 'a11y-widget' ); ?>
            </p>

            <?php if ( empty( $sections ) ) : ?>
                <p class="a11y-widget-admin-empty">
                    <?php esc_html_e( 'Aucune fonctionnalité n’est disponible pour le moment.', 'a11y-widget' ); ?>
                </p>
            <?php else : ?>
                <input
                    type="hidden"
                    class="a11y-widget-admin-section-order"
                    data-section-order-input="true"
                    name="<?php echo esc_attr( $section_order_option ); ?>"
                    value="<?php echo esc_attr( $section_order_value ); ?>"
                />
                <div class="a11y-widget-admin-grid">
                    <?php
                    foreach ( $sections as $section ) :
                        $section_title = isset( $section['title'] ) ? $section['title'] : '';
                        $section_slug  = isset( $section['slug'] ) ? sanitize_title( $section['slug'] ) : '';
                        $section_icon  = isset( $section['icon'] ) ? sanitize_key( $section['icon'] ) : '';
                        $icon_markup   = '';
                        $children      = isset( $section['children'] ) && is_array( $section['children'] ) ? $section['children'] : array();

                        if ( '' !== $section_icon && function_exists( 'a11y_widget_get_icon_markup' ) ) {
                            $icon_markup = a11y_widget_get_icon_markup(
                                $section_icon,
                                array(
                                    'class' => 'a11y-widget-admin-section__icon-svg',
                                )
                            );
                        }

                        if ( '' === $section_slug ) {
                            continue;
                        }
                        ?>
                        <?php
                        $section_classes = array(
                            'a11y-widget-admin-section',
                            'a11y-widget-admin-section--' . $section_slug,
                        );
                        ?>
                        <fieldset
                            class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>"
                            data-section="<?php echo esc_attr( $section_slug ); ?>"
                        >
                            <legend class="a11y-widget-admin-section__title">
                                <?php
                                $handle_label = sprintf(
                                    __( 'Réorganiser la catégorie %s', 'a11y-widget' ),
                                    '' !== $section_title ? wp_strip_all_tags( $section_title ) : $section_slug
                                );
                                ?>
                                <button
                                    type="button"
                                    class="a11y-widget-admin-section__handle"
                                    aria-label="<?php echo esc_attr( $handle_label ); ?>"
                                >
                                    <span aria-hidden="true" class="a11y-widget-admin-section__handle-icon">⋮⋮</span>
                                    <span class="screen-reader-text"><?php echo esc_html( $handle_label ); ?></span>
                                </button>
                                <?php if ( '' !== $icon_markup ) : ?>
                                    <span class="a11y-widget-admin-section__icon" aria-hidden="true"><?php echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                <?php endif; ?>
                                <span class="a11y-widget-admin-section__title-text"><?php echo esc_html( $section_title ); ?></span>
                            </legend>

                            <?php
                            $layout_input_id = 'a11y-widget-layout-' . $section_slug;
                            ?>
                            <input
                                type="hidden"
                                id="<?php echo esc_attr( $layout_input_id ); ?>"
                                class="a11y-widget-admin-layout"
                                name="<?php echo esc_attr( $layout_option_key ); ?>[<?php echo esc_attr( $section_slug ); ?>]"
                                value="<?php echo esc_attr( implode( ',', wp_list_pluck( $children, 'slug' ) ) ); ?>"
                            />

                            <div
                                class="a11y-widget-admin-section__content"
                                data-section="<?php echo esc_attr( $section_slug ); ?>"
                                data-layout-input="#<?php echo esc_attr( $layout_input_id ); ?>"
                            >
                                <p class="a11y-widget-admin-empty a11y-widget-admin-section__empty-message"<?php if ( ! empty( $children ) ) : ?> hidden<?php endif; ?>>
                                    <em><?php esc_html_e( 'Aucune fonctionnalité dans cette catégorie.', 'a11y-widget' ); ?></em>
                                </p>

                                <?php
                    foreach ( $children as $feature ) :
                        $feature_slug  = isset( $feature['slug'] ) ? sanitize_key( $feature['slug'] ) : '';
                        $feature_label = isset( $feature['label'] ) ? $feature['label'] : '';
                        $feature_hint  = isset( $feature['hint'] ) ? $feature['hint'] : '';
                        $feature_children = array();

                                    if ( isset( $feature['children'] ) && is_array( $feature['children'] ) ) {
                                        foreach ( $feature['children'] as $sub_feature ) {
                                            if ( empty( $sub_feature['slug'] ) || empty( $sub_feature['label'] ) ) {
                                                continue;
                                            }

                                            $sub_slug  = sanitize_key( $sub_feature['slug'] );
                                            $sub_label = wp_strip_all_tags( $sub_feature['label'] );

                                            if ( '' === $sub_slug || '' === $sub_label ) {
                                                continue;
                                            }

                                            $feature_children[] = array(
                                                'slug'       => $sub_slug,
                                                'label'      => $sub_label,
                                                'hint'       => isset( $sub_feature['hint'] ) ? wp_strip_all_tags( $sub_feature['hint'] ) : '',
                                                'aria_label' => isset( $sub_feature['aria_label'] ) ? wp_strip_all_tags( $sub_feature['aria_label'] ) : $sub_label,
                                            );
                                        }
                                    }

                                    if ( '' === $feature_slug || '' === $feature_label ) {
                                        continue;
                                    }

                                    if ( ! empty( $feature_children ) && isset( $stored_subfeature_layout[ $feature_slug ] ) && is_array( $stored_subfeature_layout[ $feature_slug ] ) ) {
                                        $ordered_children = array();
                                        $assigned         = array();

                                        foreach ( $stored_subfeature_layout[ $feature_slug ] as $stored_sub_slug ) {
                                            $stored_sub_slug = sanitize_key( $stored_sub_slug );

                                            if ( '' === $stored_sub_slug || isset( $assigned[ $stored_sub_slug ] ) ) {
                                                continue;
                                            }

                                            foreach ( $feature_children as $child ) {
                                                $child_slug = isset( $child['slug'] ) ? sanitize_key( $child['slug'] ) : '';

                                                if ( '' === $child_slug || $child_slug !== $stored_sub_slug || isset( $assigned[ $child_slug ] ) ) {
                                                    continue;
                                                }

                                                $ordered_children[]      = $child;
                                                $assigned[ $child_slug ] = true;
                                                break;
                                            }
                                        }

                                        if ( count( $ordered_children ) !== count( $feature_children ) ) {
                                            foreach ( $feature_children as $child ) {
                                                $child_slug = isset( $child['slug'] ) ? sanitize_key( $child['slug'] ) : '';

                                                if ( '' === $child_slug || isset( $assigned[ $child_slug ] ) ) {
                                                    continue;
                                                }

                                                $ordered_children[]      = $child;
                                                $assigned[ $child_slug ] = true;
                                            }
                                        }

                                        $feature_children = $ordered_children;
                                    }

                                    $is_disabled   = isset( $disabled_lookup[ $feature_slug ] );
                                    $input_id      = 'a11y-widget-toggle-' . ( $section_slug ? $section_slug . '-' : '' ) . $feature_slug;
                                    $group_label_id = 'a11y-widget-feature-label-' . $feature_slug;
                                    $has_children  = ! empty( $feature_children );
                                    ?>
                                    <div class="a11y-widget-admin-feature<?php echo $has_children ? ' a11y-widget-admin-feature--group' : ''; ?>" data-feature-slug="<?php echo esc_attr( $feature_slug ); ?>">
                                        <button type="button" class="a11y-widget-admin-feature__handle">
                                            <span class="screen-reader-text">
                                                <?php
                                                printf(
                                                    /* translators: %s: feature label */
                                                    esc_html__( 'Déplacer la fonctionnalité « %s »', 'a11y-widget' ),
                                                    wp_strip_all_tags( $feature_label )
                                                );
                                                ?>
                                            </span>
                                            <span class="dashicons dashicons-move" aria-hidden="true"></span>
                                        </button>

                                        <div class="a11y-widget-admin-feature__main">
                                            <div class="a11y-widget-admin-feature__description">
                                                <?php if ( $has_children ) : ?>
                                                    <span class="a11y-widget-admin-feature__label" id="<?php echo esc_attr( $group_label_id ); ?>"><?php echo esc_html( $feature_label ); ?></span>
                                                <?php else : ?>
                                                    <label for="<?php echo esc_attr( $input_id ); ?>">
                                                        <span class="a11y-widget-admin-feature__label"><?php echo esc_html( $feature_label ); ?></span>
                                                    </label>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( ! $has_children ) : ?>
                                                <div class="a11y-widget-admin-toggle">
                                                    <label class="a11y-widget-switch" for="<?php echo esc_attr( $input_id ); ?>">
                                                        <span class="screen-reader-text">
                                                            <?php
                                                            printf(
                                                                /* translators: %s: feature label */
                                                                esc_html__( 'Masquer la fonctionnalité « %s » pour les utilisateurs', 'a11y-widget' ),
                                                                wp_strip_all_tags( $feature_label )
                                                            );
                                                            ?>
                                                        </span>
                                                        <input
                                                            type="checkbox"
                                                            id="<?php echo esc_attr( $input_id ); ?>"
                                                            name="<?php echo esc_attr( a11y_widget_get_disabled_features_option_name() ); ?>[]"
                                                            value="<?php echo esc_attr( $feature_slug ); ?>"
                                                            <?php checked( $is_disabled ); ?>
                                                            data-a11y-feature-toggle="true"
                                                        />
                                                        <span class="a11y-widget-switch__ui">
                                                            <span
                                                                class="a11y-widget-switch__state"
                                                                data-state-visible="<?php echo esc_attr_x( 'Visible', 'feature state', 'a11y-widget' ); ?>"
                                                                data-state-hidden="<?php echo esc_attr_x( 'Masqué', 'feature state', 'a11y-widget' ); ?>"
                                                            ></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ( $has_children ) :
                                            $subfeatures_input_id = 'a11y-widget-subfeatures-' . $feature_slug;
                                            $subfeature_slugs     = implode( ',', wp_list_pluck( $feature_children, 'slug' ) );
                                            ?>
                                            <input
                                                type="hidden"
                                                id="<?php echo esc_attr( $subfeatures_input_id ); ?>"
                                                class="a11y-widget-admin-subfeatures-layout"
                                                name="<?php echo esc_attr( $subfeature_layout_key ); ?>[<?php echo esc_attr( $feature_slug ); ?>]"
                                                value="<?php echo esc_attr( $subfeature_slugs ); ?>"
                                            />
                                            <div
                                                class="a11y-widget-admin-subfeatures"
                                                role="group"
                                                aria-labelledby="<?php echo esc_attr( $group_label_id ); ?>"
                                                data-subfeatures-input="#<?php echo esc_attr( $subfeatures_input_id ); ?>"
                                            >
                                                <?php foreach ( $feature_children as $sub_feature ) :
                                                    $sub_slug         = $sub_feature['slug'];
                                                    $sub_label        = $sub_feature['label'];
                                                    $sub_hint         = $sub_feature['hint'];
                                                    $sub_aria_label   = $sub_feature['aria_label'];
                                                    $sub_is_disabled  = isset( $disabled_lookup[ $sub_slug ] );
                                                    $sub_input_id     = 'a11y-widget-toggle-' . $sub_slug;
                                                    $sub_label_id     = $sub_input_id . '-label';
                                                    $sub_can_reorder  = ( 'vision-daltonisme' !== $feature_slug );
                                                    $sub_classes      = array( 'a11y-widget-admin-subfeature' );

                                                    if ( ! $sub_can_reorder ) {
                                                        $sub_classes[] = 'a11y-widget-admin-subfeature--static';
                                                    }

                                                    $sub_handle_label = sprintf(
                                                        /* translators: %s: sub-feature label */
                                                        esc_html__( 'Déplacer la sous-fonctionnalité « %s »', 'a11y-widget' ),
                                                        wp_strip_all_tags( $sub_label )
                                                    );
                                                    ?>
                                                    <div class="<?php echo esc_attr( implode( ' ', $sub_classes ) ); ?>" data-subfeature-slug="<?php echo esc_attr( $sub_slug ); ?>">
                                                        <?php if ( $sub_can_reorder ) : ?>
                                                            <button type="button" class="a11y-widget-admin-subfeature__handle" aria-label="<?php echo esc_attr( $sub_handle_label ); ?>">
                                                                <span class="dashicons dashicons-move" aria-hidden="true"></span>
                                                                <span class="screen-reader-text"><?php echo esc_html( $sub_handle_label ); ?></span>
                                                            </button>
                                                        <?php endif; ?>
                                                        <div class="a11y-widget-admin-subfeature__description">
                                                            <label for="<?php echo esc_attr( $sub_input_id ); ?>" id="<?php echo esc_attr( $sub_label_id ); ?>">
                                                                <span class="a11y-widget-admin-subfeature__label"><?php echo esc_html( $sub_label ); ?></span>
                                                            </label>
                                                        </div>
                                                        <div class="a11y-widget-admin-toggle">
                                                            <label class="a11y-widget-switch" for="<?php echo esc_attr( $sub_input_id ); ?>">
                                                                <span class="screen-reader-text">
                                                                    <?php
                                                                    printf(
                                                                        /* translators: %s: feature label */
                                                                        esc_html__( 'Masquer la fonctionnalité « %s » pour les utilisateurs', 'a11y-widget' ),
                                                                        wp_strip_all_tags( $sub_label )
                                                                    );
                                                                    ?>
                                                                </span>
                                                                <input
                                                                    type="checkbox"
                                                                    id="<?php echo esc_attr( $sub_input_id ); ?>"
                                                                    name="<?php echo esc_attr( a11y_widget_get_disabled_features_option_name() ); ?>[]"
                                                                    value="<?php echo esc_attr( $sub_slug ); ?>"
                                                                    <?php checked( $sub_is_disabled ); ?>
                                                                    aria-label="<?php echo esc_attr( $sub_aria_label ); ?>"
                                                                    data-a11y-feature-toggle="true"
                                                                />
                                                                <span class="a11y-widget-switch__ui">
                                                                    <span
                                                                        class="a11y-widget-switch__state"
                                                                        data-state-visible="<?php echo esc_attr_x( 'Visible', 'feature state', 'a11y-widget' ); ?>"
                                                                        data-state-hidden="<?php echo esc_attr_x( 'Masqué', 'feature state', 'a11y-widget' ); ?>"
                                                                    ></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php submit_button( __( 'Enregistrer les modifications', 'a11y-widget' ) ); ?>
        </form>

        <section id="a11y-widget-settings-config" class="a11y-widget-admin-card a11y-widget-admin-config-transfer" aria-labelledby="a11y-widget-settings-config-title">
            <div class="a11y-widget-admin-config-transfer__header">
                <h2 id="a11y-widget-settings-config-title"><?php esc_html_e( 'Importer / exporter la configuration', 'a11y-widget' ); ?></h2>
                <p class="description">
                    <?php esc_html_e( 'Transférez la configuration du widget entre environnements sans exporter les retours utilisateurs, les notes internes ni les données détaillées de RGAA_Audit.', 'a11y-widget' ); ?>
                </p>
            </div>

            <div class="a11y-widget-admin-config-transfer__grid">
                <div class="a11y-widget-admin-config-transfer__panel">
                    <h3><?php esc_html_e( 'Export JSON', 'a11y-widget' ); ?></h3>
                    <p class="description">
                        <?php esc_html_e( 'Le fichier contient les réglages du panneau, de l’apparence, de la déclaration synthétique, du feedback et de l’ordre des fonctionnalités.', 'a11y-widget' ); ?>
                    </p>
                    <a class="button button-secondary" href="<?php echo esc_url( $configuration_export_url ); ?>">
                        <?php esc_html_e( 'Exporter la configuration', 'a11y-widget' ); ?>
                    </a>
                </div>

                <form class="a11y-widget-admin-config-transfer__panel" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <h3><?php esc_html_e( 'Import JSON', 'a11y-widget' ); ?></h3>
                    <p class="description">
                        <?php esc_html_e( 'Collez le contenu d’un export JSON généré par ce widget. Les notes internes d’audit existantes sont conservées.', 'a11y-widget' ); ?>
                    </p>
                    <input type="hidden" name="action" value="a11y_widget_import_configuration" />
                    <?php wp_nonce_field( 'a11y_widget_import_configuration' ); ?>
                    <label for="a11y-widget-config-json"><?php esc_html_e( 'JSON de configuration', 'a11y-widget' ); ?></label>
                    <textarea
                        id="a11y-widget-config-json"
                        name="a11y_widget_config_json"
                        rows="8"
                        spellcheck="false"
                        required
                    ></textarea>
                    <p class="description">
                        <?php esc_html_e( 'L’import remplace uniquement les options reconnues par le schéma courant. Les retours reçus ne sont jamais supprimés ni importés.', 'a11y-widget' ); ?>
                    </p>
                    <button type="submit" class="button button-primary">
                        <?php esc_html_e( 'Importer cette configuration', 'a11y-widget' ); ?>
                    </button>
                </form>
            </div>
        </section>
    </div>
    <?php
}

/**
 * Render the guided setup assistant page.
 */
function a11y_widget_render_setup_assistant_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $background_mode           = a11y_widget_get_background_mode();
    $background_mode_choices   = array(
        'modal'       => array(
            'label'       => __( 'Mode modal', 'a11y-widget' ),
            'description' => __( 'Le panneau prend le focus, le site reste en arrière-plan. Recommandé pour limiter les conflits clavier.', 'a11y-widget' ),
        ),
        'interactive' => array(
            'label'       => __( 'Mode interactif', 'a11y-widget' ),
            'description' => __( 'Le site reste manipulable pendant que le panneau est ouvert. À réserver aux sites déjà bien testés au clavier.', 'a11y-widget' ),
        ),
    );
    $scope_choices             = a11y_widget_get_setup_assistant_feature_scope_choices();
    $current_scope             = a11y_widget_get_current_setup_assistant_scope();
    $statement_options         = a11y_widget_get_accessibility_statement_options();
    $statement_enabled         = ! empty( $statement_options['enabled'] );
    $statement_url             = isset( $statement_options['declaration_url'] ) ? (string) $statement_options['declaration_url'] : '';
    $feedback_enabled          = a11y_widget_feedback_collection_enabled();
    $feedback_retention        = a11y_widget_get_feedback_retention_days();
    $feedback_retention_choices = a11y_widget_get_feedback_retention_choices();
    $rgaa_integration          = a11y_widget_get_rgaa_audit_integration_status();
    $rgaa_detected             = ! empty( $rgaa_integration['detected'] );
    $sections                  = a11y_widget_get_sections();
    $feature_slugs             = a11y_widget_collect_health_feature_slugs( $sections );
    $total_features            = count( $feature_slugs );
    $disabled_features         = a11y_widget_get_disabled_features();
    $disabled_lookup           = array_fill_keys( $disabled_features, true );
    $known_disabled_count      = 0;
    $force_all_features        = a11y_widget_force_all_features_enabled();

    foreach ( $feature_slugs as $feature_slug ) {
        if ( isset( $disabled_lookup[ $feature_slug ] ) ) {
            ++$known_disabled_count;
        }
    }

    $visible_features = $force_all_features ? $total_features : max( 0, $total_features - $known_disabled_count );
    $message_key      = isset( $_GET['a11y_setup_message'] ) ? sanitize_key( (string) wp_unslash( $_GET['a11y_setup_message'] ) ) : '';
    $messages         = array(
        'saved' => __( 'Configuration guidée enregistrée.', 'a11y-widget' ),
    );
    ?>
    <div class="wrap a11y-widget-admin a11y-widget-admin-setup">
        <h1><?php esc_html_e( 'Assistant de configuration', 'a11y-widget' ); ?></h1>
        <p class="a11y-widget-admin__intro">
            <?php esc_html_e( 'Configurez les choix structurants du widget sans parcourir tous les réglages. Les données détaillées RGAA_Audit restent dans l’application compagnon.', 'a11y-widget' ); ?>
        </p>

        <?php if ( isset( $messages[ $message_key ] ) ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html( $messages[ $message_key ] ); ?></p>
            </div>
        <?php endif; ?>

        <section class="a11y-widget-admin-card a11y-widget-admin-setup-overview" aria-labelledby="a11y-widget-setup-overview-title">
            <div class="a11y-widget-admin-setup-overview__header">
                <h2 id="a11y-widget-setup-overview-title"><?php esc_html_e( 'État actuel', 'a11y-widget' ); ?></h2>
                <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=a11y-widget' ) ); ?>">
                    <?php esc_html_e( 'Ouvrir les réglages complets', 'a11y-widget' ); ?>
                </a>
            </div>
            <dl class="a11y-widget-admin-setup-metrics">
                <div>
                    <dt><?php esc_html_e( 'Mode', 'a11y-widget' ); ?></dt>
                    <dd><?php echo 'interactive' === $background_mode ? esc_html__( 'Interactif', 'a11y-widget' ) : esc_html__( 'Modal', 'a11y-widget' ); ?></dd>
                </div>
                <div>
                    <dt><?php esc_html_e( 'Fonctionnalités', 'a11y-widget' ); ?></dt>
                    <dd>
                        <?php
                        printf(
                            /* translators: 1: visible feature count, 2: total feature count */
                            esc_html__( '%1$d visibles sur %2$d', 'a11y-widget' ),
                            $visible_features,
                            $total_features
                        );
                        ?>
                    </dd>
                </div>
                <div>
                    <dt><?php esc_html_e( 'Retours', 'a11y-widget' ); ?></dt>
                    <dd><?php echo $feedback_enabled ? esc_html__( 'Activés', 'a11y-widget' ) : esc_html__( 'Désactivés', 'a11y-widget' ); ?></dd>
                </div>
                <div>
                    <dt><?php esc_html_e( 'RGAA_Audit', 'a11y-widget' ); ?></dt>
                    <dd><?php echo $rgaa_detected ? esc_html__( 'Détecté', 'a11y-widget' ) : esc_html__( 'Non détecté', 'a11y-widget' ); ?></dd>
                </div>
            </dl>
        </section>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="a11y-widget-admin-setup-form">
            <input type="hidden" name="action" value="a11y_widget_save_setup_assistant" />
            <?php wp_nonce_field( 'a11y_widget_setup_assistant' ); ?>

            <ol class="a11y-widget-admin-setup-steps">
                <li class="a11y-widget-admin-card a11y-widget-admin-setup-step">
                    <span class="a11y-widget-admin-setup-step__number" aria-hidden="true">1</span>
                    <fieldset>
                        <legend><?php esc_html_e( 'Comportement du panneau', 'a11y-widget' ); ?></legend>
                        <p class="description"><?php esc_html_e( 'Le mode modal est le choix le plus prévisible pour le clavier et les technologies d’assistance.', 'a11y-widget' ); ?></p>
                        <div class="a11y-widget-admin-setup-choice-grid">
                            <?php foreach ( $background_mode_choices as $mode_slug => $mode_choice ) :
                                $mode_slug = sanitize_key( $mode_slug );
                                $mode_id   = 'a11y-widget-setup-background-' . $mode_slug;
                                ?>
                                <label class="a11y-widget-admin-setup-choice" for="<?php echo esc_attr( $mode_id ); ?>">
                                    <input
                                        type="radio"
                                        id="<?php echo esc_attr( $mode_id ); ?>"
                                        name="a11y_widget_setup_background_mode"
                                        value="<?php echo esc_attr( $mode_slug ); ?>"
                                        <?php checked( $background_mode, $mode_slug ); ?>
                                    />
                                    <span>
                                        <strong><?php echo esc_html( $mode_choice['label'] ); ?></strong>
                                        <span class="description"><?php echo esc_html( $mode_choice['description'] ); ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </li>

                <li class="a11y-widget-admin-card a11y-widget-admin-setup-step">
                    <span class="a11y-widget-admin-setup-step__number" aria-hidden="true">2</span>
                    <fieldset>
                        <legend><?php esc_html_e( 'Fonctionnalités visibles', 'a11y-widget' ); ?></legend>
                        <p class="description"><?php esc_html_e( 'Choisissez un périmètre de départ. Les réglages complets restent disponibles ensuite pour ajuster fonctionnalité par fonctionnalité.', 'a11y-widget' ); ?></p>
                        <div class="a11y-widget-admin-setup-choice-grid">
                            <?php foreach ( $scope_choices as $scope_slug => $scope_choice ) :
                                $scope_slug = sanitize_key( $scope_slug );
                                $scope_id   = 'a11y-widget-setup-scope-' . $scope_slug;
                                $visible_count = '';

                                if ( empty( $scope_choice['preserve_features'] ) && empty( $scope_choice['force_all'] ) ) {
                                    $visible_count = sprintf(
                                        /* translators: %d: visible feature count */
                                        _n( '%d fonctionnalité visible', '%d fonctionnalités visibles', count( $scope_choice['visible_slugs'] ), 'a11y-widget' ),
                                        count( $scope_choice['visible_slugs'] )
                                    );
                                } elseif ( ! empty( $scope_choice['force_all'] ) ) {
                                    $visible_count = __( 'Toutes les fonctionnalités', 'a11y-widget' );
                                } else {
                                    $visible_count = __( 'Aucun changement', 'a11y-widget' );
                                }
                                ?>
                                <label class="a11y-widget-admin-setup-choice" for="<?php echo esc_attr( $scope_id ); ?>">
                                    <input
                                        type="radio"
                                        id="<?php echo esc_attr( $scope_id ); ?>"
                                        name="a11y_widget_setup_feature_scope"
                                        value="<?php echo esc_attr( $scope_slug ); ?>"
                                        <?php checked( $current_scope, $scope_slug ); ?>
                                    />
                                    <span>
                                        <strong><?php echo esc_html( $scope_choice['label'] ); ?></strong>
                                        <span class="a11y-widget-admin-setup-choice__meta"><?php echo esc_html( $visible_count ); ?></span>
                                        <span class="description"><?php echo esc_html( $scope_choice['description'] ); ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </li>

                <li class="a11y-widget-admin-card a11y-widget-admin-setup-step">
                    <span class="a11y-widget-admin-setup-step__number" aria-hidden="true">3</span>
                    <fieldset>
                        <legend><?php esc_html_e( 'Déclaration et retours', 'a11y-widget' ); ?></legend>
                        <p class="description"><?php esc_html_e( 'Gardez la déclaration publique et les retours utilisateurs dans le widget. Le suivi détaillé de conformité reste côté administration ou RGAA_Audit.', 'a11y-widget' ); ?></p>

                        <div class="a11y-widget-admin-setup-two-columns">
                            <div class="a11y-widget-admin-setup-field-group">
                                <label class="a11y-widget-admin-statement__toggle" for="a11y-widget-setup-statement-enabled">
                                    <input
                                        type="checkbox"
                                        id="a11y-widget-setup-statement-enabled"
                                        name="a11y_widget_setup_statement_enabled"
                                        value="1"
                                        <?php checked( $statement_enabled ); ?>
                                    />
                                    <?php esc_html_e( 'Afficher la déclaration publiée', 'a11y-widget' ); ?>
                                </label>
                                <label for="a11y-widget-setup-declaration-url"><?php esc_html_e( 'URL de la déclaration', 'a11y-widget' ); ?></label>
                                <input
                                    type="url"
                                    id="a11y-widget-setup-declaration-url"
                                    name="a11y_widget_setup_declaration_url"
                                    value="<?php echo esc_attr( $statement_url ); ?>"
                                    placeholder="<?php echo esc_attr( home_url( '/declaration-accessibilite/' ) ); ?>"
                                />
                                <p class="description"><?php esc_html_e( 'L’assistant ne modifie pas les notes internes, preuves, critères ou anomalies d’audit.', 'a11y-widget' ); ?></p>
                            </div>

                            <div class="a11y-widget-admin-setup-field-group">
                                <label class="a11y-widget-admin-statement__toggle" for="a11y-widget-setup-feedback-enabled">
                                    <input
                                        type="checkbox"
                                        id="a11y-widget-setup-feedback-enabled"
                                        name="a11y_widget_setup_feedback_enabled"
                                        value="1"
                                        <?php checked( $feedback_enabled ); ?>
                                    />
                                    <?php esc_html_e( 'Collecter les retours dans WordPress', 'a11y-widget' ); ?>
                                </label>
                                <label for="a11y-widget-setup-feedback-retention"><?php esc_html_e( 'Conservation des retours', 'a11y-widget' ); ?></label>
                                <select id="a11y-widget-setup-feedback-retention" name="a11y_widget_setup_feedback_retention">
                                    <?php foreach ( $feedback_retention_choices as $retention_value => $retention_label ) : ?>
                                        <option value="<?php echo esc_attr( $retention_value ); ?>" <?php selected( $feedback_retention, $retention_value ); ?>>
                                            <?php echo esc_html( $retention_label ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description"><?php esc_html_e( 'Les retours restent privés dans WordPress et nécessitent le consentement de l’utilisateur avant envoi.', 'a11y-widget' ); ?></p>
                            </div>
                        </div>
                    </fieldset>
                </li>

                <li class="a11y-widget-admin-card a11y-widget-admin-setup-step">
                    <span class="a11y-widget-admin-setup-step__number" aria-hidden="true">4</span>
                    <section aria-labelledby="a11y-widget-setup-summary-title">
                        <h2 id="a11y-widget-setup-summary-title"><?php esc_html_e( 'Récapitulatif avant enregistrement', 'a11y-widget' ); ?></h2>
                        <ul class="a11y-widget-admin-setup-summary">
                            <li><?php esc_html_e( 'Le mode d’ouverture, le périmètre visible, la déclaration publique et les retours peuvent être mis à jour.', 'a11y-widget' ); ?></li>
                            <li><?php esc_html_e( 'Les notes internes d’audit existantes sont conservées.', 'a11y-widget' ); ?></li>
                            <li><?php esc_html_e( 'RGAA_Audit est seulement signalé ici. Aucun critère, preuve, anomalie ou rapport n’est lu par cet assistant.', 'a11y-widget' ); ?></li>
                        </ul>

                        <div class="a11y-widget-admin-setup-rgaa">
                            <strong><?php esc_html_e( 'Liaison RGAA_Audit', 'a11y-widget' ); ?></strong>
                            <span><?php echo $rgaa_detected ? esc_html__( 'Disponible en lecture de statut', 'a11y-widget' ) : esc_html__( 'Non détectée, le widget reste autonome', 'a11y-widget' ); ?></span>
                            <?php if ( ! empty( $rgaa_integration['admin_url'] ) ) : ?>
                                <a class="button button-secondary" href="<?php echo esc_url( (string) $rgaa_integration['admin_url'] ); ?>">
                                    <?php esc_html_e( 'Ouvrir RGAA_Audit', 'a11y-widget' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <p class="submit">
                            <button type="submit" class="button button-primary"><?php esc_html_e( 'Enregistrer cette configuration', 'a11y-widget' ); ?></button>
                            <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=a11y-widget' ) ); ?>">
                                <?php esc_html_e( 'Annuler', 'a11y-widget' ); ?>
                            </a>
                        </p>
                    </section>
                </li>
            </ol>
        </form>
    </div>
    <?php
}

/**
 * Render the comfort profile preset configuration page.
 */
function a11y_widget_render_profile_presets_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $option_name           = a11y_widget_get_profile_presets_option_name();
    $profiles              = a11y_widget_get_profile_presets();
    $feature_choices       = a11y_widget_get_profile_feature_choices();
    $enabled_profile_count = 0;

    foreach ( $profiles as $profile ) {
        if ( ! empty( $profile['enabled'] ) ) {
            ++$enabled_profile_count;
        }
    }
    ?>
    <div class="wrap a11y-widget-admin a11y-widget-admin-profiles">
        <h1><?php esc_html_e( 'Profils de confort', 'a11y-widget' ); ?></h1>
        <p class="a11y-widget-admin__intro">
            <?php esc_html_e( 'Ajustez les profils rapides affichés dans le widget. Ils restent des raccourcis de confort et ne remplacent ni un choix utilisateur ni RGAA_Audit.', 'a11y-widget' ); ?>
        </p>

        <?php settings_errors(); ?>

        <section class="a11y-widget-admin-card a11y-widget-admin-profile-overview" aria-labelledby="a11y-widget-profiles-overview-title">
            <div class="a11y-widget-admin-profile-overview__body">
                <h2 id="a11y-widget-profiles-overview-title"><?php esc_html_e( 'Publication côté widget', 'a11y-widget' ); ?></h2>
                <p class="description">
                    <?php esc_html_e( 'Un profil désactivé disparaît du panneau public et ne peut plus être envoyé comme profil actif dans un retour utilisateur.', 'a11y-widget' ); ?>
                </p>
            </div>
            <dl class="a11y-widget-admin-profile-metrics">
                <div>
                    <dt><?php esc_html_e( 'Profils actifs', 'a11y-widget' ); ?></dt>
                    <dd>
                        <?php
                        printf(
                            /* translators: 1: active profile count, 2: total profile count */
                            esc_html__( '%1$d sur %2$d', 'a11y-widget' ),
                            $enabled_profile_count,
                            count( $profiles )
                        );
                        ?>
                    </dd>
                </div>
                <div>
                    <dt><?php esc_html_e( 'Réglages disponibles', 'a11y-widget' ); ?></dt>
                    <dd><?php echo esc_html( number_format_i18n( count( $feature_choices ) ) ); ?></dd>
                </div>
            </dl>
            <div class="a11y-widget-admin-profile-overview__actions">
                <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=a11y-widget-setup' ) ); ?>">
                    <?php esc_html_e( 'Ouvrir l’assistant', 'a11y-widget' ); ?>
                </a>
                <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=a11y-widget' ) ); ?>">
                    <?php esc_html_e( 'Réglages complets', 'a11y-widget' ); ?>
                </a>
            </div>
        </section>

        <form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" class="a11y-widget-admin-profile-form">
            <?php settings_fields( 'a11y_widget_profile_settings' ); ?>

            <div class="a11y-widget-admin-profile-grid">
                <?php foreach ( $profiles as $profile_key => $profile ) :
                    $profile_key      = sanitize_key( (string) $profile_key );
                    $profile_enabled  = ! empty( $profile['enabled'] );
                    $profile_label    = isset( $profile['label'] ) ? (string) $profile['label'] : '';
                    $profile_hint     = isset( $profile['hint'] ) ? (string) $profile['hint'] : '';
                    $profile_features = isset( $profile['features'] ) && is_array( $profile['features'] )
                        ? a11y_widget_normalize_feature_slugs( $profile['features'] )
                        : array();
                    $profile_feature_lookup = array_fill_keys( $profile_features, true );
                    $profile_field_id       = 'a11y-widget-profile-' . $profile_key;
                    $selected_feature_labels = array();

                    foreach ( $profile_features as $feature_slug ) {
                        if ( isset( $feature_choices[ $feature_slug ]['label'] ) ) {
                            $selected_feature_labels[] = $feature_choices[ $feature_slug ]['label'];
                        }
                    }
                    ?>
                    <fieldset
                        class="a11y-widget-admin-profile-preset<?php echo $profile_enabled ? '' : ' is-disabled'; ?>"
                        data-profile-config-card
                    >
                        <legend><?php echo esc_html( $profile_label ); ?></legend>
                        <div class="a11y-widget-admin-profile-preset__header">
                            <label class="a11y-widget-admin-profile-toggle" for="<?php echo esc_attr( $profile_field_id ); ?>-enabled">
                                <input
                                    type="hidden"
                                    name="<?php echo esc_attr( $option_name . '[' . $profile_key . '][enabled]' ); ?>"
                                    value="0"
                                />
                                <input
                                    type="checkbox"
                                    id="<?php echo esc_attr( $profile_field_id ); ?>-enabled"
                                    name="<?php echo esc_attr( $option_name . '[' . $profile_key . '][enabled]' ); ?>"
                                    value="1"
                                    data-profile-enabled
                                    <?php checked( $profile_enabled ); ?>
                                />
                                <span><?php esc_html_e( 'Afficher ce profil', 'a11y-widget' ); ?></span>
                            </label>
                            <p class="a11y-widget-admin-profile-summary" data-profile-summary>
                                <?php echo esc_html( implode( ', ', $selected_feature_labels ) ); ?>
                            </p>
                        </div>

                        <div class="a11y-widget-admin-profile-fields">
                            <div class="a11y-widget-admin-profile-field">
                                <label for="<?php echo esc_attr( $profile_field_id ); ?>-label"><?php esc_html_e( 'Libellé', 'a11y-widget' ); ?></label>
                                <input
                                    type="text"
                                    id="<?php echo esc_attr( $profile_field_id ); ?>-label"
                                    name="<?php echo esc_attr( $option_name . '[' . $profile_key . '][label]' ); ?>"
                                    value="<?php echo esc_attr( $profile_label ); ?>"
                                />
                            </div>

                            <div class="a11y-widget-admin-profile-field">
                                <label for="<?php echo esc_attr( $profile_field_id ); ?>-hint"><?php esc_html_e( 'Aide courte', 'a11y-widget' ); ?></label>
                                <textarea
                                    id="<?php echo esc_attr( $profile_field_id ); ?>-hint"
                                    name="<?php echo esc_attr( $option_name . '[' . $profile_key . '][hint]' ); ?>"
                                    rows="3"
                                ><?php echo esc_textarea( $profile_hint ); ?></textarea>
                            </div>
                        </div>

                        <div class="a11y-widget-admin-profile-feature-block">
                            <span class="a11y-widget-admin-profile-feature-block__label"><?php esc_html_e( 'Réglages appliqués', 'a11y-widget' ); ?></span>
                            <div class="a11y-widget-admin-profile-feature-grid">
                                <?php foreach ( $feature_choices as $feature_slug => $feature_choice ) :
                                    $feature_slug  = sanitize_key( (string) $feature_slug );
                                    $feature_label = isset( $feature_choice['label'] ) ? (string) $feature_choice['label'] : $feature_slug;
                                    $feature_group = isset( $feature_choice['group'] ) ? (string) $feature_choice['group'] : '';
                                    $feature_id    = $profile_field_id . '-feature-' . $feature_slug;
                                    ?>
                                    <label class="a11y-widget-admin-profile-feature" for="<?php echo esc_attr( $feature_id ); ?>">
                                        <input
                                            type="checkbox"
                                            id="<?php echo esc_attr( $feature_id ); ?>"
                                            name="<?php echo esc_attr( $option_name . '[' . $profile_key . '][features][]' ); ?>"
                                            value="<?php echo esc_attr( $feature_slug ); ?>"
                                            data-profile-feature-choice
                                            data-profile-feature-label="<?php echo esc_attr( $feature_label ); ?>"
                                            <?php checked( isset( $profile_feature_lookup[ $feature_slug ] ) ); ?>
                                        />
                                        <span>
                                            <strong><?php echo esc_html( $feature_label ); ?></strong>
                                            <?php if ( '' !== $feature_group ) : ?>
                                                <small><?php echo esc_html( $feature_group ); ?></small>
                                            <?php endif; ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <p class="description" data-profile-summary-empty hidden>
                                <?php esc_html_e( 'Si aucun réglage n’est coché, les réglages par défaut du profil seront restaurés à l’enregistrement.', 'a11y-widget' ); ?>
                            </p>
                        </div>
                    </fieldset>
                <?php endforeach; ?>
            </div>

            <?php submit_button( __( 'Enregistrer les profils', 'a11y-widget' ) ); ?>
        </form>
    </div>
    <?php
}

/**
 * Render the local widget health page.
 */
function a11y_widget_render_health_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $report       = a11y_widget_get_widget_health_report();
    $status       = isset( $report['status'] ) ? sanitize_html_class( (string) $report['status'] ) : 'ok';
    $status_label = isset( $report['status_label'] ) ? (string) $report['status_label'] : a11y_widget_get_health_level_label( $status );
    $summary      = isset( $report['summary'] ) ? (string) $report['summary'] : '';
    $metrics      = isset( $report['metrics'] ) && is_array( $report['metrics'] ) ? $report['metrics'] : array();
    $checks       = isset( $report['checks'] ) && is_array( $report['checks'] ) ? $report['checks'] : array();
    $actions      = array();

    foreach ( $checks as $check ) {
        $check_level = isset( $check['level'] ) ? sanitize_key( (string) $check['level'] ) : 'ok';

        if ( a11y_widget_get_health_level_weight( $check_level ) < 2 ) {
            continue;
        }

        $action_url   = isset( $check['action_url'] ) ? trim( (string) $check['action_url'] ) : '';
        $action_label = isset( $check['action_label'] ) ? trim( (string) $check['action_label'] ) : '';

        if ( '' === $action_url || '' === $action_label ) {
            continue;
        }

        $actions[] = array(
            'level' => $check_level,
            'title' => isset( $check['title'] ) ? (string) $check['title'] : '',
            'url'   => $action_url,
            'label' => $action_label,
        );
    }
    ?>
    <div class="wrap a11y-widget-admin a11y-widget-admin-health">
        <h1><?php esc_html_e( 'Santé du widget', 'a11y-widget' ); ?></h1>
        <p class="a11y-widget-admin__intro">
            <?php esc_html_e( 'Diagnostic local de la configuration du widget. Aucun appel externe n’est effectué et les critères, preuves ou anomalies RGAA_Audit ne sont pas lus ici.', 'a11y-widget' ); ?>
        </p>

        <section class="a11y-widget-admin-card a11y-widget-admin-health-summary" aria-labelledby="a11y-widget-health-summary-title">
            <div class="a11y-widget-admin-health-summary__header">
                <span class="a11y-widget-admin-health-badge a11y-widget-admin-health-badge--<?php echo esc_attr( $status ); ?>">
                    <?php echo esc_html( $status_label ); ?>
                </span>
                <div>
                    <h2 id="a11y-widget-health-summary-title"><?php esc_html_e( 'Diagnostic local', 'a11y-widget' ); ?></h2>
                    <p class="description"><?php echo esc_html( $summary ); ?></p>
                </div>
            </div>

            <dl class="a11y-widget-admin-health-metrics">
                <?php foreach ( $metrics as $metric ) : ?>
                    <div>
                        <dt><?php echo esc_html( isset( $metric['label'] ) ? $metric['label'] : '' ); ?></dt>
                        <dd><?php echo esc_html( isset( $metric['value'] ) ? $metric['value'] : '' ); ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>

        <section class="a11y-widget-admin-card a11y-widget-admin-health-checks" aria-labelledby="a11y-widget-health-checks-title">
            <h2 class="a11y-widget-admin-section__title" id="a11y-widget-health-checks-title"><?php esc_html_e( 'Contrôles locaux', 'a11y-widget' ); ?></h2>
            <ul class="a11y-widget-admin-health-checks__list">
                <?php foreach ( $checks as $check ) : ?>
                    <?php
                    $check_level = isset( $check['level'] ) ? sanitize_html_class( (string) $check['level'] ) : 'ok';
                    $check_title = isset( $check['title'] ) ? (string) $check['title'] : '';
                    $check_text  = isset( $check['text'] ) ? (string) $check['text'] : '';
                    $action_url  = isset( $check['action_url'] ) ? trim( (string) $check['action_url'] ) : '';
                    $action_label = isset( $check['action_label'] ) ? trim( (string) $check['action_label'] ) : '';
                    ?>
                    <li class="a11y-widget-admin-health-check a11y-widget-admin-health-check--<?php echo esc_attr( $check_level ); ?>">
                        <span class="a11y-widget-admin-health-badge a11y-widget-admin-health-badge--<?php echo esc_attr( $check_level ); ?>">
                            <?php echo esc_html( a11y_widget_get_health_level_label( $check_level ) ); ?>
                        </span>
                        <div class="a11y-widget-admin-health-check__body">
                            <h3><?php echo esc_html( $check_title ); ?></h3>
                            <p class="description"><?php echo esc_html( $check_text ); ?></p>
                            <?php if ( '' !== $action_url && '' !== $action_label ) : ?>
                                <a class="button button-secondary" href="<?php echo esc_url( $action_url ); ?>">
                                    <?php echo esc_html( $action_label ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="a11y-widget-admin-card a11y-widget-admin-health-actions" aria-labelledby="a11y-widget-health-actions-title">
            <h2 class="a11y-widget-admin-section__title" id="a11y-widget-health-actions-title"><?php esc_html_e( 'Suite conseillée', 'a11y-widget' ); ?></h2>
            <?php if ( empty( $actions ) ) : ?>
                <p class="description"><?php esc_html_e( 'Aucune action bloquante n’est remontée. Gardez une recette clavier/front avant publication.', 'a11y-widget' ); ?></p>
            <?php else : ?>
                <ul class="a11y-widget-admin-health-actions__list">
                    <?php foreach ( $actions as $action ) : ?>
                        <li>
                            <span class="a11y-widget-admin-health-badge a11y-widget-admin-health-badge--<?php echo esc_attr( sanitize_html_class( $action['level'] ) ); ?>">
                                <?php echo esc_html( a11y_widget_get_health_level_label( $action['level'] ) ); ?>
                            </span>
                            <span><?php echo esc_html( $action['title'] ); ?></span>
                            <a class="button button-secondary" href="<?php echo esc_url( $action['url'] ); ?>">
                                <?php echo esc_html( $action['label'] ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </div>
    <?php
}

/**
 * Render the audit and follow-up administration page.
 */
function a11y_widget_render_audit_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $statement_option_key     = a11y_widget_get_accessibility_statement_option_name();
    $statement_options        = a11y_widget_get_accessibility_statement_options();
    $statement_status_choices = a11y_widget_get_accessibility_statement_status_choices();
    $statement_status         = isset( $statement_options['audit_status'] )
        ? sanitize_key( (string) $statement_options['audit_status'] )
        : 'not_assessed';
    $statement_status_label   = isset( $statement_status_choices[ $statement_status ] )
        ? $statement_status_choices[ $statement_status ]
        : $statement_status_choices['not_assessed'];
    $followup_notices         = a11y_widget_get_audit_followup_notices( $statement_options );
    $rgaa_integration         = a11y_widget_get_rgaa_audit_integration_status();
    $rgaa_audit_admin_url     = isset( $rgaa_integration['admin_url'] ) ? (string) $rgaa_integration['admin_url'] : '';
    $rgaa_detected            = ! empty( $rgaa_integration['detected'] );
    $rgaa_version             = isset( $rgaa_integration['version'] ) ? (string) $rgaa_integration['version'] : '';
    $rgaa_mode                = isset( $rgaa_integration['mode'] ) ? (string) $rgaa_integration['mode'] : '';
    $credits_url              = admin_url( 'admin.php?page=a11y-widget-credits' );
    ?>
    <div class="wrap a11y-widget-admin a11y-widget-admin-audit">
        <h1><?php esc_html_e( 'Audit et suivi', 'a11y-widget' ); ?></h1>
        <p class="a11y-widget-admin__intro">
            <?php esc_html_e( 'Cet écran garde une synthèse d’audit côté administration. RGAA_Audit reste l’application de référence pour les critères, preuves, anomalies et rapports ; le widget public reste limité à une déclaration synthétique.', 'a11y-widget' ); ?>
        </p>

        <div class="a11y-widget-admin-audit__layout">
            <section class="a11y-widget-admin-card a11y-widget-admin-audit-summary-card">
                <h2 class="a11y-widget-admin-section__title"><?php esc_html_e( 'État du suivi', 'a11y-widget' ); ?></h2>
                <dl class="a11y-widget-admin-audit-summary">
                    <div>
                        <dt><?php esc_html_e( 'Affichage dans le widget', 'a11y-widget' ); ?></dt>
                        <dd><?php echo ! empty( $statement_options['enabled'] ) ? esc_html__( 'Activé', 'a11y-widget' ) : esc_html__( 'Désactivé', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Déclaration publique', 'a11y-widget' ); ?></dt>
                        <dd><?php echo ! empty( $statement_options['declaration_url'] ) ? esc_html__( 'URL renseignée', 'a11y-widget' ) : esc_html__( 'Non renseignée', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Statut déclaré', 'a11y-widget' ); ?></dt>
                        <dd><?php echo esc_html( $statement_status_label ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Date de l’audit', 'a11y-widget' ); ?></dt>
                        <dd><?php echo ! empty( $statement_options['audit_date'] ) ? esc_html( $statement_options['audit_date'] ) : esc_html__( 'Non renseignée', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Rapport lié', 'a11y-widget' ); ?></dt>
                        <dd><?php echo ! empty( $statement_options['audit_url'] ) ? esc_html__( 'Oui', 'a11y-widget' ) : esc_html__( 'Non', 'a11y-widget' ); ?></dd>
                    </div>
                </dl>

                <div class="a11y-widget-admin-audit-actions">
                    <?php if ( ! empty( $statement_options['declaration_url'] ) ) : ?>
                        <a class="button button-secondary" href="<?php echo esc_url( $statement_options['declaration_url'] ); ?>" target="_blank" rel="noopener">
                            <?php esc_html_e( 'Ouvrir la déclaration publique', 'a11y-widget' ); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( $statement_options['audit_url'] ) ) : ?>
                        <a class="button button-secondary" href="<?php echo esc_url( $statement_options['audit_url'] ); ?>" target="_blank" rel="noopener">
                            <?php esc_html_e( 'Ouvrir le rapport d’audit', 'a11y-widget' ); ?>
                        </a>
                    <?php endif; ?>
                    <a class="button button-secondary" href="<?php echo esc_url( $credits_url ); ?>">
                        <?php esc_html_e( 'Voir les crédits du projet', 'a11y-widget' ); ?>
                    </a>
                </div>
            </section>

            <section class="a11y-widget-admin-card a11y-widget-admin-audit-notices">
                <h2 class="a11y-widget-admin-section__title"><?php esc_html_e( 'Points de vigilance', 'a11y-widget' ); ?></h2>
                <ul class="a11y-widget-admin-audit-notices__list">
                    <?php foreach ( $followup_notices as $notice ) : ?>
                        <?php
                        $notice_level = isset( $notice['level'] ) ? sanitize_html_class( $notice['level'] ) : 'info';
                        ?>
                        <li class="a11y-widget-admin-audit-notice a11y-widget-admin-audit-notice--<?php echo esc_attr( $notice_level ); ?>">
                            <strong><?php echo esc_html( isset( $notice['title'] ) ? $notice['title'] : '' ); ?></strong>
                            <span><?php echo esc_html( isset( $notice['text'] ) ? $notice['text'] : '' ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="a11y-widget-admin-card a11y-widget-admin-rgaa-integration" aria-labelledby="a11y-widget-rgaa-integration-title">
                <h2 class="a11y-widget-admin-section__title" id="a11y-widget-rgaa-integration-title"><?php esc_html_e( 'Intégration RGAA_Audit', 'a11y-widget' ); ?></h2>
                <p class="description">
                    <?php esc_html_e( 'Cette carte vérifie uniquement la liaison avec l’application compagnon. Le widget reste en lecture seule sur ce périmètre : il ne lit pas la grille des critères, ne calcule pas de conformité et ne crée pas d’anomalie RGAA automatiquement.', 'a11y-widget' ); ?>
                </p>

                <dl class="a11y-widget-admin-rgaa-integration__status">
                    <div>
                        <dt><?php esc_html_e( 'État', 'a11y-widget' ); ?></dt>
                        <dd>
                            <span class="a11y-widget-admin-rgaa-integration__badge <?php echo $rgaa_detected ? 'a11y-widget-admin-rgaa-integration__badge--detected' : 'a11y-widget-admin-rgaa-integration__badge--missing'; ?>">
                                <?php echo $rgaa_detected ? esc_html__( 'RGAA_Audit détecté', 'a11y-widget' ) : esc_html__( 'RGAA_Audit non détecté', 'a11y-widget' ); ?>
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Version', 'a11y-widget' ); ?></dt>
                        <dd><?php echo '' !== $rgaa_version ? esc_html( $rgaa_version ) : esc_html__( 'Non renseignée', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Lien administration', 'a11y-widget' ); ?></dt>
                        <dd><?php echo '' !== $rgaa_audit_admin_url ? esc_html__( 'Disponible', 'a11y-widget' ) : esc_html__( 'Indisponible ou masqué', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Mode de liaison', 'a11y-widget' ); ?></dt>
                        <dd><?php echo esc_html( $rgaa_mode ); ?></dd>
                    </div>
                </dl>

                <div class="a11y-widget-admin-rgaa-integration__body">
                    <div>
                        <h3><?php esc_html_e( 'Responsabilités conservées', 'a11y-widget' ); ?></h3>
                        <ul class="a11y-widget-admin-rgaa-integration__rules">
                            <li><?php esc_html_e( 'RGAA_Audit reste la source de vérité pour les critères, preuves, anomalies, rapports et statuts de conformité.', 'a11y-widget' ); ?></li>
                            <li><?php esc_html_e( 'Le widget affiche seulement des aides de confort, une déclaration synthétique et des liens administrateur.', 'a11y-widget' ); ?></li>
                            <li><?php esc_html_e( 'Les retours utilisateurs restent des signaux humains à qualifier, pas des anomalies RGAA automatiques.', 'a11y-widget' ); ?></li>
                        </ul>
                    </div>

                    <div class="a11y-widget-admin-rgaa-integration__actions">
                        <?php if ( '' !== $rgaa_audit_admin_url ) : ?>
                            <a class="button button-secondary" href="<?php echo esc_url( $rgaa_audit_admin_url ); ?>">
                                <?php esc_html_e( 'Ouvrir RGAA_Audit', 'a11y-widget' ); ?>
                            </a>
                        <?php else : ?>
                            <p class="description"><?php esc_html_e( 'Aucun lien RGAA_Audit n’est disponible pour le moment. Le widget continue de fonctionner en mode autonome.', 'a11y-widget' ); ?></p>
                        <?php endif; ?>
                        <p class="description">
                            <?php esc_html_e( 'Le filtre a11y_widget_rgaa_audit_admin_url permet d’adapter ce lien si l’application compagnon utilise une URL différente.', 'a11y-widget' ); ?>
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields( 'a11y_widget_audit_settings' ); ?>

            <fieldset class="a11y-widget-admin-card a11y-widget-admin-statement">
                <legend><?php esc_html_e( 'Déclaration publique', 'a11y-widget' ); ?></legend>
                <p class="description">
                    <?php esc_html_e( 'Ces deux réglages contrôlent ce qui peut apparaître dans le widget public.', 'a11y-widget' ); ?>
                </p>

                <label class="a11y-widget-admin-statement__toggle" for="a11y-widget-audit-statement-enabled">
                    <input type="hidden" name="<?php echo esc_attr( $statement_option_key ); ?>[enabled]" value="0" />
                    <input
                        type="checkbox"
                        id="a11y-widget-audit-statement-enabled"
                        name="<?php echo esc_attr( $statement_option_key ); ?>[enabled]"
                        value="1"
                        <?php checked( ! empty( $statement_options['enabled'] ) ); ?>
                    />
                    <?php esc_html_e( 'Afficher une entrée Déclaration dans le widget', 'a11y-widget' ); ?>
                </label>

                <div class="a11y-widget-admin-statement__fields">
                    <div class="a11y-widget-admin-statement__field a11y-widget-admin-statement__field--wide">
                        <label for="a11y-widget-audit-declaration-url"><?php esc_html_e( 'URL de la déclaration publiée', 'a11y-widget' ); ?></label>
                        <input
                            type="url"
                            id="a11y-widget-audit-declaration-url"
                            class="regular-text"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[declaration_url]"
                            value="<?php echo esc_attr( isset( $statement_options['declaration_url'] ) ? $statement_options['declaration_url'] : '' ); ?>"
                            placeholder="<?php echo esc_attr( home_url( '/declaration-accessibilite/' ) ); ?>"
                        />
                    </div>
                </div>
            </fieldset>

            <fieldset class="a11y-widget-admin-card a11y-widget-admin-statement a11y-widget-admin-audit-fields-card">
                <legend><?php esc_html_e( 'Synthèse d’audit liée', 'a11y-widget' ); ?></legend>
                <p class="description">
                    <?php esc_html_e( 'Ces informations servent au suivi administrateur et peuvent refléter un rapport RGAA_Audit. Le widget public n’affiche pas la grille complète ni les notes de correction.', 'a11y-widget' ); ?>
                </p>

                <div class="a11y-widget-admin-statement__fields a11y-widget-admin-audit-fields">
                    <div class="a11y-widget-admin-statement__field a11y-widget-admin-statement__field--wide">
                        <label for="a11y-widget-audit-url"><?php esc_html_e( 'URL de l’audit ou du rapport', 'a11y-widget' ); ?></label>
                        <input
                            type="url"
                            id="a11y-widget-audit-url"
                            class="regular-text"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[audit_url]"
                            value="<?php echo esc_attr( isset( $statement_options['audit_url'] ) ? $statement_options['audit_url'] : '' ); ?>"
                        />
                        <p class="description"><?php esc_html_e( 'Lien vers le rapport, l’outil d’audit ou une synthèse vérifiable.', 'a11y-widget' ); ?></p>
                    </div>

                    <div class="a11y-widget-admin-statement__field">
                        <label for="a11y-widget-audit-date"><?php esc_html_e( 'Date de l’audit', 'a11y-widget' ); ?></label>
                        <input
                            type="date"
                            id="a11y-widget-audit-date"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[audit_date]"
                            value="<?php echo esc_attr( isset( $statement_options['audit_date'] ) ? $statement_options['audit_date'] : '' ); ?>"
                        />
                    </div>

                    <div class="a11y-widget-admin-statement__field">
                        <label for="a11y-widget-audit-status"><?php esc_html_e( 'Statut déclaré', 'a11y-widget' ); ?></label>
                        <select
                            id="a11y-widget-audit-status"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[audit_status]"
                        >
                            <?php foreach ( $statement_status_choices as $status_slug => $status_label ) : ?>
                                <option value="<?php echo esc_attr( $status_slug ); ?>" <?php selected( $statement_status, $status_slug ); ?>>
                                    <?php echo esc_html( $status_label ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="a11y-widget-admin-statement__field">
                        <label for="a11y-widget-audit-compliance-rate"><?php esc_html_e( 'Taux indiqué', 'a11y-widget' ); ?></label>
                        <input
                            type="number"
                            id="a11y-widget-audit-compliance-rate"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[compliance_rate]"
                            value="<?php echo esc_attr( isset( $statement_options['compliance_rate'] ) ? $statement_options['compliance_rate'] : '' ); ?>"
                            min="0"
                            max="100"
                            step="0.1"
                        />
                        <p class="description"><?php esc_html_e( 'Pourcentage facultatif, uniquement s’il provient du rapport.', 'a11y-widget' ); ?></p>
                    </div>

                    <div class="a11y-widget-admin-statement__field">
                        <label for="a11y-widget-audit-auditor"><?php esc_html_e( 'Réalisé par', 'a11y-widget' ); ?></label>
                        <input
                            type="text"
                            id="a11y-widget-audit-auditor"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[auditor]"
                            value="<?php echo esc_attr( isset( $statement_options['auditor'] ) ? $statement_options['auditor'] : '' ); ?>"
                        />
                    </div>

                    <div class="a11y-widget-admin-statement__field a11y-widget-admin-statement__field--wide">
                        <label for="a11y-widget-audit-scope"><?php esc_html_e( 'Périmètre audité', 'a11y-widget' ); ?></label>
                        <input
                            type="text"
                            id="a11y-widget-audit-scope"
                            class="regular-text"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[audit_scope]"
                            value="<?php echo esc_attr( isset( $statement_options['audit_scope'] ) ? $statement_options['audit_scope'] : '' ); ?>"
                        />
                    </div>

                    <div class="a11y-widget-admin-statement__field a11y-widget-admin-statement__field--wide">
                        <label for="a11y-widget-audit-notes"><?php esc_html_e( 'Notes de suivi internes', 'a11y-widget' ); ?></label>
                        <textarea
                            id="a11y-widget-audit-notes"
                            name="<?php echo esc_attr( $statement_option_key ); ?>[notes]"
                            rows="5"
                        ><?php echo esc_textarea( isset( $statement_options['notes'] ) ? $statement_options['notes'] : '' ); ?></textarea>
                        <p class="description"><?php esc_html_e( 'Notes destinées à l’administration. Elles ne sont pas affichées dans le widget public.', 'a11y-widget' ); ?></p>
                    </div>
                </div>
            </fieldset>

            <?php submit_button( __( 'Enregistrer le suivi d’audit', 'a11y-widget' ) ); ?>
        </form>
    </div>
    <?php
}

/**
 * Render the project credits administration page.
 */
function a11y_widget_render_credits_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $creators = function_exists( 'a11y_widget_get_project_creators' )
        ? a11y_widget_get_project_creators()
        : array();
    ?>
    <div class="wrap a11y-widget-admin a11y-widget-admin-credits">
        <h1><?php esc_html_e( 'Crédits', 'a11y-widget' ); ?></h1>
        <p class="a11y-widget-admin__intro">
            <?php esc_html_e( 'Cette page présente les créatrices et le cadre du projet MOBLS. Elle est volontairement séparée du panneau public et des écrans d’audit.', 'a11y-widget' ); ?>
        </p>

        <div class="a11y-widget-admin-credits__grid">
            <section class="a11y-widget-admin-card a11y-widget-admin-credits__card" aria-labelledby="a11y-widget-credits-creators-title">
                <h2 class="a11y-widget-admin-section__title" id="a11y-widget-credits-creators-title"><?php esc_html_e( 'Créatrices de l’application', 'a11y-widget' ); ?></h2>
                <ul class="a11y-widget-admin-credits__people">
                    <?php foreach ( $creators as $creator ) : ?>
                        <li><?php echo esc_html( $creator ); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="a11y-widget-admin-card a11y-widget-admin-credits__card" aria-labelledby="a11y-widget-credits-context-title">
                <h2 class="a11y-widget-admin-section__title" id="a11y-widget-credits-context-title"><?php esc_html_e( 'Cadre du projet', 'a11y-widget' ); ?></h2>
                <dl class="a11y-widget-admin-credits__meta">
                    <div>
                        <dt><?php esc_html_e( 'Nom du projet', 'a11y-widget' ); ?></dt>
                        <dd><?php esc_html_e( 'MOBLS', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Organisation', 'a11y-widget' ); ?></dt>
                        <dd><?php esc_html_e( 'Université Bretagne Sud', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Professeur encadrant', 'a11y-widget' ); ?></dt>
                        <dd><?php esc_html_e( 'Jérôme Le Gousse', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'IA utilisées', 'a11y-widget' ); ?></dt>
                        <dd><?php esc_html_e( 'ChatGPT, Gemini, Mistral, Claude, Copilot', 'a11y-widget' ); ?></dd>
                    </div>
                </dl>
            </section>

            <section class="a11y-widget-admin-card a11y-widget-admin-credits__card" aria-labelledby="a11y-widget-credits-scope-title">
                <h2 class="a11y-widget-admin-section__title" id="a11y-widget-credits-scope-title"><?php esc_html_e( 'Rappel de périmètre', 'a11y-widget' ); ?></h2>
                <p class="description">
                    <?php esc_html_e( 'Les crédits identifient les personnes ayant créé l’application. Ils ne constituent pas une déclaration de conformité RGAA/WCAG et ne modifient pas les réglages du widget.', 'a11y-widget' ); ?>
                </p>
                <p>
                    <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=a11y-widget-audit' ) ); ?>">
                        <?php esc_html_e( 'Retour à Audit et suivi', 'a11y-widget' ); ?>
                    </a>
                </p>
            </section>
        </div>
    </div>
    <?php
}

/**
 * Render a single feedback entry detail panel.
 *
 * @param WP_Post               $feedback_item  Feedback post.
 * @param array<string, string> $rating_choices Rating labels.
 * @param array<string, string> $status_choices Status labels.
 * @param string                $status_filter  Active status filter.
 */
function a11y_widget_render_feedback_detail( $feedback_item, $rating_choices, $status_choices, $status_filter ) {
    $rating   = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_rating', true );
    $page     = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_page_url', true );
    $profile  = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_profile', true );
    $features = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_features', true );
    $status   = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_status', true );
    $note     = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_internal_note', true );

    if ( ! is_array( $features ) ) {
        $features = array();
    }

    if ( ! isset( $status_choices[ $status ] ) ) {
        $status = 'new';
    }

    $rating_label = isset( $rating_choices[ $rating ] ) ? $rating_choices[ $rating ] : __( 'Non renseigné', 'a11y-widget' );
    $date         = get_the_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $feedback_item );
    $comment      = trim( (string) $feedback_item->post_content );
    $rgaa_url     = a11y_widget_get_rgaa_audit_admin_url();
    ?>
    <section class="a11y-widget-admin-card a11y-widget-admin-feedback-detail" aria-labelledby="a11y-widget-feedback-detail-title">
        <div class="a11y-widget-admin-feedback-detail__header">
            <div>
                <h2 class="a11y-widget-admin-section__title" id="a11y-widget-feedback-detail-title">
                    <?php
                    echo esc_html(
                        sprintf(
                            /* translators: %d: feedback post ID */
                            __( 'Détail du retour #%d', 'a11y-widget' ),
                            (int) $feedback_item->ID
                        )
                    );
                    ?>
                </h2>
                <p class="description">
                    <?php esc_html_e( 'Vue de suivi interne. Ce retour peut aider à prioriser une vérification humaine, mais il ne devient pas une anomalie RGAA automatiquement.', 'a11y-widget' ); ?>
                </p>
            </div>
            <a class="button button-secondary" href="<?php echo esc_url( a11y_widget_get_feedback_admin_url( '' !== $status_filter ? array( 'feedback_status' => $status_filter ) : array() ) ); ?>">
                <?php esc_html_e( 'Retour à la liste', 'a11y-widget' ); ?>
            </a>
        </div>

        <div class="a11y-widget-admin-feedback-detail__grid">
            <div class="a11y-widget-admin-feedback-detail__main">
                <dl class="a11y-widget-admin-feedback-detail__summary">
                    <div>
                        <dt><?php esc_html_e( 'Date', 'a11y-widget' ); ?></dt>
                        <dd><?php echo esc_html( $date ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Statut', 'a11y-widget' ); ?></dt>
                        <dd>
                            <span class="a11y-widget-admin-feedback-status a11y-widget-admin-feedback-status--<?php echo esc_attr( sanitize_html_class( $status ) ); ?>">
                                <?php echo esc_html( $status_choices[ $status ] ); ?>
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Aide perçue', 'a11y-widget' ); ?></dt>
                        <dd><?php echo esc_html( $rating_label ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Page concernée', 'a11y-widget' ); ?></dt>
                        <dd>
                            <?php if ( '' !== $page ) : ?>
                                <a href="<?php echo esc_url( $page ); ?>" target="_blank" rel="noopener">
                                    <?php echo esc_html( $page ); ?>
                                </a>
                            <?php else : ?>
                                <?php esc_html_e( 'Non renseignée', 'a11y-widget' ); ?>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Profil actif', 'a11y-widget' ); ?></dt>
                        <dd><?php echo '' !== $profile ? esc_html( $profile ) : esc_html__( 'Aucun', 'a11y-widget' ); ?></dd>
                    </div>
                    <div>
                        <dt><?php esc_html_e( 'Fonctions actives', 'a11y-widget' ); ?></dt>
                        <dd><?php echo ! empty( $features ) ? esc_html( implode( ', ', array_map( 'sanitize_key', $features ) ) ) : esc_html__( 'Aucune', 'a11y-widget' ); ?></dd>
                    </div>
                </dl>

                <div class="a11y-widget-admin-feedback-detail__comment">
                    <h3><?php esc_html_e( 'Commentaire utilisateur', 'a11y-widget' ); ?></h3>
                    <p><?php echo '' !== $comment ? esc_html( $comment ) : esc_html__( 'Sans commentaire.', 'a11y-widget' ); ?></p>
                </div>
            </div>

            <div class="a11y-widget-admin-feedback-detail__side">
                <form class="a11y-widget-admin-feedback-detail__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <input type="hidden" name="action" value="a11y_widget_update_feedback_status" />
                    <input type="hidden" name="feedback_id" value="<?php echo esc_attr( (string) $feedback_item->ID ); ?>" />
                    <input type="hidden" name="feedback_detail" value="1" />
                    <?php if ( '' !== $status_filter ) : ?>
                        <input type="hidden" name="feedback_status_filter" value="<?php echo esc_attr( $status_filter ); ?>" />
                    <?php endif; ?>
                    <?php wp_nonce_field( 'a11y_widget_update_feedback_status_' . $feedback_item->ID ); ?>
                    <label for="a11y-widget-feedback-detail-status"><?php esc_html_e( 'Statut de traitement', 'a11y-widget' ); ?></label>
                    <select id="a11y-widget-feedback-detail-status" name="feedback_status">
                        <?php foreach ( $status_choices as $status_slug => $status_label ) : ?>
                            <option value="<?php echo esc_attr( $status_slug ); ?>" <?php selected( $status, $status_slug ); ?>>
                                <?php echo esc_html( $status_label ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="button button-secondary">
                        <?php esc_html_e( 'Mettre à jour le statut', 'a11y-widget' ); ?>
                    </button>
                </form>

                <form class="a11y-widget-admin-feedback-detail__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <input type="hidden" name="action" value="a11y_widget_update_feedback_note" />
                    <input type="hidden" name="feedback_id" value="<?php echo esc_attr( (string) $feedback_item->ID ); ?>" />
                    <input type="hidden" name="feedback_detail" value="1" />
                    <?php if ( '' !== $status_filter ) : ?>
                        <input type="hidden" name="feedback_status_filter" value="<?php echo esc_attr( $status_filter ); ?>" />
                    <?php endif; ?>
                    <?php wp_nonce_field( 'a11y_widget_update_feedback_note_' . $feedback_item->ID ); ?>
                    <label for="a11y-widget-feedback-internal-note"><?php esc_html_e( 'Note interne', 'a11y-widget' ); ?></label>
                    <textarea id="a11y-widget-feedback-internal-note" name="feedback_internal_note" rows="6"><?php echo esc_textarea( (string) $note ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Note privée pour le suivi humain. Elle n’est jamais affichée dans le widget public.', 'a11y-widget' ); ?></p>
                    <button type="submit" class="button button-primary">
                        <?php esc_html_e( 'Enregistrer la note', 'a11y-widget' ); ?>
                    </button>
                </form>

                <div class="a11y-widget-admin-feedback-detail__rgaa">
                    <h3><?php esc_html_e( 'Rapprochement RGAA_Audit', 'a11y-widget' ); ?></h3>
                    <p class="description">
                        <?php esc_html_e( 'Ouvrez RGAA_Audit uniquement si ce retour doit être qualifié manuellement dans une démarche d’audit. Aucune anomalie n’est créée automatiquement.', 'a11y-widget' ); ?>
                    </p>
                    <?php if ( '' !== $rgaa_url ) : ?>
                        <a class="button button-secondary" href="<?php echo esc_url( $rgaa_url ); ?>">
                            <?php esc_html_e( 'Ouvrir RGAA_Audit', 'a11y-widget' ); ?>
                        </a>
                    <?php else : ?>
                        <p class="description"><?php esc_html_e( 'RGAA_Audit n’est pas détecté ou son lien est masqué.', 'a11y-widget' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Render the user feedback administration page.
 */
function a11y_widget_render_feedback_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $feedback_enabled = a11y_widget_feedback_collection_enabled();
    $rating_choices   = a11y_widget_get_feedback_rating_choices();
    $status_choices   = a11y_widget_get_feedback_status_choices();
    $status_filter    = a11y_widget_get_feedback_status_filter_from_request();
    $status_counts    = a11y_widget_get_feedback_status_counts();
    $retention_value  = a11y_widget_get_feedback_retention_days();
    $retention_choices = a11y_widget_get_feedback_retention_choices();
    $retention_label  = isset( $retention_choices[ $retention_value ] ) ? $retention_choices[ $retention_value ] : $retention_choices['90'];
    $retention_schedule = defined( 'A11Y_WIDGET_FEEDBACK_RETENTION_HOOK' ) ? wp_next_scheduled( A11Y_WIDGET_FEEDBACK_RETENTION_HOOK ) : false;
    $retention_schedule_label = $retention_schedule
        ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $retention_schedule )
        : '';
    $feedback_query_args = array(
        'post_type'      => a11y_widget_get_feedback_post_type(),
        'post_status'    => 'private',
        'posts_per_page' => 50,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    );
    $feedback_meta_query = a11y_widget_get_feedback_status_meta_query( $status_filter );

    if ( ! empty( $feedback_meta_query ) ) {
        $feedback_query_args['meta_query'] = $feedback_meta_query;
    }

    $feedback_items = get_posts( $feedback_query_args );
    $message_key    = isset( $_GET['a11y_feedback_message'] ) ? sanitize_key( (string) wp_unslash( $_GET['a11y_feedback_message'] ) ) : '';
    $message_count  = isset( $_GET['a11y_feedback_count'] ) ? absint( wp_unslash( $_GET['a11y_feedback_count'] ) ) : null;
    $detail_feedback_id = isset( $_GET['feedback_id'] ) ? absint( wp_unslash( $_GET['feedback_id'] ) ) : 0;
    $detail_feedback_item = $detail_feedback_id ? a11y_widget_get_feedback_item( $detail_feedback_id ) : null;
    $message_lookup = array(
        'status_updated'    => __( 'Statut du retour mis à jour.', 'a11y-widget' ),
        'note_updated'      => __( 'Note interne enregistrée.', 'a11y-widget' ),
        'deleted'           => __( 'Retour supprimé.', 'a11y-widget' ),
        'invalid_feedback'  => __( 'Retour introuvable.', 'a11y-widget' ),
        'retention_unlimited' => __( 'La durée de conservation est illimitée : aucune purge par ancienneté n’a été effectuée.', 'a11y-widget' ),
    );

    if ( null !== $message_count ) {
        $message_lookup['purged_archived'] = sprintf(
            _n( '%d retour archivé supprimé.', '%d retours archivés supprimés.', $message_count, 'a11y-widget' ),
            $message_count
        );
        $message_lookup['purged_old'] = sprintf(
            _n( '%d retour expiré supprimé.', '%d retours expirés supprimés.', $message_count, 'a11y-widget' ),
            $message_count
        );
    } else {
        $message_lookup['purged_archived'] = __( 'Retours archivés supprimés.', 'a11y-widget' );
        $message_lookup['purged_old'] = __( 'Retours expirés supprimés.', 'a11y-widget' );
    }

    $export_args = array( 'action' => 'a11y_widget_export_feedback' );

    if ( '' !== $status_filter ) {
        $export_args['feedback_status'] = $status_filter;
    }

    $export_url = wp_nonce_url(
        add_query_arg(
            $export_args,
            admin_url( 'admin-post.php' )
        ),
        'a11y_widget_export_feedback'
    );
    ?>
    <div class="wrap a11y-widget-admin a11y-widget-admin-feedback">
        <h1><?php esc_html_e( 'Retours utilisateurs', 'a11y-widget' ); ?></h1>
        <p class="a11y-widget-admin__intro">
            <?php esc_html_e( 'Cette page liste les retours envoyés depuis le widget. Elle sert au suivi humain des besoins, pas à produire un diagnostic automatique.', 'a11y-widget' ); ?>
        </p>

        <?php if ( isset( $message_lookup[ $message_key ] ) ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html( $message_lookup[ $message_key ] ); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( $detail_feedback_id && ! $detail_feedback_item ) : ?>
            <div class="notice notice-error">
                <p><?php esc_html_e( 'Le retour demandé est introuvable ou n’est plus disponible.', 'a11y-widget' ); ?></p>
            </div>
        <?php endif; ?>

        <section class="a11y-widget-admin-card a11y-widget-admin-feedback-overview">
            <h2 class="a11y-widget-admin-section__title"><?php esc_html_e( 'Collecte', 'a11y-widget' ); ?></h2>
            <p class="a11y-widget-admin-feedback-overview__status">
                <strong><?php esc_html_e( 'État :', 'a11y-widget' ); ?></strong>
                <?php echo $feedback_enabled ? esc_html__( 'activée', 'a11y-widget' ) : esc_html__( 'désactivée', 'a11y-widget' ); ?>
            </p>
            <p class="description">
                <?php esc_html_e( 'Quand la collecte est désactivée, la carte Feedback n’apparaît pas dans le widget public.', 'a11y-widget' ); ?>
            </p>
            <p class="a11y-widget-admin-feedback-overview__actions">
                <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=a11y-widget#a11y-widget-settings-feedback' ) ); ?>">
                    <?php esc_html_e( 'Modifier le réglage de collecte', 'a11y-widget' ); ?>
                </a>
                <a class="button button-secondary" href="<?php echo esc_url( $export_url ); ?>">
                    <?php echo '' !== $status_filter ? esc_html__( 'Exporter le filtre en CSV', 'a11y-widget' ) : esc_html__( 'Exporter en CSV', 'a11y-widget' ); ?>
                </a>
            </p>

            <nav class="a11y-widget-admin-feedback-filters" aria-label="<?php echo esc_attr__( 'Filtrer les retours par statut', 'a11y-widget' ); ?>">
                <?php
                $all_filter_classes = array( 'a11y-widget-admin-feedback-filter' );

                if ( '' === $status_filter ) {
                    $all_filter_classes[] = 'a11y-widget-admin-feedback-filter--active';
                }
                ?>
                <a
                    class="<?php echo esc_attr( implode( ' ', $all_filter_classes ) ); ?>"
                    href="<?php echo esc_url( a11y_widget_get_feedback_admin_url() ); ?>"
                    <?php if ( '' === $status_filter ) : ?>
                        aria-current="page"
                    <?php endif; ?>
                >
                    <span><?php esc_html_e( 'Tous', 'a11y-widget' ); ?></span>
                    <strong><?php echo esc_html( (string) $status_counts['all'] ); ?></strong>
                </a>
                <?php foreach ( $status_choices as $status_slug => $status_label ) : ?>
                    <?php
                    $filter_classes = array( 'a11y-widget-admin-feedback-filter' );

                    if ( $status_filter === $status_slug ) {
                        $filter_classes[] = 'a11y-widget-admin-feedback-filter--active';
                    }
                    ?>
                    <a
                        class="<?php echo esc_attr( implode( ' ', $filter_classes ) ); ?>"
                        href="<?php echo esc_url( a11y_widget_get_feedback_admin_url( array( 'feedback_status' => $status_slug ) ) ); ?>"
                        <?php if ( $status_filter === $status_slug ) : ?>
                            aria-current="page"
                        <?php endif; ?>
                    >
                        <span><?php echo esc_html( $status_label ); ?></span>
                        <strong><?php echo esc_html( (string) $status_counts[ $status_slug ] ); ?></strong>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="a11y-widget-admin-feedback-maintenance">
                <p>
                    <strong><?php esc_html_e( 'Conservation :', 'a11y-widget' ); ?></strong>
                    <?php echo esc_html( $retention_label ); ?>
                </p>
                <p class="description">
                    <?php if ( '' !== $retention_schedule_label ) : ?>
                        <?php
                        echo esc_html(
                            sprintf(
                                /* translators: %s: next scheduled purge date */
                                __( 'Purge automatique quotidienne planifiée. Prochain passage : %s.', 'a11y-widget' ),
                                $retention_schedule_label
                            )
                        );
                        ?>
                    <?php else : ?>
                        <?php esc_html_e( 'Purge automatique non planifiée pour le moment. Elle sera recréée au prochain chargement du plugin.', 'a11y-widget' ); ?>
                    <?php endif; ?>
                </p>
                <div class="a11y-widget-admin-feedback-maintenance__actions">
                    <form
                        method="post"
                        action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                        onsubmit="return window.confirm('<?php echo esc_js( __( 'Supprimer définitivement tous les retours archivés ?', 'a11y-widget' ) ); ?>');"
                    >
                        <input type="hidden" name="action" value="a11y_widget_purge_archived_feedback" />
                        <?php wp_nonce_field( 'a11y_widget_purge_archived_feedback' ); ?>
                        <button type="submit" class="button button-secondary">
                            <?php esc_html_e( 'Supprimer les retours archivés', 'a11y-widget' ); ?>
                        </button>
                    </form>
                    <form
                        method="post"
                        action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                        onsubmit="return window.confirm('<?php echo esc_js( __( 'Supprimer définitivement les retours plus anciens que la durée de conservation choisie ?', 'a11y-widget' ) ); ?>');"
                    >
                        <input type="hidden" name="action" value="a11y_widget_purge_old_feedback" />
                        <?php wp_nonce_field( 'a11y_widget_purge_old_feedback' ); ?>
                        <button type="submit" class="button button-secondary">
                            <?php esc_html_e( 'Supprimer les retours expirés', 'a11y-widget' ); ?>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <?php if ( $detail_feedback_item ) : ?>
            <?php a11y_widget_render_feedback_detail( $detail_feedback_item, $rating_choices, $status_choices, $status_filter ); ?>
        <?php endif; ?>

        <section class="a11y-widget-admin-card a11y-widget-admin-feedback-list">
            <h2 class="a11y-widget-admin-section__title">
                <?php echo '' !== $status_filter ? esc_html( sprintf( __( 'Derniers retours : %s', 'a11y-widget' ), $status_choices[ $status_filter ] ) ) : esc_html__( 'Derniers retours', 'a11y-widget' ); ?>
            </h2>

            <?php if ( empty( $feedback_items ) ) : ?>
                <div class="a11y-widget-admin-empty-state">
                    <p class="a11y-widget-admin-empty-state__title"><?php esc_html_e( 'Aucun retour à traiter pour ce filtre.', 'a11y-widget' ); ?></p>
                    <p class="description">
                        <?php esc_html_e( 'Les retours envoyés depuis le widget apparaîtront ici avec leur statut, la page concernée et les réglages actifs.', 'a11y-widget' ); ?>
                    </p>
                    <p>
                        <a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=a11y-widget#a11y-widget-settings-feedback' ) ); ?>">
                            <?php esc_html_e( 'Configurer la collecte', 'a11y-widget' ); ?>
                        </a>
                    </p>
                </div>
            <?php else : ?>
                <div class="a11y-widget-admin-feedback-table-wrap">
                    <table class="widefat striped a11y-widget-admin-feedback-table">
                        <caption class="screen-reader-text"><?php esc_html_e( 'Derniers retours utilisateurs reçus depuis le widget', 'a11y-widget' ); ?></caption>
                        <thead>
                            <tr>
                                <th scope="col"><?php esc_html_e( 'Date', 'a11y-widget' ); ?></th>
                                <th scope="col"><?php esc_html_e( 'Statut', 'a11y-widget' ); ?></th>
                                <th scope="col"><?php esc_html_e( 'Aide perçue', 'a11y-widget' ); ?></th>
                                <th scope="col"><?php esc_html_e( 'Page', 'a11y-widget' ); ?></th>
                                <th scope="col"><?php esc_html_e( 'Commentaire', 'a11y-widget' ); ?></th>
                                <th scope="col"><?php esc_html_e( 'Contexte', 'a11y-widget' ); ?></th>
                                <th scope="col"><?php esc_html_e( 'Actions', 'a11y-widget' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $feedback_items as $feedback_item ) : ?>
                                <?php
                                $rating  = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_rating', true );
                                $page    = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_page_url', true );
                                $profile = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_profile', true );
                                $features = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_features', true );
                                $status  = get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_status', true );
                                $note    = trim( (string) get_post_meta( $feedback_item->ID, '_a11y_widget_feedback_internal_note', true ) );

                                if ( ! is_array( $features ) ) {
                                    $features = array();
                                }

                                if ( ! isset( $status_choices[ $status ] ) ) {
                                    $status = 'new';
                                }

                                $rating_label = isset( $rating_choices[ $rating ] ) ? $rating_choices[ $rating ] : __( 'Non renseigné', 'a11y-widget' );
                                $date         = get_the_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $feedback_item );
                                $comment      = trim( (string) $feedback_item->post_content );
                                ?>
                                <tr data-feedback-status="<?php echo esc_attr( $status ); ?>">
                                    <td data-label="<?php echo esc_attr__( 'Date', 'a11y-widget' ); ?>"><?php echo esc_html( $date ); ?></td>
                                    <td data-label="<?php echo esc_attr__( 'Statut', 'a11y-widget' ); ?>">
                                        <span class="a11y-widget-admin-feedback-status a11y-widget-admin-feedback-status--<?php echo esc_attr( sanitize_html_class( $status ) ); ?>">
                                            <?php echo esc_html( $status_choices[ $status ] ); ?>
                                        </span>
                                    </td>
                                    <td data-label="<?php echo esc_attr__( 'Aide perçue', 'a11y-widget' ); ?>"><?php echo esc_html( $rating_label ); ?></td>
                                    <td data-label="<?php echo esc_attr__( 'Page', 'a11y-widget' ); ?>">
                                        <?php if ( '' !== $page ) : ?>
                                            <a href="<?php echo esc_url( $page ); ?>" target="_blank" rel="noopener">
                                                <?php echo esc_html( wp_parse_url( $page, PHP_URL_PATH ) ?: $page ); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php esc_html_e( 'Non renseignée', 'a11y-widget' ); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="a11y-widget-admin-feedback-comment" data-label="<?php echo esc_attr__( 'Commentaire', 'a11y-widget' ); ?>">
                                        <?php echo '' !== $comment ? esc_html( $comment ) : esc_html__( 'Sans commentaire', 'a11y-widget' ); ?>
                                    </td>
                                    <td data-label="<?php echo esc_attr__( 'Contexte', 'a11y-widget' ); ?>">
                                        <span class="a11y-widget-admin-feedback-context">
                                            <span><?php echo '' !== $profile ? esc_html( sprintf( __( 'Profil : %s', 'a11y-widget' ), $profile ) ) : esc_html__( 'Profil : aucun', 'a11y-widget' ); ?></span>
                                            <span><?php echo ! empty( $features ) ? esc_html( sprintf( __( 'Fonctions : %s', 'a11y-widget' ), implode( ', ', array_map( 'sanitize_key', $features ) ) ) ) : esc_html__( 'Fonctions : aucune', 'a11y-widget' ); ?></span>
                                            <?php if ( '' !== $note ) : ?>
                                                <span><?php esc_html_e( 'Note interne : oui', 'a11y-widget' ); ?></span>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td data-label="<?php echo esc_attr__( 'Actions', 'a11y-widget' ); ?>">
                                        <p class="a11y-widget-admin-feedback-detail-link">
                                            <a class="button button-secondary button-small" href="<?php echo esc_url( a11y_widget_get_feedback_detail_admin_url( $feedback_item->ID, '' !== $status_filter ? array( 'feedback_status' => $status_filter ) : array() ) ); ?>">
                                                <?php esc_html_e( 'Voir le détail', 'a11y-widget' ); ?>
                                            </a>
                                        </p>
                                        <form class="a11y-widget-admin-feedback-action" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                                            <input type="hidden" name="action" value="a11y_widget_update_feedback_status" />
                                            <input type="hidden" name="feedback_id" value="<?php echo esc_attr( (string) $feedback_item->ID ); ?>" />
                                            <?php if ( '' !== $status_filter ) : ?>
                                                <input type="hidden" name="feedback_status_filter" value="<?php echo esc_attr( $status_filter ); ?>" />
                                            <?php endif; ?>
                                            <?php wp_nonce_field( 'a11y_widget_update_feedback_status_' . $feedback_item->ID ); ?>
                                            <label class="screen-reader-text" for="a11y-widget-feedback-status-<?php echo esc_attr( (string) $feedback_item->ID ); ?>">
                                                <?php esc_html_e( 'Changer le statut du retour', 'a11y-widget' ); ?>
                                            </label>
                                            <select id="a11y-widget-feedback-status-<?php echo esc_attr( (string) $feedback_item->ID ); ?>" name="feedback_status">
                                                <?php foreach ( $status_choices as $status_slug => $status_label ) : ?>
                                                    <option value="<?php echo esc_attr( $status_slug ); ?>" <?php selected( $status, $status_slug ); ?>>
                                                        <?php echo esc_html( $status_label ); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="button button-secondary button-small">
                                                <?php esc_html_e( 'Mettre à jour', 'a11y-widget' ); ?>
                                            </button>
                                        </form>
                                        <form
                                            class="a11y-widget-admin-feedback-action"
                                            method="post"
                                            action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                                            onsubmit="return window.confirm('<?php echo esc_js( __( 'Supprimer définitivement ce retour ?', 'a11y-widget' ) ); ?>');"
                                        >
                                            <input type="hidden" name="action" value="a11y_widget_delete_feedback" />
                                            <input type="hidden" name="feedback_id" value="<?php echo esc_attr( (string) $feedback_item->ID ); ?>" />
                                            <?php if ( '' !== $status_filter ) : ?>
                                                <input type="hidden" name="feedback_status_filter" value="<?php echo esc_attr( $status_filter ); ?>" />
                                            <?php endif; ?>
                                            <?php wp_nonce_field( 'a11y_widget_delete_feedback_' . $feedback_item->ID ); ?>
                                            <button type="submit" class="button button-link-delete">
                                                <?php esc_html_e( 'Supprimer', 'a11y-widget' ); ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
    <?php
}

/**
 * Remove disabled sub-features from a feature tree.
 *
 * @param array $feature          Feature definition.
 * @param array $disabled_lookup  Lookup of disabled slugs.
 *
 * @return array
 */
function a11y_widget_filter_disabled_subfeatures( $feature, $disabled_lookup ) {
    if ( empty( $feature['children'] ) || ! is_array( $feature['children'] ) ) {
        return $feature;
    }

    $filtered_children = array();

    foreach ( $feature['children'] as $child ) {
        if ( empty( $child['slug'] ) ) {
            continue;
        }

        $child_slug = sanitize_key( $child['slug'] );

        if ( '' === $child_slug || isset( $disabled_lookup[ $child_slug ] ) ) {
            continue;
        }

        if ( isset( $child['children'] ) && is_array( $child['children'] ) ) {
            $child = a11y_widget_filter_disabled_subfeatures( $child, $disabled_lookup );

            if ( empty( $child['children'] ) ) {
                unset( $child['children'] );
            }
        }

        $filtered_children[] = $child;
    }

    if ( empty( $filtered_children ) ) {
        unset( $feature['children'] );
    } else {
        $feature['children'] = array_values( $filtered_children );
    }

    return $feature;
}

/**
 * Remove disabled features from the sections used on the front-end.
 *
 * @param array $sections Sections passed to the template.
 *
 * @return array
 */
function a11y_widget_filter_disabled_features( $sections ) {
    if ( a11y_widget_force_all_features_enabled() ) {
        return $sections;
    }

    $doing_ajax = false;

    if ( function_exists( 'wp_doing_ajax' ) ) {
        $doing_ajax = wp_doing_ajax();
    } elseif ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        $doing_ajax = true;
    }

    if ( is_admin() && ! $doing_ajax ) {
        return $sections;
    }

    $disabled = a11y_widget_get_disabled_features();

    if ( empty( $disabled ) ) {
        return $sections;
    }

    $disabled_lookup = array_fill_keys( $disabled, true );
    $filtered        = array();

    foreach ( $sections as $section ) {
        if ( ! isset( $section['children'] ) || ! is_array( $section['children'] ) ) {
            continue;
        }

        $children = array();

        foreach ( $section['children'] as $feature ) {
            $slug = isset( $feature['slug'] ) ? sanitize_key( $feature['slug'] ) : '';

            if ( '' === $slug ) {
                continue;
            }

            if ( isset( $disabled_lookup[ $slug ] ) ) {
                continue;
            }

            if ( isset( $feature['children'] ) && is_array( $feature['children'] ) ) {
                $feature = a11y_widget_filter_disabled_subfeatures( $feature, $disabled_lookup );

                if ( empty( $feature['children'] ) ) {
                    continue;
                }
            }

            $children[] = $feature;
        }

        if ( empty( $children ) ) {
            continue;
        }

        $section['children'] = array_values( $children );
        $filtered[]           = $section;
    }

    return $filtered;
}
add_filter( 'a11y_widget_sections', 'a11y_widget_filter_disabled_features', 20 );
