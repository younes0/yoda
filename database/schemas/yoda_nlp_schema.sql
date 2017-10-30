-- Yoda Postgres NLP
-- ==========================================================

-- Schema
-- ----------------------------------------------------------

CREATE TABLE documents (
	id bigserial,
	domain text not null DEFAULT 'law-fr',
	is_checked boolean not null DEFAULT false,
	class text,
	tokens jsonb,
	source text not null,
	content text not null,
	classified_as text,
	classified_score numeric,
	created_at timestamp,
	updated_at timestamp,
	primary key (id),
	foreign key (domain) references domains (id) on delete cascade on update cascade,
	foreign key (class) references classes (id) on delete cascade on update cascade
);

CREATE TABLE documents_tokens (
	id bigserial,
	document_id bigint,
	token varchar,
	primary key (id),
	foreign key (document_id) references documents (id) on delete cascade on update cascade
);

CREATE TABLE ngrams (
	id varchar not null,
	count bigint,
	primary key (id)
);

CREATE TABLE scraped (
	id bigserial,
	url varchar not null,
	html varchar,
	cat_first varchar(255),
	cat_second varchar(255),
	content varchar,
	lang varchar(5),
	class varchar(255),
	title varchar,
	description varchar,
	created_at timestamp,
	updated_at timestamp,
	primary key (id)
);
