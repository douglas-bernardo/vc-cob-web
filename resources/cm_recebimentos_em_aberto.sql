SELECT /*+RULE*/
P.RAZAOSOCIAL,
P.NOME AS NOMECLIENTE,
DECODE(D.COMPLDOCUMENTO,
        NULL,
        D.NODOCUMENTO,
        (D.NODOCUMENTO || '/' || D.COMPLDOCUMENTO)) AS DOC,
V.NUMEROCONTRATO,
D.DATAPROGRAMADA,
L.DATALANCTO,
L.HISTORICOCOMPL,
TO_NUMBER(DECODE(D.RECPAG, 'P', SALDO.SVALOR, SALDO.SVALOR * -1)) AS VALOR,
TO_NUMBER(DECODE(D.RECPAG,
                  'P',
                  SALDO.SVALOROUTRAMOEDA,
                  SALDO.SVALOROUTRAMOEDA * -1)) AS VALOROUTRA,
F.DESCRICAO AS DESCFORMARECPAG,
P.TIPO,
P.NUMDOCUMENTO,
S.NOMEPROJETO,
S.NUMEROPROJETO,
T.DESCRICAO,
D.CODTIPDOC,
V.FLGCANCELADO,
D.CODDOCUMENTO,
cel.ddi ||cel.ddd ||cel.numero  as Celular,
tel.ddi ||tel.ddd ||tel.numero  as Telefone
  FROM DOCUMENTO D,
       LANCTODOCUM L,
       PESSOA P,
       PORTADORFORMA F,
       VENDAXCONTRATOTS V,
       PROJETOTS S,
       ATENDCLIENTETS A,
       VENDATS VS,
       LANCAMENTOTS LTS,
       TIPODOCRECPAG T,
       cm.endpess endres,
       (select ddi, ddd, numero, tipo, idendereco
          from (select ddi,
                       ddd,
                       numero,
                       tipo,
                       idendereco,
                       ROW_NUMBER() OVER(PARTITION BY idendereco ORDER BY NULL) rn
                  from cm.telendpess
                 where tipo = 'L'
                   and numero is not null)
         where rn = 1) cel,
       (select ddi, ddd, numero, tipo, idendereco
          from (select ddi,
                       ddd,
                       numero,
                       tipo,
                       idendereco,
                       ROW_NUMBER() OVER(PARTITION BY idendereco ORDER BY NULL) rn
                  from cm.telendpess
                 where tipo = 'P'
                   and numero is not null)
         where rn = 1) tel,

       (SELECT D.CODDOCUMENTO,
               SUM(DECODE(L.DEBCRE, 'C', (L.VALOR), (L.VALOR) * -1)) AS SVALOR,
               SUM(DECODE(L.DEBCRE,
                          'C',
                          (L.VALOROUTRAMOEDA),
                          (L.VALOROUTRAMOEDA) * -1)) AS SVALOROUTRAMOEDA
          FROM DOCUMENTO D, LANCTODOCUM L
         WHERE (D.CODDOCUMENTO = L.CODDOCUMENTO)
           AND (L.OPERACAO <> '16')
            GROUP BY D.CODDOCUMENTO
        HAVING(ABS(SUM(DECODE(L.DEBCRE, 'C', L.VALOR, L.VALOR * -1))) >= 0.01) OR (ABS(SUM(DECODE(L.DEBCRE, 'C', L.VALOROUTRAMOEDA, L.VALOROUTRAMOEDA * -1))) >= 0.01)) SALDO

WHERE (D.CODPORTFORMA = F.CODPORTFORMA(+))
   AND (D.CODDOCUMENTO = SALDO.CODDOCUMENTO(+))
   AND (LTRIM(RTRIM(D.STATUS)) <> '2')
   AND (D.RECPAG = 'R')
   AND (D.IDPESSOA = 1)
   AND (D.OPERACAO IN ('1', '2', '3', '14'))
   AND (D.CODDOCUMENTO = L.CODDOCUMENTO)
   AND (D.OPERACAO = L.OPERACAO)
   AND (L.ESTORNO IS NULL)
   AND (D.IDFORCLI = P.IDPESSOA)
   AND (SALDO.SVALOR <> 0)
   AND (V.IDPROJETOTS = S.IDPROJETOTS)
   AND (D.CODTIPDOC = T.CODTIPDOC)
   AND (D.CODTIPDOC IN (12, 14, 15, 16, 20, 28, 38, 76, 85))
   AND (V.IDVENDATS = VS.IDVENDATS)
   AND (VS.IDATENDCLIENTETS = A.IDATENDCLIENTETS)
   AND (V.IDVENDATS = LTS.IDVENDATS)
   AND (LTS.CODDOCUMENTO = D.CODDOCUMENTO)
   AND V.FLGREVERTIDO = 'N'
   AND p.idendresidencial = endres.idendereco
   AND endres.idendereco = cel.idendereco(+)
   AND endres.idendereco = tel.idendereco(+)
   AND (D.DATAPROGRAMADA >= TO_DATE('01/07/2006','DD/MM/YYYY'))
   -- AND (D.DATAPROGRAMADA <= TO_DATE('31/07/2019','DD/MM/YYYY'))
   -- AND ROWNUM <=10