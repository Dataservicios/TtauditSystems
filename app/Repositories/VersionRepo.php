<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 27/03/2019
 * Time: 16:19
 */

namespace App\Repositories;

use App\Models\Version;
use Illuminate\Support\Facades\DB;

class VersionRepo extends Repository
{

    public function getModel()
    {
        return new Version;
    }

    public function getRegsVersions($vigente)
    {
        $results = DB::select('SELECT 
  `versiones`.`id`,
  `versiones`.`title`,
  `versiones`.`content`,
  `versiones`.`version`,
  `versiones`.`type`,
  `versiones`.`company_id`,
  `versiones`.`programador`,
  `versiones`.`url`,
  `versiones`.`file`,
  `versiones`.`vigente`,
  `versiones`.`admin`,
  `versiones`.`created_at`,
  `versiones`.`updated_at`,
  `companies`.`fullname`
FROM
  `versiones`
  INNER JOIN `companies` ON (`versiones`.`company_id` = `companies`.`id`)
WHERE
`companies`.`id`>138 and  `companies`.`active` = ? AND 
  `versiones`.`vigente` = ? AND 
  `versiones`.`title` IS NOT NULL', [ $vigente,$vigente]);
        return $results;
    }
}
