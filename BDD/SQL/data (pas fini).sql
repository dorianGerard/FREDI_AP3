-- Ligues
INSERT INTO 'ligue'('id_ligue', 'lib_ligue')
VALUES 
(1, 'Football'),
(2, 'Handball'),
(3, 'Golf'),
(4, 'Judo'),
(5, 'Rugby'),

-- Clubs
INSERT INTO 'club'('id_club', 'lib_club', 'adr1', 'adr2', 'adr3', 'id_ligue')
VALUES
(1, 'Club de Foot de Nancy', 1),
(2, 'Club de Foot de Metz', 1),
(3, 'Club de Handball de Lunéville', 2),
(4, 'Club de Handball de Epinal', 2),
(5, 'Club de Golf de Verdun', 3),
(6, 'Club de Golf de Longwy', 3),
(7, 'Club de Judo de ThionVille', 4),
(8, 'Club de Judo de bar-le-Duc', 4),
(9, 'Club de Rugby de Yutz', 5),
(10, 'Club de Rugby de Bitche', 5);

-- Utilisateurs
INSERT INTO 'utilisateur'('id_utilisateur', 'pseudo', 'mdp', 'mail', 'nom', 'prenom', 'role')
VALUES 
(1, 'Darksasuke', 'Q8h6ntA5BVHPwjd', 'Darksasuke@gmail.com', 'Richard', 'Cuterrie', 1),
(2, 'Poney123', 'xiYaaVhauuBj4Oc', 'Poney123@gmail.com', 'Henry', 'Car', 1),
(3, 'DocteurSol', 'OrpSP3hdwJVJuF6', 'DocteurSol@gmail.com', 'Khaoutar', 'Tiflette', 1),
(4, 'SISRcNUL', '5gcFm9XbyJ6sNqP', 'SISRcNUL@gmail.com', 'Jean', 'Bonbeur', 2),
(5, 'JaimelaM2L', 'TKX90MBx8c1bMi4', 'JaimelaM2L@gmail.com', 'Ibrhima', 'Carronie', 1),
(6, 'FouBrave', '5cYqqgMn3unc9tA', 'FouBrave@gmail.com', 'Angélica', 'Kahuète', 1),
(7, 'GoodRiku', 'OBZvDPjCrNMVrfw', 'GoodRiku@gmail.com', 'Oscar', 'Got', 2),
(8, 'CookieVif', 'jsA05o8nN80NJuS', 'CookieVif@gmail.com', 'Vladimir', 'Aclette', 1);

--
INSERT INTO `adherent`(`id_adherent`, `nr_licence`, `adr1`, `adr2`, `adr3`, `id_utilisateur`, `id_club`)
VALUES
(1, '',
(2, '',
(3, '',
(4, '',
(5, '',
(6, '',
(7, '',
(8, '',