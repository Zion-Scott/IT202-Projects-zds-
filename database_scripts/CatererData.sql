INSERT INTO Caterers (catererID, firstName, lastName, password, phoneNumber, emailAddress) VALUES
(1001, 'Marcus',   'Reed',     '!A3x',   '973-555-0101', 'marcus.reed@culconn.com'),
(1002, 'Danielle', 'Lopez',    '@B7z',   '973-555-0102', 'danielle.lopez@culconn.com'),
(1003, 'Harold',   'Nguyen',   '#C9Q',   '973-555-0103', 'harold.nguyen@culconn.com'),
(1004, 'Jasmine',  'Patel',    '$D4t',   '973-555-0104', 'jasmine.patel@culconn.com'),
(1005, 'Omar',     'Ali',      '%E2K',   '973-555-0105', 'omar.ali@culconn.com'),
(1006, 'Sophia',   'Martinez', '^F8p',   '973-555-0106', 'sophia.martinez@culconn.com'),
(1007, 'Ethan',    'Brown',    '&G1R',   '973-555-0107', 'ethan.brown@culconn.com'),
(1008, 'Priya',    'Shah',     '*H5m',   '973-555-0108', 'priya.shah@culconn.com'),
(1009, 'Caleb',    'Johnson',  '!J7V',   '973-555-0109', 'caleb.johnson@culconn.com'),
(1010,'Natalie',  'Kim',      '@K6b',   '973-555-0110', 'natalie.kim@culconn.com');

SET FOREIGN_KEY_CHECKS = 0;






INSERT INTO ClientCateringInfo (cateringID, clientID, catererID, dateOfEvent, foodOrder) VALUES
(1,  1,  1001,  '2025-12-10', 'Italian buffet: pasta, chicken parmesan, salad'),
(2,  2,  1002,  '2025-11-20', 'Mexican fiesta: tacos, rice, beans, churros'),
(3,  3,  1003,  '2026-01-05', 'Asian fusion: stir fry, dumplings, sushi platters'),
(4,  4,  1004,  '2025-10-15', 'BBQ spread: ribs, brisket, mac and cheese'),
(5,  5,  1005,  '2026-03-01', 'Mediterranean: hummus, shawarma, falafel, pita'),
(6,  6,  1006,  '2025-09-25', 'Brunch: waffles, omelets, fruit platters'),
(7,  7,  1007,  '2026-02-14', 'Formal dinner: steak, salmon, roasted vegetables'),
(8,  8,  1008,  '2025-08-30', 'Birthday party: sliders, wings, fries, cake'),
(9,  9,  1009,  '2025-11-05', 'Vegan menu: roasted veggies, grain bowls, salads'),
(10, 10, 1010, '2026-04-20', 'Corporate lunch: sandwich trays, soups, salads');
SELECT * FROM `ClientCateringInfo`


INSERT INTO EventSupplies (supplyID, cateringID, supplyType, quantity) VALUES
(1,  1,  'Round tables',          10),
(2,  1,  'Folding chairs',        80),
(3,  2,  'Buffet warmers',        6),
(4,  2,  'Serving trays',         12),
(5,  3,  'Chafing dishes',        8),
(6,  3,  'Soup kettles',          3),
(7,  4,  'BBQ smokers',           2),
(8,  4,  'Disposable plates',     100),
(9,  5,  'Glassware sets',        60),
(10, 5,  'Cloth napkins',         80),
(11, 6,  'Coffee urns',           3),
(12, 6,  'Mimosa flutes',         40),
(13, 7,  'Centerpieces',          15),
(14, 7,  'Tablecloths',           15),
(15, 8,  'Paper cups',            120),
(16, 8,  'Plastic cutlery sets',  120),
(17, 9,  'Compostable plates',    90),
(18, 9,  'Water dispensers',      4),
(19,10,  'Podium and mic',        1),
(20,10,  'Serving utensils',      20);
SELECT * FROM `EventSupplies`


