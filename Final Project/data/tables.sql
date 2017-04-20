CREATE TABLE Users (
	ID int NOT NULL AUTO_INCREMENT,
	fullName varchar(70) NOT NULL,
	userName varchar(30) NOT NULL,
	age int NOT NULL,
	email varchar(50) NOT NULL,
	password varchar(80) NOT NULL,
	weight decimal(4,1),
	height decimal(3,2),
	PRIMARY KEY (ID, userName)
	);

CREATE TABLE Wods (
	ID int NOT NULL AUTO_INCREMENT,
	day date NOT NULL,
	wod varchar(500) NOT NULL,
	type varchar(5) NOT NULL,
	PRIMARY KEY(ID, day)
);
