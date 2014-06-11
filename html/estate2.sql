CREATE TABLE IF NOT EXISTS "agencies" (
  "id" int AUTO_INCREMENT,
  "name" varchar(255) COLLATE binary NOT NULL,
  "photo" varchar(255) COLLATE binary NOT NULL,
  "brief" text COLLATE binary NOT NULL,
  PRIMARY KEY ("id"),
  KEY "photoId" ("photo")
);

CREATE TABLE IF NOT EXISTS "faqs" (
  "id" int AUTO_INCREMENT,
  "question" varchar(255) COLLATE binary NOT NULL,
  "answer" text COLLATE binary NOT NULL,
  "nice" int(11) NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE KEY "un" ("nice")
);

CREATE TABLE IF NOT EXISTS "offer" (
  "id" int  AUTO_INCREMENT,
  "agencyId" int(11) NOT NULL,
  "frontPhotoId" int(11) NOT NULL,
  "area" int(11) NOT NULL,
  "rooms" int(11) NOT NULL,
  "price" int(11) NOT NULL,
  "type" int(11) NOT NULL,
  "ansoon" int(11) NOT NULL,
  "brief" text COLLATE binary NOT NULL,
  PRIMARY KEY ("id")
);

CREATE TABLE IF NOT EXISTS "photos" (
  "id" int AUTO_INCREMENT,
  "ofr_id" int(11) NOT NULL,
  "path" varchar(255) COLLATE binary NOT NULL,
  "type" int(11) NOT NULL,
  PRIMARY KEY ("id"),
  KEY "ofr_id" ("ofr_id")
);
