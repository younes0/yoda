-- Yoda Schema
-- ==========================================================

-- App
-- -------------------------------------------------
CREATE TABLE users (
	id bigserial,
	email varchar(128) not null,
	password varchar(128) not null,
	remember_token varchar(128) null,
	is_admin boolean default false not null,
	firstname varchar(128) not null,
	lastname varchar(128) not null,
	-- timestamps
	deleted_at timestamp,
	created_at timestamp,
	updated_at timestamp,
	primary key (id),
	unique (email)
);

CREATE TABLE oauth_tokens (
    id varchar not null,
    value varchar null,
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id)
);

CREATE TYPE firewall_type AS ENUM ('white', 'black');

CREATE TABLE firewall_entries (
    entry varchar(255),
    type firewall_type,
    primary key (entry)
);

-- Tweeter Curation
-- -------------------------------------------------
CREATE TYPE origin_type AS ENUM ('home', 'list');

CREATE TABLE origins (
    id serial,
    type origin_type DEFAULT 'home' not null,
    account_id bigint not null,
    list_id bigint,
    name varchar not null DEFAULT 'untitled', 
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id)
);

CREATE TABLE collects (
    id bigserial, 
    origin_id integer not null, -- FK
    exception varchar,
    has_links_populated boolean DEFAULT false not null,
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id),
    foreign key (origin_id) references origins (id) on delete cascade
);

CREATE TABLE tweets (
    id bigserial,
    collect_id bigint not null, -- FK
    expanded_url varchar,
    source_id bigint not null,
    user_id bigint null,
    user_name varchar null,
    url varchar not null,
    published_at timestamp,
    content varchar not null,
    image_url varchar,
    hashtags json,
    lang varchar,
    is_retweet boolean DEFAULT false not null,
    retweet_count integer DEFAULT 0 not null,
    favorite_count integer DEFAULT 0 not null,
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id),
    foreign key (collect_id) references collects (id) on delete cascade
);

CREATE TABLE links ( -- taggable
    id bigserial,
    url varchar not null,
    -- checks
    is_human_approved boolean,
    is_machine_approved boolean,
    is_nlpdoc_checked boolean DEFAULT false,
    -- infos
    host varchar,
    type varchar,
    title varchar,
    description varchar,
    content varchar,
    html varchar,
    images_url json,
    lang char(2),
    published_at timestamp,
    has_paywall boolean,
    -- rating
    rating numeric,
    rated_at timestamp,
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id)
);

CREATE TABLE links_metrics (
    id bigserial,
    link_id bigint, -- FK
    shares integer,
    retweets integer,
    favorites integer,
     -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id),
    foreign key (link_id) references links (id) on delete cascade
);

CREATE TABLE jedi_users (
    id serial,
    jedi_id bigint not null,
    fullname varchar not null,
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id)
);

CREATE TABLE posts (
    id bigserial,
    link_id bigint not null, -- FK
    -- override
    url varchar,
    description varchar,
    content varchar,
    -- publish options
    is_ignored boolean,
    publish_at timestamp,
    publish_on varchar, --- default: jediwp
    publisher_id integer, -- FK
    -- publish response
    published_id bigint,
    published_url varchar,
    has_failed boolean,
    -- timestamps
    deleted_at timestamp,
    created_at timestamp,
    updated_at timestamp,
    primary key (id),
    foreign key (link_id) references links (id) on delete cascade,
    foreign key (publisher_id) references jedi_users (id) on delete set null,
    unique (link_id)
);

CREATE TABLE nlp_classed (
    id bigserial,
    model_type varchar not null,
    model_id bigint not null,
    nlp_model varchar not null,
    method varchar, -- svm/bayes/etc.
    class varchar,
    score numeric,
    more varchar,
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id)
);

CREATE TABLE tweeps (
    id varchar not null, -- screen_name
    description varchar,
    is_human_approved boolean,
    is_machine_approved boolean,
    score integer,
    -- metrics
    tweets_urls jsonb,
    tweets_per_day numeric,
    links_per_tweet numeric,
    proper_lang_per_tweet numeric,
    proper_domain_per_link numeric,
    metrics_updated_at timestamp,
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id)
);

CREATE TABLE scraped (
    id bigserial,
    url varchar not null,
    html varchar,
    content varchar,
    title varchar,
    description varchar,
    lang varchar(5),
    class varchar(255),
    cat_first varchar(255),
    cat_second varchar(255),
    -- timestamps
    created_at timestamp,
    updated_at timestamp,
    primary key (id)
);

CREATE TABLE tagged (
    id bigserial,
    tag_domain varchar,
    tag_codename varchar,
    taggable_id bigint,
    taggable_type varchar,
    source varchar, -- nlp, human
    primary key (id)
);

-- Privileges
-------------------------------
-- app
GRANT SELECT, UPDATE, INSERT, DELETE ON ALL TABLES IN SCHEMA public TO app;
GRANT USAGE, SELECT, UPDATE ON ALL SEQUENCES IN SCHEMA public TO app;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO app;
-- read
GRANT SELECT ON ALL TABLES IN SCHEMA public TO read;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO read;
