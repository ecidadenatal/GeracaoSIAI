CREATE TABLE plugins.orcunidadecodigotce (
    anousu integer,
    orgao integer,
    unidade integer,
    codigotribunal character varying(10),
    instit integer
);

alter table plugins.assinaturaordenadordespesa add column cpf varchar(11);

update plugins.assinaturaordenadordespesa set cpf = w_ordenadordespesa.cpf from w_ordenadordespesa where w_ordenadordespesa.nome = plugins.assinaturaordenadordespesa.nome;

update plugins.assinaturaordenadordespesa set cpf = '83689265487' where nome ilike 'GETULIO BATISTA S. NETO';
update plugins.assinaturaordenadordespesa set cpf = '20045972400' where nome ilike 'WILMA MARIA DE FARIAS';
update plugins.assinaturaordenadordespesa set cpf = '17592542404' where nome ilike 'HOMERO CREC CRUZ SÁ';
update plugins.assinaturaordenadordespesa set cpf = '46621806434' where nome ilike 'WALTER PEDRO DA SILVA';
update plugins.assinaturaordenadordespesa set cpf = '20052537404' where nome ilike 'JUSTINA IVA DE ARAÚJO SILVA';
update plugins.assinaturaordenadordespesa set cpf = '44095201304' where nome ilike 'LUÍS ROBERTO LEITE FONSECA';
update plugins.assinaturaordenadordespesa set cpf = '14624109449' where nome ilike 'José Dionisio Gomes';
update plugins.assinaturaordenadordespesa set cpf = '24319112415' where nome ilike 'REJANE MARIA DE OLIVEIRA';
update plugins.assinaturaordenadordespesa set cpf = '10775005487' where nome ilike 'RIVALDO FERNANDES PEREIRA';

insert into plugins.orcunidadecodigotce values (2016, 25, 10, 'I040');
insert into plugins.orcunidadecodigotce values (2016, 17, 10, 'I039');
insert into plugins.orcunidadecodigotce values (2016, 26, 01, 'I103');
insert into plugins.orcunidadecodigotce values (2016, 17, 20, 'I051');
insert into plugins.orcunidadecodigotce values (2016, 18, 49, 'A088');
insert into plugins.orcunidadecodigotce values (2016, 15, 49, 'I118');
insert into plugins.orcunidadecodigotce values (2016, 20, 49, 'F088');
insert into plugins.orcunidadecodigotce values (2016, 12, 01, 'I100');
insert into plugins.orcunidadecodigotce values (2016, 35, 20, 'I117');
insert into plugins.orcunidadecodigotce values (2016, 13, 01, 'I102');
insert into plugins.orcunidadecodigotce values (2016, 36, 01, 'I116');
insert into plugins.orcunidadecodigotce values (2016, 29, 01, 'I115');
insert into plugins.orcunidadecodigotce values (2016, 18, 01, 'I108');
insert into plugins.orcunidadecodigotce values (2016, 22, 01, 'I113');
insert into plugins.orcunidadecodigotce values (2016, 33, 01, 'I101');
insert into plugins.orcunidadecodigotce values (2016, 37, 01, 'I093');
insert into plugins.orcunidadecodigotce values (2016, 15, 01, 'I106');
insert into plugins.orcunidadecodigotce values (2016, 28, 01, 'I112');
insert into plugins.orcunidadecodigotce values (2016, 11, 01, 'I099');
insert into plugins.orcunidadecodigotce values (2016, 23, 01, 'I109');
insert into plugins.orcunidadecodigotce values (2016, 25, 01, 'I104');
insert into plugins.orcunidadecodigotce values (2016, 20, 01, 'I107');
insert into plugins.orcunidadecodigotce values (2016, 17, 01, 'I114');
insert into plugins.orcunidadecodigotce values (2016, 27, 01, 'I105');
insert into plugins.orcunidadecodigotce values (2016, 31, 01, 'I111');
insert into plugins.orcunidadecodigotce values (2016, 35, 01, 'I110');

alter table plugins.orcunidadecodigotce add column nometribunal varchar(255);

update plugins.orcunidadecodigotce set nometribunal = 'AGÊNCIA REGULADORA DE SANEAMENTO BÁSICO DE NATAL' where codigotribunal = 'I040';
update plugins.orcunidadecodigotce set nometribunal = 'COMPANHIA DE SERVICOS URBANOS DE NATAL' where codigotribunal = 'I039';
update plugins.orcunidadecodigotce set nometribunal = 'CONTROLADORIA GERAL DO MUNICÍPIO DE NATAL' where codigotribunal = 'I103';
update plugins.orcunidadecodigotce set nometribunal = 'EMPRESA DE FOMENTO E SEG ALIMENTAR E NUTRICIONAL' where codigotribunal = 'I051';
update plugins.orcunidadecodigotce set nometribunal = 'FUNDO ASSIST. SOCIAL.NATAL' where codigotribunal = 'A088';
update plugins.orcunidadecodigotce set nometribunal = 'FUNDO MUNICIPAL DE EDUCAÇÃO DE NATAL' where codigotribunal = 'I118';
update plugins.orcunidadecodigotce set nometribunal = 'FUNDO SAÚDE.NATAL' where codigotribunal = 'F088';
update plugins.orcunidadecodigotce set nometribunal = 'GABINETE DO VICE PREFEITO DE NATAL' where codigotribunal = 'I100';
update plugins.orcunidadecodigotce set nometribunal = 'INST. MUN. DE PROTEÇÃO E DEFESA AO CONSUMIDOR NAT' where codigotribunal = 'I117';
update plugins.orcunidadecodigotce set nometribunal = 'INSTITUTO DE PREV SOCIAL SERV DO MUNICÍPIO DE NATAL' where codigotribunal = 'I052';
update plugins.orcunidadecodigotce set nometribunal = 'PROCURADORIA GERAL DO MUNICÍPIO DE NATAL' where codigotribunal = 'I102';
update plugins.orcunidadecodigotce set nometribunal = 'SEC. MUN. DE POLITICAS PARA AS MULHERES DE NATAL' where codigotribunal = 'I116';
update plugins.orcunidadecodigotce set nometribunal = 'SEC. MUN. DE SEGUR PUBLICA E DEFESA SOCIAL - NATAL' where codigotribunal = 'I110';
update plugins.orcunidadecodigotce set nometribunal = 'SEC. MUN. DO MEIO AMBIENTE E URBANISMO DE NATAL' where codigotribunal = 'I115';
update plugins.orcunidadecodigotce set nometribunal = 'SEC. MUN. DO TRABALHO E ASSISTÊNCIA SOCIAL - NATAL' where codigotribunal = 'I108';
update plugins.orcunidadecodigotce set nometribunal = 'SEC. MUN. OBRAS PÚBLICAS E INFRAESTRUTURA - NATAL' where codigotribunal = 'I113';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE COMUNICAÇÃO SOCIAL - NATAL' where codigotribunal = 'I101';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE CULTURA - SECULT' where codigotribunal = 'I093';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE EDUCAÇÃO DE NATAL' where codigotribunal = 'I106';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE ESPORTE E LAZER DE NATAL' where codigotribunal = 'I112';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE GOVERNO - NATAL' where codigotribunal = 'I099';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE MOBILIDADE URBANA DE NATAL' where codigotribunal = 'I109';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE PLANEJAMENTO - NATAL' where codigotribunal = 'I104';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE SAÚDE DE NATAL' where codigotribunal = 'I107';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE SERVIÇOS URBANOS DE NATAL' where codigotribunal = 'I114';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE TRIBUTAÇÃO DE NATAL' where codigotribunal = 'I105';
update plugins.orcunidadecodigotce set nometribunal = 'SECRETARIA MUNICIPAL DE TURISMO DE NATAL' where codigotribunal = 'I111';
