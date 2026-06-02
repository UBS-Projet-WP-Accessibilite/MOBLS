# Architecture combo - A11y Widget et RGAA_Audit

Version de reference : prochaine version de stabilisation UX/RGAA.

Ce document fixe la separation entre le widget public MOBLS A11Y et l'application compagnon `RGAA_Audit`.

## Principe

Les deux outils peuvent fonctionner en combo, mais ils ne portent pas la meme responsabilite.

`A11y Widget` sert les visiteurs et les administrateurs qui configurent le module de confort. Il propose des aides de lecture, d'affichage, de reperage, de lecture audio de confort, de feedback utilisateur et un acces synthetique a la declaration d'accessibilite.

`RGAA_Audit` reste la source de verite pour l'audit : criteres RGAA/WCAG, pages auditees, preuves, anomalies, corrections, statut, taux, rapport et suivi de conformite.

## Responsabilites du widget

- Afficher un module public de personnalisation et de confort.
- Persister les preferences utilisateur dans le navigateur.
- Collecter des retours utilisateurs si l'administrateur l'active.
- Afficher un lien vers la declaration publique quand elle est configuree.
- Afficher une synthese declarative limitee : statut, date, perimetre, taux ou rapport, si ces informations viennent d'une demarche d'audit reelle.
- Pointer vers `RGAA_Audit` dans l'administration quand l'application compagnon est active.

Le widget ne doit pas :

- calculer une conformite RGAA/WCAG ;
- afficher la grille complete des criteres RGAA ;
- transformer un retour utilisateur en anomalie d'audit sans validation humaine ;
- annoncer qu'il corrige automatiquement les defauts du site ;
- generer une declaration de conformite sans audit.

## Responsabilites de RGAA_Audit

- Gerer les campagnes d'audit.
- Porter les criteres, tests, preuves et statuts de correction.
- Produire ou lier le rapport d'audit.
- Calculer les taux uniquement a partir d'un audit reel.
- Fournir la source des informations publiees dans la declaration d'accessibilite.
- Alimenter les suivis administrateur, exports et historiques d'audit.

## Contrat de liaison

Le lien actuel est volontairement faible :

- Le widget detecte `RGAA_Audit` via la constante `RGAA_AUDIT_VERSION`.
- Quand l'application compagnon est active, l'administration du widget affiche un lien vers `admin.php?page=rgaa-audit`.
- L'ecran `Audit et suivi` affiche une carte d'etat d'integration `RGAA_Audit`, limitee a la detection, la version, le lien administrateur et le rappel des responsabilites.
- L'ecran `Sante du widget` peut signaler la presence ou l'absence de `RGAA_Audit`, mais il reste un diagnostic local du widget et ne lit pas les criteres, preuves ou anomalies de l'application compagnon.
- L'ecran `Retours utilisateurs` peut afficher une vue detaillee d'un retour et une note interne privee, avec un lien manuel vers `RGAA_Audit` si une qualification humaine est necessaire.
- Le widget stocke seulement une synthese et des liens dans ses options de declaration.
- L'import/export JSON du widget transporte uniquement la configuration reconnue par son schema. Il exclut les retours utilisateurs, les notes de retour, les notes internes d'audit, les criteres, preuves et anomalies `RGAA_Audit`.

Le contrat futur peut exposer des informations en lecture seule depuis `RGAA_Audit` :

| Donnee | Source de verite | Usage dans le widget |
|---|---|---|
| Statut d'audit | `RGAA_Audit` | Resume administrateur et declaration publique synthetique |
| Date d'audit | `RGAA_Audit` | Contexte declaratif |
| Perimetre | `RGAA_Audit` | Contexte declaratif |
| Taux | `RGAA_Audit` | Affichage facultatif si issu du rapport |
| URL du rapport | `RGAA_Audit` ou admin | Lien administrateur, puis lien public si valide |
| Declaration publique | WordPress / admin | Lien public affiche par le widget |
| Retours utilisateurs | A11y Widget | Signal humain, export ou rapprochement manuel |

## Regles d'integration

1. Le public ne voit jamais une grille d'audit dans le widget.
2. Le widget ne cree pas d'anomalie RGAA automatiquement.
3. Les retours utilisateurs restent des signaux de confort ou de blocage percu.
4. Le passage d'un retour utilisateur vers `RGAA_Audit` doit etre explicite et humainement valide.
5. Toute valeur de conformite visible doit avoir une source : rapport, date, perimetre et responsable.
6. Si `RGAA_Audit` est absent, le widget continue de fonctionner en mode autonome.

## Parcours recommande

### Visiteur

1. Ouvre le widget.
2. Adapte l'affichage ou la lecture.
3. Consulte la declaration si elle est publiee.
4. Envoie un retour si la collecte est activee.

### Administrateur du widget

1. Configure les fonctions visibles.
2. Configure la declaration publique.
3. Consulte les retours utilisateurs.
4. Ouvre `RGAA_Audit` pour les audits, criteres et corrections.

### Auditeur

1. Travaille dans `RGAA_Audit`.
2. Produit les resultats, preuves et rapports.
3. Valide les informations qui peuvent etre exposees dans la declaration publique.

## Prochaines evolutions possibles

1. Ajouter un filtre WordPress ou endpoint REST en lecture seule pour recuperer une synthese d'audit depuis `RGAA_Audit`.
2. Ajouter une action admin plus structuree pour associer manuellement un retour qualifie a une entree `RGAA_Audit`, sans creation automatique.
3. Ajouter des tests statiques qui bloquent toute formulation laissant penser que le widget remplace `RGAA_Audit`.
