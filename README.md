# Elements de Réponse

## Commande d'installation symfony 
composer create-project symfony/website-skeleton chaimaa-projet

## Configuration Domaine local
```bash
symfony proxy:start --port=xxxxx
```

Configuration du proxy local

```bash
symfony symfony proxy:domain:attach domain
```

Avec Appache

1) Ajout du domaine dans fichier hosts

2) Activation du mode rewrite_mode dans php.ini

3) Ajout d'un virtual host dans le fichier httpd-vhosts.conf

4) Activation du httpd-vhosts.conf dans httpd.conf 

## Authentification
Utilisation des composant de base de Symfony

Documentation bien détaillée

Besoin n'est pas très compliqué

# Guide d'installation

1) Pointer sur le dossier de base de l'applciation.

2) Installer les dépendances du projet :
```bash
php composer install
```
3) Configurer la base de données dans le fichier .env

4) Mettre à ajour le scheme de la base de données:
```bash
php bin/console doctrine:database:create 
php bin/console doctrine:migrations:migrate 
```
5) Loader un jeux de données pour les vérifications
```bash
 php bin/console doctrine:fixtures:load
```

# Tests unitaires

1) Configurer l'accès à la base de données de tests dans le fichier .env.test

2) Mettre à ajour le scheme de la base de données:
```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test
```
3) Lancer les tests :
```bash
 php php bin/phpunit
```


## License
[MIT](https://choosealicense.com/licenses/mit/)