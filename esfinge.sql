create table usuario(
id integer not null auto_increment primary key,
email varchar(150) not null,
senha varchar(70) not null
);

create table perfil(
id integer not null auto_increment primary key,
nome varchar(150) not null,
idade integer null,
grau_estudo varchar(20) null,
date_cadastro date not null,
id_usuario_perfil integer not null
);

ALTER TABLE perfil ADD CONSTRAINT fk_usuario_perfil FOREIGN KEY ( id_usuario_perfil ) 
	REFERENCES usuario ( id );

create table resumo(
id integer not null auto_increment primary key,
titulo varchar(50) not null,
texto longtext null,
status_publicacao integer not null,
data_publicacao datetime null,
area varchar(20) null,
referencia varchar(40) null,
status_visibilidade integer not null,
id_usuario_resumo integer not null
);

ALTER TABLE resumo ADD CONSTRAINT fk_usuario_resumo FOREIGN KEY ( id_usuario_resumo ) 
	REFERENCES usuario ( id );

create table amizade(
id integer not null auto_increment primary key,
status_amizade integer not null,
data_amizade date null,
id_convidador integer not null,
id_convidado integer not null
);

ALTER TABLE amizade ADD CONSTRAINT fk_convidador FOREIGN KEY ( id_convidador ) 
	REFERENCES usuario ( id );
ALTER TABLE amizade ADD CONSTRAINT fk_convidado FOREIGN KEY ( id_convidado ) 
	REFERENCES usuario ( id );