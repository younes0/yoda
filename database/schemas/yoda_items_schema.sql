-- Yoda Items Schema
-- ==========================================================

CREATE TABLE various (
    oid integer,
    item text,
    id text,
    text_fr text,
    text_en text,
    primary key (oid),
    unique (item, id)
);

CREATE TABLE hosts (
    id text not null,
    can_have_paywall boolean default 0 not null,
    is_responsive boolean default 1 not null,
    is_trusted boolean,
    is_ignored boolean default 0 not null,
    is_banned boolean default 0 not null,
    created_at integer,
    updated_at integer,
    primary key (id),
    check (can_have_paywall in (0,1)),
    check (is_responsive in (0,1)),
    check (is_trusted in (0,1)),
    check (is_ignored in (0,1)),
    check (is_banned in (0,1))
);

CREATE TABLE tags (
    oid integer,
    domain text not null DEFAULT 'law-fr',
    codename text,
    is_primary boolean not null default 0,
    parent text,
    text text,
    description text,
    nlp_classes text, -- json
    meta_color text, -- json
    primary key (oid),
    unique (domain, codename)
    check (parent in (0,1))
);


-- Values
-- --------------------------------------------------------
INSERT INTO tags VALUES (1, 'law-fr', 'affaires', 1, null, 'Affaires', 'MISSING', null);
INSERT INTO tags VALUES (2, 'law-fr', 'assurances', 1, null, 'Assurances', 'MISSING', null);
INSERT INTO tags VALUES (3, 'law-fr', 'civil', 1, null, 'Civil', 'MISSING', null);
INSERT INTO tags VALUES (4, 'law-fr', 'civil > patrimoine', 1, 'civil', 'Patrimoine', 'MISSING', null);
INSERT INTO tags VALUES (5, 'law-fr', 'civil > procedures', 1, 'civil', 'Procédures civiles', 'MISSING', null);
INSERT INTO tags VALUES (6, 'law-fr', 'civil > responsabilite-civile', 1, 'civil', 'Responsabilité civile', 'MISSING', null);
INSERT INTO tags VALUES (7, 'law-fr', 'consommation', 1, null, 'Consommation', 'MISSING', null);
INSERT INTO tags VALUES (8, 'law-fr', 'constitutionnel', 1, null, 'Constitutionnel', 'MISSING', null);
INSERT INTO tags VALUES (9, 'law-fr', 'environnement', 1, null, 'Environnement', 'MISSING', null);
INSERT INTO tags VALUES (10, 'law-fr', 'etrangers', 1, null, 'Étrangers', 'MISSING', null);
INSERT INTO tags VALUES (11, 'law-fr', 'europe-international', 1, null, 'Europe et International', 'MISSING', null);
INSERT INTO tags VALUES (12, 'law-fr', 'famille', 1, null, 'Famille', 'MISSING', null);
INSERT INTO tags VALUES (13, 'law-fr', 'famille > couple-divorce', 1, 'famille', 'Couples et Divorces', 'MISSING', null);
INSERT INTO tags VALUES (14, 'law-fr', 'famille > enfant', 1, 'famille', 'Enfant', 'MISSING', null);
INSERT INTO tags VALUES (15, 'law-fr', 'financier-bancaire', 1, null, 'Financier et Bancaire', 'MISSING', null);
INSERT INTO tags VALUES (16, 'law-fr', 'fiscal-douanier', 1, null, 'Fiscal et Douanier', 'MISSING', null);
INSERT INTO tags VALUES (17, 'law-fr', 'immobilier-urbanisme', 1, null, 'Immobilier et Urbanisme', 'MISSING', null);
INSERT INTO tags VALUES (18, 'law-fr', 'libertes-fondamentales', 1, null, 'Libertés fondamentales', 'MISSING', null);
INSERT INTO tags VALUES (19, 'law-fr', 'medias', 1, null, 'Médias', 'MISSING', null);
INSERT INTO tags VALUES (20, 'law-fr', 'none', 1, null, 'Aucune', 'MISSING', null);
INSERT INTO tags VALUES (21, 'law-fr', 'ntic', 1, null, 'NTIC', 'MISSING', null);
INSERT INTO tags VALUES (22, 'law-fr', 'ntic > vie-privee', 1, 'ntic', 'Vie privée', 'MISSING', null);
INSERT INTO tags VALUES (23, 'law-fr', 'penal', 1, null, 'Pénal', 'MISSING', null);
INSERT INTO tags VALUES (24, 'law-fr', 'penal > procedures', 1, 'penal', 'Procédures pénales', 'MISSING', null);
INSERT INTO tags VALUES (25, 'law-fr', 'penal > routier', 1, 'penal', 'Routier', 'MISSING', null);
INSERT INTO tags VALUES (26, 'law-fr', 'profession', 1, null, 'Profession', 'MISSING', null);
INSERT INTO tags VALUES (27, 'law-fr', 'propriete-intellectuelle', 1, null, 'Propriété intellectuelle', 'MISSING', null);
INSERT INTO tags VALUES (28, 'law-fr', 'propriete-intellectuelle > auteur', 0, 'propriete-intellectuelle', 'Auteur', 'MISSING', null);
INSERT INTO tags VALUES (29, 'law-fr', 'propriete-intellectuelle > marque', 0, 'propriete-intellectuelle', 'Marque', 'MISSING', null);
INSERT INTO tags VALUES (30, 'law-fr', 'public > administratif', 1, 'public', 'Administratif', 'MISSING', null);
INSERT INTO tags VALUES (31, 'law-fr', 'public > marches-publics', 1, 'public', 'Marchés publics', 'MISSING', null);
INSERT INTO tags VALUES (32, 'law-fr', 'sante', 1, null, 'Santé', 'MISSING', null);
INSERT INTO tags VALUES (33, 'law-fr', 'social > protection-sociale', 1, 'social', 'Protection sociale', 'MISSING', null);
INSERT INTO tags VALUES (34, 'law-fr', 'social > retraite', 1, 'social', 'Retraite', 'MISSING', null);
INSERT INTO tags VALUES (35, 'law-fr', 'sport', 1, null, 'Sport', 'MISSING', null);
INSERT INTO tags VALUES (36, 'law-fr', 'transport', 1, null, 'Transports', 'MISSING', null);
INSERT INTO tags VALUES (37, 'law-fr', 'travail', 1, null, 'Travail', 'MISSING', null);
INSERT INTO tags VALUES (38, 'law-fr', 'travail > chomage', 1, 'travail', 'Chômage', 'MISSING', null);
INSERT INTO tags VALUES (39, 'law-fr', 'travail > comite-entreprise', 1, 'travail', 'Comité d''entreprise', 'MISSING', null);
INSERT INTO tags VALUES (40, 'law-fr', 'travail > conflits-et-sanctions', 1, 'travail', 'Conflits et sanctions', 'MISSING', null);
INSERT INTO tags VALUES (41, 'law-fr', 'travail > conges', 1, 'travail', 'Congès', 'MISSING', null);
INSERT INTO tags VALUES (42, 'law-fr', 'travail > contrat-de-travail', 1, 'travail', 'Contrat de travail', 'MISSING', null);
INSERT INTO tags VALUES (43, 'law-fr', 'travail > demission', 1, 'travail', 'Démission', 'MISSING', null);
INSERT INTO tags VALUES (44, 'law-fr', 'travail > fonctionnaires', 1, 'travail', 'Fonctionnaires', 'MISSING', null);
INSERT INTO tags VALUES (45, 'law-fr', 'travail > licenciement', 1, 'travail', 'Licenciement', 'MISSING', null);
INSERT INTO tags VALUES (46, 'law-fr', 'travail > ntic', 1, 'travail', 'NTIC', 'MISSING', null);
INSERT INTO tags VALUES (47, 'law-fr', 'travail > remuneration', 1, 'travail', 'Rémunération', 'MISSING', null);
INSERT INTO tags VALUES (48, 'law-fr', 'travail > representants-personel', 1, 'travail', 'Représentation du personnel', 'MISSING', null);
INSERT INTO tags VALUES (49, 'law-fr', 'travail > temps-de-travail', 1, 'travail', 'Temps de travail', 'MISSING', null);
INSERT INTO tags VALUES (50, 'law-fr', 'social', 0, null, 'Social', 'MISSING', null);
