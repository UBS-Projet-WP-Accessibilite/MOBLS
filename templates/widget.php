<?php
/**
 * Widget markup (front)
 * This is printed in the footer or via shortcode.
 */

$default_logo = '';

if ( function_exists( 'a11y_widget_get_launcher_logo_image_markup' ) ) {
    $default_logo = (string) a11y_widget_get_launcher_logo_image_markup( 'rouge', 'default' );
} elseif ( function_exists( 'a11y_widget_get_logo_svg_from_file' ) ) {
    $default_logo = a11y_widget_get_logo_svg_from_file( 'logo_rouge.svg' );
}

if ( '' === $default_logo ) {
    $default_logo = '<svg viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12" fill="#dc2626" /><path fill="#ffffff" d="M12 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm6.75 6.5h-4.5v11a1 1 0 1 1-2 0v-5h-1v5a1 1 0 1 1-2 0v-11h-4.5a1 1 0 1 1 0-2h14a1 1 0 1 1 0 2Z" /></svg>';
}

if ( function_exists( 'a11y_widget_get_launcher_logo_image_markup' ) ) {
    $launcher_logo_markup = (string) a11y_widget_get_launcher_logo_image_markup( null, 'launcher' );
    $panel_logo_markup    = (string) a11y_widget_get_launcher_logo_image_markup( null, 'panel' );
} elseif ( function_exists( 'a11y_widget_get_launcher_logo_markup' ) ) {
    $launcher_logo_markup = (string) a11y_widget_get_launcher_logo_markup();
    $panel_logo_markup    = (string) a11y_widget_get_launcher_logo_markup();

    if ( '' === trim( $launcher_logo_markup ) ) {
        $launcher_logo_markup = $default_logo;
    }

    if ( '' === trim( $panel_logo_markup ) ) {
        $panel_logo_markup = $default_logo;
    }
} else {
    $launcher_logo_markup = $default_logo;
    $panel_logo_markup    = $default_logo;
}

$launcher_has_logo = (bool) preg_match( '/<(svg|img)\b/i', $launcher_logo_markup );
$launcher_classes  = 'a11y-launcher' . ( $launcher_has_logo ? ' has-logo' : '' );
$logo_scale_value  = 1.0;
$launcher_size_px  = 56;
$panel_logo_px     = 28;

if ( function_exists( 'a11y_widget_get_launcher_logo_scale' ) ) {
    $logo_scale_value = (float) a11y_widget_get_launcher_logo_scale();

    if ( $logo_scale_value <= 0 ) {
        $logo_scale_value = 1.0;
    }
}

$launcher_size_px = max( 1, (int) round( $launcher_size_px * $logo_scale_value ) );
$panel_logo_px    = max( 1, (int) round( $panel_logo_px * $logo_scale_value ) );

$logo_scale_css_value = rtrim( rtrim( number_format( $logo_scale_value, 2, '.', '' ), '0' ), '.' );

$logo_scale_style = sprintf(
    '--a11y-widget-logo-scale: %1$s; --a11y-launcher-size: %2$dpx; --a11y-panel-logo-size: %3$dpx;',
    $logo_scale_css_value,
    $launcher_size_px,
    $panel_logo_px
);

$background_mode = 'modal';

if ( function_exists( 'a11y_widget_get_background_mode' ) ) {
    $background_mode = a11y_widget_get_background_mode();
}

if ( ! in_array( $background_mode, array( 'modal', 'interactive' ), true ) ) {
    $background_mode = 'modal';
}

$visual_theme = 'mobls';

if ( function_exists( 'a11y_widget_get_active_visual_options' ) ) {
    $active_visual_options = a11y_widget_get_active_visual_options();

    if ( isset( $active_visual_options['theme'] ) ) {
        $visual_theme = sanitize_key( (string) $active_visual_options['theme'] );
    }
}

if ( '' === $visual_theme ) {
    $visual_theme = 'mobls';
}

$root_class_names = array( 'a11y-root', 'a11y-root--mode-' . $background_mode, 'a11y-root--theme-' . $visual_theme );

if ( function_exists( 'sanitize_html_class' ) ) {
    $root_class_names = array_map( 'sanitize_html_class', $root_class_names );
}

$root_classes = implode( ' ', array_filter( array_unique( $root_class_names ) ) );
?>
<div
  id="a11y-widget-root"
  class="<?php echo esc_attr( $root_classes ); ?>"
  data-a11y-filter-exempt
  data-background-mode="<?php echo esc_attr( $background_mode ); ?>"
  data-visual-theme="<?php echo esc_attr( $visual_theme ); ?>"
  style="<?php echo esc_attr( $logo_scale_style ); ?>"
>
  <button type="button" class="<?php echo esc_attr( $launcher_classes ); ?>" id="a11y-launcher" aria-haspopup="dialog" aria-expanded="false" aria-controls="a11y-panel" aria-label="<?php echo esc_attr__('Ouvrir le module d’accessibilité', 'a11y-widget'); ?>" data-a11y-preserve-colors data-a11y-filter-exempt>
    <?php echo $launcher_logo_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
  </button>

  <section
    class="a11y-panel is-right"
    id="a11y-panel"
    role="dialog"
    aria-modal="<?php echo esc_attr( 'modal' === $background_mode ? 'true' : 'false' ); ?>"
    aria-labelledby="a11y-title"
    tabindex="-1"
    aria-hidden="true"
    hidden
    data-a11y-preserve-colors
  >
    <?php
    $panel_label_left   = esc_attr__( 'Déplacer le panneau à gauche', 'a11y-widget' );
    $panel_label_right  = esc_attr__( 'Déplacer le panneau à droite', 'a11y-widget' );
    $panel_text_left    = esc_attr__( 'À gauche', 'a11y-widget' );
    $panel_text_right   = esc_attr__( 'À droite', 'a11y-widget' );
    $panel_status_left  = esc_attr__( 'Le panneau est placé à gauche.', 'a11y-widget' );
    $panel_status_right = esc_attr__( 'Le panneau est placé à droite.', 'a11y-widget' );
    ?>
    <header class="a11y-header">
      <div class="a11y-header__title-row">
        <h2 id="a11y-title" class="a11y-title"><?php echo esc_html__( 'Accessibilité et lecture', 'a11y-widget' ); ?></h2>
        <button
          type="button"
          class="a11y-close"
          id="a11y-close"
          aria-label="<?php echo esc_attr__( 'Fermer le panneau', 'a11y-widget' ); ?>"
          title="<?php echo esc_attr__( 'Fermer le panneau', 'a11y-widget' ); ?>"
          data-tooltip="<?php echo esc_attr__( 'Fermer', 'a11y-widget' ); ?>"
        >✕</button>
      </div>
      <div class="a11y-header__controls">
        <div class="a11y-view-switch" role="group" aria-label="<?php echo esc_attr__( 'Vue affichée dans le panneau', 'a11y-widget' ); ?>">
          <button
            type="button"
            class="a11y-view-toggle is-active"
            id="a11y-options-toggle"
            aria-pressed="true"
            aria-controls="a11y-options-view"
            aria-label="<?php echo esc_attr__( 'Afficher les options', 'a11y-widget' ); ?>"
            title="<?php echo esc_attr__( 'Afficher les options', 'a11y-widget' ); ?>"
            data-tooltip="<?php echo esc_attr__( 'Options', 'a11y-widget' ); ?>"
          >
            <span class="a11y-view-toggle__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <path d="M21 4h-7"></path>
                <path d="M10 4H3"></path>
                <circle cx="12" cy="4" r="2"></circle>
                <path d="M21 12h-9"></path>
                <path d="M8 12H3"></path>
                <circle cx="10" cy="12" r="2"></circle>
                <path d="M21 20h-5"></path>
                <path d="M12 20H3"></path>
                <circle cx="14" cy="20" r="2"></circle>
              </svg>
            </span>
            <span class="a11y-view-toggle__text a11y-sr-only"><?php echo esc_html__( 'Options', 'a11y-widget' ); ?></span>
          </button>
          <button
            type="button"
            class="a11y-view-toggle a11y-tutorial-toggle"
            id="a11y-tutorial-toggle"
            aria-haspopup="dialog"
            aria-expanded="false"
            aria-controls="a11y-tutorial"
            aria-label="<?php echo esc_attr__( 'Afficher les raccourcis clavier du widget', 'a11y-widget' ); ?>"
            data-open-label="<?php echo esc_attr__( 'Afficher les raccourcis clavier du widget', 'a11y-widget' ); ?>"
            data-close-label="<?php echo esc_attr__( 'Fermer les raccourcis clavier du widget', 'a11y-widget' ); ?>"
            title="<?php echo esc_attr__( 'Afficher les raccourcis clavier', 'a11y-widget' ); ?>"
            data-tooltip="<?php echo esc_attr__( 'Raccourcis clavier', 'a11y-widget' ); ?>"
          >
            <span class="a11y-view-toggle__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                <path d="M7 9h.01"></path>
                <path d="M11 9h.01"></path>
                <path d="M15 9h.01"></path>
                <path d="M19 9h.01"></path>
                <path d="M7 13h.01"></path>
                <path d="M11 13h.01"></path>
                <path d="M15 13h.01"></path>
                <path d="M19 13h.01"></path>
                <path d="M9 17h6"></path>
              </svg>
            </span>
            <span class="a11y-view-toggle__text a11y-sr-only"><?php echo esc_html__( 'Raccourcis clavier', 'a11y-widget' ); ?></span>
          </button>
        </div>
        <div class="a11y-header__utility-group" role="group" aria-label="<?php echo esc_attr__( 'Aide, structure et placement du panneau', 'a11y-widget' ); ?>">
          <button
            type="button"
            class="a11y-view-toggle a11y-info-trigger"
            id="a11y-info-trigger"
            aria-haspopup="dialog"
            aria-expanded="false"
            aria-controls="a11y-info-dialog"
            aria-label="<?php echo esc_attr__( 'Ouvrir l’aide et les tutoriels', 'a11y-widget' ); ?>"
            data-open-label="<?php echo esc_attr__( 'Ouvrir l’aide et les tutoriels', 'a11y-widget' ); ?>"
            data-close-label="<?php echo esc_attr__( 'Fermer l’aide et les tutoriels', 'a11y-widget' ); ?>"
            title="<?php echo esc_attr__( 'Ouvrir l’aide et les tutoriels', 'a11y-widget' ); ?>"
            data-tooltip="<?php echo esc_attr__( 'Aide', 'a11y-widget' ); ?>"
          >
            <span class="a11y-view-toggle__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M9.75 9a2.35 2.35 0 0 1 4.5 1c0 1.5-1.35 2.05-2.05 2.55-.5.35-.7.7-.7 1.45"></path>
                <path d="M12 17h.01"></path>
              </svg>
            </span>
            <span class="a11y-view-toggle__text a11y-sr-only"><?php echo esc_html__( 'Aide', 'a11y-widget' ); ?></span>
          </button>
          <button
            type="button"
            class="a11y-view-toggle a11y-structure-toggle"
            id="a11y-structure-toggle"
            aria-pressed="false"
            aria-expanded="false"
            aria-controls="a11y-structure-view"
            aria-label="<?php echo esc_attr__( 'Afficher la structure de la page', 'a11y-widget' ); ?>"
            title="<?php echo esc_attr__( 'Afficher la structure de la page', 'a11y-widget' ); ?>"
            data-tooltip="<?php echo esc_attr__( 'Structure de page', 'a11y-widget' ); ?>"
          >
            <span class="a11y-view-toggle__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <path d="M4 5h16"></path>
                <path d="M4 12h10"></path>
                <path d="M4 19h7"></path>
                <path d="M18 12v7"></path>
                <path d="m15.5 16.5 2.5 2.5 2.5-2.5"></path>
              </svg>
            </span>
            <span class="a11y-view-toggle__text a11y-sr-only"><?php echo esc_html__( 'Structure de page', 'a11y-widget' ); ?></span>
          </button>
          <button
            type="button"
            class="a11y-side-toggle"
            id="a11y-side-toggle"
            aria-label="<?php echo $panel_label_left; ?>"
            aria-describedby="a11y-panel-position-status"
            data-label-left="<?php echo $panel_label_left; ?>"
            data-label-right="<?php echo $panel_label_right; ?>"
            data-text-left="<?php echo $panel_text_left; ?>"
            data-text-right="<?php echo $panel_text_right; ?>"
            data-status-left="<?php echo $panel_status_left; ?>"
            data-status-right="<?php echo $panel_status_right; ?>"
            data-panel-target="left"
            title="<?php echo $panel_label_left; ?>"
            data-tooltip="<?php echo $panel_label_left; ?>"
          >
            <span class="a11y-side-toggle__icon" data-role="side-toggle-icon" aria-hidden="true">
              <svg class="a11y-side-toggle__icon-left" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                <path d="M9 4v16"></path>
                <path d="M6 12h3"></path>
                <path d="m7.5 10.5-1.5 1.5 1.5 1.5"></path>
              </svg>
              <svg class="a11y-side-toggle__icon-right" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                <path d="M15 4v16"></path>
                <path d="M15 12h3"></path>
                <path d="m16.5 10.5 1.5 1.5-1.5 1.5"></path>
              </svg>
            </span>
            <span class="a11y-side-toggle__text a11y-sr-only" data-role="side-toggle-text"><?php echo esc_html__( 'À gauche', 'a11y-widget' ); ?></span>
          </button>
        </div>
      </div>
      <p class="a11y-sr-only" id="a11y-panel-position-status" data-role="panel-position-status" aria-live="polite"><?php echo esc_html__( 'Le panneau est placé à droite.', 'a11y-widget' ); ?></p>
    </header>

    <?php
    $a11y_panel_mode_explanation = 'modal' === $background_mode
        ? __( 'Mode actuel : modal. Quand le panneau est ouvert, l’arrière-plan est isolé et le focus reste dans le module.', 'a11y-widget' )
        : __( 'Mode actuel : interactif. Quand le panneau est ouvert, l’arrière-plan reste utilisable.', 'a11y-widget' );

    $a11y_info_sections = array(
        array(
            'slug'     => 'prise-en-main',
            'label'    => __( 'Prise en main', 'a11y-widget' ),
            'heading'  => __( 'Comprendre le module en quelques étapes', 'a11y-widget' ),
            'intro'    => __( 'Ce module sert à adapter l’affichage, la lecture et certaines interactions de la page selon les besoins de l’utilisateur.', 'a11y-widget' ),
            'panels'   => array(
                array(
                    'slug'       => 'parcours',
                    'title'      => __( 'Parcours conseillé', 'a11y-widget' ),
                    'paragraphs' => array(
                        __( 'Commencez par chercher une fonction ou par ouvrir une catégorie. Activez un réglage, observez son effet sur la page, puis ajustez-le si nécessaire.', 'a11y-widget' ),
                    ),
                    'steps'      => array(
                        __( 'Ouvrir le module avec le bouton flottant.', 'a11y-widget' ),
                        __( 'Utiliser la recherche pour trouver un besoin précis : contraste, lecture, curseur, mouvement, dyslexie, etc.', 'a11y-widget' ),
                        __( 'Activer un réglage à la fois pour vérifier son effet réel.', 'a11y-widget' ),
                        __( 'Fermer le module : les réglages restent actifs sur la page.', 'a11y-widget' ),
                        __( 'Utiliser « Réinitialiser » pour supprimer les préférences enregistrées par le widget.', 'a11y-widget' ),
                    ),
                ),
                array(
                    'slug'       => 'navigation',
                    'title'      => __( 'Navigation dans le panneau', 'a11y-widget' ),
                    'paragraphs' => array(
                        $a11y_panel_mode_explanation,
                    ),
                    'items'      => array(
                        __( 'Le bouton de placement permet d’afficher le panneau à gauche ou à droite.', 'a11y-widget' ),
                        __( 'Le champ de recherche filtre les fonctions disponibles.', 'a11y-widget' ),
                        __( 'Les onglets de catégories permettent de parcourir les grandes familles de réglages.', 'a11y-widget' ),
                        __( 'Le bouton « Raccourcis » ouvre une fenêtre superposée avec les combinaisons clavier disponibles.', 'a11y-widget' ),
                    ),
                ),
                array(
                    'slug'       => 'etat-preferences',
                    'title'      => __( 'État des réglages', 'a11y-widget' ),
                    'paragraphs' => array(
                        __( 'Les interrupteurs indiquent les fonctions actives. Certains modules proposent aussi leurs propres boutons de réinitialisation pour revenir au réglage standard de la section.', 'a11y-widget' ),
                    ),
                    'items'      => array(
                        __( 'Les réglages visuels modifient l’affichage du navigateur, pas le contenu source du site.', 'a11y-widget' ),
                        __( 'Les réglages de lecture et de focus peuvent aider au confort, mais ne corrigent pas une structure HTML incorrecte.', 'a11y-widget' ),
                        __( 'Les effets peuvent varier selon le thème WordPress, le navigateur et les contenus de la page.', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'choisir-selon-besoin',
            'label'    => __( 'Choisir selon le besoin', 'a11y-widget' ),
            'heading'  => __( 'Orienter l’utilisateur vers les bons réglages', 'a11y-widget' ),
            'intro'    => __( 'Les catégories ne sont pas des diagnostics. Elles servent à trouver plus vite un réglage utile dans une situation de lecture ou d’interaction.', 'a11y-widget' ),
            'panels'   => array(
                array(
                    'slug'  => 'besoins-frequents',
                    'title' => __( 'Besoins fréquents', 'a11y-widget' ),
                    'cards' => array(
                        array(
                            'title' => __( 'Je ne sais pas quoi choisir', 'a11y-widget' ),
                            'text'  => __( 'Commencer par un profil, puis ajuster les options une par une selon le résultat réel.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Je cherche le statut d’accessibilité du site', 'a11y-widget' ),
                            'text'  => __( 'Ouvrir Déclaration d’accessibilité pour consulter le lien officiel, la date et le statut d’audit renseignés.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Le texte est difficile à suivre', 'a11y-widget' ),
                            'text'  => __( 'Essayer le guide de lecture, l’espacement, la séparation syllabique ou les réglages dyslexie.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'La structure de page n’est pas évidente', 'a11y-widget' ),
                            'text'  => __( 'Utiliser Structure de page pour lister les titres, repères et accès rapides au contenu.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Un mot ou un sigle bloque la compréhension', 'a11y-widget' ),
                            'text'  => __( 'Ouvrir Aide, puis Glossaire, pour consulter les définitions intégrées et les abréviations trouvées dans la page.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'La page fatigue visuellement', 'a11y-widget' ),
                            'text'  => __( 'Essayer la luminosité, le contraste réduit, le filtre ambré ou les réglages de confort visuel.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Les couleurs prêtent à confusion', 'a11y-widget' ),
                            'text'  => __( 'Essayer les filtres daltonisme, les niveaux de gris ou un contraste plus lisible.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Il y a trop de mouvement', 'a11y-widget' ),
                            'text'  => __( 'Activer la réduction des animations, GIFs, vidéos en autoplay, parallax et flashs potentiels.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Les boutons ou le curseur sont trop discrets', 'a11y-widget' ),
                            'text'  => __( 'Utiliser les réglages moteur pour agrandir les boutons, renforcer leur apparence ou personnaliser le curseur.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Écouter un passage', 'a11y-widget' ),
                            'text'  => __( 'Utiliser Audio / vidéo pour lancer une lecture de confort sur une sélection ou le contenu principal de la page.', 'a11y-widget' ),
                        ),
                    ),
                ),
                array(
                    'slug'       => 'tester-sans-surpromesse',
                    'title'      => __( 'Tester sans surpromesse', 'a11y-widget' ),
                    'paragraphs' => array(
                        __( 'Le bon usage consiste à essayer plusieurs réglages et à garder uniquement ceux qui améliorent réellement la situation. Le widget ne décide pas à la place de l’utilisateur.', 'a11y-widget' ),
                    ),
                    'items'      => array(
                        __( 'Ne pas activer tous les réglages par défaut : certaines combinaisons peuvent gêner.', 'a11y-widget' ),
                        __( 'Préférer un réglage simple, compréhensible et réversible.', 'a11y-widget' ),
                        __( 'Vérifier le résultat avec le clavier, le zoom et les contenus longs.', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'categories',
            'label'    => __( 'Catégories du module', 'a11y-widget' ),
            'heading'  => __( 'Ce que contient chaque catégorie', 'a11y-widget' ),
            'intro'    => __( 'Cette synthèse aide à comprendre rapidement le rôle des sections affichées dans le module.', 'a11y-widget' ),
            'panels'   => array(
                array(
                    'slug'  => 'sections',
                    'title' => __( 'Repères rapides', 'a11y-widget' ),
                    'cards' => array(
                        array(
                            'title' => __( 'Profils', 'a11y-widget' ),
                            'text'  => __( 'Points de départ rapides, retour utilisateur si la collecte est activée, et accès à la déclaration d’accessibilité configurée.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Vision', 'a11y-widget' ),
                            'text'  => __( 'Filtres de perception des couleurs, luminosité, contraste et confort visuel.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Lecture', 'a11y-widget' ),
                            'text'  => __( 'Guide de lecture, structure de page, dyslexie, texte seul, images masquées et braille visuel.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Cognitif', 'a11y-widget' ),
                            'text'  => __( 'Réduction des distractions et réglages d’aide à la concentration. Le glossaire est rangé dans Aide.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Moteur', 'a11y-widget' ),
                            'text'  => __( 'Boutons plus visibles, zones plus faciles à repérer et curseur renforcé.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Mouvement', 'a11y-widget' ),
                            'text'  => __( 'Réduction de certains mouvements et déclencheurs visuels potentiels, sans garantie de suppression du risque.', 'a11y-widget' ),
                        ),
                        array(
                            'title' => __( 'Audio / vidéo', 'a11y-widget' ),
                            'text'  => __( 'Lecture de confort avec les voix du navigateur. Ce n’est pas un lecteur d’écran.', 'a11y-widget' ),
                        ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'glossaire',
            'label'    => __( 'Glossaire', 'a11y-widget' ),
            'heading'  => __( 'Comprendre les termes du module et de la page', 'a11y-widget' ),
            'intro'    => __( 'Le glossaire regroupe les définitions intégrées et les termes détectés dans la page. Il reste une aide de compréhension, pas un outil d’audit.', 'a11y-widget' ),
            'panels'   => array(
                array(
                    'slug'       => 'dictionnaire-glossaire',
                    'title'      => __( 'Dictionnaire / glossaire', 'a11y-widget' ),
                    'paragraphs' => array(
                        __( 'Cherchez un sigle, un terme d’interface ou utilisez la sélection de la page pour obtenir un repère rapide.', 'a11y-widget' ),
                    ),
                    'dynamic'    => 'glossary',
                ),
            ),
        ),
        array(
            'slug'     => 'clavier',
            'label'    => __( 'Clavier et raccourcis', 'a11y-widget' ),
            'heading'  => __( 'Utiliser le module sans souris', 'a11y-widget' ),
            'intro'    => __( 'Le module est prévu pour être utilisé au clavier. Les raccourcis de fonctions restent limités au panneau ouvert pour réduire les conflits.', 'a11y-widget' ),
            'panels'   => array(
                array(
                    'slug'  => 'navigation-clavier',
                    'title' => __( 'Commandes de base', 'a11y-widget' ),
                    'items' => array(
                        __( 'Tabulation : avancer dans les boutons, champs et interrupteurs.', 'a11y-widget' ),
                        __( 'Maj + Tabulation : revenir à l’élément précédent.', 'a11y-widget' ),
                        __( 'Entrée ou Espace : activer un bouton ou un interrupteur.', 'a11y-widget' ),
                        __( 'Échap : fermer le panneau, l’aide ou les raccourcis selon le contexte.', 'a11y-widget' ),
                        __( 'Flèches gauche/droite : naviguer entre les onglets de catégories quand le focus est dans la liste d’onglets.', 'a11y-widget' ),
                    ),
                ),
                array(
                    'slug'       => 'raccourcis',
                    'title'      => __( 'Raccourcis de fonctions', 'a11y-widget' ),
                    'paragraphs' => array(
                        __( 'Le bouton « Raccourcis » ouvre une fenêtre superposée avec les combinaisons disponibles pour les fonctions présentes. Ces raccourcis ne sont actifs que lorsque le panneau est ouvert.', 'a11y-widget' ),
                    ),
                    'items'      => array(
                        __( 'Windows / Linux : Alt + touche.', 'a11y-widget' ),
                        __( 'macOS : Ctrl + Option + touche.', 'a11y-widget' ),
                        __( 'Si un raccourci entre en conflit avec un navigateur ou une technologie d’assistance, utilisez l’interrupteur visible plutôt que le raccourci.', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'donnees-limites',
            'label'    => __( 'Données et limites', 'a11y-widget' ),
            'heading'  => __( 'Ce que le module enregistre et ce qu’il ne remplace pas', 'a11y-widget' ),
            'intro'    => __( 'Cette section clarifie les préférences locales, la réinitialisation et les limites importantes du widget.', 'a11y-widget' ),
            'panels'   => array(
                array(
                    'slug'       => 'preferences',
                    'title'      => __( 'Préférences locales', 'a11y-widget' ),
                    'paragraphs' => array(
                        __( 'Les préférences sont enregistrées localement dans le navigateur via localStorage. Elles ne sont pas envoyées au serveur par ce module ; seul le formulaire de retour utilisateur peut transmettre un message si l’administration l’a activé et si l’utilisateur confirme l’envoi.', 'a11y-widget' ),
                    ),
                    'items'      => array(
                        __( 'Le stockage dépend du navigateur et du profil utilisateur.', 'a11y-widget' ),
                        __( 'Le bouton « Réinitialiser » supprime les préférences enregistrées par le widget.', 'a11y-widget' ),
                        __( 'Un autre navigateur, un autre appareil ou un mode privé peut ne pas retrouver les mêmes réglages.', 'a11y-widget' ),
                    ),
                ),
                array(
                    'slug'       => 'limites',
                    'title'      => __( 'Limites à connaître', 'a11y-widget' ),
                    'paragraphs' => array(
                        __( 'Le module est un outil de personnalisation et de confort. Il ne rend pas automatiquement un site conforme RGAA/WCAG.', 'a11y-widget' ),
                    ),
                    'items'      => array(
                        __( 'Il ne remplace pas un audit d’accessibilité ni une correction du thème ou des contenus.', 'a11y-widget' ),
                        __( 'La déclaration affichée dans le widget doit renvoyer vers une page officielle et un audit réel renseigné par l’administration.', 'a11y-widget' ),
                        __( 'La lecture audio ne remplace pas un lecteur d’écran : elle ne restitue pas tous les rôles, états, tableaux, landmarks et erreurs.', 'a11y-widget' ),
                        __( 'Le braille visuel ne pilote pas une plage braille matérielle.', 'a11y-widget' ),
                        __( 'Les réglages mouvement, flashs ou migraine ne garantissent pas la suppression des risques visuels.', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
        array(
            'slug'     => 'administration-demo',
            'label'    => __( 'Démo et administration', 'a11y-widget' ),
            'heading'  => __( 'Tester, configurer et présenter le module', 'a11y-widget' ),
            'intro'    => __( 'Cette aide sert aussi de support de démonstration pour expliquer ce que le widget peut faire et ce qu’il ne doit pas promettre.', 'a11y-widget' ),
            'panels'   => array(
                array(
                    'slug'  => 'recette-demo',
                    'title' => __( 'Recette WordPress', 'a11y-widget' ),
                    'items' => array(
                        __( 'Tester les pages longues, les formulaires, les tableaux, les images, les contenus animés et le shortcode.', 'a11y-widget' ),
                        __( 'Tester en navigation clavier seule, avec zoom 200 %, sur mobile et avec au moins un lecteur d’écran.', 'a11y-widget' ),
                        __( 'Vérifier que le bouton Réinitialiser remet bien les modules à leur état standard.', 'a11y-widget' ),
                    ),
                ),
                array(
                    'slug'  => 'configuration-admin',
                    'title' => __( 'Réglages administrateur', 'a11y-widget' ),
                    'items' => array(
                        __( 'Masquer les fonctions qui ne sont pas pertinentes pour le site de démonstration.', 'a11y-widget' ),
                        __( 'Réordonner les sections pour mettre en avant les usages pédagogiques prioritaires.', 'a11y-widget' ),
                        __( 'Personnaliser les couleurs du widget en conservant un contraste suffisant.', 'a11y-widget' ),
                        __( 'Choisir le mode modal ou interactif selon le scénario de test.', 'a11y-widget' ),
                        __( 'Renseigner la déclaration d’accessibilité uniquement avec une URL publique, une date et un statut issus d’un audit vérifiable.', 'a11y-widget' ),
                    ),
                ),
            ),
        ),
    );
    ?>
    <div
      id="a11y-info-backdrop"
      class="a11y-info-dialog-backdrop"
      aria-hidden="true"
      data-role="info-dialog-backdrop"
      hidden
    ></div>

    <aside
      id="a11y-info-dialog"
      class="a11y-info-dialog"
      role="dialog"
      aria-modal="true"
      aria-labelledby="a11y-info-title"
      aria-describedby="a11y-info-summary"
      aria-hidden="true"
      tabindex="-1"
      hidden
    >
      <div class="a11y-info-dialog__inner">
        <div class="a11y-info-dialog__header" id="a11y-info-handle">
          <div class="a11y-info-dialog__title-group">
            <h3 class="a11y-info-dialog__title" id="a11y-info-title"><?php echo esc_html__( 'Aide et tutoriels', 'a11y-widget' ); ?></h3>
            <p class="a11y-info-dialog__subtitle"><?php echo esc_html__( 'Comprendre, choisir et vérifier les réglages du module.', 'a11y-widget' ); ?></p>
          </div>
          <button
            type="button"
            class="a11y-info-dialog__close"
            id="a11y-info-close"
            aria-label="<?php echo esc_attr__( 'Fermer l’aide et les tutoriels', 'a11y-widget' ); ?>"
          >
            <span aria-hidden="true">✕</span>
          </button>
        </div>
        <div class="a11y-info-dialog__content" id="a11y-info-content">
          <div class="a11y-info-dialog__intro">
            <?php
            $info_search_label              = esc_html__( 'Rechercher dans l’aide', 'a11y-widget' );
            $info_search_placeholder        = esc_attr__( 'Rechercher un tutoriel…', 'a11y-widget' );
            $info_search_empty              = esc_html__( 'Aucun tutoriel ne correspond à cette recherche.', 'a11y-widget' );
            $info_search_result_singular    = esc_attr__( '%d rubrique trouvée dans l’aide.', 'a11y-widget' );
            $info_search_result_plural      = esc_attr__( '%d rubriques trouvées dans l’aide.', 'a11y-widget' );
            ?>
            <p class="a11y-info-summary" id="a11y-info-summary">
              <?php echo esc_html__( 'Utilisez ces tutoriels pour comprendre le module, choisir un réglage, utiliser le clavier et connaître les limites du widget.', 'a11y-widget' ); ?>
            </p>
            <form
              class="a11y-info-search"
              role="search"
              data-role="info-search-form"
              data-result-singular="<?php echo $info_search_result_singular; ?>"
              data-result-plural="<?php echo $info_search_result_plural; ?>"
              data-empty-text="<?php echo esc_attr( $info_search_empty ); ?>"
            >
              <label class="a11y-info-search__label" for="a11y-info-search"><?php echo $info_search_label; ?></label>
              <input
                type="search"
                id="a11y-info-search"
                class="a11y-info-search__input"
                placeholder="<?php echo $info_search_placeholder; ?>"
                autocomplete="off"
                aria-describedby="a11y-info-search-status"
                data-role="info-search-input"
              />
              <p
                class="a11y-info-search__status"
                id="a11y-info-search-status"
                role="status"
                aria-live="polite"
                data-role="info-search-status"
              ></p>
            </form>
            <ul class="a11y-info-overview" aria-label="<?php echo esc_attr__( 'Repères rapides du tutoriel', 'a11y-widget' ); ?>">
              <li class="a11y-info-overview__item">
                <strong><?php echo esc_html__( 'Comprendre', 'a11y-widget' ); ?></strong>
                <span><?php echo esc_html__( 'Identifier le rôle du module et les réglages disponibles.', 'a11y-widget' ); ?></span>
              </li>
              <li class="a11y-info-overview__item">
                <strong><?php echo esc_html__( 'Choisir', 'a11y-widget' ); ?></strong>
                <span><?php echo esc_html__( 'Partir d’un besoin concret sans transformer les catégories en diagnostic.', 'a11y-widget' ); ?></span>
              </li>
              <li class="a11y-info-overview__item">
                <strong><?php echo esc_html__( 'Vérifier', 'a11y-widget' ); ?></strong>
                <span><?php echo esc_html__( 'Tester au clavier, au zoom, sur mobile et avec des contenus réels.', 'a11y-widget' ); ?></span>
              </li>
            </ul>
          </div>
          <nav
            class="a11y-info-menu"
            data-role="info-menu"
            aria-label="<?php echo esc_attr__( 'Tutoriels du module', 'a11y-widget' ); ?>"
          >
            <p class="a11y-info-search__empty" data-role="info-search-empty" hidden><?php echo $info_search_empty; ?></p>
            <ul class="a11y-info-menu__list">
              <?php foreach ( $a11y_info_sections as $section_index => $section ) : ?>
                <?php
                $section_slug       = sanitize_title( isset( $section['slug'] ) ? (string) $section['slug'] : '' );
                $section_label      = isset( $section['label'] ) ? (string) $section['label'] : '';
                $section_heading    = isset( $section['heading'] ) ? (string) $section['heading'] : $section_label;
                $section_intro      = isset( $section['intro'] ) ? (string) $section['intro'] : '';
                $section_panels     = isset( $section['panels'] ) && is_array( $section['panels'] ) ? $section['panels'] : array();

                if ( '' === $section_slug || '' === $section_label ) {
                    continue;
                }

                $section_is_open  = 0 === $section_index;
                $section_toggle_id = 'a11y-info-menu-toggle-' . $section_slug;
                $section_panel_id  = 'a11y-info-menu-panel-' . $section_slug;
                ?>
                <li class="a11y-info-menu__item a11y-info-disclosure<?php echo $section_is_open ? ' is-open' : ''; ?>">
                  <button
                    type="button"
                    class="a11y-info-menu__toggle a11y-info-disclosure__toggle"
                    id="<?php echo esc_attr( $section_toggle_id ); ?>"
                    aria-expanded="<?php echo $section_is_open ? 'true' : 'false'; ?>"
                    aria-controls="<?php echo esc_attr( $section_panel_id ); ?>"
                    data-role="info-menu-toggle"
                  >
                    <span class="a11y-info-menu__label"><?php echo esc_html( $section_label ); ?></span>
                  </button>
                  <div
                    class="a11y-info-menu__panel a11y-info-disclosure__panel"
                    id="<?php echo esc_attr( $section_panel_id ); ?>"
                    role="region"
                    aria-labelledby="<?php echo esc_attr( $section_toggle_id ); ?>"
                    aria-hidden="<?php echo $section_is_open ? 'false' : 'true'; ?>"
                    <?php echo $section_is_open ? '' : 'hidden'; ?>
                    data-role="info-menu-panel"
                  >
                    <div class="a11y-info-panel__body">
                      <?php if ( '' !== trim( $section_intro ) ) : ?>
                        <p class="a11y-info-panel__intro"><?php echo esc_html( $section_intro ); ?></p>
                      <?php endif; ?>
                      <h4 class="a11y-info-heading"><?php echo esc_html( $section_heading ); ?></h4>
                      <div class="a11y-info-submenus">
                        <?php foreach ( $section_panels as $panel_index => $panel_item ) : ?>
                          <?php
                          $panel_slug      = sanitize_title( isset( $panel_item['slug'] ) ? (string) $panel_item['slug'] : '' );
                          $panel_title     = isset( $panel_item['title'] ) ? (string) $panel_item['title'] : '';
                          $paragraphs      = isset( $panel_item['paragraphs'] ) && is_array( $panel_item['paragraphs'] ) ? $panel_item['paragraphs'] : array();
                          $steps           = isset( $panel_item['steps'] ) && is_array( $panel_item['steps'] ) ? $panel_item['steps'] : array();
                          $items           = isset( $panel_item['items'] ) && is_array( $panel_item['items'] ) ? $panel_item['items'] : array();
                          $cards           = isset( $panel_item['cards'] ) && is_array( $panel_item['cards'] ) ? $panel_item['cards'] : array();
                          $dynamic_panel   = isset( $panel_item['dynamic'] ) ? sanitize_key( (string) $panel_item['dynamic'] ) : '';

                          if ( '' === $panel_slug || '' === $panel_title ) {
                              continue;
                          }

                          $panel_is_open  = $section_is_open && 0 === $panel_index;
                          $panel_toggle_id = 'a11y-info-submenu-toggle-' . $section_slug . '-' . $panel_slug;
                          $panel_id        = 'a11y-info-submenu-panel-' . $section_slug . '-' . $panel_slug;
                          ?>
                          <div class="a11y-info-submenu a11y-info-disclosure a11y-info-disclosure--nested<?php echo $panel_is_open ? ' is-open' : ''; ?>">
                            <button
                              type="button"
                              class="a11y-info-submenu__toggle a11y-info-disclosure__toggle"
                              id="<?php echo esc_attr( $panel_toggle_id ); ?>"
                              aria-expanded="<?php echo $panel_is_open ? 'true' : 'false'; ?>"
                              aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                              data-role="info-submenu-toggle"
                            >
                              <span><?php echo esc_html( $panel_title ); ?></span>
                            </button>
                            <div
                              class="a11y-info-submenu__panel a11y-info-disclosure__panel"
                              id="<?php echo esc_attr( $panel_id ); ?>"
                              role="region"
                              aria-labelledby="<?php echo esc_attr( $panel_toggle_id ); ?>"
                              aria-hidden="<?php echo $panel_is_open ? 'false' : 'true'; ?>"
                              <?php echo $panel_is_open ? '' : 'hidden'; ?>
                              data-role="info-submenu-panel"
                            >
                              <?php foreach ( $paragraphs as $paragraph ) : ?>
                                <p class="a11y-info-panel__text"><?php echo esc_html( (string) $paragraph ); ?></p>
                              <?php endforeach; ?>
                              <?php if ( 'glossary' === $dynamic_panel ) : ?>
                                <div class="a11y-info-dynamic a11y-info-glossary" data-role="info-glossary-content"></div>
                                <p class="a11y-info-dynamic__empty" data-role="info-glossary-empty" hidden><?php echo esc_html__( 'Le glossaire n’est pas disponible pour le moment.', 'a11y-widget' ); ?></p>
                              <?php endif; ?>
                              <?php if ( ! empty( $steps ) ) : ?>
                                <ol class="a11y-info-steps">
                                  <?php foreach ( $steps as $step ) : ?>
                                    <li><?php echo esc_html( (string) $step ); ?></li>
                                  <?php endforeach; ?>
                                </ol>
                              <?php endif; ?>
                              <?php if ( ! empty( $items ) ) : ?>
                                <ul class="a11y-info-list">
                                  <?php foreach ( $items as $item ) : ?>
                                    <li class="a11y-info-list__item"><p class="a11y-info-list__label"><?php echo esc_html( (string) $item ); ?></p></li>
                                  <?php endforeach; ?>
                                </ul>
                              <?php endif; ?>
                              <?php if ( ! empty( $cards ) ) : ?>
                                <div class="a11y-info-card-grid">
                                  <?php foreach ( $cards as $card ) : ?>
                                    <?php
                                    $card_title = isset( $card['title'] ) ? (string) $card['title'] : '';
                                    $card_text  = isset( $card['text'] ) ? (string) $card['text'] : '';
                                    ?>
                                    <article class="a11y-info-card">
                                      <h5 class="a11y-info-card__title"><?php echo esc_html( $card_title ); ?></h5>
                                      <p class="a11y-info-card__text"><?php echo esc_html( $card_text ); ?></p>
                                    </article>
                                  <?php endforeach; ?>
                                </div>
                              <?php endif; ?>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </nav>
        </div>
      </div>
    </aside>

    <div class="a11y-content" id="a11y-content">
      <?php
      $tutorial_intro  = esc_html__( 'Les raccourcis fonctionnent lorsque le panneau est ouvert. Ils activent ou désactivent les fonctions sans remplacer la navigation clavier standard.', 'a11y-widget' );
      $tutorial_legend = esc_html__( 'Windows / Linux : Alt + touche. macOS : Ctrl + Option + touche. Les raccourcis sont ignorés dans les champs de saisie.', 'a11y-widget' );
      $tutorial_empty  = esc_html__( 'Aucun raccourci n’est disponible pour les réglages actuellement affichés.', 'a11y-widget' );
      $structure_empty = esc_html__( 'La structure de page n’est pas disponible pour le moment.', 'a11y-widget' );
      ?>
      <div
        id="a11y-shortcuts-backdrop"
        class="a11y-shortcuts-dialog-backdrop"
        aria-hidden="true"
        data-role="shortcuts-dialog-backdrop"
        hidden
      ></div>

      <aside
        class="a11y-shortcuts-dialog"
        id="a11y-tutorial"
        role="dialog"
        aria-modal="false"
        aria-labelledby="a11y-tutorial-title"
        aria-describedby="a11y-tutorial-summary"
        hidden
        aria-hidden="true"
        tabindex="-1"
        data-role="tutorial"
        data-platform-win-label="<?php echo esc_attr__( 'Windows / Linux', 'a11y-widget' ); ?>"
        data-platform-mac-label="<?php echo esc_attr__( 'macOS', 'a11y-widget' ); ?>"
        data-shortcut-win-pattern="<?php echo esc_attr__( 'Alt + %s', 'a11y-widget' ); ?>"
        data-shortcut-mac-pattern="<?php echo esc_attr__( 'Ctrl + Option + %s', 'a11y-widget' ); ?>"
      >
        <div class="a11y-shortcuts-dialog__inner">
          <div class="a11y-shortcuts-dialog__header">
            <div class="a11y-shortcuts-dialog__title-group">
              <h3 class="a11y-shortcuts-dialog__title" id="a11y-tutorial-title"><?php echo esc_html__( 'Raccourcis clavier', 'a11y-widget' ); ?></h3>
              <p class="a11y-shortcuts-dialog__subtitle"><?php echo esc_html__( 'Combinaisons actives uniquement lorsque le panneau est ouvert.', 'a11y-widget' ); ?></p>
            </div>
            <button
              type="button"
              class="a11y-shortcuts-dialog__close"
              id="a11y-tutorial-close"
              aria-label="<?php echo esc_attr__( 'Fermer les raccourcis clavier', 'a11y-widget' ); ?>"
            >
              <span aria-hidden="true">✕</span>
            </button>
          </div>
          <div class="a11y-shortcuts-dialog__content">
            <div class="a11y-tutorial">
              <p class="a11y-tutorial__intro" id="a11y-tutorial-summary"><?php echo $tutorial_intro; ?></p>
              <p class="a11y-tutorial__legend" data-role="tutorial-legend"><?php echo $tutorial_legend; ?></p>
              <ul class="a11y-tutorial__list" data-role="tutorial-list"></ul>
              <p class="a11y-tutorial__empty" data-role="tutorial-empty" hidden><?php echo $tutorial_empty; ?></p>
            </div>
          </div>
        </div>
      </aside>

      <section
        class="a11y-structure-view"
        id="a11y-structure-view"
        role="region"
        aria-labelledby="a11y-structure-view-title"
        hidden
        aria-hidden="true"
        data-role="structure-view"
      >
        <div class="a11y-structure-view__header">
          <h3 class="a11y-structure-view__title" id="a11y-structure-view-title"><?php echo esc_html__( 'Structure de page', 'a11y-widget' ); ?></h3>
          <button
            type="button"
            class="a11y-structure-view__close"
            id="a11y-structure-close"
            aria-label="<?php echo esc_attr__( 'Fermer la structure de page', 'a11y-widget' ); ?>"
          >
            <span aria-hidden="true">✕</span>
          </button>
        </div>
        <div class="a11y-structure-view__content" data-role="structure-view-content"></div>
        <p class="a11y-structure-view__empty" data-role="structure-view-empty" hidden><?php echo $structure_empty; ?></p>
      </section>

      <div
        class="a11y-options-view"
        id="a11y-options-view"
        data-role="options-view"
        aria-hidden="false"
      >
      <?php
      $search_label       = esc_html__( 'Rechercher une fonctionnalité', 'a11y-widget' );
      $search_placeholder = esc_attr__( 'Rechercher une fonctionnalité…', 'a11y-widget' );
      $search_empty       = esc_html__( 'Aucun résultat ne correspond à votre recherche pour le moment.', 'a11y-widget' );
      ?>
      <form class="a11y-search" role="search" data-role="search-form">
        <label class="a11y-search__label" for="a11y-search"><?php echo $search_label; ?></label>
        <input
          type="search"
          id="a11y-search"
          class="a11y-search__input"
          placeholder="<?php echo $search_placeholder; ?>"
          autocomplete="off"
        />
      </form>

      <section
        class="a11y-active-summary"
        data-role="active-summary"
        aria-live="polite"
        aria-label="<?php echo esc_attr__( 'Résumé des réglages actifs', 'a11y-widget' ); ?>"
      >
        <span class="a11y-active-summary__count" data-role="active-summary-count">0</span>
        <span class="a11y-active-summary__label" data-role="active-summary-label"><?php echo esc_html__( 'Aucun réglage actif', 'a11y-widget' ); ?></span>
        <button
          type="button"
          class="a11y-active-summary__undo"
          id="a11y-active-undo"
          disabled
        >
          <?php echo esc_html__( 'Annuler', 'a11y-widget' ); ?>
        </button>
        <button
          type="button"
          class="a11y-active-summary__reset"
          id="a11y-active-reset"
          disabled
        >
          <?php echo esc_html__( 'Réinitialiser', 'a11y-widget' ); ?>
        </button>
      </section>

      <section
        class="a11y-favorites"
        data-role="favorites-panel"
        aria-labelledby="a11y-favorites-title"
        hidden
      >
        <div class="a11y-favorites__header">
          <h3 class="a11y-favorites__title" id="a11y-favorites-title"><?php echo esc_html__( 'Favoris', 'a11y-widget' ); ?></h3>
          <p class="a11y-favorites__count" data-role="favorites-count"><?php echo esc_html__( '0/5', 'a11y-widget' ); ?></p>
        </div>
        <div class="a11y-favorites__list" data-role="favorites-list"></div>
        <p class="a11y-favorites__empty" data-role="favorites-empty"><?php echo esc_html__( 'Épinglez jusqu’à 5 réglages avec l’étoile.', 'a11y-widget' ); ?></p>
      </section>

      <section
        class="a11y-search-results"
        data-role="search-results"
        hidden
        aria-hidden="true"
        aria-live="polite"
      >
        <h3 class="a11y-search-results__title" data-sr-only><?php echo esc_html__( 'Résultats de recherche', 'a11y-widget' ); ?></h3>
        <div class="a11y-search-results__list" data-role="search-list"></div>
        <p class="a11y-empty" data-role="search-empty" hidden><?php echo $search_empty; ?></p>
      </section>

      <?php
      $sections                  = a11y_widget_get_sections();
      $footer_utility_section_id = '';
      $footer_utility_panel_id   = '';
      ?>
      <?php if ( ! empty( $sections ) ) : ?>
        <?php
        $accordion_id = 'a11y-section-accordion';
        $template_id = 'a11y-feature-template';
        $payload     = array();
        ?>
        <section
          id="<?php echo esc_attr( $accordion_id ); ?>"
          class="a11y-tabs"
          aria-labelledby="a11y-section-accordion-title"
          data-role="section-accordion"
        >
          <h3 class="a11y-sr-only" id="a11y-section-accordion-title"><?php echo esc_html__( 'Catégories d’accessibilité', 'a11y-widget' ); ?></h3>
          <?php foreach ( $sections as $index => $section ) :
            $section_slug  = ! empty( $section['slug'] ) ? sanitize_title( $section['slug'] ) : '';
            $section_id    = $section_slug ? $section_slug : ( ! empty( $section['id'] ) ? sanitize_title( $section['id'] ) : sanitize_title( uniqid( 'a11y-sec-', true ) ) );
            $section_title = isset( $section['title'] ) ? $section['title'] : '';
            $children      = isset( $section['children'] ) ? (array) $section['children'] : array();
            $features_data = array();
            $section_icon  = isset( $section['icon'] ) ? sanitize_key( $section['icon'] ) : '';
            $icon_markup   = '';

            if ( ! empty( $children ) ) {
                foreach ( $children as $feature ) {
                    $feature_slug       = isset( $feature['slug'] ) ? sanitize_title( $feature['slug'] ) : '';
                    $feature_label      = isset( $feature['label'] ) ? $feature['label'] : '';
                    $feature_hint       = isset( $feature['hint'] ) ? $feature['hint'] : '';
                    $feature_aria_label = isset( $feature['aria_label'] ) ? $feature['aria_label'] : $feature_label;

                    if ( '' === $feature_slug || '' === $feature_label ) {
                        continue;
                    }

                    $children_payload = array();
                    if ( isset( $feature['children'] ) && is_array( $feature['children'] ) ) {
                        foreach ( $feature['children'] as $sub_feature ) {
                            $sub_slug       = isset( $sub_feature['slug'] ) ? sanitize_title( $sub_feature['slug'] ) : '';
                            $sub_label      = isset( $sub_feature['label'] ) ? $sub_feature['label'] : '';
                            $sub_hint       = isset( $sub_feature['hint'] ) ? $sub_feature['hint'] : '';
                            $sub_aria_label = isset( $sub_feature['aria_label'] ) ? $sub_feature['aria_label'] : $sub_label;

                            if ( '' === $sub_slug || '' === $sub_label ) {
                                continue;
                            }

                            $children_payload[] = array(
                                'slug'       => $sub_slug,
                                'label'      => wp_strip_all_tags( $sub_label ),
                                'hint'       => wp_strip_all_tags( $sub_hint ),
                                'aria_label' => wp_strip_all_tags( $sub_aria_label ),
                            );
                        }
                    }

                    $feature_payload = array(
                        'slug'       => $feature_slug,
                        'label'      => wp_strip_all_tags( $feature_label ),
                        'hint'       => wp_strip_all_tags( $feature_hint ),
                        'aria_label' => wp_strip_all_tags( $feature_aria_label ),
                    );

                    if ( isset( $feature['template'] ) ) {
                        $feature_template = sanitize_key( $feature['template'] );
                        if ( '' !== $feature_template ) {
                            $feature_payload['template'] = $feature_template;
                        }
                    }

                    if ( isset( $feature['settings'] ) && is_array( $feature['settings'] ) ) {
                        $settings_payload = array();
                        foreach ( $feature['settings'] as $setting_key => $setting_value ) {
                            $setting_slug = sanitize_key( $setting_key );

                            if ( '' === $setting_slug ) {
                                continue;
                            }

                            if ( is_scalar( $setting_value ) ) {
                                $settings_payload[ $setting_slug ] = wp_strip_all_tags( (string) $setting_value );
                            } elseif ( 'credits_people' === $setting_slug && is_array( $setting_value ) ) {
                                $credits_people = array();

                                foreach ( $setting_value as $person_name ) {
                                    if ( ! is_scalar( $person_name ) ) {
                                        continue;
                                    }

                                    $person_name = trim( wp_strip_all_tags( (string) $person_name ) );

                                    if ( '' !== $person_name ) {
                                        $credits_people[] = $person_name;
                                    }
                                }

                                if ( ! empty( $credits_people ) ) {
                                    $settings_payload[ $setting_slug ] = $credits_people;
                                }
                            } elseif ( 'profiles' === $setting_slug && is_array( $setting_value ) ) {
                                $profiles_payload = array();

                                foreach ( $setting_value as $profile ) {
                                    if ( ! is_array( $profile ) ) {
                                        continue;
                                    }

                                    $profile_key = isset( $profile['key'] ) ? sanitize_key( (string) $profile['key'] ) : '';

                                    if ( '' === $profile_key ) {
                                        continue;
                                    }

                                    $profile_features = isset( $profile['features'] ) && is_array( $profile['features'] )
                                        ? a11y_widget_normalize_feature_slugs( $profile['features'] )
                                        : array();

                                    $profiles_payload[] = array(
                                        'key'      => $profile_key,
                                        'label'    => isset( $profile['label'] ) ? wp_strip_all_tags( (string) $profile['label'] ) : '',
                                        'hint'     => isset( $profile['hint'] ) ? wp_strip_all_tags( (string) $profile['hint'] ) : '',
                                        'features' => $profile_features,
                                    );
                                }

                                if ( ! empty( $profiles_payload ) ) {
                                    $settings_payload[ $setting_slug ] = $profiles_payload;
                                }
                            }
                        }

                        if ( ! empty( $settings_payload ) ) {
                            $feature_payload['settings'] = $settings_payload;
                        }
                    }

                    if ( ! empty( $children_payload ) ) {
                        $feature_payload['children'] = $children_payload;
                    }

                    $features_data[] = $feature_payload;
                }
            }

            if ( '' !== $section_icon && function_exists( 'a11y_widget_get_icon_markup' ) ) {
                $icon_markup = a11y_widget_get_icon_markup(
                    $section_icon,
                    array(
                        'class' => 'a11y-tab__icon-svg',
                    )
                );
            }

            $payload[] = array(
                'index'    => (int) $index,
                'id'       => $section_id,
                'slug'     => $section_slug ? $section_slug : $section_id,
                'title'    => wp_strip_all_tags( $section_title ),
                'icon'     => $section_icon,
                'features' => $features_data,
            );

            $toggle_id = 'a11y-accordion-toggle-' . $section_id;
            $panel_id  = 'a11y-accordion-panel-' . $section_id;
            $is_first = 0 === (int) $index;
            $is_footer_utility = 'retours-informations' === $section_id;
            $tab_class = 'a11y-tab';
            $tab_item_classes = array( 'a11y-tab-item' );

            if ( $is_footer_utility ) {
              $tab_item_classes[]        = 'a11y-tab-item--footer-utility';
              $footer_utility_section_id = $section_id;
              $footer_utility_panel_id   = $panel_id;
            }
            ?>
            <div class="<?php echo esc_attr( implode( ' ', $tab_item_classes ) ); ?>" data-role="accordion-item" data-section-id="<?php echo esc_attr( $section_id ); ?>">
              <h4 class="a11y-tab-heading">
                <button
                  type="button"
                  class="<?php echo esc_attr( $tab_class ); ?>"
                  id="<?php echo esc_attr( $toggle_id ); ?>"
                  aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                  aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                  data-role="section-toggle"
                  data-section-index="<?php echo esc_attr( $index ); ?>"
                  data-section-id="<?php echo esc_attr( $section_id ); ?>"
                  <?php if ( '' !== $section_icon ) : ?>
                    data-section-icon="<?php echo esc_attr( $section_icon ); ?>"
                  <?php endif; ?>
                >
                  <?php if ( '' !== $icon_markup ) : ?>
                    <span class="a11y-tab__icon" aria-hidden="true"><?php echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                  <?php endif; ?>
                  <span class="a11y-tab__label"><?php echo esc_html( $section_title ); ?></span>
                  <span class="a11y-tab__count" data-role="section-active-count" hidden>0</span>
                </button>
              </h4>
              <div
                class="a11y-section-panel"
                id="<?php echo esc_attr( $panel_id ); ?>"
                data-role="section-panel"
                data-section-id="<?php echo esc_attr( $section_id ); ?>"
                aria-labelledby="<?php echo esc_attr( $toggle_id ); ?>"
                <?php echo $is_first ? '' : 'hidden'; ?>
              >
                <div class="a11y-grid" data-role="feature-grid"></div>
                <p class="a11y-empty" data-role="feature-empty" hidden><?php echo esc_html__( 'Aucune fonctionnalité disponible pour le moment.', 'a11y-widget' ); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </section>

        <template id="<?php echo esc_attr( $template_id ); ?>" data-role="feature-placeholder-template">
          <article class="a11y-card" data-role="feature-card">
            <div class="meta" data-role="feature-meta">
              <span class="label" data-role="feature-label"></span>
            </div>
            <label class="a11y-switch" data-role="feature-switch">
              <input type="checkbox" data-role="feature-input" data-feature="" aria-label="" />
              <span class="track"></span><span class="thumb"></span>
            </label>
          </article>
        </template>

        <script type="application/json" data-role="feature-data">
          <?php echo wp_json_encode( $payload ); ?>
        </script>
      <?php else : ?>
        <p class="a11y-empty"><?php echo esc_html__( 'Aucune fonctionnalité disponible pour le moment.', 'a11y-widget' ); ?></p>
      <?php endif; ?>
      </div>
    </div>

    <footer class="a11y-footer">
      <div class="a11y-footer__group a11y-footer__group--start">
        <?php if ( '' !== $footer_utility_section_id && '' !== $footer_utility_panel_id ) : ?>
          <button
            type="button"
            class="a11y-btn a11y-btn--icon a11y-footer__utility-toggle"
            id="a11y-footer-utility-toggle"
            aria-expanded="false"
            aria-controls="<?php echo esc_attr( $footer_utility_panel_id ); ?>"
            aria-label="<?php echo esc_attr__( 'Afficher les retours et informations', 'a11y-widget' ); ?>"
            title="<?php echo esc_attr__( 'Afficher les retours et informations', 'a11y-widget' ); ?>"
            data-tooltip="<?php echo esc_attr__( 'Retours et informations', 'a11y-widget' ); ?>"
            data-section-id="<?php echo esc_attr( $footer_utility_section_id ); ?>"
          >
            <span class="a11y-btn__icon" aria-hidden="true">
              <?php
              if ( function_exists( 'a11y_widget_get_icon_markup' ) ) {
                echo a11y_widget_get_icon_markup( 'message-circle', array( 'class' => 'a11y-btn__icon-svg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              } else {
                echo '<span aria-hidden="true">?</span>';
              }
              ?>
            </span>
            <span class="a11y-sr-only"><?php echo esc_html__( 'Retours et informations', 'a11y-widget' ); ?></span>
          </button>
        <?php endif; ?>
        <button
          type="button"
          class="a11y-btn a11y-btn--reset"
          id="a11y-reset"
          aria-label="<?php echo esc_attr__( 'Réinitialiser les préférences du widget', 'a11y-widget' ); ?>"
          title="<?php echo esc_attr__( 'Réinitialiser les préférences du widget', 'a11y-widget' ); ?>"
          data-tooltip="<?php echo esc_attr__( 'Réinitialiser', 'a11y-widget' ); ?>"
        >
          <span class="a11y-btn__icon" aria-hidden="true">
            <?php
            if ( function_exists( 'a11y_widget_get_icon_markup' ) ) {
              echo a11y_widget_get_icon_markup( 'rotate-ccw', array( 'class' => 'a11y-btn__icon-svg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } else {
              echo '<span aria-hidden="true">↺</span>';
            }
            ?>
          </span>
          <span class="a11y-btn__text"><?php echo esc_html__('Réinitialiser', 'a11y-widget'); ?></span>
        </button>
      </div>
      <div class="a11y-footer__group a11y-footer__group--end">
        <button
          type="button"
          class="a11y-btn a11y-btn--icon a11y-footer__close"
          id="a11y-close2"
          aria-label="<?php echo esc_attr__( 'Fermer le panneau', 'a11y-widget' ); ?>"
          title="<?php echo esc_attr__( 'Fermer le panneau', 'a11y-widget' ); ?>"
          data-tooltip="<?php echo esc_attr__( 'Fermer', 'a11y-widget' ); ?>"
        >
          <span class="a11y-btn__icon" aria-hidden="true">
            <?php
            if ( function_exists( 'a11y_widget_get_icon_markup' ) ) {
              echo a11y_widget_get_icon_markup( 'x', array( 'class' => 'a11y-btn__icon-svg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } else {
              echo '<span aria-hidden="true">×</span>';
            }
            ?>
          </span>
          <span class="a11y-sr-only"><?php echo esc_html__('Fermer', 'a11y-widget'); ?></span>
        </button>
      </div>
    </footer>
  </section>
</div>

<div
  class="a11y-overlay"
  id="a11y-overlay"
  role="presentation"
  aria-hidden="true"
  data-a11y-filter-exempt
  data-background-mode="<?php echo esc_attr( $background_mode ); ?>"
></div>
