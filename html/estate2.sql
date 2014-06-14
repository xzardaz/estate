CREATE TABLE IF NOT EXISTS "agencies" (
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" varchar COLLATE binary NOT NULL,
  "photo" varchar COLLATE binary NOT NULL,
  "brief" text COLLATE binary
);

INSERT INTO "agencies" (name, photo) VALUES ( "costaRika", "frph");
INSERT INTO "agencies" (name, photo) VALUES ("R.Ferreira", "frereiraPhoto");



-- INSERT INTO agencies (name, photo) VALUES ("Eurotop Holding", "http://imoti.net/agency/sp_agency?id=10840");




