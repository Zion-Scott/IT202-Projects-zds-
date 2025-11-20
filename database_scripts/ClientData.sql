INSERT INTO Clients (clientID, firstName, lastName) VALUES
(1,  'Alicia',   'Wong'),
(2,  'Brian',    'Carter'),
(3,  'Chloe',    'Diaz'),
(4,  'Derrick',  'Mason'),
(5,  'Elena',    'Rodriguez'),-- CLIENT DATA
(6,  'Frank',    'Stevens'),
(7,  'Grace',    'Hernandez'),
(8,  'Henry',    'Park'),
(9,  'Isabella', 'Torres'),
(10, 'Jacob',    'Singh');
SELECT * FROM `Clients`



INSERT INTO ClientInfo (personalInfoID, clientID, streetNumber, streetName, city, state, zipCode, phoneNumber) VALUES
(1, 1,  '120',  'Maple Ave',        'Newark',      'NJ', '07102', '973-555-1001'),
(2, 2,  '45',   'Elm Street',       'Jersey City', 'NJ', '07302', '973-555-1002'),
(3, 3,  '890',  'Cedar Lane',       'Harrison',    'NJ', '07029', '973-555-1003'),
(4, 4,  '32',   'Broadway',         'Newark',      'NJ', '07104', '973-555-1004'),
(5, 5,  '501',  'Washington Pl',    'Kearny',      'NJ', '07032', '973-555-1005'),
(6, 6,  '78',   'Chestnut St',      'Bloomfield',  'NJ', '07003', '973-555-1006'),
(7, 7,  '210',  'Highland Ave',     'East Orange', 'NJ', '07018', '973-555-1007'), -- CLIENT INFORMATION DATA
(8, 8,  '15',   'River Road',       'Nutley',      'NJ', '07110', '973-555-1008'),
(9, 9,  '640',  'Park Place',       'Montclair',   'NJ', '07042', '973-555-1009'),
(10,10,'300',   'Liberty Street',   'Newark',      'NJ', '07105', '973-555-1010');

SELECT * FROM `ClientInfo`


