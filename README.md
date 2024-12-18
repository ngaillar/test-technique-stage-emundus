# Test technique

## Objectifs
L'objectif de ce test est d'évaluer l'autonomie et la capacité d'adaptation du candidat. Il n'y a pas de bonne ou de mauvaise réponse, nous cherchons à comprendre votre manière de penser et de travailler.

## Pré-requis
- Installer [Docker](https://docs.docker.com/get-docker/)
- Prendre connaissances des documentations ci-dessous

## Consignes
- Cloner ce repository
   ```bash
      git clone git@github.com:bhubinet/test_stage.git
   ```
- Ouvrez le projet avec votre IDE favori (VSCode, PHPStorm, ...)
- Lancer le projet avec Docker
    ```bash
      docker-compose -f docker-compose.yml up --build -d
    ```
- Le premier démarrage prend environ 30 secondes. Le projet est ensuite accessible à l'adresse http://localhost:8484
- Les instructions des exercices sont présentes sur la page d'accueil du projet

## Accéder à l'administration Joomla
- Rendez-vous à l'adresse http://localhost:8484/administrator
- Connectez-vous avec les identifiants suivants : sysadmin / password
- Vous pouvez ensuite accéder aux paramètres des éléments de formulaire via le menu à gauche : Composants > Fabrik > Eléments

## Accéder à la base de données
Vous pouvez accéder à la base de données via un outil tel que [DBeaver](https://dbeaver.io/) ou [Datagrip](https://www.jetbrains.com/fr-fr/datagrip/). Les informations de connexion sont les suivantes :
- Host : localhost
- Port : 13309
- Database : joomla
- User : user
- Password : password

## Fichiers de travail
- Les fichiers de travail sont situés dans le dossier `plugins/fabrik_elements/emundus_colorpicker/`

## Rendu
- Envoyer un lien vers votre repository à l'adresse mail qui vous a été communiquée
- OU Envoyer une archive du projet à l'adresse mail qui vous a été communiquée

## Questions
- Si vous avez des questions, n'hésitez pas à nous contacter à l'adresse mail qui vous a été communiquée

## Documentations
- [Joomla](https://docs.joomla.org/Portal:Beginners/fr)
- [Docker](https://docs.docker.com/)
- [Joomla fields](https://docs.joomla.org/Standard_form_field_types)
- [Fabrik](https://fabrikar.com/forums/index.php?wiki/index/)
