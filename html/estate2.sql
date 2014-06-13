CREATE TABLE IF NOT EXISTS "agencies" (
  "id" int PRIMARY KEY,
  "name" varchar COLLATE binary NOT NULL,
  "photo" varchar COLLATE binary NOT NULL,
  "brief" text COLLATE binary NOT NULL
);

INSERT INTO "agencies" VALUES (0, "costaRika", "frph", "somebrief");
INSERT INTO "agencies" VALUES (1, "R.Ferreira", "frereiraPhoto", "fsomebrief");
