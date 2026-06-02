# Protocole de recette — Recette du widget MOBLS A11y

Version de référence : 1.5.0.

Ce document sert à tester le widget avant diffusion sur un site réel. Il ne remplace pas un audit RGAA/WCAG du site complet. Il vérifie principalement que le widget lui-même ne crée pas de régression d’usage, de conflit clavier, de conflit lecteur d’écran ou de blocage visuel.

## 1. Préparation

### Environnement minimal

- WordPress de test séparé de la production.
- Plugin MOBLS A11y installé et activé.
- Thème de test sobre, idéalement un thème natif WordPress récent.
- Navigateur desktop : Firefox et Chrome ou Chromium.
- Navigateur mobile ou émulation mobile.
- Au moins un lecteur d’écran : NVDA sous Windows ou VoiceOver sous macOS/iOS.
- Outils navigateur ouverts pour surveiller la console JavaScript.

### Réglages de départ

1. Vider le `localStorage` du navigateur pour le domaine de test.
2. Tester d’abord avec l’injection automatique du widget via `wp_footer`.
3. Tester ensuite le shortcode `[a11y_widget]` sur une page dédiée, sans obtenir deux widgets.
4. Conserver au moins une page longue, une page formulaire, une page avec images, une page avec tableaux et une page avec animations CSS.

## 2. Tests d’installation WordPress

| Test | Procédure | Résultat attendu |
|---|---|---|
| Activation | Activer le plugin dans l’admin WordPress. | Pas d’erreur fatale, pas de message PHP visible. |
| Désactivation | Désactiver puis réactiver. | Le site reste fonctionnel. |
| Chargement assets | Inspecter la page front. | `widget.css` et `widget.js` sont chargés une seule fois. |
| Bouton flottant | Charger une page publique. | Le bouton est visible et atteignable au clavier. |
| Admin | Ouvrir la page de réglages du widget. | Les sections, fonctionnalités, logos et modes sont configurables. |
| Import/export de configuration | Exporter la configuration JSON, réimporter le JSON exporté, puis vérifier l’admin et le front. | L’import met à jour les options reconnues sans supprimer les retours utilisateurs ni les notes internes d’audit. |
| Console | Ouvrir plusieurs pages. | Aucune erreur JS bloquante. |

## 3. Tests d’ouverture, fermeture et modalité

### Mode modal

1. Ouvrir le widget au clic souris.
2. Fermer avec le bouton de fermeture.
3. Rouvrir puis fermer avec `Échap`.
4. Rouvrir puis fermer avec un clic sur l’overlay.
5. Vérifier que le focus revient au bouton lanceur.
6. Tabuler dans le panneau.
7. Vérifier que le focus ne sort pas du panneau tant qu’il est ouvert.
8. Vérifier que le contenu derrière le panneau n’est pas atteignable au clavier.
9. Fermer puis vérifier que le contenu de page redevient atteignable.

Résultat attendu : le panneau se comporte comme un vrai dialogue modal, sans piège clavier permanent et sans fuite de focus.

### Mode interactif

1. Passer le mode d’arrière-plan à `interactif` dans l’admin.
2. Ouvrir le widget.
3. Essayer de cliquer ou tabuler dans la page derrière le panneau.
4. Fermer le widget.

Résultat attendu : le comportement correspond au réglage administrateur. Le mode interactif ne doit pas appliquer l’isolation modale stricte.

## 4. Tests clavier

| Test | Procédure | Résultat attendu |
|---|---|---|
| Accès lanceur | Tabuler depuis le début de page. | Le lanceur reçoit un focus visible. |
| Ouverture clavier | Appuyer sur `Entrée` ou `Espace` sur le lanceur. | Le panneau s’ouvre. |
| Parcours interne | Utiliser `Tab` et `Shift+Tab`. | L’ordre est logique et ne saute pas d’élément. |
| Fermeture | Appuyer sur `Échap`. | Le panneau se ferme et le focus revient au lanceur. |
| Raccourcis | Ouvrir le bouton `Raccourcis clavier`, vérifier qu’il reste ouvert, tabuler dans la fenêtre, fermer par la croix puis par `Échap`, déplacer le panneau à gauche puis rouvrir les raccourcis. Tester ensuite les raccourcis uniquement panneau ouvert. | La fenêtre superposée s’adosse au bord opposé du panneau, ne se ferme pas automatiquement, piège le focus, se ferme sans quitter le panneau et aucun raccourci du widget n’agit panneau fermé. |
| Boutons internes | Activer toggles, reset, recherche, aide. | Les états changent sans perte de focus imprévisible. |
| Résumé des réglages actifs | Activer un réglage, vérifier le compteur, appliquer un profil, puis utiliser le bouton de réinitialisation contextuel. | Le compteur, les badges de catégorie, les cartes actives et le profil mémorisé se synchronisent, puis reviennent à zéro après reset. |
| Annulation | Activer puis désactiver un réglage, utiliser `Annuler`, puis vérifier le même scénario avec une carte favorite. | Le dernier état est restauré, les cartes dupliquées restent synchronisées et le bouton redevient inactif après l’annulation. |
| Favoris | Épingler cinq réglages avec l’étoile, tenter d’en ajouter un sixième, retirer un favori puis le réajouter. | Le bloc Favoris apparaît en haut du panneau, reste limité à 5 réglages et garde des interrupteurs synchronisés avec les cartes d’origine. |
| Recherche synonymes | Chercher `mal aux yeux`, `texte trop petit`, `je veux écouter`, `curseur invisible`, `flash`, `visionneuse braille`. | Les modules pertinents remontent même si les mots exacts du libellé ne sont pas saisis. |
| Réglages simples | Activer `Réduire les distractions`, `Mode texte seul` et `Masquer les images`. | Chaque réglage utilise un bouton standard, expose un état et applique l’effet attendu. |
| Profils rapides | Appliquer un profil, désactiver manuellement un réglage inclus, appliquer un autre profil, puis utiliser `Désactiver ce profil`. | Le profil complet est annoncé par `aria-pressed`, l’état partiel est visible sans `aria-pressed`, les compteurs ne s’additionnent pas indéfiniment et la désactivation ne se confond pas avec le reset global. |
| Déclaration et crédits | Ouvrir `Retours et informations` avec une déclaration publique configurée. | La carte `Déclaration d’accessibilité` affiche le résumé et le lien officiel. Les créatrices de l’application apparaissent dans un encadré dédié gris, séparé de la déclaration. |
| Structure de page | Activer le bouton icône `Structure de page`, utiliser `Fermer la structure de page`, puis rouvrir une catégorie. | La vue Structure s’ouvre depuis le haut du panneau, liste les repères et titres, revient explicitement au panneau principal et n’apparaît plus comme carte dans la catégorie Lecture. |
| Catégories Phase 2 | Ouvrir `Mouvement`, `Audio / vidéo`, `Profils rapides` et `Retour utilisateur`. | Les catégories ne sont pas vides : Mouvement contient la réduction des déclencheurs visuels, Audio / vidéo contient la lecture à voix haute, Profils rapides ne contient pas le formulaire de retour. |
| Accordéon des catégories | Inspecter les titres de catégories dans le DOM, puis ouvrir et refermer une catégorie active. | Les titres sont des boutons avec `aria-expanded` et `aria-controls`; aucun `role="tablist"`, `role="tab"`, `role="tabpanel"` ni `aria-selected` n’est utilisé. |


## 4 bis. Tests de l’aide et des tutoriels intégrés

| Test | Procédure | Résultat attendu |
|---|---|---|
| Ouverture | Activer le bouton d’aide du lanceur. | La fenêtre **Aide et tutoriels** s’ouvre, avec un titre annoncé et une fermeture explicite. |
| Point d’entrée | Ouvrir la fenêtre pour la première fois. | La rubrique **Prise en main** et son premier panneau sont ouverts par défaut. |
| Recherche | Saisir `lecture`, `localStorage`, puis une chaîne sans résultat dans le champ **Rechercher dans l’aide**. | Les rubriques correspondantes restent visibles, la première rubrique trouvée s’ouvre, les sous-panneaux non pertinents sont masqués et un état vide apparaît quand aucun tutoriel ne correspond. |
| Rubriques | Parcourir prise en main, choix selon le besoin, catégories du module, glossaire, clavier et raccourcis, données et limites, démo et administration. | Chaque rubrique s’ouvre et se ferme sans masquer définitivement les autres contenus ; le glossaire est disponible dans l’aide, pas dans l’en-tête. |
| Sous-panneaux | Ouvrir les panneaux internes. | Les états `aria-expanded`, `aria-hidden` et `hidden` restent cohérents. |
| Sous-dialogue modal | Ouvrir l’aide puis cliquer dans la zone du panneau visible derrière la fenêtre. | Le clic ne déclenche pas les contrôles du panneau ; le focus reste dans l’aide ou revient au bouton Aide à la fermeture. |
| Clavier | Utiliser `Tab`, `Shift+Tab`, `Entrée`, `Espace` et `Échap`. | Les contrôles sont atteignables et activables sans souris. |
| Wording | Lire les rubriques de limites. | Aucune promesse de conformité automatique, de lecteur d’écran intégré, de support braille matériel ou de protection médicale. |
| Zoom | Tester à 200 % de zoom et sur écran étroit. | Les cartes et listes restent lisibles sans perte de contenu essentielle. |
| ARIA | Inspecter les attributs `aria-controls` et `aria-labelledby`. | Chaque attribut pointe vers un identifiant existant et unique. |

## 5. Tests lecteur d’écran

À faire au minimum avec NVDA + Firefox ou VoiceOver + Safari.

| Zone | Points à vérifier |
|---|---|
| Lanceur | Nom accessible clair, rôle bouton, état compréhensible. |
| Panneau | Annonce comme dialogue ou zone équivalente, titre annoncé. |
| Fermeture | Le bouton de fermeture est nommé. |
| Sections | Les titres de sections sont navigables et cohérents. |
| Interrupteurs | Chaque action possède un nom compréhensible. |
| Recherche | Le champ est nommé, les résultats sont annoncés ou compréhensibles. |
| Reset | Le bouton ne promet pas plus que ce qu’il fait. |
| Lecture vocale | Présentée comme lecture de confort, pas comme lecteur d’écran. |
| Braille | Présenté comme démonstration visuelle Unicode, pas comme plage braille. |

Résultat attendu : le widget est utilisable avec un lecteur d’écran sans se substituer à lui.

## 6. Tests responsive et zoom

| Test | Procédure | Résultat attendu |
|---|---|---|
| Zoom 200 % | Zoom navigateur à 200 %. | Le panneau reste lisible, sans perte de contenu essentielle. |
| Mobile 320 px | Émuler un écran très étroit. | Le bouton et le panneau restent utilisables, sans scroll horizontal global ; la première catégorie reste visible dans le panneau. |
| Mobile 360 px | Émuler un écran étroit. | Le bouton et le panneau restent utilisables, les raccourcis guidés restent atteignables au clavier et la première catégorie reste visible sans scroll excessif. |
| Mobile 390 px | Émuler un écran courant de téléphone. | Le résumé actif et les profils rapides restent lisibles, et la première catégorie reste visible sans scroll excessif. |
| Petite hauteur 390 x 520 | Émuler un téléphone en paysage ou une fenêtre courte. | Le panneau garde son scroll interne et ne pousse pas les catégories hors de portée. |
| Header icône seul | À 360 px, ouvrir le panneau puis vérifier Options, Raccourcis, Aide, Structure de page et déplacement gauche/droite. | Les boutons restent nommés, mesurent au moins 44 px, les infobulles ne créent pas de scroll horizontal et le haut ne déborde pas. |
| Réglages avancés | Sur mobile 360 px, ouvrir Vision puis les réglages avancés de luminosité. | Les sliders et boutons −/+ restent dans la carte, sans débordement horizontal. |
| Orientation | Tester portrait et paysage. | Pas de contenu bloqué hors écran. |
| Scroll interne | Ouvrir le panneau sur une page longue. | Le scroll du panneau et celui du document ne se concurrencent pas. |

## 7. Tests de persistance et réinitialisation

1. Activer plusieurs réglages : contraste, guide de lecture, curseur, boutons, dyslexie, migraine, daltonisme, synthèse vocale si disponible.
2. Recharger la page.
3. Vérifier que les réglages attendus persistent.
4. Cliquer sur `Réinitialiser`.
5. Recharger la page.
6. Inspecter `localStorage`.

Résultat attendu : les préférences du widget sont supprimées ou revenues à leur valeur par défaut. Aucun état résiduel ne doit rester actif visuellement.

## 8. Tests par module

### Vision et contrastes

- Activer et désactiver les contrastes.
- Tester les filtres daltonisme.
- Rechercher “daltonisme” dans la recherche du widget.
- Vérifier que l’interface spécialisée du daltonisme reste disponible dans les résultats.
- Vérifier que les réglages ne masquent pas le focus clavier.

### Confort visuel / migraine

- Activer les réglages d’atténuation.
- Vérifier que les libellés ne promettent pas un soulagement médical.
- Tester la réinitialisation du module.
- Vérifier que les effets ne rendent pas le texte illisible.

### Mouvement / épilepsie

- Tester une page avec animations CSS, GIF ou vidéos si disponibles.
- Activer la réduction de déclencheurs visuels potentiels.
- Vérifier que les animations sont arrêtées ou fortement réduites.
- Vérifier que le vocabulaire reste prudent : réduction, atténuation, déclencheurs potentiels.

### Lecture

- Tester guide de lecture, sommaire, syllabification et espacement.
- Tester les styles de règle : barre, lightbox, ombre et soulignement.
- Tester les presets Dyslexie : aéré doux, aéré fort et sans motifs.
- Tester les patterns Dyslexie : graphèmes, confusions, morphèmes puis retour à aucun pattern.
- Vérifier que les espacements lettres / mots / paragraphes et la longueur de ligne restent lisibles au zoom 200 % et sur mobile.
- Vérifier que les titres détectés correspondent au réglage admin.
- Tester une page longue avec `h2`, `h3`, `h4`.
- Vérifier que la syllabification n’altère pas les champs, boutons, liens critiques ou attributs accessibles.

### Lecture vocale de confort

- Tester lecture d’une sélection courte.
- Tester lecture d’un paragraphe long.
- Tester pause / reprise / arrêt.
- Vérifier que le module ne se présente jamais comme lecteur d’écran.
- Vérifier l’absence d’erreur si le navigateur ne fournit pas de voix compatible.

### Braille visuel Unicode

- Activer le module.
- Vérifier que la description précise le caractère pédagogique / visuel.
- Vérifier que la visionneuse Braille d’exemple affiche une ligne braille, la ligne texte correspondante et se met à jour après sélection d’un texte.
- Tester `Copier`, `Agrandir` et `Détacher`, puis déplacer et fermer la fenêtre détachée au clavier/souris sans perdre le panneau.
- Vérifier que le module ne prétend pas piloter une plage braille.

### Tests visuels automatisés

- Installer Playwright si l’environnement de recette l’autorise.
- Exécuter `node tests/visual-regressions.test.js`.
- Contrôler les captures générées dans `_artifacts/a11y-widget-visual-tests` : desktop, mobile 320 px et petite hauteur 390 x 520.
- Résultat attendu : pas de débordement horizontal, le panneau reste dans le viewport, les favoris et la visionneuse braille restent visibles.

### Curseur et boutons

- Activer curseur agrandi et boutons renforcés.
- Vérifier les effets sur les formulaires, liens, menus, cartes et boutons du thème.
- Vérifier qu’aucun élément essentiel ne devient impossible à cliquer.

## 9. Tests admin

| Réglage | Procédure | Résultat attendu |
|---|---|---|
| Masquer une fonction | Désactiver une fonction dans l’admin. | Elle disparaît du front. |
| Forcer tout afficher | Activer l’option si disponible. | Toutes les fonctions reviennent. |
| Ordre sections | Réordonner les sections. | L’ordre est conservé en front. |
| Ordre fonctions | Réordonner les fonctions. | L’ordre est conservé dans le panneau. |
| Logo lanceur | Choisir une variante. | Une seule variante active, via bouton radio. |
| Taille logo | Changer l’échelle. | Le bouton reste utilisable et non rogné. |
| Mode arrière-plan | Modal / interactif. | Le comportement front correspond au réglage. |
| Niveaux de titres | Modifier `h2,h3` vers `h2,h3,h4`. | Le sommaire suit le réglage. |
| Santé du widget | Ouvrir `Accessibilité > Santé du widget`. | La page affiche le statut global, les assets, fonctionnalités visibles, mode d’arrière-plan, feedback, déclaration et liaison `RGAA_Audit`, sans appel externe ni lecture des critères d’audit. |
| Crédits | Ouvrir `Accessibilité > Crédits`, puis ouvrir `Accessibilité > Audit et suivi` et utiliser le lien vers les crédits. | La page liste les créatrices de l’application, le cadre du projet MOBLS et propose un retour vers l’audit. Le lien depuis Audit et suivi fonctionne. |
| Assistant de configuration | Ouvrir `Accessibilité > Assistant de configuration`, choisir un mode, un périmètre, la déclaration et les retours, puis enregistrer. | Les options choisies sont appliquées sans réinitialiser les notes internes d’audit ; `RGAA_Audit` reste affiché comme statut/lien de liaison uniquement. |
| Profils de confort | Ouvrir `Accessibilité > Profils de confort`, modifier un libellé, décocher un profil et enregistrer. | Le panneau public reflète uniquement les profils actifs, les retours n’acceptent que ces profils et l’import/export conserve cette configuration sans données détaillées `RGAA_Audit`. |
| Audit et suivi | Ouvrir le sous-menu `Accessibilité > Audit et suivi`, renseigner statut, date et rapport. | Les données sont enregistrées sans modifier les autres réglages du widget. |
| Alignement audit | Contrôler les champs `Date de l’audit`, `Statut déclaré`, `Taux indiqué` et `Réalisé par`. | Les labels, champs et descriptions restent alignés sur desktop, puis passent proprement en deux colonnes et une colonne selon la largeur. |
| Intégration RGAA_Audit | Ouvrir `Accessibilité > Audit et suivi` avec `RGAA_Audit` actif, puis sans l’application compagnon. | La carte `Intégration RGAA_Audit` indique l’état détecté/non détecté, la version si disponible, le lien admin et rappelle que le widget ne lit pas les critères ni ne crée d’anomalie RGAA automatiquement. |
| Import/export de configuration | Ouvrir `Accessibilité > Réglages du widget`, exporter le JSON, coller ce JSON dans l’import, puis valider. | Un message de réussite indique le nombre de réglages importés. Les retours existants, notes de retour, notes internes d’audit et données détaillées `RGAA_Audit` ne sont pas inclus dans l’export. |
| Retours utilisateurs | Activer `Collecter les retours dans WordPress`, envoyer un retour depuis le widget, ouvrir `Accessibilité > Retours utilisateurs`. | Le retour apparaît côté admin avec la page, le commentaire et le contexte. |
| Détail d’un retour | Cliquer sur `Voir le détail`, changer le statut, renseigner une note interne, puis revenir à la liste. | Le statut et la note sont conservés, la note reste privée côté admin et le lien `RGAA_Audit` reste un rapprochement manuel. |
| État vide des retours | Ouvrir `Accessibilité > Retours utilisateurs` sans retour, puis avec un filtre sans résultat. | La page affiche un état vide compréhensible avec un accès au réglage de collecte. |
| Traitement des retours | Changer le statut d’un retour, filtrer par statut, exporter le filtre en CSV, régler une durée de conservation, puis supprimer ou purger les retours de test. | Chaque action est protégée et reflétée dans la liste admin. |
| Responsive retours | Réduire la largeur de l’admin ou tester à 200 % de zoom sur `Accessibilité > Retours utilisateurs`. | La table responsive reste lisible, les cellules sont libellées et les actions ne débordent pas. |
| Conservation automatique | Ouvrir `Accessibilité > Retours utilisateurs` et contrôler la ligne de conservation. | La page affiche que la purge automatique quotidienne est planifiée et indique le prochain passage. |
| Durcissement retours | Tenter deux envois publics rapprochés, exporter un commentaire commençant par `=`, et soumettre une URL de page externe via requête de test. | Le second envoi est temporisé, le CSV neutralise la formule et l’URL externe n’est pas conservée comme lien admin. |
| Design system léger | Contrôler le widget public et les pages admin après purge cache. | Les couleurs et états restent visuellement identiques, les états utilisent les tokens documentés et l’admin ne prend pas l’identité rose MOBLS. |

## 10. Recette finale 1.5.0 du 31/05/2026

Cette passe cible les derniers changements de panneau : recherche d’aide, profils rapides, action **Retours et informations**, icônes de pied de panneau, tooltips et reset global.

| Zone | Procédure validée | Résultat observé |
|---|---|---|
| Clavier | `Tab` jusqu’au lanceur, ouverture par `Entrée`, activation clavier de **Retours et informations**, fermeture par `Échap`, puis fermeture par la croix de pied. | Le lanceur est atteignable, le focus entre dans le bloc ouvert et revient au lanceur à la fermeture. |
| Noms accessibles | Inspecter les boutons **Retours et informations**, **Réinitialiser**, croix de pied et croix d’en-tête. | Les noms exposés sont explicites : `Afficher les retours et informations`, `Réinitialiser les préférences du widget`, `Fermer le panneau`. |
| Reflow / zoom | Émuler desktop, mobile 320 px et une largeur équivalente à un zoom 200 %. | Le panneau reste dans le viewport, sans scroll horizontal global ni interne. |
| Tooltips | Survoler l’icône **Retours et informations** et la croix de pied. | Les infobulles restent visibles dans le panneau et ne sont pas coupées aux bords. |
| Reset | Activer `Mode texte seul`, `Masquer les images` et `Réduire les distractions`, puis utiliser **Réinitialiser**. | Les états `aria-pressed`, les attributs `data-a11y*`, le stockage local et le résumé actif reviennent à zéro. |

## 11. Tests shortcode

1. Créer une page avec `[a11y_widget]`.
2. Charger la page avec l’injection automatique active.
3. Inspecter le DOM.
4. Chercher les identifiants `a11y-panel`, `a11y-overlay`, `a11y-toggle`.

Résultat attendu : une seule instance du widget est présente. Aucun doublon d’ID.

## 12. Tests avec contenu de démonstration

La démo doit contenir au minimum :

- une page longue avec plusieurs niveaux de titres ;
- une page formulaire ;
- une page avec tableaux ;
- une page avec images et alternatives variées ;
- une page avec faibles contrastes contrôlés ;
- une page avec animations CSS ;
- une page contenant des exemples volontairement problématiques, clairement signalés comme tels.

Ces contenus servent à tester le widget. Ils ne doivent pas être repris comme modèles éditoriaux sans correction.

## 13. Critères de go/no-go et blocage release

La release doit être bloquée si l’un de ces points est observé :

- impossibilité d’ouvrir ou fermer le widget au clavier ;
- focus bloqué définitivement ;
- contenu derrière le modal atteignable en mode modal ;
- doublon d’ID causé par le shortcode ;
- bouton reset laissant des états actifs visibles ;
- résumé actif, compteurs de catégorie ou profils rapides incohérents avec l’état réel ;
- catégories principales repoussées hors de portée sur une hauteur mobile courante ;
- erreur JS bloquante sur plusieurs pages ;
- vocabulaire trompeur : conformité automatique, lecteur d’écran intégré, support braille matériel, protection médicale ;
- conflit majeur avec un thème WordPress standard ;
- panneau inutilisable à 200 % de zoom ou sur écran mobile courant.

## 14. Modèle de rapport de bug

```text
Titre :
Version du plugin :
Version WordPress :
Thème :
Navigateur :
Lecteur d’écran, si utilisé :
URL ou page de test :
Réglage admin concerné :
Étapes de reproduction :
Résultat obtenu :
Résultat attendu :
Capture ou vidéo :
Console JS :
Gravité : bloquant / majeur / mineur
```
