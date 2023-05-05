# Projet "Ma Côte d'Ivoire"

## Description

"Ma Côte d'Ivoire" est une application permettant aux citoyens de partager et soutenir des pétitions, des sujets et des discussions concernant divers problèmes et initiatives en Côte d'Ivoire. L'application se compose d'une API RESTful côté back-end et d'une interface utilisateur côté front-end.

## Fonctionnalités

- Authentification des utilisateurs
- Gestion des pétitions (création, modification, suppression, signatures)
- Gestion des sujets (création, modification, suppression)
- Gestion des commentaires (création, modification, suppression)
- Gestion des catégories (création, modification, suppression)
- Système de likes pour les pétitions et les sujets
- Signalement des commentaires
- Fonctionnalités administrateur (approbation/rejet des pétitions et des sujets, gestion des signalements de commentaires)

## API Endpoints

L'API fournit plusieurs points de terminaison pour interagir avec les différentes ressources de l'application. Voici quelques-uns des points de terminaison les plus importants que le développeur front-end doit utiliser pour créer l'interface utilisateur :

- `/login` : pour l'authentification des utilisateurs
- `/petitions` : pour récupérer, créer, modifier et supprimer des pétitions
- `/petitions/{petition}/sign` : pour signer une pétition
- `/categories` : pour récupérer, créer, modifier et supprimer des catégories
- `/topics` : pour récupérer, créer, modifier et supprimer des sujets
- `/comments` : pour récupérer, créer, modifier et supprimer des commentaires
- `/reports` : pour signaler des commentaires
- `/likes` : pour gérer les likes sur les pétitions et les sujets

Pour plus de détails sur les points de terminaison et les paramètres requis, veuillez consulter le fichier `routes/api.php` du back-end.

## Technologies utilisées

- Back-end : Laravel (PHP)
- Front-end : (À déterminer par le développeur front-end)

## Développement local

larvel 8.0 et php 7.4 sont requis pour le développement local. Pour installer le projet en local, veuillez suivre les étapes suivantes :
- Cloner le dépôt
- Installer les dépendances avec la commande `composer install`
- Créer un fichier `.env` à partir du fichier `.env.example` et configurer les paramètres de base de données

## Déploiement

(Expliquez ici comment déployer le projet en production. Vous pouvez mentionner les étapes de déploiement, les services utilisés pour le déploiement, etc.)

## Contribution

Pour contribuer au projet, veuillez suivre les étapes suivantes :

1. Forkez le dépôt
2. Créez une nouvelle branche avec un nom descriptif de la fonctionnalité ou du correctif que vous souhaitez apporter
3. Effectuez vos modifications dans la branche nouvellement créée
4. Soumettez une demande de fusion (Pull Request) avec une description détaillée de vos modifications

## Auteurs

- Développeur back-end : (gnakale prime)
- Développeur front-end : (Nom du développeur front-end)

## Licence

Ce projet est sous
licence (indiquez le type de licence utilisée, par exemple, MIT, GPL, etc.).

Remerciements

Nous tenons à remercier tous les contributeurs, les testeurs et les utilisateurs pour leur soutien et leurs commentaires tout au long du développement de l'application "Ma Côte d'Ivoire".
