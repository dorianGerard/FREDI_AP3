# 🏠 - Maison des Ligues: Fredi

-----

## 🔍 - Vous y trouverez :

* Des instructions concernant l'installation du projet
* Un lien vers le Trello du projet
* Le site en lui-même en HTML/CSS/PHP
* La base de donnée, ses données associées, ainsi que des screenshots des MCD/MLD
* L'IHM en diffèrents formats, crée avec Balsamiq

-----

## ⌨ - Installation :

<ol>
  <li>Allez sur <a href="https://github.com/dorianGerard/FREDI_AP3.git">le lien du projet</a> et téléchargez le projet au format .ZIP </li>
  <li>Décompresser le .ZIP dans votre dossier XAMPP, où se trouve l'emsemble de vos projets</li>
  <li>Importez la BDD: ouvrez [...]/BDD/Database SQL/fredi21.sql et executez le contenu du fichier SQL dans phpMyAdmin afin d'importer la structure et les données de la base</li>
</ol>

-----

## 🖼️ - Trello :

<p>
  <a href="https://trello.com/b/ds9X1op6/to-do">
    Lien 
  </a>vers le Trello du projet
</p>

-----

## 📃 - MCD de la BDD :

<p align="center">
  <img src="https://github.com/dorianGerard/FREDI_AP3/blob/main/BDD/MCD/Capture%20MCD.PNG?raw=true">
</p>

-----

## 🧑‍🤝‍🧑 - Utilisateurs

### Rôle utilisateur

<ul>
  <li>1 = Adherent</li>
  <li>2 = Controleur</li>
  <li>3 = Admin</li>
</ul>

### Utilisateurs dans la BDD

<table>
  <tr><th>Pseudo</th><th>Mot de passe</th><th>Email</th><th>Prénom</th><th>Nom</th><th>Rôle</th></tr>
  <tr><td>Darksasuke</td><td>UxdZKgaes4GjLH0HjSjNNF</td><td>Darksasuke@gmail.com</td><td>Richard</td><td>Cuterrie</td><td>1</td></tr>
  <tr><td>Poney123</td><td>S91i9Wu16w7TJdUCJmyIv9</td><td>Poney123@gmail.com</td><td>Henry</td><td>Car</td><td>1</td></tr>
  <tr><td>DocteurSol</td><td>TwO9doHxvKyfgkPVLEE8lk</td><td>DocteurSol@gmail.com</td><td>Khaoutar</td><td>Tiflette</td><td>1</td></tr>
  <tr><td>SISRcNUL</td><td>qrQztrW6y6mjuc3tzWri3e</td><td>SISRcNUL@gmail.com</td><td>Jean</td><td>Bonbeur</td><td>2</td></tr>
  <tr><td>JaimelaM2L</td><td>99LILjBk5uGgNSd5TxDMba</td><td>JaimelaM2L@gmail.com</td><td>Ibrhima</td><td>Carronie</td><td>3</td></tr>
  <tr><td>FouBrave</td><td>8LHUduc2lE9jQnN7j1skyn</td><td>FouBrave@gmail.com</td><td>Angélica</td><td>Kahuète</td><td>1</td></tr>
  <tr><td>GoodRiku</td><td>huFPpmsi4pfEVpGd7FdUih</td><td>GoodRiku@gmail.com</td><td>Oscar</td><td>Got</td><td>2</td></tr>
  <tr><td>CookieVif</td><td>ntjOGEEV494VAoIhTxWFbH</td><td>CookieVif@gmail.com</td><td>Vladimir</td><td>Aclette</td><td>1</td></tr>
</table>

-----

## 📑 - Note de frais: JSON

Pour récupérer les note de frais:<br>
[...]/frais_JSON.php?email=<EMAIL_UTILISATEUR>&password=<MOT_DE_PASSE><br>
<br>
Exemple:<br>
[...]/frais_JSON.php?email=Darksasuke@gmail.com&password=UxdZKgaes4GjLH0HjSjNNF
