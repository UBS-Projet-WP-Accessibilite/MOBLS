<?php
/**
 * Plugin Name: A11y Widget – Module de personnalisation d’accessibilité
 * Nom du projet : MOBLS (Masclef ; Oger ; Bouteloup ; Le Bout ; Sing Ling)
 * Description: Bouton flottant de personnalisation d’affichage et de confort de lecture. Ne remplace pas un audit RGAA/WCAG ni les technologies d’assistance. Shortcode: [a11y_widget]. 
 * Version: 1.5.0
 * Author: Amandine Bouteloup, Marie Le Bout, Noémie Masclef, Miranda Oger, Gaëlle Sing Ling
 * Organisation : Université Bretagne Sud
 * Professeur encadrant : Jérôme Le Gousse
 * IA utilisées : ChatGPT, Gemini, Mistral, Claude, Copilot 
 * License: GPL-2.0-or-later
 * Text Domain: a11y-widget
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'A11Y_WIDGET_VERSION', '1.5.0' );
define( 'A11Y_WIDGET_URL', plugin_dir_url( __FILE__ ) );
define( 'A11Y_WIDGET_PATH', plugin_dir_path( __FILE__ ) );
define( 'A11Y_WIDGET_FEEDBACK_RETENTION_HOOK', 'a11y_widget_daily_feedback_retention' );

/**
 * Schedule the daily retention purge for stored feedback entries.
 */
function a11y_widget_schedule_feedback_retention_event() {
    if ( ! wp_next_scheduled( A11Y_WIDGET_FEEDBACK_RETENTION_HOOK ) ) {
        wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', A11Y_WIDGET_FEEDBACK_RETENTION_HOOK );
    }
}

/**
 * Clear the daily retention purge from WP-Cron.
 */
function a11y_widget_unschedule_feedback_retention_event() {
    $timestamp = wp_next_scheduled( A11Y_WIDGET_FEEDBACK_RETENTION_HOOK );

    while ( false !== $timestamp ) {
        wp_unschedule_event( $timestamp, A11Y_WIDGET_FEEDBACK_RETENTION_HOOK );
        $timestamp = wp_next_scheduled( A11Y_WIDGET_FEEDBACK_RETENTION_HOOK );
    }
}

/**
 * Activation callback.
 */
function a11y_widget_activate() {
    a11y_widget_schedule_feedback_retention_event();
}

/**
 * Deactivation callback.
 */
function a11y_widget_deactivate() {
    a11y_widget_unschedule_feedback_retention_event();
}

/**
 * Uninstall callback.
 */
function a11y_widget_uninstall() {
    a11y_widget_unschedule_feedback_retention_event();
}

register_activation_hook( __FILE__, 'a11y_widget_activate' );
register_deactivation_hook( __FILE__, 'a11y_widget_deactivate' );
register_uninstall_hook( __FILE__, 'a11y_widget_uninstall' );
add_action( 'init', 'a11y_widget_schedule_feedback_retention_event' );

/**
 * Build a cache-busting asset version while keeping the public plugin version stable.
 *
 * @param string $relative_path Asset path relative to the plugin root.
 *
 * @return string
 */
function a11y_widget_get_asset_version( $relative_path ) {
    $relative_path = ltrim( (string) $relative_path, "/\\" );
    $asset_path     = A11Y_WIDGET_PATH . $relative_path;

    if ( file_exists( $asset_path ) ) {
        return A11Y_WIDGET_VERSION . '-' . filemtime( $asset_path );
    }

    return A11Y_WIDGET_VERSION;
}

/**
 * Retrieve inline SVG markup for a logo asset stored in the plugin.
 *
 * @param string $filename Logo filename relative to the plugin logo directory.
 *
 * @return string
 */
function a11y_widget_get_logo_svg_from_file( $filename ) {
    $filename = (string) $filename;

    if ( '' === $filename ) {
        return '';
    }

    if ( function_exists( 'trailingslashit' ) ) {
        $base_dir = trailingslashit( A11Y_WIDGET_PATH );
    } else {
        $base_dir = rtrim( A11Y_WIDGET_PATH, '/\\' ) . '/';
    }

    $path = $base_dir . 'logo/' . ltrim( $filename, '/\\' );

    if ( ! file_exists( $path ) || ! is_readable( $path ) ) {
        return '';
    }

    $svg = file_get_contents( $path );

    if ( false === $svg ) {
        return '';
    }

    $clean_svg = preg_replace( '/<\?xml[^>]*\?>\s*/', '', $svg );

    if ( is_string( $clean_svg ) ) {
        $svg = $clean_svg;
    }

    return trim( (string) $svg );
}

/**
 * Apply a subset of CSS declarations defined in <style> blocks directly on the matching SVG nodes.
 *
 * Some environments strip or ignore embedded <style> tags in inline SVGs. By duplicating the
 * declarations as presentation attributes we make sure the logo keeps its colors even when the
 * stylesheet is discarded.
 *
 * @param DOMDocument $dom Parsed SVG document.
 * @param string      $css Raw CSS extracted from a <style> element.
 *
 * @return void
 */
function a11y_widget_apply_inline_svg_styles( DOMDocument $dom, $css ) {
    $css = (string) $css;

    if ( '' === trim( $css ) ) {
        return;
    }

    if ( ! preg_match_all( '/\.([A-Za-z0-9_-]+)\s*\{([^}]*)\}/', $css, $matches, PREG_SET_ORDER ) ) {
        return;
    }

    $attribute_map = array(
        'fill'              => 'fill',
        'stroke'            => 'stroke',
        'stroke-width'      => 'stroke-width',
        'stroke-linecap'    => 'stroke-linecap',
        'stroke-linejoin'   => 'stroke-linejoin',
        'stroke-miterlimit' => 'stroke-miterlimit',
        'stroke-dasharray'  => 'stroke-dasharray',
        'stroke-dashoffset' => 'stroke-dashoffset',
        'fill-opacity'      => 'fill-opacity',
        'stroke-opacity'    => 'stroke-opacity',
        'opacity'           => 'opacity',
        'stop-color'        => 'stop-color',
        'stop-opacity'      => 'stop-opacity',
    );

    $rules = array();

    foreach ( $matches as $rule ) {
        if ( count( $rule ) < 3 ) {
            continue;
        }

        $class_name = trim( $rule[1] );
        $block      = trim( $rule[2] );

        if ( '' === $class_name || '' === $block ) {
            continue;
        }

        $declarations = array();
        $properties   = preg_split( '/;/', $block );

        foreach ( $properties as $property ) {
            $property = trim( $property );

            if ( '' === $property ) {
                continue;
            }

            $parts = explode( ':', $property, 2 );

            if ( 2 !== count( $parts ) ) {
                continue;
            }

            $name  = strtolower( trim( $parts[0] ) );
            $value = trim( $parts[1] );

            if ( '' === $value || ! isset( $attribute_map[ $name ] ) ) {
                continue;
            }

            $attribute = $attribute_map[ $name ];
            $declarations[ $attribute ] = $value;
        }

        if ( empty( $declarations ) ) {
            continue;
        }

        $rules[ $class_name ] = $declarations;
    }

    if ( empty( $rules ) ) {
        return;
    }

    foreach ( $dom->getElementsByTagName( '*' ) as $node ) {
        if ( ! $node instanceof DOMElement ) {
            continue;
        }

        if ( ! $node->hasAttribute( 'class' ) ) {
            continue;
        }

        $class_value = trim( $node->getAttribute( 'class' ) );

        if ( '' === $class_value ) {
            continue;
        }

        $class_names = preg_split( '/\s+/', $class_value );

        foreach ( (array) $class_names as $candidate ) {
            $candidate = trim( (string) $candidate );

            if ( '' === $candidate || ! isset( $rules[ $candidate ] ) ) {
                continue;
            }

            foreach ( $rules[ $candidate ] as $attribute => $value ) {
                if ( $node->hasAttribute( $attribute ) && '' !== trim( $node->getAttribute( $attribute ) ) ) {
                    continue;
                }

                $node->setAttribute( $attribute, $value );
            }
        }
    }
}

/**
 * Convert raw SVG markup to a data URI that can be used in an <img> tag.
 *
 * @param string $svg Raw SVG markup.
 *
 * @return string Data URI or empty string on failure.
 */
function a11y_widget_svg_markup_to_data_uri( $svg ) {
    $svg = trim( (string) $svg );

    if ( '' === $svg ) {
        return '';
    }

    if ( function_exists( 'mb_convert_encoding' ) ) {
        $encoded = @mb_convert_encoding( $svg, 'UTF-8', 'UTF-8' );
        if ( false !== $encoded && '' !== $encoded ) {
            $svg = $encoded;
        }
    }

    $base64 = base64_encode( $svg );

    if ( false === $base64 || '' === $base64 ) {
        return '';
    }

    return 'data:image/svg+xml;base64,' . $base64;
}

/**
 * Scope the IDs defined inside an SVG fragment to prevent collisions.
 *
 * When several inline SVGs reuse the same <defs> identifiers (gradients,
 * masks, etc.), only the first definition remains effective in the DOM.
 * This helper rewrites those identifiers so each fragment stays isolated.
 *
 * @param string $svg   Raw SVG markup.
 * @param string $scope Identifier prefix to apply to every ID.
 *
 * @return string
 */
function a11y_widget_scope_logo_svg_ids( $svg, $scope ) {
    $svg   = (string) $svg;
    $scope = (string) $scope;

    if ( '' === $svg ) {
        return '';
    }

    if ( ! class_exists( 'DOMDocument' ) || ! class_exists( 'DOMXPath' ) ) {
        return $svg;
    }

    if ( function_exists( 'sanitize_key' ) ) {
        $scope = sanitize_key( $scope );
    } else {
        $scope = strtolower( preg_replace( '/[^a-zA-Z0-9_-]+/', '-', $scope ) );
    }

    if ( '' === $scope ) {
        $scope = 'a11y-logo';
    }

    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = true;
    $dom->formatOutput       = false;

    $previous_state = libxml_use_internal_errors( true );
    $loaded         = $dom->loadXML( $svg );
    libxml_clear_errors();
    libxml_use_internal_errors( $previous_state );

    if ( ! $loaded || ! $dom->documentElement ) {
        return $svg;
    }

    $xpath = new DOMXPath( $dom );
    $nodes = $xpath->query( '//*[@id]' );

    if ( ! $nodes || 0 === $nodes->length ) {
        return $svg;
    }

    $map = array();

    foreach ( $nodes as $index => $node ) {
        if ( ! $node instanceof DOMElement ) {
            continue;
        }

        $original_id = $node->getAttribute( 'id' );

        if ( '' === $original_id ) {
            continue;
        }

        if ( function_exists( 'sanitize_key' ) ) {
            $normalized = sanitize_key( $original_id );
        } else {
            $normalized = strtolower( preg_replace( '/[^a-zA-Z0-9_-]+/', '-', $original_id ) );
        }

        if ( '' === $normalized ) {
            $normalized = 'fragment-' . ( $index + 1 );
        }

        $new_id = $scope . '-' . $normalized;

        $node->setAttribute( 'id', $new_id );
        $map[ $original_id ] = $new_id;
    }

    if ( empty( $map ) ) {
        return $svg;
    }

    $url_attributes = array(
        'fill',
        'stroke',
        'clip-path',
        'mask',
        'filter',
        'style',
        'marker-start',
        'marker-mid',
        'marker-end',
    );

    foreach ( $dom->getElementsByTagName( '*' ) as $element ) {
        if ( ! $element->hasAttributes() ) {
            continue;
        }

        $attributes = array();

        foreach ( $element->attributes as $attr ) {
            $attributes[] = $attr;
        }

        foreach ( $attributes as $attr ) {
            $name  = $attr->nodeName;
            $value = $attr->nodeValue;

            if ( '' === $value ) {
                continue;
            }

            $updated = $value;

            if ( 'aria-labelledby' === $name || 'aria-describedby' === $name ) {
                $parts = preg_split( '/[[:space:]]+/', trim( $value ) );
                if ( ! empty( $parts ) ) {
                    foreach ( $parts as $idx => $part ) {
                        if ( isset( $map[ $part ] ) ) {
                            $parts[ $idx ] = $map[ $part ];
                        }
                    }
                    $updated = implode( ' ', array_filter( $parts ) );
                }
            } elseif ( 'href' === $name || 'xlink:href' === $name ) {
                foreach ( $map as $from => $to ) {
                    if ( 0 === strpos( $updated, '#' . $from ) ) {
                        $updated = '#' . $to . substr( $updated, strlen( '#' . $from ) );
                        break;
                    }
                }
            } else {
                $needs_url_replacement = in_array( $name, $url_attributes, true ) || false !== strpos( $updated, 'url(#' );

                if ( $needs_url_replacement ) {
                    foreach ( $map as $from => $to ) {
                        if ( false === strpos( $updated, $from ) ) {
                            continue;
                        }

                        $double_quote = '"';
                        $single_quote = "'";

                        $updated = str_replace(
                            array(
                                'url(#' . $from . ')',
                                'url(' . $double_quote . '#' . $from . $double_quote . ')',
                                'url(' . $single_quote . '#' . $from . $single_quote . ')',
                            ),
                            array(
                                'url(#' . $to . ')',
                                'url(' . $double_quote . '#' . $to . $double_quote . ')',
                                'url(' . $single_quote . '#' . $to . $single_quote . ')',
                            ),
                            $updated
                        );
                    }
                }
            }

            if ( $updated !== $value ) {
                $attr->nodeValue = $updated;
            }
        }
    }

    $style_nodes = $dom->getElementsByTagName( 'style' );

    if ( $style_nodes && $style_nodes->length > 0 ) {
        foreach ( $style_nodes as $style_node ) {
            if ( ! $style_node->firstChild ) {
                continue;
            }

            $css_content = $style_node->textContent;

            if ( '' === $css_content ) {
                continue;
            }

            $final_css = $css_content;

            if ( ! empty( $map ) ) {
                $updated_css = $css_content;

                foreach ( $map as $from => $to ) {
                    $updated_css = str_replace(
                        array(
                            'url(#' . $from . ')',
                            'url("#' . $from . '")',
                            "url('#" . $from . "')",
                        ),
                        array(
                            'url(#' . $to . ')',
                            'url("#' . $to . '")',
                            "url('#" . $to . "')",
                        ),
                        $updated_css
                    );
                }

                if ( $updated_css !== $css_content ) {
                    while ( $style_node->firstChild ) {
                        $style_node->removeChild( $style_node->firstChild );
                    }

                    $style_node->appendChild( $dom->createTextNode( $updated_css ) );
                    $final_css = $updated_css;
                }
            }

            a11y_widget_apply_inline_svg_styles( $dom, $final_css );
        }
    }

    return $dom->saveXML( $dom->documentElement );
}

/**
 * Prepare SVG markup so that identifiers remain unique within the DOM.
 *
 * @param string      $svg     Raw SVG markup.
 * @param string      $slug    Logo slug used as part of the scope.
 * @param string|null $context Optional context identifier appended to the scope.
 *
 * @return string
 */
function a11y_widget_prepare_logo_svg_markup( $svg, $slug, $context = null ) {
    $svg  = (string) $svg;
    $slug = (string) $slug;

    if ( '' === $svg ) {
        return '';
    }

    if ( function_exists( 'sanitize_key' ) ) {
        $slug = sanitize_key( $slug );
    } else {
        $slug = strtolower( preg_replace( '/[^a-zA-Z0-9_-]+/', '-', $slug ) );
    }

    $parts = array( 'a11y', 'logo' );

    if ( '' !== $slug ) {
        $parts[] = $slug;
    }

    if ( null !== $context && '' !== $context ) {
        if ( function_exists( 'sanitize_key' ) ) {
            $context = sanitize_key( $context );
        } else {
            $context = strtolower( preg_replace( '/[^a-zA-Z0-9_-]+/', '-', $context ) );
        }

        if ( '' !== $context ) {
            $parts[] = $context;
        }
    }

    $base_scope = implode( '-', array_filter( $parts ) );

    if ( '' === $base_scope ) {
        $base_scope = 'a11y-logo';
    }

    if ( function_exists( 'wp_unique_id' ) ) {
        $scope = wp_unique_id( $base_scope . '-' );
    } else {
        $scope = $base_scope . '-' . uniqid( '', false );
    }

    return a11y_widget_scope_logo_svg_ids( $svg, $scope );
}

/**
 * Retrieve the available launcher logo variants.
 *
 * @return array<string, array{label:string, svg:string}>
 */
function a11y_widget_get_launcher_logo_variants() {
    static $variants = null;

    if ( null !== $variants ) {
        return $variants;
    }

    $path_markup = '<path fill="#ffffff" d="M12 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm6.75 6.5h-4.5v11a1 1 0 1 1-2 0v-5h-1v5a1 1 0 1 1-2 0v-11h-4.5a1 1 0 1 1 0-2h14a1 1 0 1 1 0 2Z" />';

    $variants = array(
        'bleu-vert' => array(
            'label' => __( 'Bleu-vert', 'a11y-widget' ),
            'svg'   => '<svg viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12" fill="#0ea5e9" />' . $path_markup . '</svg>',
        ),
        'bleu'      => array(
            'label' => __( 'Bleu', 'a11y-widget' ),
            'svg'   => '<svg viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12" fill="#2563eb" />' . $path_markup . '</svg>',
        ),
        'orange'    => array(
            'label' => __( 'Orange', 'a11y-widget' ),
            'svg'   => '<svg viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12" fill="#f97316" />' . $path_markup . '</svg>',
        ),
        'rouge'     => array(
            'label' => __( 'Rouge', 'a11y-widget' ),
            'svg'   => '<svg viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12" fill="#dc2626" />' . $path_markup . '</svg>',
        ),
        'vert'      => array(
            'label' => __( 'Vert', 'a11y-widget' ),
            'svg'   => '<svg viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12" fill="#22c55e" />' . $path_markup . '</svg>',
        ),
    );

    $logo_files = array(
        'bleu-vert' => 'logo_bleu-vert.svg',
        'bleu'      => 'logo_bleu.svg',
        'orange'    => 'logo_orange.svg',
        'rouge'     => 'logo_rouge.svg',
        'vert'      => 'logo_vert.svg',
    );

    foreach ( $logo_files as $slug => $filename ) {
        $file_markup = a11y_widget_get_logo_svg_from_file( $filename );

        if ( '' === $file_markup ) {
            continue;
        }

        if ( ! isset( $variants[ $slug ] ) || ! is_array( $variants[ $slug ] ) ) {
            $variants[ $slug ] = array();
        }

        $variants[ $slug ]['svg'] = $file_markup;
    }

    $variants = apply_filters( 'a11y_widget_launcher_logo_variants', $variants );

    if ( ! is_array( $variants ) ) {
        $variants = array();
    }

    $sanitized = array();

    foreach ( $variants as $slug => $data ) {
        if ( is_array( $data ) && isset( $data['slug'] ) ) {
            $slug = $data['slug'];
        }

        $slug = sanitize_key( (string) $slug );

        if ( '' === $slug ) {
            continue;
        }

        $label = '';
        $svg   = '';

        if ( is_array( $data ) ) {
            if ( isset( $data['label'] ) ) {
                $label = (string) $data['label'];
            }

            if ( isset( $data['svg'] ) ) {
                $svg = (string) $data['svg'];
            }
        } elseif ( is_string( $data ) ) {
            $svg = $data;
        }

        if ( '' === $svg ) {
            continue;
        }

        $sanitized[ $slug ] = array(
            'label' => $label,
            'svg'   => $svg,
        );
    }

    $variants = $sanitized;

    return $variants;
}

/**
 * Retrieve the default launcher logo slug.
 *
 * @return string
 */
function a11y_widget_get_launcher_logo_default() {
    return 'rouge';
}

/**
 * Helper that returns the option name used to store the selected launcher logo.
 *
 * @return string
 */
function a11y_widget_get_launcher_logo_option_name() {
    return 'a11y_widget_launcher_logo';
}

/**
 * Sanitize the launcher logo slug.
 *
 * @param mixed $value Raw value.
 *
 * @return string
 */
function a11y_widget_sanitize_launcher_logo( $value ) {
    if ( is_array( $value ) ) {
        $value = reset( $value );
    }

    $value   = sanitize_key( (string) $value );
    $choices = a11y_widget_get_launcher_logo_variants();

    if ( isset( $choices[ $value ] ) ) {
        return $value;
    }

    return a11y_widget_get_launcher_logo_default();
}

/**
 * Retrieve the sanitized launcher logo slug stored in the options table.
 *
 * @return string
 */
function a11y_widget_get_launcher_logo() {
    $option = get_option(
        a11y_widget_get_launcher_logo_option_name(),
        a11y_widget_get_launcher_logo_default()
    );

    return a11y_widget_sanitize_launcher_logo( $option );
}

/**
 * Default background mode applied when opening the accessibility panel.
 *
 * @return string
 */
function a11y_widget_get_background_mode_default() {
    return 'modal';
}

/**
 * Helper returning the option name storing the background mode value.
 *
 * @return string
 */
function a11y_widget_get_background_mode_option_name() {
    return 'a11y_widget_background_mode';
}

/**
 * Sanitize the chosen background mode.
 *
 * @param mixed $value Raw option value.
 *
 * @return string
 */
function a11y_widget_sanitize_background_mode( $value ) {
    if ( is_array( $value ) ) {
        $value = reset( $value );
    }

    $value   = sanitize_key( (string) $value );
    $choices = array( 'modal', 'interactive' );

    if ( in_array( $value, $choices, true ) ) {
        return $value;
    }

    return a11y_widget_get_background_mode_default();
}

/**
 * Retrieve the sanitized background mode stored in the database.
 *
 * @return string
 */
function a11y_widget_get_background_mode() {
    $option = get_option(
        a11y_widget_get_background_mode_option_name(),
        a11y_widget_get_background_mode_default()
    );

    return a11y_widget_sanitize_background_mode( $option );
}

/**
 * Option name helper for the accessibility statement metadata.
 *
 * @return string
 */
function a11y_widget_get_accessibility_statement_option_name() {
    return 'a11y_widget_accessibility_statement';
}

/**
 * Default accessibility statement metadata.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_accessibility_statement_default() {
    return array(
        'enabled'         => false,
        'declaration_url' => '',
        'audit_url'       => '',
        'audit_date'      => '',
        'audit_scope'     => '',
        'audit_status'    => 'not_assessed',
        'compliance_rate' => '',
        'auditor'         => '',
        'notes'           => '',
    );
}

/**
 * Return the project creators displayed in admin and public credits areas.
 *
 * @return array<int, string>
 */
function a11y_widget_get_project_creators() {
    return array(
        __( 'Amandine Bouteloup', 'a11y-widget' ),
        __( 'Marie Le Bout', 'a11y-widget' ),
        __( 'Noémie Masclef', 'a11y-widget' ),
        __( 'Miranda Oger', 'a11y-widget' ),
        __( 'Gaëlle Sing Ling', 'a11y-widget' ),
    );
}

/**
 * Status labels used by the accessibility statement settings.
 *
 * @return array<string, string>
 */
function a11y_widget_get_accessibility_statement_status_choices() {
    return array(
        'not_assessed'        => __( 'Non évalué', 'a11y-widget' ),
        'in_progress'         => __( 'Audit en cours', 'a11y-widget' ),
        'compliant'           => __( 'Totalement conforme', 'a11y-widget' ),
        'partially_compliant' => __( 'Partiellement conforme', 'a11y-widget' ),
        'non_compliant'       => __( 'Non conforme', 'a11y-widget' ),
    );
}

/**
 * Sanitize accessibility statement metadata.
 *
 * @param mixed $input Raw option value.
 *
 * @return array<string, mixed>
 */
function a11y_widget_sanitize_accessibility_statement_options( $input ) {
    $defaults = a11y_widget_get_accessibility_statement_default();

    if ( ! is_array( $input ) ) {
        $input = array();
    }

    $statuses = a11y_widget_get_accessibility_statement_status_choices();
    $status   = isset( $input['audit_status'] ) ? sanitize_key( (string) $input['audit_status'] ) : $defaults['audit_status'];

    if ( ! isset( $statuses[ $status ] ) ) {
        $status = $defaults['audit_status'];
    }

    $date = isset( $input['audit_date'] ) ? trim( (string) $input['audit_date'] ) : '';

    if ( '' !== $date && ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
        $date = '';
    }

    $rate = '';

    if ( isset( $input['compliance_rate'] ) && '' !== trim( (string) $input['compliance_rate'] ) ) {
        $normalized_rate = str_replace( ',', '.', trim( (string) $input['compliance_rate'] ) );

        if ( is_numeric( $normalized_rate ) ) {
            $rate_number = (float) $normalized_rate;
            $rate_number = max( 0, min( 100, $rate_number ) );
            $rate        = rtrim( rtrim( number_format( $rate_number, 1, '.', '' ), '0' ), '.' );
        }
    }

    return array(
        'enabled'         => ! empty( $input['enabled'] ),
        'declaration_url' => isset( $input['declaration_url'] ) ? esc_url_raw( (string) $input['declaration_url'] ) : '',
        'audit_url'       => isset( $input['audit_url'] ) ? esc_url_raw( (string) $input['audit_url'] ) : '',
        'audit_date'      => $date,
        'audit_scope'     => isset( $input['audit_scope'] ) ? sanitize_text_field( (string) $input['audit_scope'] ) : '',
        'audit_status'    => $status,
        'compliance_rate' => $rate,
        'auditor'         => isset( $input['auditor'] ) ? sanitize_text_field( (string) $input['auditor'] ) : '',
        'notes'           => isset( $input['notes'] ) ? sanitize_textarea_field( (string) $input['notes'] ) : '',
    );
}

/**
 * Retrieve sanitized accessibility statement metadata.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_accessibility_statement_options() {
    $stored = get_option(
        a11y_widget_get_accessibility_statement_option_name(),
        a11y_widget_get_accessibility_statement_default()
    );

    return a11y_widget_sanitize_accessibility_statement_options( $stored );
}

/**
 * Option name helper for backend feedback collection.
 *
 * @return string
 */
function a11y_widget_get_feedback_collection_option_name() {
    return 'a11y_widget_feedback_collection_enabled';
}

/**
 * Sanitize backend feedback collection setting.
 *
 * @param mixed $value Raw option value.
 *
 * @return bool
 */
function a11y_widget_sanitize_feedback_collection_enabled( $value ) {
    return ! empty( $value );
}

/**
 * Whether the widget may submit feedback to WordPress.
 *
 * @return bool
 */
function a11y_widget_feedback_collection_enabled() {
    return (bool) get_option( a11y_widget_get_feedback_collection_option_name(), false );
}

/**
 * Option name helper for backend feedback retention.
 *
 * @return string
 */
function a11y_widget_get_feedback_retention_option_name() {
    return 'a11y_widget_feedback_retention_days';
}

/**
 * Feedback retention choices.
 *
 * @return array<string, string>
 */
function a11y_widget_get_feedback_retention_choices() {
    return array(
        '30'        => __( '30 jours', 'a11y-widget' ),
        '90'        => __( '90 jours', 'a11y-widget' ),
        '180'       => __( '180 jours', 'a11y-widget' ),
        'unlimited' => __( 'Illimitée', 'a11y-widget' ),
    );
}

/**
 * Sanitize backend feedback retention.
 *
 * @param mixed $value Raw option value.
 *
 * @return string
 */
function a11y_widget_sanitize_feedback_retention_days( $value ) {
    $value   = sanitize_key( (string) $value );
    $choices = a11y_widget_get_feedback_retention_choices();

    return isset( $choices[ $value ] ) ? $value : '90';
}

/**
 * Return the configured feedback retention value.
 *
 * @return string
 */
function a11y_widget_get_feedback_retention_days() {
    return a11y_widget_sanitize_feedback_retention_days(
        get_option( a11y_widget_get_feedback_retention_option_name(), '90' )
    );
}

/**
 * Feedback post type slug.
 *
 * @return string
 */
function a11y_widget_get_feedback_post_type() {
    return 'a11y_widget_feedback';
}

/**
 * Register the private post type used to store widget feedback.
 */
function a11y_widget_register_feedback_post_type() {
    register_post_type(
        a11y_widget_get_feedback_post_type(),
        array(
            'labels'              => array(
                'name'          => __( 'Retours utilisateurs', 'a11y-widget' ),
                'singular_name' => __( 'Retour utilisateur', 'a11y-widget' ),
            ),
            'public'              => false,
            'show_ui'             => false,
            'show_in_menu'        => false,
            'show_in_rest'        => false,
            'exclude_from_search' => true,
            'supports'            => array( 'title', 'editor' ),
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
        )
    );
}
add_action( 'init', 'a11y_widget_register_feedback_post_type' );

/**
 * Feedback rating labels.
 *
 * @return array<string, string>
 */
function a11y_widget_get_feedback_rating_choices() {
    return array(
        'yes'     => __( 'Oui', 'a11y-widget' ),
        'partial' => __( 'Partiellement', 'a11y-widget' ),
        'no'      => __( 'Non', 'a11y-widget' ),
    );
}

/**
 * Feedback workflow status labels.
 *
 * @return array<string, string>
 */
function a11y_widget_get_feedback_status_choices() {
    return array(
        'new'      => __( 'Nouveau', 'a11y-widget' ),
        'seen'     => __( 'Vu', 'a11y-widget' ),
        'todo'     => __( 'À traiter', 'a11y-widget' ),
        'done'     => __( 'Traité', 'a11y-widget' ),
        'archived' => __( 'Archivé', 'a11y-widget' ),
    );
}

/**
 * Profile keys that may be reported by the public widget feedback form.
 *
 * @return array<string, string>
 */
function a11y_widget_get_feedback_profile_choices() {
    if ( function_exists( 'a11y_widget_get_enabled_profile_presets' ) ) {
        $choices = array();

        foreach ( a11y_widget_get_enabled_profile_presets() as $profile ) {
            $profile_key = isset( $profile['key'] ) ? sanitize_key( (string) $profile['key'] ) : '';
            $label       = isset( $profile['label'] ) ? trim( (string) $profile['label'] ) : '';

            if ( '' !== $profile_key && '' !== $label ) {
                $choices[ $profile_key ] = $label;
            }
        }

        if ( ! empty( $choices ) ) {
            return $choices;
        }
    }

    return array(
        'reading' => __( 'Lecture confortable', 'a11y-widget' ),
        'visual'  => __( 'Contraste renforcé', 'a11y-widget' ),
        'focus'   => __( 'Concentration', 'a11y-widget' ),
        'text'    => __( 'Texte seul', 'a11y-widget' ),
        'motor'   => __( 'Navigation facilitée', 'a11y-widget' ),
        'safety'  => __( 'Sécurité visuelle', 'a11y-widget' ),
    );
}

/**
 * Sanitize the active profile key submitted with feedback.
 *
 * @param mixed $value Raw profile key.
 *
 * @return string
 */
function a11y_widget_sanitize_feedback_profile( $value ) {
    $profile = sanitize_key( (string) $value );
    $choices = a11y_widget_get_feedback_profile_choices();

    return isset( $choices[ $profile ] ) ? $profile : '';
}

/**
 * Keep feedback page URLs scoped to the current WordPress site.
 *
 * @param mixed $value Raw page URL.
 *
 * @return string
 */
function a11y_widget_sanitize_feedback_page_url( $value ) {
    $url = esc_url_raw( trim( (string) $value ) );

    if ( '' === $url ) {
        return '';
    }

    if ( function_exists( 'mb_substr' ) ) {
        $url = mb_substr( $url, 0, 2048 );
    } else {
        $url = substr( $url, 0, 2048 );
    }

    $submitted_host = wp_parse_url( $url, PHP_URL_HOST );

    if ( empty( $submitted_host ) ) {
        return $url;
    }

    $allowed_hosts = array_filter(
        array_unique(
            array_map(
                'strtolower',
                array(
                    (string) wp_parse_url( home_url(), PHP_URL_HOST ),
                    (string) wp_parse_url( site_url(), PHP_URL_HOST ),
                )
            )
        )
    );

    return in_array( strtolower( (string) $submitted_host ), $allowed_hosts, true ) ? $url : '';
}

/**
 * Return a transient key for anonymous feedback rate limiting without storing raw IP data.
 *
 * @return string
 */
function a11y_widget_get_feedback_rate_limit_key() {
    $remote_addr = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
    $user_agent  = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';

    if ( function_exists( 'mb_substr' ) ) {
        $user_agent = mb_substr( $user_agent, 0, 200 );
    } else {
        $user_agent = substr( $user_agent, 0, 200 );
    }

    $fingerprint = trim( $remote_addr . '|' . $user_agent, '|' );

    if ( '' === $fingerprint ) {
        return '';
    }

    return 'a11y_w_feedback_rl_' . substr( hash_hmac( 'sha256', $fingerprint, wp_salt( 'nonce' ) ), 0, 32 );
}

/**
 * Whether the current visitor has submitted feedback too recently.
 *
 * @return bool
 */
function a11y_widget_feedback_is_rate_limited() {
    $key = a11y_widget_get_feedback_rate_limit_key();

    return '' !== $key && false !== get_transient( $key );
}

/**
 * Mark the current visitor as having submitted feedback recently.
 */
function a11y_widget_mark_feedback_rate_limit() {
    $key = a11y_widget_get_feedback_rate_limit_key();

    if ( '' !== $key ) {
        set_transient( $key, '1', MINUTE_IN_SECONDS );
    }
}

/**
 * Handle public widget feedback submission.
 */
function a11y_widget_handle_feedback_submission() {
    if ( ! a11y_widget_feedback_collection_enabled() ) {
        wp_send_json_error(
            array( 'message' => __( 'La collecte des retours n’est pas activée pour ce site.', 'a11y-widget' ) ),
            403
        );
    }

    $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'a11y_widget_submit_feedback' ) ) {
        wp_send_json_error(
            array( 'message' => __( 'La session a expiré. Rechargez la page puis réessayez.', 'a11y-widget' ) ),
            403
        );
    }

    $honeypot = isset( $_POST['website'] ) ? trim( (string) wp_unslash( $_POST['website'] ) ) : '';

    if ( '' !== $honeypot ) {
        wp_send_json_success(
            array( 'message' => __( 'Merci, votre retour a été transmis.', 'a11y-widget' ) )
        );
    }

    $consent = isset( $_POST['consent'] ) && '1' === (string) wp_unslash( $_POST['consent'] );

    if ( ! $consent ) {
        wp_send_json_error(
            array( 'message' => __( 'Confirmez l’envoi du retour avant de continuer.', 'a11y-widget' ) ),
            400
        );
    }

    $rating_choices = a11y_widget_get_feedback_rating_choices();
    $rating         = isset( $_POST['rating'] ) ? sanitize_key( (string) wp_unslash( $_POST['rating'] ) ) : '';

    if ( ! isset( $rating_choices[ $rating ] ) ) {
        wp_send_json_error(
            array( 'message' => __( 'Choisissez une réponse avant d’envoyer.', 'a11y-widget' ) ),
            400
        );
    }

    if ( a11y_widget_feedback_is_rate_limited() ) {
        wp_send_json_error(
            array( 'message' => __( 'Patientez quelques instants avant d’envoyer un nouveau retour.', 'a11y-widget' ) ),
            429
        );
    }

    $comment = isset( $_POST['comment'] ) ? sanitize_textarea_field( wp_unslash( $_POST['comment'] ) ) : '';

    if ( function_exists( 'mb_substr' ) ) {
        $comment = mb_substr( $comment, 0, 1000 );
    } else {
        $comment = substr( $comment, 0, 1000 );
    }

    $page_url = isset( $_POST['page_url'] ) ? a11y_widget_sanitize_feedback_page_url( wp_unslash( $_POST['page_url'] ) ) : '';
    $profile  = isset( $_POST['profile'] ) ? a11y_widget_sanitize_feedback_profile( wp_unslash( $_POST['profile'] ) ) : '';
    $features = array();

    if ( isset( $_POST['active_features'] ) ) {
        $active_features_raw = (string) wp_unslash( $_POST['active_features'] );

        if ( function_exists( 'mb_substr' ) ) {
            $active_features_raw = mb_substr( $active_features_raw, 0, 5000 );
        } else {
            $active_features_raw = substr( $active_features_raw, 0, 5000 );
        }

        $decoded = json_decode( $active_features_raw, true );

        if ( is_array( $decoded ) ) {
            foreach ( $decoded as $feature_slug ) {
                $feature_slug = sanitize_key( (string) $feature_slug );

                if ( '' !== $feature_slug ) {
                    $features[] = $feature_slug;
                }

                if ( count( $features ) >= 60 ) {
                    break;
                }
            }
        }
    }

    $post_id = wp_insert_post(
        array(
            'post_type'    => a11y_widget_get_feedback_post_type(),
            'post_status'  => 'private',
            'post_title'   => sprintf(
                /* translators: %s: feedback date */
                __( 'Retour widget - %s', 'a11y-widget' ),
                current_time( 'mysql' )
            ),
            'post_content' => $comment,
        ),
        true
    );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error(
            array( 'message' => __( 'Impossible d’enregistrer le retour pour le moment.', 'a11y-widget' ) ),
            500
        );
    }

    update_post_meta( $post_id, '_a11y_widget_feedback_rating', $rating );
    update_post_meta( $post_id, '_a11y_widget_feedback_page_url', $page_url );
    update_post_meta( $post_id, '_a11y_widget_feedback_profile', $profile );
    update_post_meta( $post_id, '_a11y_widget_feedback_features', $features );
    update_post_meta( $post_id, '_a11y_widget_feedback_status', 'new' );
    update_post_meta( $post_id, '_a11y_widget_feedback_created_at', current_time( 'mysql' ) );

    a11y_widget_mark_feedback_rate_limit();

    wp_send_json_success(
        array( 'message' => __( 'Merci, votre retour a été transmis.', 'a11y-widget' ) )
    );
}
add_action( 'wp_ajax_a11y_widget_submit_feedback', 'a11y_widget_handle_feedback_submission' );
add_action( 'wp_ajax_nopriv_a11y_widget_submit_feedback', 'a11y_widget_handle_feedback_submission' );

/**
 * Return the RGAA_Audit admin URL when the companion audit plugin is active.
 *
 * @return string
 */
function a11y_widget_get_rgaa_audit_admin_url() {
    $url = '';

    if ( defined( 'RGAA_AUDIT_VERSION' ) ) {
        $url = admin_url( 'admin.php?page=rgaa-audit' );
    }

    /**
     * Filter the RGAA_Audit admin URL used by the widget integration.
     *
     * Return an empty string to hide the companion-app link.
     *
     * @param string $url RGAA_Audit admin URL, or an empty string.
     */
    return (string) apply_filters( 'a11y_widget_rgaa_audit_admin_url', $url );
}



/**
 * Option name helper for visual customization settings.
 *
 * @return string
 */
function a11y_widget_get_visual_options_option_name() {
    return 'a11y_widget_visual_options';
}

/**
 * Default visual customization values.
 *
 * The CSS file keeps the MOBLS values as its baseline. These defaults are also used
 * to sanitize stored options and to render the administration form.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_visual_options_default() {
    return array(
        'theme'            => 'mobls',
        'primary'          => '#d61e5b',
        'primary_contrast' => '#ffffff',
        'surface'          => '#ffffff',
        'surface_elev'     => '#f7f7f8',
        'text'             => '#1e1e20',
        'text_subtle'      => '#4b5563',
        'border'           => '#e6e6ea',
        'radius'           => 16,
    );
}

/**
 * Built-in visual presets.
 *
 * @return array<string, array<string, mixed>>
 */
function a11y_widget_get_visual_theme_presets() {
    $mobls = a11y_widget_get_visual_options_default();

    return array(
        'mobls'     => array_merge(
            $mobls,
            array(
                'label'       => __( 'MOBLS', 'a11y-widget' ),
                'description' => __( 'Identité visuelle actuelle du module.', 'a11y-widget' ),
            )
        ),
        'wordpress' => array(
            'theme'            => 'wordpress',
            'label'            => __( 'WordPress neutre', 'a11y-widget' ),
            'description'      => __( 'Palette sobre, proche de l’administration WordPress moderne.', 'a11y-widget' ),
            'primary'          => '#3858e9',
            'primary_contrast' => '#ffffff',
            'surface'          => '#ffffff',
            'surface_elev'     => '#f6f7f7',
            'text'             => '#1e1e1e',
            'text_subtle'      => '#50575e',
            'border'           => '#dcdcde',
            'radius'           => 8,
        ),
        'custom'    => array_merge(
            $mobls,
            array(
                'theme'       => 'custom',
                'label'       => __( 'Personnalisé', 'a11y-widget' ),
                'description' => __( 'Utilise les couleurs définies dans les champs ci-dessous.', 'a11y-widget' ),
            )
        ),
    );
}

/**
 * Sanitize a hexadecimal color with a fallback.
 *
 * @param mixed  $value    Raw color value.
 * @param string $fallback Fallback color.
 *
 * @return string
 */
function a11y_widget_sanitize_hex_color_value( $value, $fallback ) {
    $fallback = (string) $fallback;
    $value    = is_scalar( $value ) ? trim( (string) $value ) : '';

    if ( function_exists( 'sanitize_hex_color' ) ) {
        $sanitized = sanitize_hex_color( $value );

        if ( is_string( $sanitized ) && '' !== $sanitized ) {
            return strtolower( $sanitized );
        }

        $fallback_sanitized = sanitize_hex_color( $fallback );

        return is_string( $fallback_sanitized ) && '' !== $fallback_sanitized
            ? strtolower( $fallback_sanitized )
            : '#000000';
    }

    if ( preg_match( '/^#[0-9a-fA-F]{6}$/', $value ) ) {
        return strtolower( $value );
    }

    return preg_match( '/^#[0-9a-fA-F]{6}$/', $fallback ) ? strtolower( $fallback ) : '#000000';
}

/**
 * Sanitize visual customization settings.
 *
 * @param mixed $input Raw option value.
 *
 * @return array<string, mixed>
 */
function a11y_widget_sanitize_visual_options( $input ) {
    $defaults = a11y_widget_get_visual_options_default();

    if ( ! is_array( $input ) ) {
        $input = array();
    }

    $presets = a11y_widget_get_visual_theme_presets();
    $theme   = isset( $input['theme'] ) ? sanitize_key( (string) $input['theme'] ) : $defaults['theme'];

    if ( ! isset( $presets[ $theme ] ) ) {
        $theme = $defaults['theme'];
    }

    $sanitized = array(
        'theme' => $theme,
    );

    $color_keys = array(
        'primary',
        'primary_contrast',
        'surface',
        'surface_elev',
        'text',
        'text_subtle',
        'border',
    );

    foreach ( $color_keys as $key ) {
        $fallback          = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '#000000';
        $sanitized[ $key ] = a11y_widget_sanitize_hex_color_value(
            isset( $input[ $key ] ) ? $input[ $key ] : $fallback,
            $fallback
        );
    }

    $radius = isset( $input['radius'] ) ? absint( $input['radius'] ) : (int) $defaults['radius'];

    if ( $radius < 0 ) {
        $radius = (int) $defaults['radius'];
    }

    if ( $radius > 32 ) {
        $radius = 32;
    }

    $sanitized['radius'] = $radius;

    return $sanitized;
}

/**
 * Retrieve stored visual customization settings.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_visual_options() {
    $stored = get_option(
        a11y_widget_get_visual_options_option_name(),
        a11y_widget_get_visual_options_default()
    );

    return a11y_widget_sanitize_visual_options( $stored );
}

/**
 * Resolve the active visual settings used on the front-end.
 *
 * @return array<string, mixed>
 */
function a11y_widget_get_active_visual_options() {
    $stored  = a11y_widget_get_visual_options();
    $presets = a11y_widget_get_visual_theme_presets();
    $theme   = isset( $stored['theme'] ) ? sanitize_key( (string) $stored['theme'] ) : 'mobls';

    if ( 'custom' === $theme ) {
        return $stored;
    }

    if ( isset( $presets[ $theme ] ) ) {
        return a11y_widget_sanitize_visual_options( $presets[ $theme ] );
    }

    return a11y_widget_get_visual_options_default();
}

/**
 * Build inline CSS variables for the selected visual theme.
 *
 * @return string
 */
function a11y_widget_get_visual_theme_inline_css() {
    $active = a11y_widget_get_active_visual_options();
    $theme  = isset( $active['theme'] ) ? sanitize_key( (string) $active['theme'] ) : 'mobls';

    if ( 'mobls' === $theme ) {
        return '';
    }

    $declarations = array(
        '--a11y-primary'          => $active['primary'],
        '--a11y-primary-contrast' => $active['primary_contrast'],
        '--a11y-surface'          => $active['surface'],
        '--a11y-surface-elev'     => $active['surface_elev'],
        '--a11y-text'             => $active['text'],
        '--a11y-text-subtle'      => $active['text_subtle'],
        '--a11y-border'           => $active['border'],
        '--a11y-radius'           => absint( $active['radius'] ) . 'px',
    );

    $css_parts = array();

    foreach ( $declarations as $name => $value ) {
        $css_parts[] = sprintf( '%s: %s;', $name, $value );
    }

    return sprintf(
        '#a11y-widget-root, #a11y-overlay { %s }',
        implode( ' ', $css_parts )
    );
}

/**
 * Retrieve the SVG markup for the given launcher logo.
 *
 * @param string|null $slug Logo slug. Defaults to the stored option.
 *
 * @return string
 */
function a11y_widget_get_launcher_logo_markup( $slug = null ) {
    $choices = a11y_widget_get_launcher_logo_variants();

    if ( null === $slug ) {
        $slug = a11y_widget_get_launcher_logo();
    } else {
        $slug = a11y_widget_sanitize_launcher_logo( $slug );
    }

    if ( isset( $choices[ $slug ] ) ) {
        return a11y_widget_prepare_logo_svg_markup( $choices[ $slug ]['svg'], $slug, 'launcher' );
    }

    $default = a11y_widget_get_launcher_logo_default();

    return isset( $choices[ $default ] )
        ? a11y_widget_prepare_logo_svg_markup( $choices[ $default ]['svg'], $default, 'launcher' )
        : '';
}

/**
 * Retrieve an <img> tag containing the launcher logo as a data URI.
 *
 * @param string|null $slug    Logo slug. Defaults to the stored option.
 * @param string      $context Context identifier appended to the SVG scope.
 *
 * @return string
 */
function a11y_widget_get_launcher_logo_image_markup( $slug = null, $context = 'launcher' ) {
    $choices = a11y_widget_get_launcher_logo_variants();

    if ( null === $slug ) {
        $slug = a11y_widget_get_launcher_logo();
    } else {
        $slug = a11y_widget_sanitize_launcher_logo( $slug );
    }

    $svg_markup = '';

    if ( isset( $choices[ $slug ] ) ) {
        $svg_markup = a11y_widget_prepare_logo_svg_markup( $choices[ $slug ]['svg'], $slug, $context . '-image' );
    } else {
        $default = a11y_widget_get_launcher_logo_default();

        if ( isset( $choices[ $default ] ) ) {
            $svg_markup = a11y_widget_prepare_logo_svg_markup( $choices[ $default ]['svg'], $default, $context . '-image' );
        }
    }

    if ( '' === $svg_markup ) {
        return '';
    }

    $data_uri = a11y_widget_svg_markup_to_data_uri( $svg_markup );

    if ( '' === $data_uri ) {
        return '';
    }

    $attributes = array(
        'src'         => $data_uri,
        'alt'         => '',
        'role'        => 'presentation',
        'aria-hidden' => 'true',
        'decoding'    => 'async',
        'draggable'   => 'false',
    );

    $parts = array();

    foreach ( $attributes as $name => $value ) {
        $value = (string) $value;

        if ( function_exists( 'esc_attr' ) ) {
            $value = esc_attr( $value );
        }

        $parts[] = sprintf( "%s=\"%s\"", $name, $value );
    }

    return '<img ' . implode( ' ', $parts ) . ' />';
}

/**
 * Enqueue front assets
 */
function a11y_widget_enqueue() {
    // Only load on front-end
    if ( is_admin() ) { return; }

    wp_enqueue_style(
        'a11y-widget',
        A11Y_WIDGET_URL . 'assets/widget.css',
        array(),
        a11y_widget_get_asset_version( 'assets/widget.css' )
    );

    if ( function_exists( 'a11y_widget_get_visual_theme_inline_css' ) ) {
        $visual_theme_css = a11y_widget_get_visual_theme_inline_css();

        if ( '' !== trim( $visual_theme_css ) ) {
            wp_add_inline_style( 'a11y-widget', $visual_theme_css );
        }
    }

    wp_enqueue_script(
        'a11y-widget',
        A11Y_WIDGET_URL . 'assets/widget.js',
        array(),
        a11y_widget_get_asset_version( 'assets/widget.js' ),
        true
    );

}
add_action( 'wp_enqueue_scripts', 'a11y_widget_enqueue' );

/**
 * Build the widget HTML once per page render.
 *
 * @return string Rendered widget markup, or an empty string if it has already been printed.
 */
function a11y_widget_get_markup_once() {
    if ( did_action( 'a11y_widget_printed' ) ) {
        return '';
    }

    ob_start();
    include A11Y_WIDGET_PATH . 'templates/widget.php';
    $html = ob_get_clean();

    /**
     * Filter: change/augment the HTML before output.
     */
    $html = apply_filters( 'a11y_widget_markup', $html );

    do_action( 'a11y_widget_printed' );

    return (string) $html;
}

/**
 * Render the widget HTML.
 */
function a11y_widget_markup() {
    echo a11y_widget_get_markup_once(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Default widget sections definition (hierarchical: level 1 categories + level 2 placeholders).
 *
 * @return array[]
 */
function a11y_widget_get_default_sections() {
    $heading_selector = function_exists( 'a11y_widget_get_reading_guide_heading_selector' )
        ? a11y_widget_get_reading_guide_heading_selector()
        : 'main h2, main h3';
    $syllable_selector = function_exists( 'a11y_widget_get_reading_guide_syllable_selector' )
        ? a11y_widget_get_reading_guide_syllable_selector()
        : 'main p, main li';

    return array(
        array(
            'slug'     => 'vision',
            'title'    => __( 'Vision', 'a11y-widget' ),
            'icon'     => 'eye',
            'children' => array(
                array(
                    'slug'       => 'vision-daltonisme',
                    'label'      => __( 'Daltonisme', 'a11y-widget' ),
                    'aria_label' => __( 'Options pour le daltonisme', 'a11y-widget' ),
                    'children'   => array(
                        array(
                            'slug'        => 'vision-daltonisme-deuteranopie',
                            'label'       => __( 'Deutéranopie', 'a11y-widget' ),
                            'aria_label'  => __( 'Activer le mode deutéranopie', 'a11y-widget' ),
                            'placeholder' => true,
                        ),
                        array(
                            'slug'        => 'vision-daltonisme-protanopie',
                            'label'       => __( 'Protanopie', 'a11y-widget' ),
                            'aria_label'  => __( 'Activer le mode protanopie', 'a11y-widget' ),
                            'placeholder' => true,
                        ),
                        array(
                            'slug'        => 'vision-daltonisme-deuteranomalie',
                            'label'       => __( 'Deutéranomalie', 'a11y-widget' ),
                            'aria_label'  => __( 'Activer le mode deutéranomalie', 'a11y-widget' ),
                            'placeholder' => true,
                        ),
                        array(
                            'slug'        => 'vision-daltonisme-protanomalie',
                            'label'       => __( 'Protanomalie', 'a11y-widget' ),
                            'aria_label'  => __( 'Activer le mode protanomalie', 'a11y-widget' ),
                            'placeholder' => true,
                        ),
                        array(
                            'slug'        => 'vision-daltonisme-tritanopie',
                            'label'       => __( 'Tritanopie', 'a11y-widget' ),
                            'aria_label'  => __( 'Activer le mode tritanopie', 'a11y-widget' ),
                            'placeholder' => true,
                        ),
                        array(
                            'slug'        => 'vision-daltonisme-tritanomalie',
                            'label'       => __( 'Tritanomalie', 'a11y-widget' ),
                            'aria_label'  => __( 'Activer le mode tritanomalie', 'a11y-widget' ),
                            'placeholder' => true,
                        ),
                        array(
                            'slug'        => 'vision-daltonisme-achromatopsie',
                            'label'       => __( 'Achromatopsie', 'a11y-widget' ),
                            'aria_label'  => __( 'Activer le mode achromatopsie', 'a11y-widget' ),
                            'placeholder' => true,
                        ),
                    ),
                ),
                array(
                    'slug'       => 'vision-migraine',
                    'label'      => __( 'Confort visuel – migraines', 'a11y-widget' ),
                    'hint'       => '',
                    'aria_label' => __( 'Configurer les réglages de confort visuel en cas de sensibilité ou migraine', 'a11y-widget' ),
                    'template'   => 'migraine-relief',
                    'settings'   => array(
                        'intro'                        => '',
                        'theme_label'                  => __( 'Thème de confort visuel', 'a11y-widget' ),
                        'theme_hint'                   => '',
                        'theme_option_none'            => __( 'Standard atténué', 'a11y-widget' ),
                        'theme_option_none_aria'       => __( 'Utiliser le rendu standard atténué', 'a11y-widget' ),
                        'theme_option_grayscale'       => __( 'Tons neutres', 'a11y-widget' ),
                        'theme_option_grayscale_aria'  => __( 'Passer en niveaux de gris doux', 'a11y-widget' ),
                        'theme_option_amber'           => __( 'Filtre ambré', 'a11y-widget' ),
                        'theme_option_amber_aria'      => __( 'Activer le filtre ambré pour réduire la lumière bleue', 'a11y-widget' ),
                        'intensity_label'              => __( 'Intensité du filtre ambré', 'a11y-widget' ),
                        'intensity_hint'               => '',
                        'intensity_value_suffix'       => __( '%', 'a11y-widget' ),
                        'intensity_decrease'           => __( 'Diminuer l’intensité', 'a11y-widget' ),
                        'intensity_increase'           => __( 'Augmenter l’intensité', 'a11y-widget' ),
                        'remove_patterns_label'        => __( 'Supprimer les motifs répétitifs', 'a11y-widget' ),
                        'remove_patterns_hint'         => '',
                        'increase_spacing_label'       => __( 'Espacement augmenté', 'a11y-widget' ),
                        'increase_spacing_hint'        => '',
                        'presets_label'                => __( 'Réglages rapides', 'a11y-widget' ),
                        'preset_mild_label'            => __( 'Confort doux', 'a11y-widget' ),
                        'preset_mild_hint'             => '',
                        'preset_moderate_label'        => __( 'Mode focus', 'a11y-widget' ),
                        'preset_moderate_hint'         => '',
                        'preset_strong_label'          => __( 'Atténuation renforcée', 'a11y-widget' ),
                        'preset_strong_hint'           => '',
                        'preset_crisis_label'          => __( 'Mode très atténué', 'a11y-widget' ),
                        'preset_crisis_hint'           => '',
                        'reset_label'                  => __( 'Réinitialiser le confort visuel', 'a11y-widget' ),
                        'reset_aria'                   => __( 'Réinitialiser toutes les options de confort visuel', 'a11y-widget' ),
                        'live_region_label'            => __( 'Notification des réglages de confort visuel', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'luminosite',
            'title'    => __( 'Luminosité', 'a11y-widget' ),
            'icon'     => 'sun',
            'children' => array(
                array(
                    'slug'       => 'luminosite-reglages',
                    'label'      => __( 'Modes de luminosité', 'a11y-widget' ),
                    'hint'       => '',
                    'aria_label' => __( 'Configurer les options de luminosité', 'a11y-widget' ),
                    'template'   => 'brightness-settings',
                    'settings'   => array(
                        'modes_label'             => __( 'Mode d’affichage', 'a11y-widget' ),
                        'modes_hint'              => '',
                        'mode_normal_label'       => __( 'Normal', 'a11y-widget' ),
                        'mode_normal_aria'        => __( 'Revenir au mode normal', 'a11y-widget' ),
                        'mode_night_label'        => __( 'Mode nuit', 'a11y-widget' ),
                        'mode_night_aria'         => __( 'Activer le mode nuit', 'a11y-widget' ),
                        'mode_blue_light_label'   => __( 'Lumière bleue', 'a11y-widget' ),
                        'mode_blue_light_aria'    => __( 'Activer le filtre anti lumière bleue', 'a11y-widget' ),
                        'mode_high_contrast_label'=> __( 'Contraste +', 'a11y-widget' ),
                        'mode_high_contrast_aria' => __( 'Activer le mode contraste élevé', 'a11y-widget' ),
                        'mode_low_contrast_label' => __( 'Contraste -', 'a11y-widget' ),
                        'mode_low_contrast_aria'  => __( 'Activer le mode contraste réduit', 'a11y-widget' ),
                        'mode_grayscale_label'    => __( 'Niveaux de gris', 'a11y-widget' ),
                        'mode_grayscale_aria'     => __( 'Activer le mode niveaux de gris', 'a11y-widget' ),
                        'advanced_label'          => __( 'Réglages avancés', 'a11y-widget' ),
                        'advanced_hint'           => '',
                        'contrast_label'          => __( 'Contraste', 'a11y-widget' ),
                        'contrast_decrease'       => __( 'Diminuer le contraste', 'a11y-widget' ),
                        'contrast_increase'       => __( 'Augmenter le contraste', 'a11y-widget' ),
                        'brightness_label'        => __( 'Luminosité', 'a11y-widget' ),
                        'brightness_decrease'     => __( 'Diminuer la luminosité', 'a11y-widget' ),
                        'brightness_increase'     => __( 'Augmenter la luminosité', 'a11y-widget' ),
                        'saturation_label'        => __( 'Saturation', 'a11y-widget' ),
                        'saturation_decrease'     => __( 'Diminuer la saturation', 'a11y-widget' ),
                        'saturation_increase'     => __( 'Augmenter la saturation', 'a11y-widget' ),
                        'reset_label'             => __( 'Réinitialiser', 'a11y-widget' ),
                        'reset_aria'              => __( 'Réinitialiser les réglages de luminosité', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'cognitif',
            'title'    => __( 'Cognitif', 'a11y-widget' ),
            'icon'     => 'brain',
            'children' => array(
                array(
                    'slug'       => 'cognitif-reading-guide',
                    'label'      => __( 'Guide de lecture', 'a11y-widget' ),
                    'hint'       => '',
                    'aria_label' => __( 'Activer le guide de lecture', 'a11y-widget' ),
                    'template'   => 'reading-guide',
                    'settings'   => array(
                        'rule_label'                     => __( 'Règle de lecture', 'a11y-widget' ),
                        'rule_hint'                      => '',
                        'mode_label'                     => __( 'Style de règle', 'a11y-widget' ),
                        'mode_bar_label'                 => __( 'Barre', 'a11y-widget' ),
                        'mode_lightbox_label'            => __( 'Focus', 'a11y-widget' ),
                        'mode_shade_label'               => __( 'Ombre', 'a11y-widget' ),
                        'mode_underline_label'           => __( 'Soulignement', 'a11y-widget' ),
                        'personalization_label'          => __( 'Personnalisation de la règle', 'a11y-widget' ),
                        'color_label'                    => __( 'Couleur', 'a11y-widget' ),
                        'color_hint'                     => '',
                        'opacity_label'                  => __( 'Opacité', 'a11y-widget' ),
                        'opacity_hint'                   => '',
                        'height_label'                   => __( 'Taille', 'a11y-widget' ),
                        'height_hint'                    => '',
                        'summary_label'                  => __( 'Sommaire automatique', 'a11y-widget' ),
                        'summary_close_label'            => __( 'Fermer le sommaire', 'a11y-widget' ),
                        'summary_hint'                   => '',
                        'summary_toggle_label'           => __( 'Activer le sommaire', 'a11y-widget' ),
                        'summary_title_default'          => __( 'Sommaire', 'a11y-widget' ),
                        'syllable_label'                 => __( 'Séparation syllabique', 'a11y-widget' ),
                        'syllable_hint'                  => '',
                        'syllable_toggle_label'          => __( 'Activer la séparation syllabique', 'a11y-widget' ),
                        'syllable_selector_label'        => __( 'Zones à syllaber', 'a11y-widget' ),
                        'syllable_selector_hint'         => '',
                        'syllable_selector_default'      => $syllable_selector,
                        'syllable_selector_placeholder'  => __( 'Ex. article p, article li', 'a11y-widget' ),
                        'focus_label'                    => __( 'Mode focus', 'a11y-widget' ),
                        'focus_hint'                     => '',
                        'focus_toggle_label'             => __( 'Activer le mode focus', 'a11y-widget' ),
                        'selectors'                      => array(
                            'headings'                      => $heading_selector,
                            'content_attribute'             => 'data-reading-guide-content',
                            'toc_attribute'                 => 'data-reading-guide-toc',
                            'toc_title_attribute'           => 'data-reading-guide-toc-title',
                            'syllable_attribute'            => 'data-reading-guide-syllables',
                            'animation_exempt_attribute'    => 'data-reading-guide-allow-animation',
                        ),
                    ),
                ),
                array(
                    'slug'       => 'cognitif-dyslexie',
                    'label'      => __( 'Dyslexie', 'a11y-widget' ),
                    'hint'       => __( 'Espacement, patterns et styles de texte comme point de départ personnalisable.', 'a11y-widget' ),
                    'aria_label' => __( 'Activer le surligneur de lettres', 'a11y-widget' ),
                    'template'   => 'dyslexie-highlighter',
                    'settings'   => array(
                        'letter_label'        => __( 'Caractères à surligner (jusqu’à 4, espaces compris)', 'a11y-widget' ),
                        'letter_placeholder'  => __( 'Entrez jusqu’à quatre caractères, espaces compris', 'a11y-widget' ),
                        'color_label'         => __( 'Couleur du surlignage', 'a11y-widget' ),
                        'accent_label'        => __( 'Prendre les accents en compte', 'a11y-widget' ),
                        'no_letter_warning'   => __( 'Choisissez un pattern ou saisissez jusqu’à quatre caractères pour commencer le surlignage.', 'a11y-widget' ),
                        'preset_label'        => __( 'Réglages rapides', 'a11y-widget' ),
                        'preset_soft_label'   => __( 'Aéré doux', 'a11y-widget' ),
                        'preset_strong_label' => __( 'Aéré fort', 'a11y-widget' ),
                        'preset_calm_label'   => __( 'Sans motifs', 'a11y-widget' ),
                        'pattern_label'       => __( 'Patterns de surlignage', 'a11y-widget' ),
                        'pattern_none_label'  => __( 'Aucun', 'a11y-widget' ),
                        'pattern_graphemes_label' => __( 'Graphèmes', 'a11y-widget' ),
                        'pattern_confusions_label' => __( 'Confusions', 'a11y-widget' ),
                        'pattern_morphemes_label' => __( 'Morphèmes', 'a11y-widget' ),
                        'font_label'          => __( 'Police du texte', 'a11y-widget' ),
                        'font_help'           => '',
                        'font_option_default' => __( 'Police du site', 'a11y-widget' ),
                        'font_option_arial'   => __( 'Arial', 'a11y-widget' ),
                        'font_option_verdana' => __( 'Verdana', 'a11y-widget' ),
                        'font_option_trebuchet' => __( 'Trebuchet MS', 'a11y-widget' ),
                        'font_option_comic'   => __( 'Comic Sans MS', 'a11y-widget' ),
                        'font_option_open'    => __( 'OpenDyslexic', 'a11y-widget' ),
                        'font_option_dyslexic' => __( 'OpenDyslexic Alta', 'a11y-widget' ),
                        'font_option_luciole' => __( 'Luciole', 'a11y-widget' ),
                        'font_option_atkinson' => __( 'Atkinson Hyperlegible', 'a11y-widget' ),
                        'font_option_inconstant' => __( 'Inconstant', 'a11y-widget' ),
                        'font_option_accessible_dfa' => __( 'Accessible DfA', 'a11y-widget' ),
                        'size_label'          => __( 'Taille du texte', 'a11y-widget' ),
                        'size_help'           => '',
                        'line_label'          => __( 'Espacement des lignes', 'a11y-widget' ),
                        'line_help'           => '',
                        'spacing_label'       => __( 'Espacement du texte', 'a11y-widget' ),
                        'letter_spacing_label'=> __( 'Lettres', 'a11y-widget' ),
                        'word_spacing_label'  => __( 'Mots', 'a11y-widget' ),
                        'paragraph_spacing_label' => __( 'Paragraphes', 'a11y-widget' ),
                        'line_length_label'   => __( 'Longueur de ligne', 'a11y-widget' ),
                        'align_left_label'    => __( 'Aligner à gauche', 'a11y-widget' ),
                        'remove_patterns_label' => __( 'Supprimer les motifs visuels', 'a11y-widget' ),
                        'styles_label'        => __( 'Styles du texte', 'a11y-widget' ),
                        'styles_help'         => '',
                        'disable_italic_label'=> __( 'Supprimer l’italique', 'a11y-widget' ),
                        'disable_bold_label'  => __( 'Réduire le gras excessif', 'a11y-widget' ),
                        'reset_label'         => __( 'Réinitialiser les réglages du texte', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'moteur',
            'title'    => __( 'Moteur', 'a11y-widget' ),
            'icon'     => 'hand',
            'children' => array(
                array(
                    'slug'       => 'moteur-boutons',
                    'label'      => __( 'Boutons', 'a11y-widget' ),
                    'hint'       => '',
                    'aria_label' => __( 'Configurer l’apparence des boutons', 'a11y-widget' ),
                    'template'   => 'button-settings',
                    'settings'   => array(
                        'size_label'  => __( 'Taille des boutons', 'a11y-widget' ),
                        'size_help'   => '',
                        'theme_label' => __( 'Couleurs des boutons', 'a11y-widget' ),
                        'theme_help'  => '',
                        'theme_prev'  => __( 'Thème précédent', 'a11y-widget' ),
                        'theme_next'  => __( 'Thème suivant', 'a11y-widget' ),
                        'reset_label' => __( 'Réinitialiser les boutons', 'a11y-widget' ),
                    ),
                ),
                array(
                    'slug'       => 'moteur-curseur',
                    'label'      => __( 'Curseur', 'a11y-widget' ),
                    'hint'       => '',
                    'aria_label' => __( 'Configurer le curseur personnalisé', 'a11y-widget' ),
                    'template'   => 'cursor-settings',
                    'settings'   => array(
                        'size_label' => __( 'Taille du curseur', 'a11y-widget' ),
                        'size_help'  => '',
                        'color_label' => __( 'Couleur du curseur', 'a11y-widget' ),
                        'color_help'  => '',
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'epilepsie',
            'title'    => __( 'Mouvement et flashs', 'a11y-widget' ),
            'icon'     => 'bolt',
            'children' => array(
                array(
                    'slug'       => 'epilepsie-protection',
                    'label'      => __( 'Réduction des déclencheurs visuels', 'a11y-widget' ),
                    'hint'       => __( 'Réduit certains déclencheurs visuels potentiels : animations rapides, GIFs, vidéos en autoplay, effets parallax et flashs lumineux.', 'a11y-widget' ),
                    'aria_label' => __( 'Activer la réduction des déclencheurs visuels', 'a11y-widget' ),
                    'template'   => 'epilepsy-protection',
                    'settings'   => array(
                        'intro'                    => __( 'Réduit certains déclencheurs visuels potentiels : animations rapides, GIFs, vidéos en autoplay, effets parallax et flashs lumineux. Ce module ne constitue pas un dispositif médical.', 'a11y-widget' ),
                        'stop_animations_label'    => __( 'Arrêter les animations', 'a11y-widget' ),
                        'stop_animations_hint'     => __( 'Force la durée des animations CSS et JavaScript à 0 s pour stopper les clignotements.', 'a11y-widget' ),
                        'stop_gifs_label'          => __( 'Figer les GIFs animés', 'a11y-widget' ),
                        'stop_gifs_hint'           => __( 'Capture la première image des GIFs et bloque ceux ajoutés dynamiquement.', 'a11y-widget' ),
                        'stop_videos_label'        => __( 'Bloquer l’autoplay des vidéos', 'a11y-widget' ),
                        'stop_videos_hint'         => __( 'Met en pause les vidéos et désactive l’autoplay sur YouTube/Vimeo.', 'a11y-widget' ),
                        'remove_parallax_label'    => __( 'Supprimer les effets parallax', 'a11y-widget' ),
                        'remove_parallax_hint'     => __( 'Neutralise les transformations 3D et les arrière-plans fixes.', 'a11y-widget' ),
                        'reduce_motion_label'      => __( 'Réduire tous les mouvements', 'a11y-widget' ),
                        'reduce_motion_hint'       => __( 'Force prefers-reduced-motion et désactive le défilement animé.', 'a11y-widget' ),
                        'block_flashing_label'     => __( 'Détecter et bloquer les flashs', 'a11y-widget' ),
                        'block_flashing_hint'      => __( 'Analyse la luminosité pour masquer temporairement la page en cas de flash ou variation lumineuse potentiellement déclencheuse.', 'a11y-widget' ),
                        'activate_all_label'       => __( 'Activer toutes les réductions', 'a11y-widget' ),
                        'activate_all_aria'        => __( 'Activer immédiatement toutes les réductions de déclencheurs visuels', 'a11y-widget' ),
                        'activate_all_confirm'     => __( 'Activer toutes les réductions ? Cela arrêtera animations, GIFs, vidéos, effets parallax, mouvements et flashs lumineux.', 'a11y-widget' ),
                        'reset_label'              => __( 'Réinitialiser les réductions', 'a11y-widget' ),
                        'reset_aria'               => __( 'Réinitialiser les réductions de déclencheurs visuels', 'a11y-widget' ),
                        'live_region_label'        => __( 'Notifications des réductions de déclencheurs visuels', 'a11y-widget' ),
                        'gif_placeholder_label'    => __( 'GIF animé désactivé', 'a11y-widget' ),
                        'gif_placeholder_hint'     => __( 'Un GIF animé a été masqué pour limiter les déclencheurs lumineux potentiels.', 'a11y-widget' ),
                        'flash_overlay_title'      => __( 'Variation lumineuse détectée', 'a11y-widget' ),
                        'flash_overlay_body'       => __( 'La page est temporairement masquée pour réduire l’exposition à un déclencheur visuel potentiel.', 'a11y-widget' ),
                        'flash_overlay_dismiss'    => __( 'Fermer l’alerte', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'braille',
            'title'    => __( 'Braille visuel', 'a11y-widget' ),
            'icon'     => 'braille',
            'children' => array(
                array(
                    'slug'       => 'braille-contracte',
                    'label'      => __( 'Braille contracté (démo visuelle)', 'a11y-widget' ),
                    'aria_label' => __( 'Activer le convertisseur visuel en braille contracté', 'a11y-widget' ),
                    'template'   => 'braille-translator',
                    'settings'   => array(
                        'mode'               => 'contracted',
                        'selection_label'    => __( 'Texte sélectionné', 'a11y-widget' ),
                        'selection_missing'  => __( 'Aucun texte n’est sélectionné pour le moment.', 'a11y-widget' ),
                        'selection_truncated' => __( 'Seuls les 600 premiers caractères de la sélection ont été traduits.', 'a11y-widget' ),
                        'result_label'       => __( 'Résultat', 'a11y-widget' ),
                        'result_aria'        => __( 'Transcription visuelle en braille contracté', 'a11y-widget' ),
                        'live_label'         => __( 'Annonce du résultat braille contracté visuel', 'a11y-widget' ),
                        'sr_result_prefix'   => __( 'Transcription visuelle braille contracté :', 'a11y-widget' ),
                        'sr_result_cleared'  => __( 'Transcription braille contracté effacée.', 'a11y-widget' ),
                        'viewer_title'       => __( 'Visionneuse Braille de MOBLS', 'a11y-widget' ),
                        'viewer_aria'        => __( 'Exemple visuel de visionneuse braille contractée', 'a11y-widget' ),
                        'viewer_example'     => __( 'MOBLS Montrer la visionneuse Braille au démarrage', 'a11y-widget' ),
                        'viewer_mode_label'  => __( 'Mode affiché', 'a11y-widget' ),
                        'viewer_mode_value'  => __( 'Braille contracté', 'a11y-widget' ),
                        'viewer_startup_label' => __( 'Montrer la visionneuse Braille au démarrage', 'a11y-widget' ),
                        'viewer_follow_label' => __( 'Suivi de la sélection vers une cellule', 'a11y-widget' ),
                        'viewer_copy_label'  => __( 'Copier la transcription braille', 'a11y-widget' ),
                        'viewer_copied_label' => __( 'Transcription braille copiée.', 'a11y-widget' ),
                        'viewer_large_label' => __( 'Agrandir les points braille', 'a11y-widget' ),
                        'viewer_normal_label' => __( 'Réduire les points braille', 'a11y-widget' ),
                        'viewer_detach_label' => __( 'Détacher la visionneuse Braille', 'a11y-widget' ),
                        'viewer_close_label' => __( 'Fermer la visionneuse Braille détachée', 'a11y-widget' ),
                    ),
                ),
                array(
                    'slug'       => 'braille-decontracte',
                    'label'      => __( 'Braille non contracté (démo visuelle)', 'a11y-widget' ),
                    'aria_label' => __( 'Activer le convertisseur visuel en braille non contracté', 'a11y-widget' ),
                    'template'   => 'braille-translator',
                    'settings'   => array(
                        'mode'               => 'uncontracted',
                        'selection_label'    => __( 'Texte sélectionné', 'a11y-widget' ),
                        'selection_missing'  => __( 'Aucun texte n’est sélectionné pour le moment.', 'a11y-widget' ),
                        'selection_truncated' => __( 'Seuls les 600 premiers caractères de la sélection ont été traduits.', 'a11y-widget' ),
                        'result_label'       => __( 'Résultat', 'a11y-widget' ),
                        'result_aria'        => __( 'Transcription visuelle en braille non contracté', 'a11y-widget' ),
                        'live_label'         => __( 'Annonce du résultat braille non contracté visuel', 'a11y-widget' ),
                        'sr_result_prefix'   => __( 'Transcription visuelle braille non contracté :', 'a11y-widget' ),
                        'sr_result_cleared'  => __( 'Transcription braille non contracté effacée.', 'a11y-widget' ),
                        'viewer_title'       => __( 'Visionneuse Braille de MOBLS', 'a11y-widget' ),
                        'viewer_aria'        => __( 'Exemple visuel de visionneuse braille non contractée', 'a11y-widget' ),
                        'viewer_example'     => __( 'MOBLS Montrer la visionneuse Braille au démarrage', 'a11y-widget' ),
                        'viewer_mode_label'  => __( 'Mode affiché', 'a11y-widget' ),
                        'viewer_mode_value'  => __( 'Braille non contracté', 'a11y-widget' ),
                        'viewer_startup_label' => __( 'Montrer la visionneuse Braille au démarrage', 'a11y-widget' ),
                        'viewer_follow_label' => __( 'Suivi de la sélection vers une cellule', 'a11y-widget' ),
                        'viewer_copy_label'  => __( 'Copier la transcription braille', 'a11y-widget' ),
                        'viewer_copied_label' => __( 'Transcription braille copiée.', 'a11y-widget' ),
                        'viewer_large_label' => __( 'Agrandir les points braille', 'a11y-widget' ),
                        'viewer_normal_label' => __( 'Réduire les points braille', 'a11y-widget' ),
                        'viewer_detach_label' => __( 'Détacher la visionneuse Braille', 'a11y-widget' ),
                        'viewer_close_label' => __( 'Fermer la visionneuse Braille détachée', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'audition',
            'title'    => __( 'Lecture audio', 'a11y-widget' ),
            'icon'     => 'ear',
            'children' => array(
                array(
                    'slug'       => 'audition-text-to-speech',
                    'label'      => __( 'Lecture à voix haute', 'a11y-widget' ),
                    'hint'       => __( 'Lecture de confort : ne remplace pas un lecteur d’écran.', 'a11y-widget' ),
                    'aria_label' => __( 'Activer la lecture de confort à voix haute', 'a11y-widget' ),
                    'template'   => 'text-to-speech',
                    'settings'   => array(
                        'intro'             => __( 'Lecture de confort pour écouter un texte sélectionné ou le contenu principal de la page. Ne remplace pas un lecteur d’écran.', 'a11y-widget' ),
                        'mode_label'        => __( 'Mode de lecture', 'a11y-widget' ),
                        'mode_hint'         => __( '« Sélection » lit uniquement le texte mis en évidence. « Page entière » lit tout le contenu principal.', 'a11y-widget' ),
                        'mode_selection'    => __( 'Sélection', 'a11y-widget' ),
                        'mode_page'         => __( 'Page entière', 'a11y-widget' ),
                        'controls_label'    => __( 'Contrôles', 'a11y-widget' ),
                        'play_label'        => __( 'Lire', 'a11y-widget' ),
                        'pause_label'       => __( 'Pause', 'a11y-widget' ),
                        'stop_label'        => __( 'Stop', 'a11y-widget' ),
                        'status_ready'      => __( 'Prêt à lire', 'a11y-widget' ),
                        'status_selection'  => __( 'Sélectionnez du texte puis appuyez sur « Lire ».', 'a11y-widget' ),
                        'status_page'       => __( 'Prêt à lire la page entière.', 'a11y-widget' ),
                        'status_playing'    => __( 'Lecture en cours…', 'a11y-widget' ),
                        'status_paused'     => __( 'Lecture en pause', 'a11y-widget' ),
                        'status_stopped'    => __( 'Lecture arrêtée', 'a11y-widget' ),
                        'status_finished'   => __( 'Lecture terminée', 'a11y-widget' ),
                        'status_error'      => __( 'Erreur de lecture', 'a11y-widget' ),
                        'volume_label'      => __( 'Volume', 'a11y-widget' ),
                        'volume_decrease'   => __( 'Diminuer le volume', 'a11y-widget' ),
                        'volume_increase'   => __( 'Augmenter le volume', 'a11y-widget' ),
                        'rate_label'        => __( 'Vitesse de lecture', 'a11y-widget' ),
                        'rate_hint'         => __( '0,5x = lent • 1x = normal • 2x = rapide', 'a11y-widget' ),
                        'rate_decrease'     => __( 'Diminuer la vitesse de lecture', 'a11y-widget' ),
                        'rate_increase'     => __( 'Augmenter la vitesse de lecture', 'a11y-widget' ),
                        'voice_label'       => __( 'Voix', 'a11y-widget' ),
                        'voice_hint'        => '',
                        'voice_default'     => __( 'Voix du navigateur (auto)', 'a11y-widget' ),
                        'voice_loading'     => __( 'Chargement des voix…', 'a11y-widget' ),
                        'info_tip'          => '',
                        'info_shortcuts'    => '',
                        'error_no_text'     => __( 'Aucun texte à lire pour le moment.', 'a11y-widget' ),
                        'unsupported'       => __( 'La synthèse vocale n’est pas disponible sur ce navigateur.', 'a11y-widget' ),
                        'announce_enabled'  => __( 'Lecture audio activée', 'a11y-widget' ),
                        'announce_disabled' => __( 'Lecture audio désactivée', 'a11y-widget' ),
                        'announce_started'  => __( 'Lecture démarrée', 'a11y-widget' ),
                        'announce_paused'   => __( 'Lecture en pause', 'a11y-widget' ),
                        'announce_resumed'  => __( 'Lecture reprise', 'a11y-widget' ),
                        'announce_stopped'  => __( 'Lecture arrêtée', 'a11y-widget' ),
                        'announce_finished' => __( 'Lecture terminée', 'a11y-widget' ),
                        'announce_voice'    => __( 'Voix changée', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
    );
}

/**
 * Retrieve the SVG markup for a named icon.
 *
 * Icon paths are adapted from the Lucide icon set (https://lucide.dev) and
 * distributed under the MIT License.
 *
 * @param string $icon_key Registered icon identifier.
 * @param array  $args     Optional arguments. Supports `class` for custom classes.
 *
 * @return string
 */
function a11y_widget_get_icon_markup( $icon_key, $args = array() ) {
    $icon_key = sanitize_key( $icon_key );

    if ( '' === $icon_key ) {
        return '';
    }

    $icons = array(
        'eye'    => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M2 12s4.5-6 10-6 10 6 10 6-4.5 6-10 6S2 12 2 12Z',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '12',
                    'cy'   => '12',
                    'r'    => '3',
                ),
            ),
        ),
        'sun'    => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'circle',
                    'cx'   => '12',
                    'cy'   => '12',
                    'r'    => '4',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M12 2v2',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M12 20v2',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M4.93 4.93L6.34 6.34',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M17.66 17.66L19.07 19.07',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M2 12h2',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M20 12h2',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M4.93 19.07L6.34 17.66',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M17.66 6.34L19.07 4.93',
                ),
            ),
        ),
        'book-open' => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M12 7v14',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M3 18a1 1 0 0 1 -1 -1V4a1 1 0 0 1 1 -1h5a4 4 0 0 1 4 4v14a4 4 0 0 0 -4 -4H3Z',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M21 18a1 1 0 0 0 1 -1V4a1 1 0 0 0 -1 -1h-5a4 4 0 0 0 -4 4',
                ),
            ),
        ),
        'sliders-horizontal' => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M21 4h-7',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M10 4H3',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '12',
                    'cy'   => '4',
                    'r'    => '2',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M21 12h-9',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M8 12H3',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '10',
                    'cy'   => '12',
                    'r'    => '2',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M21 20h-5',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M12 20H3',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '14',
                    'cy'   => '20',
                    'r'    => '2',
                ),
            ),
        ),
        'braille' => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'circle',
                    'cx'   => '7',
                    'cy'   => '7',
                    'r'    => '1.4',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '7',
                    'cy'   => '12',
                    'r'    => '1.4',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '7',
                    'cy'   => '17',
                    'r'    => '1.4',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '17',
                    'cy'   => '7',
                    'r'    => '1.4',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '17',
                    'cy'   => '12',
                    'r'    => '1.4',
                ),
                array(
                    'type' => 'circle',
                    'cx'   => '17',
                    'cy'   => '17',
                    'r'    => '1.4',
                ),
            ),
        ),
        'ear'    => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M6 10a7 7 0 1 1 13 3.6a10 10 0 0 1 -2 2a8 8 0 0 0 -2 3a4.5 4.5 0 0 1 -6.8 1.4',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M10 10a3 3 0 1 1 5 2.2',
                ),
            ),
        ),
        'message-circle' => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M21 11.5a8.4 8.4 0 0 1 -9 8.5a8.7 8.7 0 0 1 -4.2 -1.1L3 20l1.2 -4.1A8.3 8.3 0 0 1 3 11.5a8.5 8.5 0 0 1 18 0Z',
                ),
            ),
        ),
        'info' => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'circle',
                    'cx'   => '12',
                    'cy'   => '12',
                    'r'    => '9',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M12 11v5',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M12 8h.01',
                ),
            ),
        ),
        'rotate-ccw' => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M3 12a9 9 0 1 0 9 -9 9.75 9.75 0 0 0 -6.74 2.74L3 8',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M3 3v5h5',
                ),
            ),
        ),
        'x' => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M18 6 6 18',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M6 6l12 12',
                ),
            ),
        ),
        'brain'  => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M15.5 13a3.5 3.5 0 0 0 -3.5 3.5v1a3.5 3.5 0 0 0 7 0v-1.8',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M8.5 13a3.5 3.5 0 0 1 3.5 3.5v1a3.5 3.5 0 0 1 -7 0v-1.8',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M17.5 16a3.5 3.5 0 0 0 0 -7h-.5',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M19 9.3v-2.8a3.5 3.5 0 0 0 -7 0',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M6.5 16a3.5 3.5 0 0 1 0 -7h.5',
                ),
                array(
                    'type' => 'path',
                    'd'    => 'M5 9.3v-2.8a3.5 3.5 0 0 1 7 0v10',
                ),
            ),
        ),
        'hand'   => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M10.05 4.575a1.575 1.575 0 1 0 -3.15 0v3m 3.15 -3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m -3.15 0 .075 5.925m 3.075 .75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0 -3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712 -1.538l1.732 -1.732a5.25 5.25 0 0 0 1.538 -3.712l.003 -2.024a.668 .668 0 0 1 .198 -.471 1.575 1.575 0 1 0 -2.228 -2.228 3.818 3.818 0 0 0 -1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0 1 16.35 15',
                ),
            ),
        ),
        'bolt'   => array(
            'viewBox' => '0 0 24 24',
            'elements' => array(
                array(
                    'type' => 'path',
                    'd'    => 'M13 2 4 14h7l-1 8 9-12h-7l1-8Z',
                ),
            ),
        ),
    );

    if ( ! isset( $icons[ $icon_key ] ) ) {
        return '';
    }

    $icon   = $icons[ $icon_key ];
    $class  = '';
    $output = '';

    if ( ! empty( $args['class'] ) ) {
        $classes = is_array( $args['class'] ) ? $args['class'] : preg_split( '/\s+/', (string) $args['class'] );
        $classes = array_map( 'sanitize_html_class', array_filter( (array) $classes ) );
        if ( ! empty( $classes ) ) {
            $class = implode( ' ', $classes );
        }
    }

    ob_start();
    ?>
    <svg
        viewBox="<?php echo esc_attr( isset( $icon['viewBox'] ) ? $icon['viewBox'] : '0 0 24 24' ); ?>"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
        focusable="false"
        aria-hidden="true"
        <?php if ( '' !== $class ) : ?>class="<?php echo esc_attr( $class ); ?>"<?php endif; ?>
    >
        <?php foreach ( $icon['elements'] as $element ) :
            $type = isset( $element['type'] ) ? $element['type'] : '';

            if ( 'path' === $type && ! empty( $element['d'] ) ) :
                ?>
                <path d="<?php echo esc_attr( $element['d'] ); ?>" />
                <?php
            elseif ( 'circle' === $type && isset( $element['cx'], $element['cy'], $element['r'] ) ) :
                ?>
                <circle cx="<?php echo esc_attr( $element['cx'] ); ?>" cy="<?php echo esc_attr( $element['cy'] ); ?>" r="<?php echo esc_attr( $element['r'] ); ?>" />
                <?php
            endif;
        endforeach; ?>
    </svg>
    <?php
    $output = ob_get_clean();

    return trim( $output );
}

/**
 * Normalize nested children for a feature definition.
 *
 * @param array $feature Feature data.
 *
 * @return array
 */
function a11y_widget_normalize_nested_children( $feature ) {
    if ( ! is_array( $feature ) ) {
        return array();
    }

    if ( empty( $feature['children'] ) || ! is_array( $feature['children'] ) ) {
        if ( isset( $feature['children'] ) && ! is_array( $feature['children'] ) ) {
            unset( $feature['children'] );
        }

        return $feature;
    }

    $normalized_children = array();

    foreach ( $feature['children'] as $child ) {
        if ( ! is_array( $child ) || empty( $child['slug'] ) ) {
            continue;
        }

        $child_slug = sanitize_key( $child['slug'] );

        if ( '' === $child_slug ) {
            continue;
        }

        $child['slug'] = $child_slug;
        $normalized_children[] = a11y_widget_normalize_nested_children( $child );
    }

    $feature['children'] = $normalized_children;

    return $feature;
}

/**
 * Remove hint text from placeholder features, including nested children.
 *
 * A feature is considered a placeholder when it explicitly sets the
 * `placeholder` flag or when it originates from the Markdown directory and
 * does not define a custom template. These entries are meant to be wiring
 * stubs, so their descriptive text should remain hidden in the interface.
 *
 * @param array $feature Feature data.
 *
 * @return array
 */
function a11y_widget_strip_placeholder_hint_from_feature( $feature ) {
    if ( ! is_array( $feature ) ) {
        return $feature;
    }

    $has_placeholder_flag   = ! empty( $feature['placeholder'] );
    $has_markdown_origin    = isset( $feature['source'] );
    $defines_custom_template = isset( $feature['template'] ) && '' !== $feature['template'];

    if ( $has_placeholder_flag || ( $has_markdown_origin && ! $defines_custom_template ) ) {
        $feature['hint'] = '';
    }

    if ( ! empty( $feature['children'] ) && is_array( $feature['children'] ) ) {
        foreach ( $feature['children'] as $index => $child_feature ) {
            $feature['children'][ $index ] = a11y_widget_strip_placeholder_hint_from_feature( $child_feature );
        }
    }

    return $feature;
}

/**
 * Strip hint text from placeholder features within a collection of sections.
 *
 * @param array $sections Sections with their features.
 *
 * @return array
 */
function a11y_widget_strip_placeholder_hints( $sections ) {
    if ( empty( $sections ) || ! is_array( $sections ) ) {
        return $sections;
    }

    foreach ( $sections as $section_index => $section ) {
        if ( empty( $section['children'] ) || ! is_array( $section['children'] ) ) {
            continue;
        }

        foreach ( $section['children'] as $feature_index => $feature ) {
            $section['children'][ $feature_index ] = a11y_widget_strip_placeholder_hint_from_feature( $feature );
        }

        $sections[ $section_index ] = $section;
    }

    return $sections;
}

/**
 * Remove sections with no visible feature.
 *
 * @param array $sections Sections with children.
 *
 * @return array
 */
function a11y_widget_remove_empty_sections( $sections ) {
    if ( empty( $sections ) || ! is_array( $sections ) ) {
        return array();
    }

    $filtered = array();

    foreach ( $sections as $section ) {
        if ( empty( $section['children'] ) || ! is_array( $section['children'] ) ) {
            continue;
        }

        $filtered[] = $section;
    }

    return $filtered;
}

/**
 * Reclassify the default feature model into clearer user-facing categories.
 *
 * Existing feature slugs stay stable so stored preferences, disabled-feature
 * settings and shortcuts keep working across the new information architecture.
 *
 * @param array $sections Sections with children.
 *
 * @return array
 */
function a11y_widget_apply_phase2_section_model( $sections ) {
    if ( empty( $sections ) || ! is_array( $sections ) ) {
        return $sections;
    }

    $feature_map       = array();
    $feature_section   = array();
    $section_map       = array();
    $section_order     = array();
    $section_icon_map  = array();
    $section_title_map = array();

    foreach ( $sections as $section ) {
        if ( empty( $section['slug'] ) ) {
            continue;
        }

        $section_slug = sanitize_title( $section['slug'] );

        if ( '' === $section_slug ) {
            continue;
        }

        if ( ! isset( $section_map[ $section_slug ] ) ) {
            $section_map[ $section_slug ]       = $section;
            $section_order[]                    = $section_slug;
            $section_icon_map[ $section_slug ]  = isset( $section['icon'] ) ? sanitize_key( $section['icon'] ) : '';
            $section_title_map[ $section_slug ] = isset( $section['title'] ) ? $section['title'] : '';
        }

        if ( empty( $section['children'] ) || ! is_array( $section['children'] ) ) {
            continue;
        }

        foreach ( $section['children'] as $feature ) {
            if ( empty( $feature['slug'] ) ) {
                continue;
            }

            $feature_slug = sanitize_key( $feature['slug'] );

            if ( '' === $feature_slug || isset( $feature_map[ $feature_slug ] ) ) {
                continue;
            }

            $feature['slug']                = $feature_slug;
            $feature_map[ $feature_slug ]   = $feature;
            $feature_section[ $feature_slug ] = $section_slug;
        }
    }

    $statement_options = function_exists( 'a11y_widget_get_accessibility_statement_options' )
        ? a11y_widget_get_accessibility_statement_options()
        : array();
    $statement_status_choices = function_exists( 'a11y_widget_get_accessibility_statement_status_choices' )
        ? a11y_widget_get_accessibility_statement_status_choices()
        : array();
    $statement_status = isset( $statement_options['audit_status'] )
        ? sanitize_key( (string) $statement_options['audit_status'] )
        : 'not_assessed';
    $statement_status_label = isset( $statement_status_choices[ $statement_status ] )
        ? $statement_status_choices[ $statement_status ]
        : __( 'Non évalué', 'a11y-widget' );
    $feedback_collection_enabled = function_exists( 'a11y_widget_feedback_collection_enabled' )
        ? a11y_widget_feedback_collection_enabled()
        : false;

    $phase2_features = array(
        'profils-recommandes' => array(
            'slug'       => 'profils-recommandes',
            'label'      => __( 'Points de départ', 'a11y-widget' ),
            'hint'       => __( 'Choisissez un profil rapide, puis ajustez les réglages un par un.', 'a11y-widget' ),
            'aria_label' => __( 'Choisir un profil de réglages', 'a11y-widget' ),
            'template'   => 'profile-presets',
            'settings'   => array(
                'intro'                   => __( 'Choisissez un point de départ, puis ajustez les réglages un par un. Un profil ne remplace pas un choix utilisateur ni un diagnostic.', 'a11y-widget' ),
                'profile_reading_label'   => __( 'Lecture confortable', 'a11y-widget' ),
                'profile_reading_hint'    => __( 'Guide de lecture, espacement, patterns et lecture audio de confort.', 'a11y-widget' ),
                'profile_visual_label'    => __( 'Contraste renforcé', 'a11y-widget' ),
                'profile_visual_hint'     => __( 'Luminosité, contraste et atténuation visuelle comme point de départ.', 'a11y-widget' ),
                'profile_focus_label'     => __( 'Concentration', 'a11y-widget' ),
                'profile_focus_hint'      => __( 'Réduction des distractions et aide au suivi de lecture.', 'a11y-widget' ),
                'profile_text_label'      => __( 'Texte seul', 'a11y-widget' ),
                'profile_text_hint'       => __( 'Page recentrée sur le texte, avec images et médias masqués.', 'a11y-widget' ),
                'profile_motor_label'     => __( 'Navigation facilitée', 'a11y-widget' ),
                'profile_motor_hint'      => __( 'Boutons plus visibles et curseur renforcé.', 'a11y-widget' ),
                'profile_safety_label'    => __( 'Sécurité visuelle', 'a11y-widget' ),
                'profile_safety_hint'     => __( 'Réduction des animations, vidéos automatiques et déclencheurs visuels potentiels.', 'a11y-widget' ),
                'apply_label'             => __( 'Appliquer', 'a11y-widget' ),
                'active_profile_label'    => __( 'Profil appliqué', 'a11y-widget' ),
                'partial_profile_label'   => __( 'Profil partiellement actif', 'a11y-widget' ),
                'active_badge_label'      => __( 'Actif', 'a11y-widget' ),
                'partial_badge_label'     => __( 'Partiel', 'a11y-widget' ),
                'clear_label'             => __( 'Désactiver ce profil', 'a11y-widget' ),
                'applied_status'          => __( 'Profil appliqué.', 'a11y-widget' ),
                'cleared_status'          => __( 'Profil désactivé.', 'a11y-widget' ),
                'last_profile_label'      => __( 'Dernier profil appliqué', 'a11y-widget' ),
                'live_region_label'       => __( 'Notifications des profils de réglages', 'a11y-widget' ),
                'profiles'                => function_exists( 'a11y_widget_get_enabled_profile_presets' ) ? a11y_widget_get_enabled_profile_presets() : array(),
            ),
        ),
        'feedback-utilisateur' => array(
            'slug'       => 'feedback-utilisateur',
            'label'      => __( 'Retour utilisateur', 'a11y-widget' ),
            'hint'       => __( 'Envoyer un retour à l’équipe du site lorsque la collecte est activée.', 'a11y-widget' ),
            'aria_label' => __( 'Donner un retour sur les réglages du widget', 'a11y-widget' ),
            'template'   => 'user-feedback',
            'settings'   => array(
                'intro'                => __( 'Ce retour est transmis à l’administration WordPress du site pour aider l’équipe à améliorer le module.', 'a11y-widget' ),
                'rating_label'         => __( 'Le réglage vous aide-t-il ?', 'a11y-widget' ),
                'rating_yes'           => __( 'Oui', 'a11y-widget' ),
                'rating_partial'       => __( 'Partiellement', 'a11y-widget' ),
                'rating_no'            => __( 'Non', 'a11y-widget' ),
                'comment_label'        => __( 'Commentaire', 'a11y-widget' ),
                'comment_placeholder'  => __( 'Ex. le contraste aide, mais les images restent gênantes.', 'a11y-widget' ),
                'save_label'           => __( 'Envoyer', 'a11y-widget' ),
                'clear_label'          => __( 'Effacer', 'a11y-widget' ),
                'sending_status'       => __( 'Envoi du retour…', 'a11y-widget' ),
                'saved_status'         => __( 'Merci, votre retour a été transmis.', 'a11y-widget' ),
                'cleared_status'       => __( 'Retour effacé.', 'a11y-widget' ),
                'submit_error_status'  => __( 'Impossible d’envoyer le retour pour le moment.', 'a11y-widget' ),
                'empty_status'         => __( 'Choisissez une réponse avant d’envoyer.', 'a11y-widget' ),
                'consent_status'       => __( 'Confirmez l’envoi du retour avant de continuer.', 'a11y-widget' ),
                'consent_label'        => __( 'J’accepte d’envoyer ce retour à l’équipe du site.', 'a11y-widget' ),
                'privacy_note'         => __( 'Le retour est stocké côté WordPress avec la page concernée et les réglages actifs, sans adresse IP enregistrée par ce module.', 'a11y-widget' ),
                'submit_endpoint'      => admin_url( 'admin-ajax.php' ),
                'submit_action'        => 'a11y_widget_submit_feedback',
                'submit_nonce'         => wp_create_nonce( 'a11y_widget_submit_feedback' ),
                'live_region_label'    => __( 'Notifications du retour utilisateur', 'a11y-widget' ),
            ),
        ),
        'lecture-texte-seul' => array(
            'slug'       => 'lecture-texte-seul',
            'label'      => __( 'Mode texte seul', 'a11y-widget' ),
            'hint'       => __( 'Masque les médias et neutralise les décors visuels pour revenir à une page centrée sur le texte.', 'a11y-widget' ),
            'aria_label' => __( 'Activer le mode texte seul', 'a11y-widget' ),
        ),
        'lecture-structure-page' => array(
            'slug'       => 'lecture-structure-page',
            'label'      => __( 'Structure de page', 'a11y-widget' ),
            'hint'       => __( 'Affiche les titres et repères de page pour comprendre rapidement l’organisation du contenu.', 'a11y-widget' ),
            'aria_label' => __( 'Afficher la structure de la page', 'a11y-widget' ),
            'template'   => 'page-structure',
            'settings'   => array(
                'intro'              => __( 'Cette vue liste les titres et repères détectés dans la page. Elle aide à s’orienter, sans remplacer une vérification de structure HTML.', 'a11y-widget' ),
                'headings_label'     => __( 'Titres', 'a11y-widget' ),
                'landmarks_label'    => __( 'Repères', 'a11y-widget' ),
                'refresh_label'      => __( 'Actualiser', 'a11y-widget' ),
                'go_label'           => __( 'Aller', 'a11y-widget' ),
                'empty_headings'     => __( 'Aucun titre détecté dans le contenu de la page.', 'a11y-widget' ),
                'empty_landmarks'    => __( 'Aucun repère principal détecté dans la page.', 'a11y-widget' ),
                'summary_label'      => __( 'Résumé de structure', 'a11y-widget' ),
                'heading_level_text' => __( 'Niveau', 'a11y-widget' ),
                'live_region_label'  => __( 'Notifications de la structure de page', 'a11y-widget' ),
            ),
        ),
        'lecture-masquer-images' => array(
            'slug'       => 'lecture-masquer-images',
            'label'      => __( 'Masquer les images', 'a11y-widget' ),
            'hint'       => __( 'Masque les images, vidéos et visuels de confort sans modifier le contenu source.', 'a11y-widget' ),
            'aria_label' => __( 'Masquer les images de la page', 'a11y-widget' ),
        ),
        'cognitif-dictionnaire-glossaire' => array(
            'slug'       => 'cognitif-dictionnaire-glossaire',
            'label'      => __( 'Dictionnaire / glossaire', 'a11y-widget' ),
            'hint'       => __( 'Explique des termes fréquents et les abréviations présentes dans la page, sans service externe.', 'a11y-widget' ),
            'aria_label' => __( 'Ouvrir le dictionnaire et le glossaire', 'a11y-widget' ),
            'template'   => 'dictionary-glossary',
            'settings'   => array(
                'intro'                 => __( 'Le glossaire utilise des définitions intégrées et les abréviations ou définitions présentes dans la page. Aucune requête externe n’est envoyée.', 'a11y-widget' ),
                'search_label'          => __( 'Terme à chercher', 'a11y-widget' ),
                'search_placeholder'    => __( 'Ex. RGAA, ARIA, focus…', 'a11y-widget' ),
                'selection_label'       => __( 'Utiliser la sélection', 'a11y-widget' ),
                'clear_label'           => __( 'Effacer', 'a11y-widget' ),
                'builtin_label'         => __( 'Glossaire intégré', 'a11y-widget' ),
                'page_label'            => __( 'Termes trouvés dans la page', 'a11y-widget' ),
                'empty_label'           => __( 'Aucun terme correspondant pour le moment.', 'a11y-widget' ),
                'no_selection_status'   => __( 'Sélectionnez un mot dans la page, puis réessayez.', 'a11y-widget' ),
                'results_status'        => __( 'Résultats du glossaire mis à jour.', 'a11y-widget' ),
                'live_region_label'     => __( 'Notifications du dictionnaire et glossaire', 'a11y-widget' ),
            ),
        ),
        'cognitif-reduire-distractions' => array(
            'slug'       => 'cognitif-reduire-distractions',
            'label'      => __( 'Réduire les distractions', 'a11y-widget' ),
            'hint'       => __( 'Atténue les zones périphériques pour aider à rester concentré sur le contenu principal.', 'a11y-widget' ),
            'aria_label' => __( 'Activer la réduction des distractions visuelles', 'a11y-widget' ),
        ),
    );

    if ( ! $feedback_collection_enabled ) {
        unset( $phase2_features['feedback-utilisateur'] );
    }

    if ( ! empty( $statement_options['enabled'] ) && ! empty( $statement_options['declaration_url'] ) ) {
        $phase2_features['declaration-accessibilite'] = array(
            'slug'       => 'declaration-accessibilite',
            'label'      => __( 'Déclaration d’accessibilité', 'a11y-widget' ),
            'hint'       => __( 'Consulter la déclaration publiée et un résumé administrateur, sans grille d’audit dans le widget.', 'a11y-widget' ),
            'aria_label' => __( 'Consulter la déclaration d’accessibilité du site', 'a11y-widget' ),
            'template'   => 'accessibility-statement',
            'settings'   => array(
                'enabled'          => '1',
                'intro'            => __( 'Cette carte donne accès à la déclaration publique. Le suivi détaillé de l’audit reste dans l’administration du site.', 'a11y-widget' ),
                'not_configured'   => __( 'La déclaration d’accessibilité n’est pas encore configurée pour ce site.', 'a11y-widget' ),
                'source_note'      => __( 'Les informations affichées ici sont déclaratives et doivent être vérifiées dans la déclaration officielle.', 'a11y-widget' ),
                'declaration_url'  => isset( $statement_options['declaration_url'] ) ? (string) $statement_options['declaration_url'] : '',
                'audit_url'        => isset( $statement_options['audit_url'] ) ? (string) $statement_options['audit_url'] : '',
                'audit_date'       => isset( $statement_options['audit_date'] ) ? (string) $statement_options['audit_date'] : '',
                'audit_scope'      => isset( $statement_options['audit_scope'] ) ? (string) $statement_options['audit_scope'] : '',
                'audit_status'     => $statement_status,
                'audit_status_text' => $statement_status_label,
                'compliance_rate'  => isset( $statement_options['compliance_rate'] ) ? (string) $statement_options['compliance_rate'] : '',
                'auditor'          => isset( $statement_options['auditor'] ) ? (string) $statement_options['auditor'] : '',
                'notes'            => isset( $statement_options['notes'] ) ? (string) $statement_options['notes'] : '',
                'status_label'     => __( 'Statut déclaré', 'a11y-widget' ),
                'date_label'       => __( 'Date de l’audit', 'a11y-widget' ),
                'scope_label'      => __( 'Périmètre', 'a11y-widget' ),
                'rate_label'       => __( 'Taux indiqué', 'a11y-widget' ),
                'auditor_label'    => __( 'Réalisé par', 'a11y-widget' ),
                'notes_label'      => __( 'Notes', 'a11y-widget' ),
                'declaration_label' => __( 'Ouvrir la déclaration', 'a11y-widget' ),
                'audit_label'      => __( 'Ouvrir l’audit', 'a11y-widget' ),
                'percent_suffix'   => __( '%', 'a11y-widget' ),
            ),
        );

        $phase2_features['credits-application'] = array(
            'slug'       => 'credits-application',
            'label'      => __( 'Créatrices de l’application', 'a11y-widget' ),
            'hint'       => __( 'Afficher les noms des créatrices du module MOBLS.', 'a11y-widget' ),
            'aria_label' => __( 'Consulter les créatrices de l’application', 'a11y-widget' ),
            'template'   => 'project-credits',
            'settings'   => array(
                'intro'          => __( 'Ce module a été conçu et réalisé dans le cadre du projet MOBLS.', 'a11y-widget' ),
                'credits_label'  => __( 'Créatrices de l’application', 'a11y-widget' ),
                'credits_people' => a11y_widget_get_project_creators(),
                'credits_names'  => implode( ', ', a11y_widget_get_project_creators() ),
            ),
        );
    }

    foreach ( $phase2_features as $feature_slug => $feature ) {
        if ( ! isset( $feature_map[ $feature_slug ] ) ) {
            $feature_map[ $feature_slug ]     = $feature;
            $feature_section[ $feature_slug ] = '';
        }
    }

    if ( isset( $feature_map['luminosite-reglages'] ) ) {
        $feature_map['luminosite-reglages']['label'] = __( 'Luminosité et contrastes', 'a11y-widget' );
        $feature_map['luminosite-reglages']['hint']  = __( 'Modes nuit, lumière bleue, contrastes, niveaux de gris et réglages fins.', 'a11y-widget' );
    }

    if ( isset( $feature_map['cognitif-reading-guide'] ) ) {
        $feature_map['cognitif-reading-guide']['label'] = __( 'Guide de lecture et structure', 'a11y-widget' );
        $feature_map['cognitif-reading-guide']['hint']  = __( 'Règle de lecture, sommaire automatique, séparation syllabique et mode focus.', 'a11y-widget' );
    }

    if ( isset( $feature_map['cognitif-dyslexie'] ) ) {
        $feature_map['cognitif-dyslexie']['label'] = __( 'Aides à la lecture dyslexie', 'a11y-widget' );
        $feature_map['cognitif-dyslexie']['hint']  = __( 'Police, espacement, patterns, suppression de motifs et surlignage personnalisable.', 'a11y-widget' );
    }

    if ( isset( $feature_map['braille-contracte'] ) ) {
        $feature_map['braille-contracte']['label'] = __( 'Braille visuel contracté', 'a11y-widget' );
        $feature_map['braille-contracte']['hint']  = __( 'Démonstration en caractères braille Unicode. Ne pilote pas une plage braille matérielle.', 'a11y-widget' );
    }

    if ( isset( $feature_map['braille-decontracte'] ) ) {
        $feature_map['braille-decontracte']['label'] = __( 'Braille visuel non contracté', 'a11y-widget' );
        $feature_map['braille-decontracte']['hint']  = __( 'Démonstration en caractères braille Unicode. Ne pilote pas une plage braille matérielle.', 'a11y-widget' );
    }

    $model = array(
        array(
            'slug'     => 'profils',
            'title'    => __( 'Profils rapides', 'a11y-widget' ),
            'icon'     => 'sliders-horizontal',
            'children' => array(
                'profils-recommandes',
            ),
        ),
        array(
            'slug'     => 'vision',
            'title'    => __( 'Vision', 'a11y-widget' ),
            'icon'     => 'eye',
            'children' => array(
                'vision-daltonisme',
                'luminosite-reglages',
                'vision-migraine',
            ),
        ),
        array(
            'slug'     => 'lecture',
            'title'    => __( 'Lecture', 'a11y-widget' ),
            'icon'     => 'book-open',
            'children' => array(
                'cognitif-reading-guide',
                'lecture-structure-page',
                'cognitif-dyslexie',
                'lecture-texte-seul',
                'lecture-masquer-images',
                'braille-contracte',
                'braille-decontracte',
            ),
        ),
        array(
            'slug'     => 'cognitif',
            'title'    => __( 'Cognitif', 'a11y-widget' ),
            'icon'     => 'brain',
            'children' => array(
                'cognitif-dictionnaire-glossaire',
                'cognitif-reduire-distractions',
            ),
        ),
        array(
            'slug'     => 'moteur',
            'title'    => __( 'Moteur', 'a11y-widget' ),
            'icon'     => 'hand',
            'children' => array(
                'moteur-boutons',
                'moteur-curseur',
            ),
        ),
        array(
            'slug'     => 'securite-visuelle',
            'title'    => __( 'Mouvement', 'a11y-widget' ),
            'icon'     => 'bolt',
            'children' => array(
                'epilepsie-protection',
            ),
        ),
        array(
            'slug'     => 'audio-video',
            'title'    => __( 'Audio / vidéo', 'a11y-widget' ),
            'icon'     => 'ear',
            'children' => array(
                'audition-text-to-speech',
            ),
        ),
        array(
            'slug'     => 'retours-informations',
            'title'    => __( 'Retours et informations', 'a11y-widget' ),
            'icon'     => 'message-circle',
            'children' => array(
                'feedback-utilisateur',
                'declaration-accessibilite',
                'credits-application',
            ),
        ),
    );

    $rebuilt = array();
    $assigned = array();

    foreach ( $model as $section ) {
        $children = array();

        foreach ( $section['children'] as $feature_slug ) {
            if ( ! isset( $feature_map[ $feature_slug ] ) ) {
                continue;
            }

            $children[] = $feature_map[ $feature_slug ];
            $assigned[ $feature_slug ] = true;
        }

        if ( ! empty( $children ) ) {
            $section['children'] = $children;
            $rebuilt[]           = $section;
        }
    }

    foreach ( $section_order as $section_slug ) {
        if ( empty( $section_map[ $section_slug ]['children'] ) || ! is_array( $section_map[ $section_slug ]['children'] ) ) {
            continue;
        }

        foreach ( $section_map[ $section_slug ]['children'] as $feature ) {
            if ( empty( $feature['slug'] ) ) {
                continue;
            }

            $feature_slug = sanitize_key( $feature['slug'] );

            if ( '' === $feature_slug || isset( $assigned[ $feature_slug ] ) ) {
                continue;
            }

            $fallback_slug = isset( $feature_section[ $feature_slug ] ) ? $feature_section[ $feature_slug ] : $section_slug;
            $fallback_key  = '';

            foreach ( $rebuilt as $index => $rebuilt_section ) {
                if ( isset( $rebuilt_section['slug'] ) && $fallback_slug === $rebuilt_section['slug'] ) {
                    $fallback_key = $index;
                    break;
                }
            }

            if ( '' === $fallback_key ) {
                $rebuilt[] = array(
                    'slug'     => $fallback_slug,
                    'title'    => isset( $section_title_map[ $fallback_slug ] ) ? $section_title_map[ $fallback_slug ] : $fallback_slug,
                    'icon'     => isset( $section_icon_map[ $fallback_slug ] ) ? $section_icon_map[ $fallback_slug ] : '',
                    'children' => array(),
                );
                $fallback_key = count( $rebuilt ) - 1;
            }

            $rebuilt[ $fallback_key ]['children'][] = $feature_map[ $feature_slug ];
            $assigned[ $feature_slug ] = true;
        }
    }

    return $rebuilt;
}

/**
 * Parse Markdown feature files located in the plugin `features/` directory.
 *
 * File format (per line, bullet list):
 *   # Mon titre de section (catégorie niveau 1)
 *   - `slug` **Label** : Hint optionnel (placeholders niveau 2)
 *
 * @return array[] Parsed sections.
 */
function a11y_widget_parse_markdown_sections() {
    static $cache = null;

    if ( null !== $cache ) {
        return $cache;
    }

    $dir = trailingslashit( A11Y_WIDGET_PATH ) . 'features';

    if ( ! is_dir( $dir ) ) {
        return array();
    }

    $files = glob( trailingslashit( $dir ) . '*.md' );
    if ( false === $files || empty( $files ) ) {
        return array();
    }

    sort( $files );

    $sections      = array();
    $section_order = array();

    foreach ( $files as $file ) {
        $lines = file( $file, FILE_IGNORE_NEW_LINES );
        if ( false === $lines ) {
            continue;
        }

        $current_section = null;

        foreach ( $lines as $raw_line ) {
            $line = trim( $raw_line );

            if ( '' === $line ) {
                continue;
            }

            if ( preg_match( '/^#{1,6}\s*(.+)$/u', $line, $matches ) ) {
                $title = wp_strip_all_tags( trim( $matches[1] ) );
                if ( '' === $title ) {
                    continue;
                }

                $slug = sanitize_title( $title );
                if ( '' === $slug ) {
                    $current_section = null;
                    continue;
                }

                if ( ! isset( $sections[ $slug ] ) ) {
                    $sections[ $slug ] = array(
                        'slug'           => $slug,
                        'title'          => $title,
                        'children'       => array(),
                        'children_order' => array(),
                    );
                    $section_order[] = $slug;
                } elseif ( '' === $sections[ $slug ]['title'] ) {
                    $sections[ $slug ]['title'] = $title;
                }

                $current_section = $slug;
                continue;
            }

            if ( 0 !== strpos( $line, '-' ) || null === $current_section ) {
                continue;
            }

            if ( preg_match( '/-\s*`([^`]+)`\s*(?:\*\*(.+?)\*\*|([^:]+))?\s*(?::\s*(.+))?$/u', $line, $matches ) ) {
                $slug = sanitize_key( $matches[1] );
                if ( '' === $slug ) {
                    continue;
                }

                if ( isset( $sections[ $current_section ]['children'][ $slug ] ) ) {
                    continue;
                }

                $raw_label = '';
                if ( ! empty( $matches[2] ) ) {
                    $raw_label = $matches[2];
                } elseif ( ! empty( $matches[3] ) ) {
                    $raw_label = trim( $matches[3] );
                }

                if ( '' === $raw_label ) {
                    $raw_label = $slug;
                }

                $raw_label = wp_strip_all_tags( $raw_label );

                $hint = '';
                if ( isset( $matches[4] ) ) {
                    $hint = wp_strip_all_tags( trim( $matches[4] ) );
                }

                $sections[ $current_section ]['children'][ $slug ] = array(
                    'slug'       => $slug,
                    'label'      => $raw_label,
                    'hint'       => $hint,
                    'aria_label' => sprintf( __( 'Activer %s', 'a11y-widget' ), $raw_label ),
                    'source'     => basename( $file ),
                );
                $sections[ $current_section ]['children_order'][] = $slug;
            }
        }
    }

    $ordered_sections = array();
    foreach ( $section_order as $slug ) {
        if ( ! isset( $sections[ $slug ] ) ) {
            continue;
        }

        $section = $sections[ $slug ];
        $ordered_children = array();
        if ( ! empty( $section['children_order'] ) ) {
            foreach ( $section['children_order'] as $child_slug ) {
                if ( isset( $section['children'][ $child_slug ] ) ) {
                    $ordered_children[] = $section['children'][ $child_slug ];
                }
            }
        }

        $section['children'] = $ordered_children;
        unset( $section['children_order'] );

        $ordered_sections[] = $section;
    }

    $cache = $ordered_sections;

    return $cache;
}

/**
 * Merge default and Markdown-defined sections without overwriting existing slugs.
 *
 * @return array[]
 */
function a11y_widget_get_sections() {
    $defaults          = a11y_widget_get_default_sections();
    $sections_by_slug  = array();
    $ordered_slugs     = array();
    $child_slug_global = array();

    foreach ( $defaults as $section ) {
        if ( empty( $section['slug'] ) ) {
            continue;
        }

        $slug = sanitize_title( $section['slug'] );
        if ( '' === $slug ) {
            continue;
        }

        if ( ! isset( $sections_by_slug[ $slug ] ) ) {
            $sections_by_slug[ $slug ] = array(
                'slug'           => $slug,
                'title'          => isset( $section['title'] ) ? $section['title'] : '',
                'icon'           => isset( $section['icon'] ) ? sanitize_key( $section['icon'] ) : '',
                'children'       => array(),
                'children_order' => array(),
            );
            $ordered_slugs[] = $slug;
        } else {
            if ( isset( $section['title'] ) && '' !== $section['title'] && '' === $sections_by_slug[ $slug ]['title'] ) {
                $sections_by_slug[ $slug ]['title'] = $section['title'];
            }

            if ( isset( $section['icon'] ) && '' === $sections_by_slug[ $slug ]['icon'] ) {
                $sections_by_slug[ $slug ]['icon'] = sanitize_key( $section['icon'] );
            }
        }

        $children = array();
        if ( isset( $section['children'] ) && is_array( $section['children'] ) ) {
            $children = $section['children'];
        }

        foreach ( $children as $child ) {
            if ( empty( $child['slug'] ) ) {
                continue;
            }

            $child_slug = sanitize_key( $child['slug'] );
            if ( '' === $child_slug ) {
                continue;
            }

            if ( isset( $sections_by_slug[ $slug ]['children'][ $child_slug ] ) ) {
                continue;
            }

            $child['slug'] = $child_slug;
            $child         = a11y_widget_normalize_nested_children( $child );
            $sections_by_slug[ $slug ]['children'][ $child_slug ] = $child;
            $sections_by_slug[ $slug ]['children_order'][]        = $child_slug;
            $child_slug_global[ $child_slug ]                     = true;
        }
    }

    $extra_sections = a11y_widget_parse_markdown_sections();

    foreach ( $extra_sections as $section ) {
        if ( empty( $section['slug'] ) ) {
            continue;
        }

        $slug = sanitize_title( $section['slug'] );
        if ( '' === $slug ) {
            continue;
        }

        if ( ! isset( $sections_by_slug[ $slug ] ) ) {
            $sections_by_slug[ $slug ] = array(
                'slug'           => $slug,
                'title'          => isset( $section['title'] ) ? $section['title'] : '',
                'icon'           => isset( $section['icon'] ) ? sanitize_key( $section['icon'] ) : '',
                'children'       => array(),
                'children_order' => array(),
            );
            $ordered_slugs[] = $slug;
        } else {
            if ( '' !== $section['title'] && '' === $sections_by_slug[ $slug ]['title'] ) {
                $sections_by_slug[ $slug ]['title'] = $section['title'];
            }

            if ( isset( $section['icon'] ) && '' === $sections_by_slug[ $slug ]['icon'] ) {
                $sections_by_slug[ $slug ]['icon'] = sanitize_key( $section['icon'] );
            }
        }

        if ( empty( $section['children'] ) ) {
            continue;
        }

        foreach ( $section['children'] as $child ) {
            if ( empty( $child['slug'] ) ) {
                continue;
            }

            $child_slug = sanitize_key( $child['slug'] );
            if ( '' === $child_slug ) {
                continue;
            }

            if ( isset( $child_slug_global[ $child_slug ] ) ) {
                continue;
            }

            if ( isset( $sections_by_slug[ $slug ]['children'][ $child_slug ] ) ) {
                continue;
            }

            $child['slug']                                   = $child_slug;
            $child                                           = a11y_widget_normalize_nested_children( $child );
            $child_slug_global[ $child_slug ]                = true;
            $sections_by_slug[ $slug ]['children'][ $child_slug ] = $child;
            $sections_by_slug[ $slug ]['children_order'][]        = $child_slug;
        }
    }

    $sections = array();
    foreach ( $ordered_slugs as $slug ) {
        if ( ! isset( $sections_by_slug[ $slug ] ) ) {
            continue;
        }

        $section = $sections_by_slug[ $slug ];
        if ( ! isset( $section['title'] ) || ! is_string( $section['title'] ) ) {
            $section['title'] = '';
        }

        $ordered_children = array();
        if ( isset( $section['children_order'] ) && is_array( $section['children_order'] ) ) {
            foreach ( $section['children_order'] as $child_slug ) {
                if ( isset( $section['children'][ $child_slug ] ) ) {
                    $ordered_children[] = $section['children'][ $child_slug ];
                }
            }
        }

        $section['children'] = $ordered_children;
        unset( $section['children_order'] );

        $sections[] = $section;
    }

    $sections = a11y_widget_apply_phase2_section_model( $sections );
    $sections = a11y_widget_apply_custom_feature_layout( $sections );
    $sections = a11y_widget_apply_custom_section_order( $sections );
    $sections = a11y_widget_remove_empty_sections( $sections );
    $sections = a11y_widget_strip_placeholder_hints( $sections );

    /**
     * Filter the final list of sections sent to the template.
     *
     * @param array $sections Sections with children.
     */
    return apply_filters( 'a11y_widget_sections', $sections );
}

/**
 * Apply the administrator-defined feature layout to sections.
 *
 * @param array $sections Sections with their features.
 *
 * @return array
 */
function a11y_widget_apply_custom_feature_layout( $sections ) {
    if ( empty( $sections ) || ! is_array( $sections ) ) {
        return array();
    }

    if ( ! function_exists( 'a11y_widget_get_feature_layout' ) ) {
        return $sections;
    }

    $layout = a11y_widget_get_feature_layout();
    $subfeature_layout = array();

    if ( function_exists( 'a11y_widget_get_subfeature_layout' ) ) {
        $subfeature_layout = a11y_widget_get_subfeature_layout();
    }

    if ( empty( $layout ) || ! is_array( $layout ) ) {
        return $sections;
    }

    foreach ( $layout as $layout_section_slug => &$layout_children ) {
        if ( ! is_array( $layout_children ) ) {
            continue;
        }

        $layout_children = array_values(
            array_filter(
                $layout_children,
                static function ( $child_slug ) {
                    $child_slug = sanitize_key( (string) $child_slug );
                    return ! in_array( $child_slug, array( 'feedback-utilisateur', 'declaration-accessibilite', 'credits-application' ), true );
                }
            )
        );
    }
    unset( $layout_children );

    $layout['retours-informations'] = array( 'feedback-utilisateur', 'declaration-accessibilite', 'credits-application' );

    $feature_map = array();

    foreach ( $sections as $section ) {
        if ( empty( $section['children'] ) || ! is_array( $section['children'] ) ) {
            continue;
        }

        foreach ( $section['children'] as $feature ) {
            if ( empty( $feature['slug'] ) ) {
                continue;
            }

            $feature_slug = sanitize_key( $feature['slug'] );

            if ( '' === $feature_slug ) {
                continue;
            }

            $feature['slug']          = $feature_slug;
            $feature_map[ $feature_slug ] = $feature;
        }
    }

    if ( empty( $feature_map ) ) {
        return $sections;
    }

    $assigned            = array();
    $feature_destination = array();

    foreach ( $layout as $section_slug => $child_slugs ) {
        if ( ! is_array( $child_slugs ) ) {
            continue;
        }

        $section_slug = sanitize_title( $section_slug );

        if ( '' === $section_slug ) {
            continue;
        }

        foreach ( $child_slugs as $child_slug ) {
            $child_slug = sanitize_key( $child_slug );

            if ( '' === $child_slug ) {
                continue;
            }

            if ( isset( $feature_destination[ $child_slug ] ) ) {
                continue;
            }

            $feature_destination[ $child_slug ] = $section_slug;
        }
    }

    foreach ( $sections as &$section ) {
        if ( empty( $section['slug'] ) ) {
            continue;
        }

        $section_slug = sanitize_title( $section['slug'] );

        if ( '' === $section_slug ) {
            continue;
        }

        $ordered_children = array();

        if ( isset( $layout[ $section_slug ] ) && is_array( $layout[ $section_slug ] ) ) {
            foreach ( $layout[ $section_slug ] as $child_slug ) {
                $child_slug = sanitize_key( $child_slug );

                if ( '' === $child_slug ) {
                    continue;
                }

                if ( isset( $assigned[ $child_slug ] ) || ! isset( $feature_map[ $child_slug ] ) ) {
                    continue;
                }

                $feature = $feature_map[ $child_slug ];
                $feature['slug'] = $child_slug;

                $ordered_children[]      = $feature;
                $assigned[ $child_slug ] = true;
            }
        }

        if ( isset( $section['children'] ) && is_array( $section['children'] ) ) {
            foreach ( $section['children'] as $feature ) {
                if ( empty( $feature['slug'] ) ) {
                    continue;
                }

                $child_slug = sanitize_key( $feature['slug'] );

                if ( '' === $child_slug || isset( $assigned[ $child_slug ] ) || ! isset( $feature_map[ $child_slug ] ) ) {
                    continue;
                }

                if ( isset( $feature_destination[ $child_slug ] ) && $feature_destination[ $child_slug ] !== $section_slug ) {
                    continue;
                }

                $feature['slug']         = $child_slug;
                $ordered_children[]      = $feature;
                $assigned[ $child_slug ] = true;
            }
        }

        $section['children'] = $ordered_children;

        if ( ! empty( $section['children'] ) && ! empty( $subfeature_layout ) ) {
            foreach ( $section['children'] as &$feature ) {
                if ( empty( $feature['slug'] ) || empty( $feature['children'] ) || ! is_array( $feature['children'] ) ) {
                    continue;
                }

                $feature_slug = sanitize_key( $feature['slug'] );

                if ( '' === $feature_slug || empty( $subfeature_layout[ $feature_slug ] ) || ! is_array( $subfeature_layout[ $feature_slug ] ) ) {
                    continue;
                }

                $ordered_subfeatures  = array();
                $assigned_subfeatures = array();

                foreach ( $subfeature_layout[ $feature_slug ] as $sub_slug ) {
                    $sub_slug = sanitize_key( $sub_slug );

                    if ( '' === $sub_slug || isset( $assigned_subfeatures[ $sub_slug ] ) ) {
                        continue;
                    }

                    foreach ( $feature['children'] as $child ) {
                        if ( empty( $child['slug'] ) ) {
                            continue;
                        }

                        $child_slug = sanitize_key( $child['slug'] );

                        if ( '' === $child_slug || $child_slug !== $sub_slug || isset( $assigned_subfeatures[ $child_slug ] ) ) {
                            continue;
                        }

                        $child['slug'] = $child_slug;
                        $ordered_subfeatures[]      = $child;
                        $assigned_subfeatures[ $child_slug ] = true;
                        break;
                    }
                }

                if ( count( $ordered_subfeatures ) !== count( $feature['children'] ) ) {
                    foreach ( $feature['children'] as $child ) {
                        if ( empty( $child['slug'] ) ) {
                            continue;
                        }

                        $child_slug = sanitize_key( $child['slug'] );

                        if ( '' === $child_slug || isset( $assigned_subfeatures[ $child_slug ] ) ) {
                            continue;
                        }

                        $child['slug'] = $child_slug;
                        $ordered_subfeatures[]      = $child;
                        $assigned_subfeatures[ $child_slug ] = true;
                    }
                }

                $feature['children'] = $ordered_subfeatures;
            }
            unset( $feature );
        }
    }
    unset( $section );

    return $sections;
}

/**
 * Apply the administrator-defined section order.
 *
 * @param array $sections Sections with their features.
 *
 * @return array
 */
function a11y_widget_apply_custom_section_order( $sections ) {
    if ( empty( $sections ) || ! is_array( $sections ) ) {
        return $sections;
    }

    if ( ! function_exists( 'a11y_widget_get_section_order' ) ) {
        return $sections;
    }

    $order = a11y_widget_get_section_order();

    if ( empty( $order ) || ! is_array( $order ) ) {
        return $sections;
    }

    $sections_by_slug = array();

    foreach ( $sections as $section ) {
        if ( empty( $section['slug'] ) ) {
            continue;
        }

        $slug = sanitize_title( $section['slug'] );

        if ( '' === $slug || isset( $sections_by_slug[ $slug ] ) ) {
            continue;
        }

        $section['slug']        = $slug;
        $sections_by_slug[ $slug ] = $section;
    }

    if ( empty( $sections_by_slug ) ) {
        return $sections;
    }

    $ordered = array();

    foreach ( $order as $slug ) {
        $slug = sanitize_title( $slug );

        if ( '' === $slug || ! isset( $sections_by_slug[ $slug ] ) ) {
            continue;
        }

        $ordered[] = $sections_by_slug[ $slug ];
        unset( $sections_by_slug[ $slug ] );
    }

    if ( empty( $ordered ) ) {
        return $sections;
    }

    foreach ( $sections as $section ) {
        if ( empty( $section['slug'] ) ) {
            continue;
        }

        $slug = sanitize_title( $section['slug'] );

        if ( '' === $slug || ! isset( $sections_by_slug[ $slug ] ) ) {
            continue;
        }

        $ordered[] = $sections_by_slug[ $slug ];
        unset( $sections_by_slug[ $slug ] );
    }

    return array_values( $ordered );
}

// Load admin settings and feature visibility management.
require_once A11Y_WIDGET_PATH . 'includes/admin-settings.php';
add_action( A11Y_WIDGET_FEEDBACK_RETENTION_HOOK, 'a11y_widget_purge_expired_feedback_posts' );

/**
 * Auto-inject in footer unless disabled
 */
function a11y_widget_auto_inject() {
    $enable_auto = apply_filters( 'a11y_widget_enable_auto', true );
    if ( $enable_auto ) {
        a11y_widget_markup();
    }
}
add_action( 'wp_footer', 'a11y_widget_auto_inject', 5 );

/**
 * Shortcode: [a11y_widget]
 */
function a11y_widget_shortcode() {
    return a11y_widget_get_markup_once();
}
add_shortcode( 'a11y_widget', 'a11y_widget_shortcode' );
