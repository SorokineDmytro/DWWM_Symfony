insert into typeMouvement (prefixe,libelle,format,numeroInitial) values 
('FACT','Facture','%06d',999); 

---- Insertion de donnees dans la table LigneMouvement
---ligneMouvement
+----+-------------------+----------+--------------+---------------------+
| id | type_mouvement_id | tiers_id | numMouvement | dateMouvement       |
+----+-------------------+----------+--------------+---------------------+
|  4 |                 1 |        1 | FACT00002    | 2024-11-18 00:00:00 |
|  5 |                 2 |        1 | DEV00002     | 2024-11-18 00:00:00 |
|  6 |                 1 |        1 | FACT00003    | 2024-11-18 00:00:00 |
|  8 |                 1 |        1 | FACT00004    | 2024-11-19 09:52:00 |
+----+-------------------+----------+--------------+---------------------+
---produit
+----+------------+-----------------+--------------+-------------+--------------+
| id | numProduit | designation     | prixUnitaire | prixRevient | categorie_id |
+----+------------+-----------------+--------------+-------------+--------------+
|  1 | BB0001     | Biere Castel    |         2.50 |        2.00 |            1 |
|  2 | BJ00014    | Jus Ananas      |         1.50 |        1.00 |            8 |
|  3 | BB0002     | Biere HK        |         4.50 |        2.25 |            1 |
|  4 | BB000001   | Biere Phoenix   |         2.50 |        1.00 |            1 |
|  5 | BB000002   | Biere Rouge     |         4.50 |        2.25 |            1 |
|  7 | BB000004   | Biere Carlsberg |         5.00 |        2.25 |            1 |
+----+------------+-----------------+--------------+-------------+--------------+
---desc ligneMouvement
+--------------+---------------+------+-----+---------+----------------+
| Field        | Type          | Null | Key | Default | Extra          |
+--------------+---------------+------+-----+---------+----------------+
| id           | int(11)       | NO   | PRI | NULL    | auto_increment |
| mouvement_id | int(11)       | YES  | MUL | NULL    |                |
| produit_id   | int(11)       | YES  | MUL | NULL    |                |
| quantite     | decimal(10,2) | NO   |     | NULL    |                |
| prixUnitaire | decimal(10,2) | NO   |     | NULL    |                |
+--------------+---------------+------+-----+---------+----------------+
INSERT INTO ligneMouvement(mouvement_id, produit_id, quantite, prixUnitaire) VALUES 
(4,4,50,2.50),
(4,5,500,4.50),
(4,7,150,5);