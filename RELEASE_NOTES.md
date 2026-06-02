# Release notes — A11y Widget MOBLS 2025-2026

## Version 1.5.0 - stabilisation UX/RGAA du panneau

Cette version stabilise le panneau public, renforce l'administration WordPress et clarifie le fonctionnement en combo avec `RGAA_Audit`, sans fusionner les deux périmètres.

### Panneau principal et clavier

- Refonte du haut du panneau autour de commandes explicites : Options, Raccourcis clavier, Aide, placement gauche/droite et fermeture.
- Passage des commandes Options, Raccourcis clavier, Aide et déplacement gauche/droite en boutons icône seuls, avec `aria-label`, texte masqué pour les technologies d’assistance et infobulles visibles.
- Rangement du **Glossaire dans l’aide** : le glossaire n’est plus un bouton d’en-tête séparé et reste accessible dans la fenêtre Aide et tutoriels.
- Ajout d’un bouton icône Structure de page dans le haut du panneau, avec une vue dédiée sortie de la catégorie Lecture.
- Ajout d’un bouton de fermeture dans la vue **Structure de page** pour revenir explicitement à l’ancre du panneau.
- Allègement de la carte Déclaration d’accessibilité côté widget : elle n’affiche plus la grille détaillée d’audit, seulement un résumé public et le lien de déclaration.
- Masquage automatique de la Déclaration côté widget si aucune URL publique n’est renseignée.
- Séparation du Retour utilisateur hors de la catégorie Profils, avec une catégorie dédiée lorsqu’une collecte est activée.
- Ajout d’un état visible et accessible pour le profil appliqué : le bouton du profil actif est marqué et annonce son état.
- Stabilisation des profils : l’état actif dépend maintenant des réglages réellement actifs, un profil partiellement modifié est signalé comme partiel et le reset global efface aussi le dernier profil mémorisé.
- L’action de désactivation des profils cible désormais le profil courant ou dernier profil appliqué, sans confondre ce reset partiel avec la réinitialisation globale du widget.
- Passage du Feedback utilisateur en collecte backend optionnelle : la carte n’apparaît que si l’administration active la collecte et les retours sont envoyés à WordPress avec consentement.
- Correction des réglages simples Réduire les distractions, Mode texte seul et Masquer les images : ils utilisent maintenant un bouton standard avec état `aria-pressed`.
- Les titres de catégories peuvent maintenant ouvrir puis refermer leur propre panneau, sans obliger l’utilisateur à cliquer sur une autre catégorie.
- Correction du modèle ARIA des catégories : l’interface est maintenant exposée comme un accordéon de boutons (`aria-expanded` / `aria-controls`) et n’utilise plus le pattern trompeur `tablist` / `tab` / `tabpanel`.
- Compactage de la carte Structure de page : lignes, titres et boutons Aller moins volumineux pour limiter le défilement.
- Passage des **Raccourcis clavier** en fenêtre superposée, comme l’aide, pour ne plus remplacer le contenu principal du panneau.
- Ancrage de la fenêtre **Raccourcis clavier** au bord du panneau : elle s’ouvre à gauche quand le panneau est à droite, à droite quand le panneau est à gauche, et ne se referme plus à cause d’un état de transition précédent.
- Correction des débordements invisibles du haut du panneau : les infobulles et libellés masqués des boutons icône ne créent plus de largeur scrollable.
- Correction des débordements internes des switches et des réglages avancés de luminosité sur mobile étroit.
- Restauration du contenu des catégories Mouvement et Audio / vidéo après reclassement : réduction des déclencheurs visuels et lecture à voix haute restent disponibles.
- Raccourcissement du titre visible `Mouvement / sécurité visuelle` en `Mouvement`, avec le détail de réduction des déclencheurs conservé dans les descriptions.
- Compactage typographique de la carte `Retour utilisateur` : choix radio, champ commentaire, note de confidentialité et textes secondaires moins lourds visuellement.
- Remplacement de l’accent latéral du statut de lecture audio par une bordure complète plus sobre.
- Ajout d’un résumé des réglages actifs, d’un raccourci de réinitialisation contextuel et de compteurs par catégorie pour mieux situer l’état du panneau.
- Ajout de favoris utilisateur : jusqu’à cinq réglages peuvent être épinglés en haut du panneau et restent synchronisés avec les cartes d’origine.
- Ajout d’une action **Annuler** pour revenir sur le dernier réglage activé ou désactivé sans lancer une réinitialisation globale.
- Extension de la recherche par synonymes et formulations d’usage : fatigue visuelle, texte trop petit, lecture difficile, écouter, curseur invisible, flashs, braille, etc.
- Suppression du bloc **Démarrer avec**, redondant avec les profils, et clarification de la catégorie **Profils rapides** avec des profils présentés comme points de départ.
- Durcissement de l’ouverture clavier du lanceur : `Entrée` et `Espace` déclenchent explicitement le panneau.
- Correction du parcours clavier des catégories : chaque titre reste un bouton natif dans l’ordre de tabulation, sans roving tabindex hérité des onglets.
- Recalcul de la focalisation du panneau actif à l’ouverture du widget, afin d’éviter un état hérité du rendu masqué initial.
- Correction des sliders du guide de lecture : les réglages Opacité et Taille ont maintenant une association native `label for` / `input id`.
- Ajout de styles de règle de lecture : barre, lightbox, ombre et soulignement, avec presets sobres et réversibles.
- Extension du module Dyslexie avec presets d’espacement, suppression de motifs visuels, largeur de ligne, alignement à gauche et patterns de surlignage graphèmes / confusions / morphèmes.
- Le profil **Lecture confortable** applique maintenant des réglages concrets : guide de lecture, espacement de texte, patterns graphémiques et lecture audio de confort prête à l’emploi.
- Le profil **Lecture confortable** remet aussi le guide de lecture en mode barre sans conserver le mode focus du profil **Concentration**.
- Les retours utilisateurs envoyés au backend ne transmettent plus un profil simplement mémorisé : le profil n’est joint que s’il correspond encore aux réglages actifs.
- Ajout d’une visionneuse Braille d’exemple dans les cartes de braille visuel, avec ligne braille Unicode, ligne texte, copie de la transcription, mode points agrandis et détachement en fenêtre déplaçable.
- Ajout d’une carte dédiée **Créatrices de l’application** dans **Retours et informations**, séparée de la carte **Déclaration d’accessibilité** et conservant le traitement gris demandé.

### Aide et tutoriels

- Agrandissement du dialogue Aide et tutoriels pour mieux utiliser la largeur disponible.
- Passage en mise en page à deux colonnes sur écran large : navigation des rubriques à gauche, contenu à droite.
- Traitement de l’aide comme un sous-dialogue modal : focus piégé, fermeture par Échap, retour du focus au bouton Aide.
- Ajout d’une couche de capture du sous-dialogue d’aide : le panneau derrière l’aide n’est plus cliquable tant que la fenêtre est ouverte.
- Une seule grande rubrique d’aide reste ouverte à la fois pour réduire l’effet d’accordéon long et difficile à parcourir.
- Ajout d’une recherche interne dans **Aide et tutoriels** pour filtrer les rubriques et sous-panneaux par mot-clé.
- Ajout d’un espacement haut dans les sous-accordéons pour que les textes ne collent plus à la bordure de leur menu.
- Passage des contrôles critiques du panneau et de l’aide à une cible tactile minimale de 44 px, y compris sur mobile étroit.
- Regroupement de **Retour utilisateur** et de la déclaration publique dans une action de pied de panneau **Retours et informations**, afin d’éviter deux catégories utilitaires redondantes.
- Remplacement de la fermeture de pied de panneau par une croix nommée et infobulle, et ajout d’une icône de réinitialisation `rotate-ccw` tout en conservant le libellé visible **Réinitialiser**.
- Correction des infobulles de pied de panneau sur les bords gauche et droit : elles restent maintenant visibles dans le panneau, sans être coupées par le conteneur.

### Technique et recette

- Ouverture de la branche source `1.5.0-dev` après gel du ZIP final `1.4.6`.
- Ajout d’une carte admin **Intégration RGAA_Audit** qui affiche l’état de détection, la version, le lien administrateur et le mode de liaison, sans importer la grille d’audit dans le widget.
- Amélioration de l’admin **Retours utilisateurs** : vue détail par retour, note interne privée, statut modifiable depuis le détail, export CSV enrichi et lien manuel vers `RGAA_Audit` sans création automatique d’anomalie.
- Ajout d’un import/export JSON de configuration dans l’admin : fonctionnalités visibles, ordre, sous-ordre, apparence, logo, mode d’arrière-plan, guide de lecture, profils de confort, déclaration synthétique et collecte feedback.
- L’import/export de configuration exclut explicitement les retours utilisateurs, notes de retour, notes internes d’audit et données détaillées `RGAA_Audit`; les notes internes d’audit existantes sont conservées à l’import.
- Ajout d’un sous-menu admin **Santé du widget** : diagnostic local des assets, fonctionnalités visibles, mode d’arrière-plan, injection automatique, feedback, déclaration et liaison `RGAA_Audit`, sans télémétrie externe.
- Densification responsive du panneau front : raccourcis guidés en rangée défilante sur largeur ou hauteur contrainte, scroll interne mieux contenu, `100dvh` mobile et infobulles d’icônes stabilisées.
- Ajout d’un sous-menu admin **Assistant de configuration** : parcours guidé pour choisir le mode d’ouverture, le périmètre visible, la déclaration publique et les retours utilisateurs, avec `RGAA_Audit` limité à un statut de liaison en lecture.
- Ajout d’un sous-menu admin **Profils de confort** : activation, libellés, aides courtes et composition des profils rapides, exportés avec la configuration sans intégrer les données détaillées `RGAA_Audit`.
- Ajout d’une couche légère de tokens CSS sémantiques pour le widget et l’admin, sans refonte visuelle, afin de préserver les décisions de design validées tout en réduisant les couleurs dupliquées.
- Ajout de `docs/ARCHITECTURE_COMBO_RGAA_AUDIT.md` pour formaliser la séparation entre le widget de confort et l’application compagnon `RGAA_Audit`.
- Clarification du combo : `RGAA_Audit` reste la source de vérité pour les critères, preuves, anomalies, rapports et statuts de conformité ; le widget garde la déclaration synthétique, les retours utilisateurs et les aides de confort.
- Ajout d’un sous-menu admin **Audit et suivi** pour centraliser les informations d’audit, les liens de rapport et les points de vigilance sans charger le widget front.
- Ajout d’un sous-menu admin **Crédits**, accessible aussi depuis **Audit et suivi**, listant les créatrices de l’application et le cadre du projet MOBLS.
- Ajout d’un sous-menu admin **Retours utilisateurs** listant les derniers retours reçus, leur page, leur commentaire et le contexte de réglages.
- Ajout d’un flux de traitement des retours : statuts Nouveau, Vu, À traiter, Traité, Archivé, suppression sécurisée par nonce et export CSV.
- Ajout de filtres par statut, compteurs, durée de conservation et purges sécurisées des retours utilisateurs côté administration.
- L’export CSV des retours respecte maintenant le filtre de statut courant.
- Amélioration de l’admin **Retours utilisateurs** : état vide plus utile, table responsive, colonnes stabilisées et actions moins tassées.
- Durcissement backend des retours : cadence limitée côté AJAX, URL de page limitée au site courant, profil validé, payload JSON plafonné, identifiants admin déséchappés avant sanitization et cellules CSV protégées contre les formules.
- Ajout d’une purge automatique quotidienne des retours expirés via WP-Cron, avec planification à l’activation, nettoyage à la désactivation/désinstallation et affichage du prochain passage côté administration.
- Correction de l’alignement du formulaire admin **Audit et suivi** : grille explicite, champs de même hauteur et descriptions qui ne décalent plus les colonnes voisines.
- Versionnement des assets CSS/JS avec `filemtime()` en complément de la version du plugin, pour éviter de tester un ancien fichier gardé en cache.
- Ajout de régressions statiques sur les libellés des contrôles, le focus des panneaux, le dialogue d’aide et le cache-busting des assets.
- Ajout d’un test visuel Playwright optionnel sur une fixture de panneau : favoris, annulation, reflow mobile/petite hauteur et visionneuse braille.
- Vérifications locales effectuées dans Local WP : lint PHP, contrôle syntaxique JS, tests statiques Node, audit DOM navigateur, recette responsive 360 px / petite hauteur et absence d’erreurs console.
- Recette finale du 31/05/2026 : parcours clavier du lanceur et des actions de pied, noms accessibles, reflow équivalent zoom 200 %, mobile 320 px, infobulles non coupées et réinitialisation globale validés dans Chrome sur l’instance Local WP.
- Recette de livraison du 02/06/2026 : tests statiques, contrôles syntaxiques JS, lint PHP 8.0/8.2, tests TTS et syllabification validés avant packaging du ZIP final.

## Version 1.4.6 — aide et tutoriels intégrés

Cette release ne modifie pas les fonctionnalités de fond et ne réintroduit pas de fonctionnalité IA. Elle améliore la partie pédagogique du widget pour que les utilisateurs comprennent mieux les réglages, les scénarios d’usage et les limites du module.

### Tutoriels intégrés

- Remplacement de l’ancienne fenêtre didactique centrée sur la luminosité par une fenêtre **Aide et tutoriels** structurée.
- Ajout de six rubriques : prise en main, choix selon le besoin, catégories du module, clavier et raccourcis, données et limites, démo et administration.
- Ajout de cartes de besoins fréquents pour orienter l’utilisateur sans diagnostic médical ni promesse de correction automatique.
- Première rubrique et premier panneau ouverts par défaut pour donner un point d’entrée immédiat.
- Ajout d’explications sur le mode modal ou interactif selon le réglage administrateur.

### Clarification des limites

- Le guide rappelle directement dans l’interface que le widget ne corrige pas à lui seul le code du site.
- La lecture audio est décrite comme une lecture de confort, non comme un lecteur d’écran.
- Le braille visuel est décrit comme une démonstration Unicode, non comme une prise en charge d’une plage braille matérielle.
- Les réglages liés aux mouvements, flashs et variations lumineuses sont décrits comme des aides de réduction d’exposition, sans garantie de suppression du risque.
- Les textes évitent les promesses de conformité automatique, de protection médicale ou de remplacement des technologies d’assistance.

### Raccourcis

- Le panneau **Raccourcis** précise maintenant que les raccourcis ne sont actifs que lorsque le panneau est ouvert.
- Le texte explique que cette limitation vise à réduire les conflits avec le navigateur et les technologies d’assistance.
- Les raccourcis sont signalés comme ignorés dans les champs de saisie.

### Documentation

- Ajout de `docs/GUIDE_UTILISATION_WIDGET.md` pour documenter le contenu du guide intégré, ses principes de rédaction et les points de test associés.
- Mise à jour du readme avec les nouveautés 1.4.6.
- Ajout d’un bloc de recette dédié à l’aide et aux tutoriels intégrés dans `TESTING.md`.

### Points hors périmètre

- Pas de migration vers `@wordpress/ui`.
- Pas d’ajout IA.
- Pas d’audit RGAA/WCAG complet.

## Version 1.4.5 — alignement WordPress 7.0 et personnalisation visuelle

Cette release conserve le nom de l’application et ne réintroduit pas de fonctionnalité IA. Elle cible l’intégration WordPress : écran d’administration plus sobre, réglages visuels configurables et wording plus prudent pour les déclencheurs visuels.

### Administration WordPress

- Interface admin rapprochée de l’esprit WordPress 7.0 : couleurs d’administration, surfaces neutres, bordures simples, suppression des effets visuels trop marqués.
- Ajout de liens d’ancrage internes vers les grands blocs de réglages : comportement, personnalisation, fonctionnalités et lecture.
- Ajout d’une couche CSS `prefers-reduced-motion` pour éviter les animations dans l’administration quand l’utilisateur demande une réduction du mouvement.
- Conservation du nom et de l’entrée de menu existants.

### Personnalisation visuelle

- Ajout d’un bloc « Personnalisation visuelle » dans les options.
- Ajout de trois modes : `MOBLS`, `WordPress neutre` et `Personnalisé`.
- Ajout de réglages de couleurs : couleur principale, contraste de la couleur principale, surface du panneau, surface secondaire, texte principal, texte secondaire et bordures.
- Ajout d’un réglage de rayon des coins, limité à 32 px.
- Application côté front via variables CSS injectées par WordPress, sans modifier les fichiers CSS générés par l’utilisateur.

### Clarification produit

- Renommage affiché de la catégorie « Épilepsie » en « Mouvement et flashs ». Les slugs techniques existants restent inchangés pour préserver la compatibilité des préférences, des tests et des options stockées.

### Documentation et pistes

- Ajout de `docs/WORDPRESS_UI_EXPERIMENT.md` pour cadrer une expérimentation future avec `@wordpress/ui` et `@wordpress/admin-ui`.
- Ajout de `docs/WORDPRESS_ACCESSIBILITY_INTEGRATIONS.md` pour lister des intégrations WordPress utiles autour de l’accessibilité : éditeur de blocs, Site Health, WP-CLI, signalement utilisateur et tableau de bord.

### Points hors périmètre

- Pas de migration vers `@wordpress/ui` dans cette version.
- Pas d’audit RGAA/WCAG complet.
- Pas d’augmentation de `Tested up to`, tant que la campagne WordPress réelle n’est pas exécutée et documentée.

## Version 1.4.4 — recette WordPress et kit de démonstration

Cette release ne contient pas de nouvelle fonctionnalité utilisateur et ne réintroduit pas de fonctionnalité IA. Elle formalise la suite de la stabilisation : recette WordPress réelle, protocole de tests, positionnement accessibilité et préparation d’un environnement de démonstration.

### Ajouts documentaires

- **Protocole de recette intégré** : ajout de `TESTING.md` avec tests d’installation, administration, front, modal, clavier, lecteur d’écran, mobile, shortcode, persistance, reset, recherche et modules.
- **Critères de go/no-go** : ajout d’une grille claire pour décider si la version peut être validée ou doit être bloquée.
- **Format de rapport d’anomalie** : ajout d’un modèle de ticket reproductible avec environnement, étapes, résultat obtenu et gravité.
- **Documentation de démo WordPress** : ajout de `docs/DEMO_WORDPRESS.md` pour utiliser un WordPress local peuplé avec des pages de test représentatives.
- **Positionnement accessibilité** : ajout de `docs/POSITIONNEMENT_ACCESSIBILITE.md` pour cadrer les formulations à conserver et celles à éviter.

### Positionnement maintenu

- Pas de promesse de conformité RGAA/WCAG automatique.
- Pas de fonction IA.
- Pas de présentation de la lecture audio comme lecteur d’écran.
- Pas de présentation du braille visuel comme compatibilité plage braille.
- Pas de vocabulaire médical pour migraine ou épilepsie.

### Points hors périmètre

- Pas d’audit RGAA/WCAG complet.
- Pas de modification fonctionnelle majeure du JavaScript ou du CSS.
- Pas d’augmentation de `Tested up to`, tant que la campagne WordPress réelle n’est pas exécutée et documentée.

## Version 1.4.3 — consolidation modale, sémantique et wording

Cette release poursuit le nettoyage engagé en 1.4.2. Elle ne contient pas de fonctionnalité IA. Elle cible les corrections d’interface encore trompeuses, les redondances techniques et le comportement modal du panneau.

### Correctifs fonctionnels

- **Comportement modal renforcé** : en mode modal, les contenus hors widget sont isolés avec `inert` et `aria-hidden` pendant l’ouverture du panneau, puis restaurés à leur état initial à la fermeture.
- **Focus mieux contenu** : ajout d’un garde-fou `focusin` pour ramener le focus dans le panneau si un script, un clic ou une interaction externe le fait sortir du dialogue modal.
- **Raccourcis contextualisés** : les raccourcis de fonctionnalités ne se déclenchent plus lorsque le panneau est fermé, afin de limiter les conflits avec les raccourcis du navigateur ou des technologies d’assistance.
- **Restauration du scroll corrigée** : l’état `overflow` existant sur `body` est mémorisé avant l’ouverture, puis restauré à la fermeture au lieu d’être écrasé systématiquement.
- **Suppression d’un gestionnaire redondant** : retrait du gestionnaire global de clic ajouté après l’IIFE JS, qui doublonnait l’ouverture/fermeture du panneau et pouvait contourner une partie de la logique modale.
- **Boutons front sécurisés** : ajout de `type="button"` aux boutons du widget qui ne déclenchent pas de soumission de formulaire.

### Clarifications produit et accessibilité

- **Migraine / confort visuel** : remplacement des formulations de type “soulagement”, “protection renforcée” et “mode crise” par un vocabulaire d’atténuation et de confort visuel. Le module reste un réglage d’affichage, pas une réponse médicale.
- **Aide clavier** : remplacement de l’intitulé “Aria” par “Raccourcis”, car la zone liste des raccourcis clavier et ne documente pas ARIA.
- **Titre du panneau** : remplacement de “Accessibilité du site” par “Options d’accessibilité et de lecture” pour éviter de suggérer que le widget rend le site accessible à lui seul.
- **Administration** : le choix du logo du bouton lanceur passe de cases à cocher à des boutons radio, puisque la configuration n’autorise qu’un seul logo actif.
- **Lecture audio** : les annonces d’activation/désactivation utilisent “Lecture audio” plutôt que “Text-to-Speech”.

### Points hors périmètre

- Pas d’ajout de profil IA, résumé IA, glossaire IA ou audit IA.
- Pas d’audit RGAA/WCAG complet.
- Pas de validation terrain avec lecteurs d’écran ou panels utilisateurs.
- Pas d’augmentation de `Tested up to`, faute de test WordPress réel documenté.

## Version 1.4.2 — stabilisation et clarification

Cette release met l’IA de côté et se concentre sur les correctifs de base, la cohérence produit et les formulations à risque. Le widget est désormais présenté comme un module de personnalisation d’accessibilité et de confort de lecture, pas comme une solution de conformité automatique.

### Correctifs fonctionnels

- **Réinitialisation globale corrigée** : le bouton « Réinitialiser » remet à zéro les modules et supprime les clés `localStorage` associées : préférences générales, position du lanceur, côté du panneau, fenêtre d’information, luminosité, daltonisme, boutons, curseur, guide de lecture, épilepsie, braille, lecture audio, migraine et dyslexie.
- **Overlay modal corrigé** : l’overlay accepte désormais les événements pointeur quand il est visible. Le clic sur l’arrière-plan peut donc fermer le panneau en mode modal.
- **Shortcode sécurisé contre les doublons** : `[a11y_widget]` utilise le même rendu à instance unique que l’injection automatique. Cela évite deux widgets, des doublons d’ID et des conflits d’état.
- **Recherche interne corrigée** : le résultat « Daltonisme » conserve l’interface spécialisée du module daltonisme, au lieu d’être rendu comme un groupe générique.
- **Version assets mise à jour** : CSS et JS sont servis avec la constante `A11Y_WIDGET_VERSION` passée à `1.4.2`, ce qui facilite l’invalidation de cache.
- **Polices distantes retirées** : le CSS ne charge plus automatiquement les polices depuis GitHub. Les options typographiques utilisent les polices locales disponibles, puis les fallbacks système.

### Corrections de wording et de positionnement

- **Stockage des préférences** : le texte indiquait un stockage par cookies. Il indique maintenant correctement l’usage de `localStorage`.
- **Épilepsie** : retrait des promesses de protection et du vocabulaire de danger garanti. Le module parle maintenant de réduction de déclencheurs visuels potentiels et conserve une limite explicite.
- **Lecture audio** : la section anciennement intitulée « Audition » est repositionnée en « Lecture audio ». La synthèse vocale est décrite comme une lecture de confort, non comme un lecteur d’écran.
- **Braille** : la section est renommée « Braille visuel ». Les fonctionnalités sont présentées comme des démonstrations visuelles en caractères braille Unicode, non comme une compatibilité avec une plage braille.
- **Administration** : retrait de l’intitulé « Accessibilité RGAA » au profit de « Réglages du widget d’accessibilité » pour éviter de suggérer une conformité automatique.
- **Readme** : documentation remise à jour, avec limites connues, avertissement de conformité et compatibilité WordPress déclarée sans surpromesse.

### Points explicitement hors périmètre de cette release

- Assistant IA, résumé automatique, glossaire IA, alt text IA ou audit IA.
- Audit RGAA/WCAG complet.
- Campagne de tests WordPress permettant d’augmenter la valeur `Tested up to`.
- Fourniture ou auto-hébergement de fichiers de polices spécialisées.
- Tests terrain avec utilisateurs et technologies d’assistance.

### Limites à conserver dans la communication

- Le widget ne rend pas un site conforme RGAA/WCAG à lui seul.
- La lecture audio ne remplace pas NVDA, JAWS, VoiceOver, Narrator ou toute autre technologie d’assistance.
- Le braille visuel ne remplace pas une plage braille matérielle.
- Le module épilepsie réduit certains déclencheurs visuels potentiels, mais ne constitue pas un dispositif médical.
- Les préférences sont locales au navigateur et peuvent être effacées par l’utilisateur ou par le bouton « Réinitialiser ».

### Fichiers modifiés

- `a11y-widget.php`
- `assets/widget.js`
- `assets/widget.css`
- `templates/widget.php`
- `includes/admin-settings.php`
- `readme.txt`
- `RELEASE_NOTES.md`
