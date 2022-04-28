create table doctrine_migration_versions
(
    version        VARCHAR(191) not null
        primary key,
    executed_at    DATETIME default NULL,
    execution_time INTEGER  default NULL
);

INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\Version20220425122503', '2022-04-25 12:25:19', 35);

create table im22_products
(
    id    INTEGER          not null
        primary key autoincrement,
    label VARCHAR(30)      not null,
    price DOUBLE PRECISION not null,
    stock INTEGER          not null
);

INSERT INTO im22_products (id, label, price, stock) VALUES (1, 'Pomme', 1.5, 25);
INSERT INTO im22_products (id, label, price, stock) VALUES (2, 'Banane', 2, 25);

create table im22_shopping_basket
(
    id         INTEGER not null
        primary key autoincrement,
    user_id    INTEGER not null,
    product_id INTEGER not null,
    quantity   INTEGER not null
);

create index IDX_27ACDEDC4584665A
    on im22_shopping_basket (product_id);

create index IDX_27ACDEDCA76ED395
    on im22_shopping_basket (user_id);

INSERT INTO im22_shopping_basket (id, user_id, product_id, quantity) VALUES (5, 2, 1, 10);
INSERT INTO im22_shopping_basket (id, user_id, product_id, quantity) VALUES (6, 2, 2, 35);

create table im22_users
(
    id             INTEGER     not null
        primary key autoincrement,
    login          VARCHAR(60) not null,
    password       VARCHAR(60) not null,
    surname        VARCHAR(30) not null,
    roles          CLOB        not null,
    firstname      VARCHAR(30) not null,
    date_of_birth  DATE    default NULL,
    is_admin       BOOLEAN default 0 not null,
    is_super_admin BOOLEAN default 0 not null
);

create unique index UNIQ_B4392992AA08CB10
    on im22_users (login);

INSERT INTO im22_users (id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin) VALUES (2, 'gilles', '$2y$13$HeyjHbYYjNdnENnLNgU7h.nl6gSuiExShHd9nWeMkrZrvd/AdcOvG', 'Subrenat', '["ROLE_ADMIN"]', 'Gilles', '1975-07-10', 1, 0);
INSERT INTO im22_users (id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin) VALUES (3, 'simon', '$2y$13$3fQLxff2SYruyLt6o6pc0ezfVXQ/Za3xjhXqac1S.6KP98dy2xUOu', 'Kortsmit', '["ROLE_ADMIN"]', 'Simon', '2000-07-03', 1, 0);
INSERT INTO im22_users (id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin) VALUES (4, 'rita', '$2y$13$sRHHz8rg85CHLr7EnJ7VLeqJ3t5kn4nQMXpUqZAcwxuR5EtbRyjZC', 'Zrour', '[]', 'Rita', '1978-11-23', 0, 0);
INSERT INTO im22_users (id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin) VALUES (7, 'dede', '$2y$13$sT2TXYrV.YBuRBsQ5SPLx.rKZs0PBsI.RfsVZuXNnC3xCrqXhkuN.', 'dede', '[]', 'dede', null, 0, 0);
INSERT INTO im22_users (id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin) VALUES (10, 'admin', '$2y$13$Cd5VWfzgjreF59pXP77N4OA5nJsg3cuIMIH2wxoQSR9eHUIrygr2y', 'Admin', '["ROLE_SUPERADMIN"]', 'Admin', null, 0, 1);
INSERT INTO im22_users (id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin) VALUES (11, 'adm', '$2y$13$BkPXk3vBj37GMG.naguI..ZUaeVWZaoCLEQUn0wpPrCWOsbUyjakC', 'adm', '["ROLE_SUPERADMIN"]', 'adm', null, 0, 1);
INSERT INTO im22_users (id, login, password, surname, roles, firstname, date_of_birth, is_admin, is_super_admin) VALUES (12, 'adm2', '$2y$13$Sf67VIQ2duZUFu3k1.VNC.83KBMhc9b0vr33oDKuSdG5KtAyrBBbS', 'adm2', '["ROLE_SUPERADMIN"]', 'adm2', null, 0, 1);

create table sqlite_master
(
    type     text,
    name     text,
    tbl_name text,
    rootpage int,
    sql      text
);

INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('table', 'doctrine_migration_versions', 'doctrine_migration_versions', 2, 'CREATE TABLE doctrine_migration_versions (version VARCHAR(191) NOT NULL, executed_at DATETIME DEFAULT NULL, execution_time INTEGER DEFAULT NULL, PRIMARY KEY(version))');
INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('index', 'sqlite_autoindex_doctrine_migration_versions_1', 'doctrine_migration_versions', 3, null);
INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('table', 'im22_products', 'im22_products', 4, 'CREATE TABLE im22_products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(30) NOT NULL, price DOUBLE PRECISION NOT NULL, stock INTEGER NOT NULL)');
INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('table', 'sqlite_sequence', 'sqlite_sequence', 5, 'CREATE TABLE sqlite_sequence(name,seq)');
INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('table', 'im22_shopping_basket', 'im22_shopping_basket', 6, 'CREATE TABLE im22_shopping_basket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL)');
INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('index', 'IDX_27ACDEDCA76ED395', 'im22_shopping_basket', 7, 'CREATE INDEX IDX_27ACDEDCA76ED395 ON im22_shopping_basket (user_id)');
INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('index', 'IDX_27ACDEDC4584665A', 'im22_shopping_basket', 8, 'CREATE INDEX IDX_27ACDEDC4584665A ON im22_shopping_basket (product_id)');
INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('table', 'im22_users', 'im22_users', 9, 'CREATE TABLE im22_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(60) NOT NULL, password VARCHAR(60) NOT NULL, surname VARCHAR(30) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , firstname VARCHAR(30) NOT NULL, date_of_birth DATE DEFAULT NULL, is_admin BOOLEAN DEFAULT 0 NOT NULL, is_super_admin BOOLEAN DEFAULT 0 NOT NULL)');
INSERT INTO sqlite_master (type, name, tbl_name, rootpage, sql) VALUES ('index', 'UNIQ_B4392992AA08CB10', 'im22_users', 10, 'CREATE UNIQUE INDEX UNIQ_B4392992AA08CB10 ON im22_users (login)');

create table sqlite_sequence
(
    name,
    seq
);

INSERT INTO sqlite_sequence (name, seq) VALUES ('im22_users', 12);
INSERT INTO sqlite_sequence (name, seq) VALUES ('im22_products', 2);
INSERT INTO sqlite_sequence (name, seq) VALUES ('im22_shopping_basket', 10);
