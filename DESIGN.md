# Design

## Register

product

## Design Intent

MOBLS A11Y Widget doit avoir une finition professionnelle : directe, lisible, utile, sans effet de marque excessif. Certaines decisions de design prises par les etudiantes doivent etre preservees par defaut, notamment l'accent MOBLS, la sobriete generale, le ton pedagogique et l'integration native a WordPress.

Le style attendu est calme, concret et accessible. Les choix visuels doivent aider l'utilisateur a comprendre et annuler les reglages, pas impressionner. Toute amelioration visuelle notable qui change ces decisions doit etre proposee et validee avant implementation.

## Visual Identity

- Surface principale claire, neutre et lisible.
- Accent MOBLS rose pour les actions primaires, l'etat actif et les reperes importants du widget public.
- Administration volontairement proche de WordPress : variables d'admin, bordures simples, fonds neutres, peu d'ombres.
- Icones simples et comprehensibles, seulement quand elles accelerent la reconnaissance.
- Aucun style medical, anxiogene, spectaculaire ou "outil IA".

## Color Tokens

### Public Widget

Les tokens existants restent la reference :

- `--a11y-primary: #d61e5b`
- `--a11y-primary-hover: #b8174b`
- `--a11y-primary-contrast: #ffffff`
- `--a11y-primary-soft`
- `--a11y-primary-soft-strong`
- `--a11y-surface: #ffffff`
- `--a11y-surface-elev: #f7f7f8`
- `--a11y-surface-active: #fff0f5`
- `--a11y-surface-muted: #f8fafc`
- `--a11y-text: #1e1e20`
- `--a11y-text-subtle: #4b5563`
- `--a11y-border: #e6e6ea`
- `--a11y-focus-outline: #111827`
- `--a11y-radius: 16px`
- `--a11y-status-success-*`, `--a11y-status-warning-*`, `--a11y-status-danger-*`

L'accent rose doit rester minoritaire : lanceur, boutons primaires, etats actifs, focus thematique ou confirmation d'application. Les fonds neutres portent la majorite de l'interface.

### WordPress Admin

L'admin doit suivre les variables WordPress quand elles existent :

- `--a11y-admin-accent: var(--wp-admin-theme-color, #3858e9)`
- `--a11y-admin-surface: #fff`
- `--a11y-admin-surface-subtle: #f6f7f7`
- `--a11y-admin-border: #dcdcde`
- `--a11y-admin-text: #1d2327`
- `--a11y-admin-text-subtle: #50575e`
- `--a11y-admin-radius: 4px`
- `--a11y-admin-state-ok-*`, `--a11y-admin-state-info-*`, `--a11y-admin-state-warning-*`, `--a11y-admin-state-error-*`

Ne pas recolorer l'admin en rose MOBLS par defaut. L'admin doit sembler natif WordPress, avec seulement les composants necessaires au plugin.

### Implementation Rule

Les nouveaux styles doivent reutiliser les tokens semantiques avant d'ajouter une couleur brute. Une nouvelle couleur brute n'est acceptable que si elle represente un nouvel etat produit documente ici ou une contrainte technique locale.

## Typography

- Police systeme ou heritee de WordPress. Ne pas ajouter de police externe.
- Hierarchie compacte : titres utiles, labels forts, descriptions courtes.
- Admin : labels autour de 13 a 15 px, descriptions a 13 px, champs stables a 40 px minimum.
- Widget : titres de cartes autour de `1rem` a `1.05rem`, aides autour de `.84rem` a `.9rem`.
- Pas de typographie decorative, pas de titres hero dans les panneaux ou cartes.

## Layout

### Public Widget

- Panneau flottant compact, ancre a droite ou a gauche selon le reglage utilisateur.
- Largeur cible : environ 360 a 480 px, avec contraintes `100vw` et `100dvh`.
- Header dense, recherche compacte, resume actif visible, puis categories en accordéon.
- Les cartes servent aux reglages individuels. Eviter les cartes dans des cartes.
- Les `Profils rapides` doivent rester visibles ou defilables sur petit ecran sans doublonner les tutoriels.

### Admin

- Largeur de travail : 1100 a 1180 px selon la page.
- Cartes admin simples : bordure 1 px, rayon 4 px, fond blanc ou gris tres clair.
- Grilles explicites pour les pages denses : deux colonnes desktop, une colonne mobile.
- Les tables longues doivent garder des largeurs stables sur desktop et devenir lisibles en cartes sur mobile.

## Components

### Buttons

- Utiliser des boutons natifs `<button>` pour les actions.
- Les boutons icone doivent avoir `aria-label`, texte masque pour technologies d'assistance et infobulle visible si necessaire.
- Les boutons primaires utilisent l'accent seulement pour une action claire : appliquer, enregistrer, envoyer.

### Cards

- Public : cartes a rayon 10 a 16 px, fond clair, bordure sobre.
- Admin : cartes a rayon 4 px, sans ombre decorative.
- Une carte doit contenir une decision ou un groupe de reglages coherent.
- Ne pas ajouter de bande coloree laterale decorative.

### Toggles And Inputs

- Preferer les controles natifs : checkbox, radio, select, range, textarea.
- Les switches custom existants doivent rester lisibles au clavier et annoncer leur etat.
- Les controles numeriques ou sliders doivent afficher la valeur courante.

### Profiles

- Les profils sont des raccourcis de confort, pas des diagnostics.
- Chaque profil doit afficher un libelle court, une aide courte et un etat actif ou partiel.
- La desactivation d'un profil doit etre reversible et ne doit pas etre confondue avec le reset global.

## Interaction

- Focus visible fort, en particulier dans le widget public.
- `Entrée`, `Espace`, `Tab`, `Shift+Tab` et `Échap` doivent rester previsibles.
- Transitions courtes, environ 150 a 250 ms, uniquement pour signaler un changement d'etat.
- Respect strict de `prefers-reduced-motion`.
- Aucun defilement horizontal global sur mobile.

## Copy

- Ton : clair, prudent, pedagogique.
- Dire "aide de confort", "repere", "personnalisation", "peut aider".
- Eviter "corrige l'accessibilite", "rend conforme", "diagnostique", "soulage", "protege", "mode crise".
- Toujours separer le widget de `RGAA_Audit` : le widget expose des aides et une synthese, `RGAA_Audit` reste la source de verite pour criteres, preuves, anomalies, rapports et conformite.

## Responsive Rules

- Tester au minimum 320, 360, 390, 768 et desktop.
- Tester aussi une hauteur courte, par exemple 390 x 520.
- Les textes de boutons doivent passer a la ligne ou se compacter sans deborder.
- Les grilles admin doivent tomber en une colonne sous 782 px.
- Les contenus front doivent garder un scroll interne au panneau.

## Do Not Change Without Reason

- Ne pas remplacer les decisions de design existantes par une direction SaaS, medicale ou institutionnelle lourde sans validation explicite.
- Ne pas introduire de hero, de grande illustration, de gradient decoratif ou d'effet de verre.
- Ne pas basculer l'admin vers une identite visuelle differente de WordPress.
- Ne pas renommer les notions produit si elles clarifient deja la separation avec `RGAA_Audit`.
- Ne pas modifier les tokens principaux sans recette visuelle front, admin, mobile et zoom 200 %.
- Demander validation avant toute amelioration qui change sensiblement la perception du produit, meme si elle semble plus "premium".
