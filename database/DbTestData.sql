INSERT INTO user (Name, Email, Password)
values ('Clement','gclems@gmail.com','$2y$10$R1vd2iayvf8ZzmjxGDkZh.x1ne/gSWJu8W362fgkba.AuqWcRfN/S');

INSERT INTO author (Name, Creator_Id)
values
('Céline (Louis Fernand)', 1),
('Marcel Proust', 1),
('Apollinaire', 1),
('Fédor Dostoievski', 1),
('Victor Hugo', 1),
('Honoré de Balzac', 1),
('Rabelais', 1),
('Arthur Rimbaud', 1),
('Georges Sand', 1),
('Albert Camus', 1),
('Alfred de Musset', 1),
('J. K. Rowling', 1),
('Stephen King', 1),
('Neil Gaiman', 1);

INSERT INTO serie(Title, Creator_Id)
values
('Harry Potter', 1),
('La Tour Sombre', 1);

INSERT INTO editor(Name, Creator_Id)
values
('Gallimard', 1),
('j''ai lu', 1),
('Bantam Books', 1),
('Bragelone', 1);

INSERT INTO book(Title, Serie_Id, Editor_Id, SerieNumber, Creator_Id)
values
('Harry Potter à l''école des sorciers', 1, 1, 1, 1),
('Harry Potter et la chambre des secrets', 1, 1, 2, 1),
('Harry Potter et le prisonnier d''Azkaban', 1, 1, 3, 1),
('Harry Potter et la coupe de feu', 1, 1, 4, 1),
('Harry Potter et l''Ordre du phénix', 1, 1, 5, 1),
('Harry Potter et le prince de sang mélé', 1, 1, 6, 1),
('Harry Potter et les reliques de la mort', 1, 1, 7, 1),

('American Gods', null, 2,null, 1),

('Le Pistolero', 2, 2, 1, 1),
('Les Trois Cartes', 2, 2, 2, 1),
('Terres perdues', 2, 2, 3, 1),
('Magie et Cristal', 2, 2, 4, 1),
('Les Loups de la Calla', 2, 2, 5, 1),
('Le Chant de Susannah', 2, 2, 6, 1),
('La Tour Sombre', 2, 2, 7, 1),
('La Clé des vents', 2, 2, 8, 1);

INSERT INTO book_author(book_id, author_id)
values
(1, 12),
(2, 12),
(3, 12),
(4, 12),
(5, 12),
(6, 12),
(7, 12),

(8, 14),
(8, 13), -- fake for test

(9, 13),
(10, 13),
(11, 13),
(12, 13),
(13, 13),
(14, 13),
(15, 13),
(16, 13);
