<?php
/**
 * 栏目模型
 * Created by Hzg.
 * Date: 2016-09-15
 * Time: 23:55
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Columns extends Model {
    use NodeTrait;

    protected $table = 'columns';
    protected $fillable = ['name', 'description', 'parent_id'];

}
