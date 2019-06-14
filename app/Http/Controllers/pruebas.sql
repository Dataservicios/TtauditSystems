CREATE DEFINER=`dataser_admin`@`%` PROCEDURE `sp_validacion_graph`(IN _company_id INT,IN _ubigeo varchar(50))
BEGIN


  /*inicio */
SET @consulta01 = 'SELECT  count(`exchanges`.`id`) as cantidad, `exchanges`.`office` as valor,`exchanges`.`office`,`exchange_master`.`fullname`,1 type
FROM
  `exchanges`
  INNER JOIN `exchange_master` ON (`exchanges`.`exchange_master_id` = `exchange_master`.`id`)
WHERE
  `exchange_master`.`company_id` = ';

if (_ubigeo = '0') then
    set @where01 = '';
  else
    set @where01 = CONCAT(' and `exchanges`.`office` = ''',_ubigeo,''' COLLATE utf8_general_ci');
  end if;
  
SET @consulta02 =' Group by `exchanges`.`office`';


SET @consulta0 = CONCAT(@consulta01,_company_id,@where01,@consulta02);


SET @consulta11 = 'select count(nro) cantidad,office as valor,office,fullname,2 type 
from 
(SELECT
  count(`exchanges`.`invoice_id`) as nro,`exchanges`.`office`,
`exchange_master`.`fullname`
FROM
  `exchanges`
  INNER JOIN `exchange_master` ON (`exchanges`.`exchange_master_id` = `exchange_master`.`id`)
WHERE
  `exchange_master`.`company_id` = ';

if (_ubigeo = '0') then
    set @where11 = '';
  else
    set @where11 = CONCAT(' and `exchanges`.`office` = ''',_ubigeo,''' COLLATE utf8_general_ci');
  end if;

SET @consulta12 =' Group by `exchanges`.`invoice_id`) a group by a.office';

SET @consulta1 = CONCAT(@consulta11,_company_id,@where11,@consulta12);


SET @consulta21 = 'select count(invoice_id) as cantidad,document_type as valor,office,fullname,3 type 
from
(SELECT `exchanges`.`invoice_id`,`exchanges`.`office`,
`exchange_master`.`fullname`,`exchanges`.`document_type`
FROM `exchanges`  INNER JOIN `exchange_master` ON (`exchanges`.`exchange_master_id` = `exchange_master`.`id`)
where `exchanges`.`company_id`= ';

if (_ubigeo = '0') then
    set @where21 = '';
  else
    set @where21 = CONCAT(' and `exchanges`.`office` = ''',_ubigeo,''' COLLATE utf8_general_ci');
  end if;

SET @consulta22 =' group by `exchanges`.`invoice_id`) p group by office,document_type order by office asc,cantidad asc';

SET @consulta2 = CONCAT(@consulta21,_company_id,@where21,@consulta22);


 SET @consulta31 ='SELECT concat(count(b),'|', sum(q),'|', ROUND(avg(a))) as cantidad,category as valor,1 as office,fullname,4 type
FROM (
          SELECT
            `e`.`invoice_id` b,
            `e`.`category`,
            sum(`e`.`quantity`) q,
            sum(`e`.`amount`) a,
`d`.`fullname`
          FROM
            `exchanges` `e`
          INNER JOIN `exchange_master` `d` ON (`e`.`exchange_master_id` = `d`.`id`)
          WHERE
            `e`.`company_id` = ';

if (_ubigeo = '0') then
    set @where31 = '';
  else
    set @where31 = CONCAT(' and `e`.`office` = ''',_ubigeo,''' COLLATE utf8_general_ci');
  end if;

SET @consulta32 =' group by `e`.`invoice_id`,  `e`.`category`
) tmp_prom
group by tmp_prom.category';

SET @consulta3 = CONCAT(@consulta31,_company_id,@where31,@consulta32);

SET @consulta41 = 'SELECT count(q) cantidad,pdv_description as valor,o office,fullname,5 type
FROM (
          SELECT
            `e`.`pdv_id` b,
            `e`.`pdv_description`,
            count(`e`.`pdv_id`) q,
`e`.`office` o,
`d`.`fullname`
          FROM
            `exchanges` `e`
          INNER JOIN `exchange_master` `d` ON (`e`.`exchange_master_id` = `d`.`id`)
          WHERE
            `e`.`company_id` = ';

if (_ubigeo = '0') then
    set @where41 = '';
  else
    set @where41 = CONCAT(' and `e`.`office` = ''',_ubigeo,''' COLLATE utf8_general_ci');
  end if;

SET @consulta42 =' group by `e`.`invoice_id`,`e`.`pdv_id`
) tmp_prom
group by tmp_prom.b order by cantidad desc';

SET @consulta4 = CONCAT(@consulta41,_company_id,@where41,@consulta42);

SET @sql = CONCAT('(',@consulta0,')',' UNION ','(',@consulta1,')',' UNION ','(',@consulta2,')',' UNION ','(',@consulta3,')',' UNION ','(',@consulta4,');');
PREPARE stmt1 FROM @sql ;
EXECUTE stmt1;

END