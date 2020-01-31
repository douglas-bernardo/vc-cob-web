-- Retorna os dados de uma tabela específica, como nomes de coluna, tipo de dado, etc
select * from all_tab_columns
 where table_name = upper('VENDAXCONTRATOTS')
and owner = 'CM'
order by column_name;