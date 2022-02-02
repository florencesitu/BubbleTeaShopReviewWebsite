drop table OfTable;
drop table Collaborates;
drop table OpinionOf;
drop table Judgement;
drop table About;
drop table FruitTea;
drop table MilkTea;
drop table Have;
drop table Milk;
drop table Topping;
drop table Packaging;
drop table Packaging_SStyle;
drop table TakeOutPlatform;
drop table Review;
drop table Customer;
drop table Beverage;
drop table Ingredients;
drop table Beverage_Price;
drop table Shop;


CREATE TABLE Shop(
    BTS_Name		char(50),		
    loca            char(50),		
    rating	        int,
    PRIMARY KEY (BTS_Name, loca)
);
grant select on Shop to public;


CREATE TABLE Packaging_SStyle(
seasonal_style			char(50)	PRIMARY KEY,
ambassador	            char(50)
);
grant select on Packaging_SStyle to public;


CREATE TABLE Packaging(
logo 			char(50)	PRIMARY KEY,
seasonal_style	char(50),
type			char(50),
FOREIGN KEY(seasonal_style) REFERENCES Packaging_SStyle(seasonal_style) ON DELETE CASCADE
);
grant select on Packaging to public;


CREATE TABLE Beverage_Price (
    price			char(50),		
    ice_level		char(50),		
    sugar_level		char(50),
    PRIMARY KEY (price)
);
grant select on Beverage_Price to public;


CREATE TABLE Beverage (
    B_Name		char(50) UNIQUE,		
    BTS_Name	char(50),	
    loca  	    char(50),			
    teaType		char(50),		
    price       char(50),   	 
    PRIMARY KEY (B_Name, BTS_Name, loca),
    FOREIGN KEY (BTS_Name, loca) REFERENCES Shop (BTS_Name, loca) ON DELETE CASCADE,
    FOREIGN KEY (price) REFERENCES Beverage_Price (price)
);
grant select on Beverage to public;

CREATE TABLE OfTable (
    B_Name 		char(50),
    logo		char(50),
    PRIMARY KEY(B_Name, logo),
    FOREIGN KEY(B_Name) REFERENCES Beverage(B_Name),
    FOREIGN KEY(logo) REFERENCES Packaging(logo)
);
grant select on OfTable to public;


CREATE TABLE TakeOutPlatform(
    tname			char(50),		
    delivery_speed	char(50),
    delivery_fee	float,
    PRIMARY KEY(tname)
);
grant select on TakeOutPlatform to public;



CREATE TABLE Collaborates(
    BTS_Name		char(50),		
    loca  		    char(50),		
    tname			char(50),		
    PRIMARY KEY(BTS_Name, loca, tname),
    FOREIGN KEY(BTS_Name, loca)  REFERENCES Shop (BTS_Name, loca),
    FOREIGN KEY(tname) REFERENCES TakeOutPlatform(tname)
);
grant select on Collaborates to public;

CREATE TABLE Customer
(
    C_id   int PRIMARY KEY,
    C_name char(50)
);
grant select on Customer to public;

CREATE TABLE Review(
    review_number	int			PRIMARY KEY,
    rating			int,			
    paragraph		char(50),
    id 			    int			  NOT NULL,
    FOREIGN KEY(id) REFERENCES Customer(C_id) ON DELETE CASCADE
);
grant select on Review to public;


CREATE TABLE OpinionOf(
    tname		    char(50),
    review_number 	int,
    PRIMARY KEY(tname, review_number),
    FOREIGN KEY(tname) REFERENCES TakeOutPlatform(tname),
    FOREIGN KEY(review_number) REFERENCES Review(review_number)
);
grant select on OpinionOf to public;

CREATE TABLE Judgement(
    BTS_Name		char(50),
    loca 	        char(50),	
    B_Name          char(50),
    review_number	int,
    PRIMARY KEY(BTS_Name, loca, B_Name, review_number),
    FOREIGN KEY(BTS_Name, loca) REFERENCES Shop (BTS_Name, loca), 
    FOREIGN KEY(B_Name) REFERENCES Beverage(B_Name),
    FOREIGN KEY(review_number) REFERENCES Review(review_number)
);
grant select on Judgement to public;

CREATE TABLE About (
logo			char(50),
review_number	int,
PRIMARY KEY(logo, review_number),
FOREIGN KEY(logo) REFERENCES Packaging(logo),
FOREIGN KEY(review_number) REFERENCES Review(review_number)
);
grant select on About to public;


CREATE TABLE FruitTea(
    BTS_Name 		char(50),
    B_Name		    char(50),
    loca		    char(50),
    fruit			char(50),
    PRIMARY KEY(BTS_Name, B_Name, loca),
    FOREIGN KEY(BTS_Name, loca) REFERENCES Shop(BTS_Name, loca) ON DELETE CASCADE,
    FOREIGN KEY (B_Name) REFERENCES Beverage(B_Name) ON DELETE CASCADE
);
grant select on FruitTea to public;

CREATE TABLE MilkTea(
    BTS_Name 		char(50),
    B_Name		    char(50),
    loca		    char(50),
    mlik_foam		char(50),
    PRIMARY KEY(BTS_Name, B_Name, loca),
    FOREIGN KEY(BTS_Name, loca) REFERENCES Shop(BTS_Name, loca) ON DELETE CASCADE,
    FOREIGN KEY (B_Name) REFERENCES Beverage(B_Name) ON DELETE CASCADE
); 
grant select on MilkTea to public;


CREATE TABLE Ingredients
(
    name char(50) PRIMARY KEY
);
grant select on Ingredients to public;


CREATE TABLE Have(
    BTS_Name 		char(50),
    B_Name		    char(50),
    loca		    char(50),
    name			char(50),
    PRIMARY KEY(BTS_Name, B_Name, loca),
    FOREIGN KEY(BTS_Name, loca) REFERENCES Shop(BTS_Name, loca),
    FOREIGN KEY (B_Name) REFERENCES Beverage(B_Name)
);
grant select on Have to public;


CREATE TABLE Milk(
    name			char(50) 		PRIMARY KEY,
    fat_level		char(50),
    FOREIGN KEY(name) REFERENCES Ingredients(name)
);
grant select on Milk to public;


CREATE TABLE Topping
(
    name    char(50) PRIMARY KEY,
    texture char(50),
    flavour char(50),
    FOREIGN KEY (name) REFERENCES Ingredients(name) ON DELETE CASCADE
);
grant select on Topping to public;


insert into Shop  values('Coco', '655 W Pender St', 4.2);
insert into Shop  values('Coco', '5728 University Blvd', 4.3);
insert into Shop  values('Bengongs Tea', '5728 University Blvd', 4.2);
insert into Shop  values('Bengongs Tea', '3341 Wesbrook Mall', 4.9);
insert into Shop  values('Bengongs Tea', '4515 W 10th Ave', 3.9);
insert into Shop  values('Share Tea', '1234 West Mall', 2.9);

insert into Beverage_Price values( 4,'100%','50%');
insert into Beverage_Price values( 5,'100%','25%');
insert into Beverage_Price values( 5.25,'50%','50%');
insert into Beverage_Price values( 6,'75%', '25%');

insert into Beverage values('Bubble Tea','Coco','655 W Pender St','Black Tea', 4);
insert into Beverage values('Black Sugar Bubble Tea','Coco','655 W Pender St','Green Tea', 5);
insert into Beverage values('Red Bean Matcha Milk Tea','Bengongs Tea','5728 University Blvd','Green Tea', 5);
insert into Beverage values('Matcha Green Tea','Bengongs Tea','5728 University Blvd','Green Tea', 4);
insert into Beverage values('Lemon Tea','Bengongs Tea','5728 University Blvd','Green Tea', 5.25);
insert into Beverage values('Fruit Tea','Bengongs Tea','5728 University Blvd','Green Tea', 6);
insert into Beverage values('Milk Foam Oolong Tea', 'Share Tea', '1234 West Mall', 'Oolong Tea', 5);

insert into TakeOutPlatform values('Skip the Dishes','10km/hr', 5.0);
insert into TakeOutPlatform values('Fantuan','5km/hr', 4.85);
insert into TakeOutPlatform values('Chowbus','13km/hr', 5.5);
insert into TakeOutPlatform values('Uber Eats','20km/hr', 5.5);
insert into TakeOutPlatform values('DoorDash','2km/hr', 3.5);

insert into Collaborates  values('Coco', '655 W Pender St', 'Uber Eats');
insert into Collaborates  values('Coco', '655 W Pender St',  'Fantuan');
insert into Collaborates  values('Bengongs Tea', '5728 University Blvd', 'Uber Eats');
insert into Collaborates  values('Bengongs Tea', '4515 W 10th Ave', 'DoorDash');

insert into Customer values(101, 'Florence');
insert into Customer values(102, 'Heyison');
insert into Customer values(103, 'Amy');
insert into Customer values(104, 'Barry');
insert into Customer values(105, 'Bruce');

insert into Review  values(1, 3, 'ok', 101);
insert into Review  values(2, 4, 'nice', 101);
insert into Review  values(3, 5, 'great', 102);
insert into Review  values(4, 5, 'great', 103);
insert into Review  values(5, 2, 'ok', 104);
insert into Review  values(6, 3,'ok', 104);
insert into Review  values(7, 4, 'good', 105);

insert into OpinionOf values('Skip the Dishes', 4);
insert into OpinionOf values('Skip the Dishes', 5);

insert into Judgement values('Coco','655 W Pender St','Red Bean Matcha Milk Tea',1);
insert into Judgement values('Coco','655 W Pender St','Black Sugar Bubble Tea',  2);
insert into Judgement values('Bengongs Tea','5728 University Blvd', 'Bubble Tea', 3);


insert into Packaging_SStyle values('New year','Xukun Cai');
insert into Packaging_SStyle values('Winter','none');

insert into Packaging values('Logo 1', 'Winter', 'platic');
insert into Packaging values('Logo 2', 'New year', 'paper');

insert into About values('Logo 1', 6);
insert into About values('Logo 2', 7);

insert into FruitTea values('Bengongs Tea','Matcha Green Tea','5728 University Blvd' ,'orange'); 
insert into FruitTea values('Bengongs Tea','Lemon Tea','5728 University Blvd' ,'ornage'); 
insert into FruitTea values('Bengongs Tea','Fruit Tea' , '3341 Wesbrook Mall','banada'); 

insert into MilkTea values('Coco','Red Bean Matcha Milk Tea','655 W Pender St','salted milk foam' ); 
insert into MilkTea values('Coco','Black Sugar Bubble Tea','655 W Pender St','unsalted milk foam' ); 
insert into MilkTea values('Bengongs Tea','Bubble Tea','5728 University Blvd','salted milk foam' ); 

insert into Ingredients values('Coconut jelly');
insert into Ingredients values('Boba');
insert into Ingredients values('Aiyu');
insert into Ingredients values('Almond Milk');
insert into Ingredients values('Grass jelly');
insert into Ingredients values('Oat Milk');


insert into Have values('Coco','Red Bean Matcha Milk Tea','655 W Pender St', 'Coconut jelly');
insert into Have values('Coco', 'Black Sugar Bubble Tea','655 W Pender St', 'Boba');
insert into Have values('Bengongs Tea','Bubble Tea','5728 University Blvd', 'Almond Milk');

insert into Milk  values('Almond Milk', 0);
insert into Milk  values('Oat Milk', 0.05);
insert into Topping  values('Boba', 'soft', 'sweet');
insert into Topping  values('Coconut jelly', 'tender', 'coconut');