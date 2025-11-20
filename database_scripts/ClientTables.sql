CREATE TABLE Clients ( -- CLIENTS TABLE
 clientID      INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
 firstName     VARCHAR(60)  NOT NULL,
 lastName      VARCHAR(60)  NOT NULL
);



CREATE TABLE ClientInfo ( -- CLIENT INFORMATION
 personalInfoID   INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
 clientID         INT(11)      NOT NULL UNIQUE,
 streetNumber     VARCHAR(20)  NOT NULL,
 streetName       VARCHAR(60)  NOT NULL,
 city             VARCHAR(60)  NOT NULL,
 state            CHAR(2)      NOT NULL,
 zipCode          VARCHAR(10)  NOT NULL,
 phoneNumber      VARCHAR(20)  NOT NULL,
 FOREIGN KEY (clientID) REFERENCES Clients(clientID)
);



CREATE TABLE ClientCateringInfo ( -- CLIENT CATERING INFORMATION
 cateringID       INT(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
 clientID         INT(11)       NOT NULL ,
 catererID        INT(11)       NOT NULL,
 dateOfEvent      DATE          NOT NULL,
 foodOrder        VARCHAR(255)  NOT NULL,
 FOREIGN KEY (clientID)  REFERENCES Clients(clientID),
 FOREIGN KEY (catererID) REFERENCES Caterers(catererID)
);



CREATE TABLE EventSupplies ( -- CLIENT CATERING INFORMATION
 supplyID      INT(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
 cateringID    INT(11)       NOT NULL,
 supplyType    VARCHAR(255)  NOT NULL,
 quantity      INT(11)       NOT NULL,
 FOREIGN KEY (cateringID)  REFERENCES ClientCateringInfo(cateringID),
 UNIQUE(cateringID, supplyType)
);

