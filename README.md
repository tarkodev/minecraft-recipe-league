

# Minecraft Recipes League

Projet de Programmation Web 2 - 4ème Semestre de la Licence d'Informatique à Strasbourg

## Concept

J'ai une idée d'application mobile à faire, ce projet est donc pour moi une occasion d'en faire un prototype afin de le faire tester à des amis et mesurer à quel point l'application serait amusante! (`concept.png` étant le premier prototype d'à quoi le projet va ressembler)

Le but de ce jeu est de trouver la recette d'un objet donné aléatoirement parmis toute les recettes présente dans Minecraft grâce à un inventaire de 18 objets (l'équivalent de 2 colonnes dans le jeu de base) contenant les ingrédients de la recette ainsi que des objets aléatoire pour semer le doute.

Pour y jouer, il faut sélectionner un objet dans l'inventaire en dessous et le mettre dans le bon pattern dans l'établi (l'inventaire au dessus), si vous ne trouvez pas la solution passer votre souris sur l'objet du dessus spécifiant le craft à trouver (pour voir son nom) et chercher sur internet "`nom de l'objet` + minecraft craft". Vous pouvez aussi cliquer sur "Un autre" pour essayer de trouver un craft plus simple (les escaliers, planche, etc... sont assez fréquente et simple)

## Source externe

Ce ne sont pas pas des dépendances mais les différentes sources qui forme mes assets.

### [PrismarineJS/minecraft-data](https://github.com/PrismarineJS/minecraft-data/tree/master/data/pc/1.19)
#### Une façon simplifié d'avoir toute les recettes et objets via ID.
#### Fichier:
- `items.json`
- `recipes.json`

### [.minecraft/versions/1.19.4.jar](https://piston-data.mojang.com/v1/objects/958928a560c9167687bea0cefeb7375da1e552a8/client.jar)
#### Pour les traductions et quelque texture.
#### Dossier:
- `lang/`
- `minecraft/`

### [FabricMC](https://fabricmc.net/)
#### J'ai créé un mod pour générer toute les images du jeu en bonne qualité (256*256) grâce à l'API FabricMC
#### Dossier:
- `fabric/`
- `textures/`

## Conclusion

Je trouve ce prototype fun et addictif, il parle à presque tout le monde vu que Minecraft est le jeu le plus vendu de l'histoire. Le potientiel de l'application est donc validé et je vais pouvoir en faire une application 

## Démarrage rapide

### Option 1: Avec PHP directement

Prérequis:
- PHP 8+ (CLI) installé
- Extension SQLite pour PHP (`php-sqlite3`)

Installation (Ubuntu/Debian):
- `sudo apt-get update && sudo apt-get install -y php-cli php-sqlite3`

Lancement:
- `php -S 0.0.0.0:7418 -t ./`

Utilisation:
- Ouvrir le navigateur sur `http://localhost:7418`
- La base `database/database.sqlite` est créée automatiquement au premier accès

### Option 2: Avec Docker Compose

Prérequis:
- Docker et Docker Compose installés

Lancement:
`docker-compose up -d`

Utilisation:
- Ouvrir le navigateur sur `http://localhost:7418`
- La base `database/database.sqlite` est créée automatiquement au premier accès

## Structure du projet

- `index.php` point d'entrée
- `server/request/*.php` endpoints JSON (recettes, items, traductions)
- `database/request/*.php` endpoints JSON (utilisateurs/stats via SQLite)
- `assets/` données du jeu (items, recettes, traductions, textures)

