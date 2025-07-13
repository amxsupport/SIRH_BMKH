#!/bin/bash

# Définir les variables de connexion MySQL
MYSQL_USER="root"
MYSQL_PASS=""
DB_NAME="sirh_bmkh"

echo "Configuration de la base de données pour SIRH BMK..."

# Créer la base de données et les tables
echo "1. Création de la base de données et des tables..."
mysql -u $MYSQL_USER $([[ ! -z "$MYSQL_PASS" ]] && echo "-p$MYSQL_PASS") < ../config/database.sql
if [ $? -eq 0 ]; then
    echo "✓ Base de données et tables créées avec succès"
else
    echo "✗ Erreur lors de la création de la base de données"
    exit 1
fi

# Exécuter le script PHP pour générer les données de test
echo "2. Génération des données de test..."
php generate_test_data.php
if [ $? -eq 0 ]; then
    echo "✓ Données de test générées avec succès"
else
    echo "✗ Erreur lors de la génération des données de test"
    exit 1
fi

echo "Configuration terminée avec succès!"
echo "La base de données est prête à être utilisée."
