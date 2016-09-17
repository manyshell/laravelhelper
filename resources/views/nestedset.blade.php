<!DOCTYPE html>
<html>
<head>
    <title>laravel-nestedset</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        p {
            text-indent: 2em;
            font-size: 16px;
        }
        .title {
            font-size: 18px;font-weight: bold;border-bottom: 1px dashed #ccc;padding:10px 0;
        }
    </style>
</head>
<body>
<div>
    <div style="padding-top:10px;text-align: center;font-size: 30px;font-weight: bold;">Nestedset帮助文档</div>
    <p>
        嵌套集合或嵌套集合模型是有效存储层次结构的一种方法，“<a href="https://github.com/lazychaser/laravel-nestedset" target="_blank">Nestedset</a>”提供了多种方法接口对数据模型进行处理。由于原作者文档过于精简，所以我对官方文档作些补充，以便学习起来更容易。以下所有命令行都是在项目根目录下执行。
    </p>

    <p class="title">第一步：安装插件</p>
    <p>安装laravel-nestedset插件需要对应laravel的版本号</p>
    <ul style="margin-left: 10px;">
        <li>Laravel 5.2, 5.3 is supported since v4</li>
        <li>Laravel 5.1 is supported in v3</li>
        <li>Laravel 4 is supported in v2</li>
    </ul>
    <p>如果知道laravel的版本号，就安装相对应的laravel-nestedset插件。如果不知道，则通过命令得到laravel的版本号。</p>
    <p style="background: #dddddd;">php artisan --version</p>

    <p>
     编辑composer.json，我的laravel的版本号: Laravel Framework version 5.2.45，所以安装v4版。下面引号内是版本号。
    <div style="background: #dddddd;">
        <pre>
           "require": {
               "kalnoy/nestedset": "4.*"
           }
        </pre>
    </div>
    </p>
    <p>执行安装</p>
    <p style="background: #dddddd;">composer update</p>

    <p class="title">第二步：创建数据库</p>

    <p>初始化一个名为“年_月_日_时分秒_create_category_table.php”的这样一个数据库迁移文件，表名为“category”，迁移文件位于/database/migrations。</p>
    <p style="background: #dddddd;">php artisan make:migration create_category_table --create=category</p>
    <p>编辑xxx_create_category_table</p>
    <p>修改前：</p>
    <pre style="background: #dddddd;">
        public function up()
        {
            Schema::create('category', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
            });
        }
        public function down()
        {
            Schema::drop('category');
        }
    </pre>
    <p>修改后：命名空间增加一条，原作者文档未提到这条，不增加命名空间会报错。</p>
    <p style="background: #dddddd;">use Kalnoy\Nestedset\NestedSet;</p>
    <pre style="background: #dddddd;">
        public function up()
        {
            Schema::create('category', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
                NestedSet::columns($table);
            });
        }        public function down()
        {
            Schema::table('category', function (Blueprint $table) {
                NestedSet::dropColumns($table);
            });
        }
    </pre>
    <p>执行迁移</p>
    <p style="background: #dddddd;">php artisan migrate</p>

    <p>打开数据库，看到刚才创建的表“category”，结构是这样的</p>
    <pre style="background: #dddddd;">
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,                   //由$table->timestamps();创建
    `updated_at` timestamp NULL DEFAULT NULL,                   //由$table->timestamps();创建
    `_lft` int(10) unsigned NOT NULL,                           //由NestedSet创建
    `_rgt` int(10) unsigned NOT NULL,                           //由NestedSet创建
    `parent_id` int(10) unsigned DEFAULT NULL,                  //由NestedSet创建
    </pre>

    <p class="title">第三步：创建数据模型</p>

    <p>新建模型 /app/Models/Category.php</p>
    <pre style="background: #dddddd;">
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    use Kalnoy\Nestedset\NodeTrait;
    class Columns extends Model {
        use NodeTrait;
        protected $table = 'category';
        protected $fillable = ['name', 'parent_id'];
    }
    </pre>

    <p class="title">第四步：使用方法</p>

    <p>1. 根据数组创建嵌套数据</p>
    <pre style="background: #dddddd;">
        $node = Category::create([
            'name' => 'Foo',
            'children' => [
                [
                    'name' => 'Bar',
                    'children' => [
                        [ 'name' => 'Baz' ],
                    ],
                ],
            ],
        ]);
    </pre>

    <pre style="background: #dddddd;">
    mysql> select * from category;
    +----+------+---------------------+---------------------+------+------+-----------+
    | id | name | created_at          | updated_at          | _lft | _rgt | parent_id |
    +----+------+---------------------+---------------------+------+------+-----------+
    |  1 | Foo  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    1 |    6 | NULL      |
    |  2 | Bar  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    2 |    5 |         1 |
    |  3 | Baz  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    3 |    4 |         2 |
    +----+------+---------------------+---------------------+------+------+-----------+
    </pre>

    <p>2. 创建子节点</p>
    <pre style="background: #dddddd;">
    $parent = Category::find(1);
    $node = ['name' => 'car'];
    $child = $parent->children()->create($node);
    </pre>

    <pre style="background: #dddddd;">
    mysql> select * from category;
    +----+------+---------------------+---------------------+------+------+-----------+
    | id | name | created_at          | updated_at          | _lft | _rgt | parent_id |
    +----+------+---------------------+---------------------+------+------+-----------+
    |  1 | Foo  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    1 |    8 | NULL      |
    |  2 | Bar  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    2 |    5 |         1 |
    |  3 | Baz  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    3 |    4 |         2 |
    |  4 | car  | 2016-09-17 03:40:41 | 2016-09-17 03:40:41 |    6 |    7 |         1 |
    +----+------+---------------------+---------------------+------+------+-----------+
    </pre>
    <p>在父节点1新增节点4，父节点1的_rgt也发生了变化</p>
    <pre style="background: #dddddd;">
    $parent = Category::find(3);
    $node = ['name' => 'pen'];
    $child = $parent->children()->create($node);
    </pre>
    <pre style="background: #dddddd;">
    mysql> select * from category;
    +----+------+---------------------+---------------------+------+------+-----------+
    | id | name | created_at          | updated_at          | _lft | _rgt | parent_id |
    +----+------+---------------------+---------------------+------+------+-----------+
    |  1 | Foo  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    1 |   10 | NULL      |
    |  2 | Bar  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    2 |    7 |         1 |
    |  3 | Baz  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    3 |    6 |         2 |
    |  4 | car  | 2016-09-17 03:40:41 | 2016-09-17 03:40:41 |    8 |    9 |         1 |
    |  5 | pen  | 2016-09-17 03:52:57 | 2016-09-17 03:52:57 |    4 |    5 |         3 |
    +----+------+---------------------+---------------------+------+------+-----------+
    </pre>
    <p>在父节点3新增节点5</p>

    <p>3. 删除节点，含子节点</p>
    <pre style="background: #dddddd;">
    $node = Category::findOrFail(4);
    $node->delete();
    </pre>
    <pre style="background: #dddddd;">
    mysql> select * from category;
    +----+------+---------------------+---------------------+------+------+-----------+
    | id | name | created_at          | updated_at          | _lft | _rgt | parent_id |
    +----+------+---------------------+---------------------+------+------+-----------+
    |  1 | Foo  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    1 |    8 | NULL      |
    |  2 | Bar  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    2 |    7 |         1 |
    |  3 | Baz  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    3 |    6 |         2 |
    |  5 | pen  | 2016-09-17 03:52:57 | 2016-09-17 03:52:57 |    4 |    5 |         3 |
    +----+------+---------------------+---------------------+------+------+-----------+
    </pre>
    <p>删除节点4，该节点没有子节点</p>

    <pre style="background: #dddddd;">
    $node = Category::findOrFail(3);
    $node->delete();
    </pre>
    <pre style="background: #dddddd;">
    mysql> select * from category;
    +----+------+---------------------+---------------------+------+------+-----------+
    | id | name | created_at          | updated_at          | _lft | _rgt | parent_id |
    +----+------+---------------------+---------------------+------+------+-----------+
    |  1 | Foo  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    1 |    4 | NULL      |
    |  2 | Bar  | 2016-09-17 03:30:55 | 2016-09-17 03:30:55 |    2 |    3 |         1 |
    +----+------+---------------------+---------------------+------+------+-----------+
    </pre>
    <p>删除节点3，该节点有子节点</p>

    <p>4. 另一个创建子节点的方法</p>
    <pre style="background: #dddddd;">
        $parent = Category::find(1);
        $node = [
            'name' => 'Baz',
            'children' => [
                [
                    'name' => 'pen',
                    'children' => [
                        [
                            'name' => 'box',
                        ],
                    ],

                ],
                [
                    'name' => 'picture',
                ],
                [
                    'name' => 'dog',
                ],
                [
                    'name' => 'cat',
                ],
            ],
        ];
        $child = Category::create($node, $parent);
    </pre>
    <pre style="background: #dddddd;">
    mysql> select * from category;
    +----+---------+---------------------+---------------------+------+------+-----------+
    | id | name    | created_at          | updated_at          | _lft | _rgt | parent_id |
    +----+---------+---------------------+---------------------+------+------+-----------+
    |  1 | Foo     | 2016-09-17 08:36:17 | 2016-09-17 08:36:17 |    1 |   16 | NULL      |
    |  2 | Bar     | 2016-09-17 08:36:17 | 2016-09-17 08:36:17 |    2 |    3 |         1 |
    |  3 | Baz     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    4 |   15 |         1 |
    |  4 | pen     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    5 |    8 |         3 |
    |  5 | box     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    6 |    7 |         4 |
    |  6 | picture | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    9 |   10 |         3 |
    |  7 | dog     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |   11 |   12 |         3 |
    |  8 | cat     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |   13 |   14 |         3 |
    +----+---------+---------------------+---------------------+------+------+-----------+
    </pre>

    <p>5. 物理排序，移动节点以默认的顺序</p>
    <pre style="background: #dddddd;">
        $node = Category::findOrFail(7);
        $bool = $node->up();
    </pre>
    <pre style="background: #dddddd;">
    mysql> select * from category;
    +----+---------+---------------------+---------------------+------+------+-----------+
    | id | name    | created_at          | updated_at          | _lft | _rgt | parent_id |
    +----+---------+---------------------+---------------------+------+------+-----------+
    |  1 | Foo     | 2016-09-17 08:36:17 | 2016-09-17 08:36:17 |    1 |   16 | NULL      |
    |  2 | Bar     | 2016-09-17 08:36:17 | 2016-09-17 08:36:17 |    2 |    3 |         1 |
    |  3 | Baz     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    4 |   15 |         1 |
    |  4 | pen     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    5 |    8 |         3 |
    |  5 | box     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    6 |    7 |         4 |
    |  6 | picture | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |   11 |   12 |         3 |
    |  7 | dog     | 2016-09-17 08:37:18 | 2016-09-17 08:42:08 |    9 |   10 |         3 |
    |  8 | cat     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |   13 |   14 |         3 |
    +----+---------+---------------------+---------------------+------+------+-----------+
    </pre>
    <p>让节点7的排序上移1位，节点7的上一个节点是节点6，根据 _lft 值可以看到，节点7的 _lft 和 _rgt 变小了，实际原理是他们的 _lft 和 _rgt 发生了交换。</p>
    <pre style="background: #dddddd;">
        $node = Category::findOrFail(4);
        $bool = $node->down(3);
    </pre>
    <pre style="background: #dddddd;">
    mysql> select * from category;
    +----+---------+---------------------+---------------------+------+------+-----------+
    | id | name    | created_at          | updated_at          | _lft | _rgt | parent_id |
    +----+---------+---------------------+---------------------+------+------+-----------+
    |  1 | Foo     | 2016-09-17 08:36:17 | 2016-09-17 08:36:17 |    1 |   16 | NULL      |
    |  2 | Bar     | 2016-09-17 08:36:17 | 2016-09-17 08:36:17 |    2 |    3 |         1 |
    |  3 | Baz     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    4 |   15 |         1 |
    |  4 | pen     | 2016-09-17 08:37:18 | 2016-09-17 08:54:00 |   11 |   14 |         3 |
    |  5 | box     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |   12 |   13 |         4 |
    |  6 | picture | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    7 |    8 |         3 |
    |  7 | dog     | 2016-09-17 08:37:18 | 2016-09-17 08:42:08 |    5 |    6 |         3 |
    |  8 | cat     | 2016-09-17 08:37:18 | 2016-09-17 08:37:18 |    9 |   10 |         3 |
    +----+---------+---------------------+---------------------+------+------+-----------+
    </pre>
    <p>让节点4的排序下移3位，节点4现在排最后1位了。参数为0时，节点不会移动。如果当前节点已处在最后1位，移动操作无效。节点要移动的位数超过排序中所在节点后面节点的数目，移动操作无效。</p>

    <p>6. 输出</p>
    <pre style="background: #dddddd;">
        $nodes = Category::get()->toFlatTree();
        print_r($nodes->toArray());
    </pre>
    <pre style="background: #dddddd;">
    Array
    (
    [0] => Array
        (
            [id] => 1
            [name] => Foo
            [description] =>
            [created_at] => 2016-09-17 08:36:17
            [updated_at] => 2016-09-17 08:36:17
            [_lft] => 1
            [_rgt] => 16
            [parent_id] =>
        )

    [1] => Array
        (
            [id] => 2
            [name] => Bar
            [description] =>
            [created_at] => 2016-09-17 08:36:17
            [updated_at] => 2016-09-17 08:36:17
            [_lft] => 2
            [_rgt] => 3
            [parent_id] => 1
        )

    [2] => Array
        (
            [id] => 3
            [name] => Baz
            [description] =>
            [created_at] => 2016-09-17 08:37:18
            [updated_at] => 2016-09-17 08:37:18
            [_lft] => 4
            [_rgt] => 15
            [parent_id] => 1
        )
    ......
    )
    </pre>
    <p>将符合嵌套集合的数据全部输出</p>

    <pre style="background: #dddddd;">
        $tree = Category::descendantsOf(1);
        print_r($nodes->toArray());
    </pre>
    <pre style="background: #dddddd;">
        Array
        (
            [0] => Array
                (
                    [id] => 2
                    [name] => Bar
                    [description] =>
                    [created_at] => 2016-09-17 08:36:17
                    [updated_at] => 2016-09-17 08:36:17
                    [_lft] => 2
                    [_rgt] => 3
                    [parent_id] => 1
                )

            [1] => Array
                (
                    [id] => 3
                    [name] => Baz
                    [description] =>
                    [created_at] => 2016-09-17 08:37:18
                    [updated_at] => 2016-09-17 08:37:18
                    [_lft] => 4
                    [_rgt] => 15
                    [parent_id] => 1
                )

            [2] => Array
                (
                    [id] => 4
                    [name] => pen
                    [description] =>
                    [created_at] => 2016-09-17 08:37:18
                    [updated_at] => 2016-09-17 08:54:00
                    [_lft] => 11
                    [_rgt] => 14
                    [parent_id] => 3
                )
            ......
        )
    </pre>
    <p>以单数组形式输出后代子树中的所有节点，即节点的孩子，孩子，不含自己本身</p>

    <pre style="background: #dddddd;">
        $tree = Category::descendantsOf(1)->toTree();
        print_r($nodes->toArray());
    </pre>
    <pre style="background: #dddddd;">
        Array
        (
            [0] => Array
                (
                    [id] => 2
                    [name] => Bar
                    [description] =>
                    [created_at] => 2016-09-17 08:36:17
                    [updated_at] => 2016-09-17 08:36:17
                    [_lft] => 2
                    [_rgt] => 3
                    [parent_id] => 1
                    [children] => Array
                        (
                        )

                )

            [1] => Array
                (
                    [id] => 3
                    [name] => Baz
                    [description] =>
                    [created_at] => 2016-09-17 08:37:18
                    [updated_at] => 2016-09-17 08:37:18
                    [_lft] => 4
                    [_rgt] => 15
                    [parent_id] => 1
                    [children] => Array
                        (
                            [0] => Array
                                (
                                    [id] => 4
                                    [name] => pen
                                    [description] =>
                                    [created_at] => 2016-09-17 08:37:18
                                    [updated_at] => 2016-09-17 08:54:00
                                    [_lft] => 11
                                    [_rgt] => 14
                                    [parent_id] => 3
                                    [children] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [id] => 5
                                                    [name] => box
                                                    [description] =>
                                                    [created_at] => 2016-09-17 08:37:18
                                                    [updated_at] => 2016-09-17 08:37:18
                                                    [_lft] => 12
                                                    [_rgt] => 13
                                                    [parent_id] => 4
                                                    [children] => Array
                                                        (
                                                        )

                                                )
                                            ......

                                        )

                                )
                        )
                )
        )
    </pre>
    <p>以嵌套数组形式输出后代子树中的所有节点，即节点的孩子，孩子，不含自己本身</p>

    <pre style="background: #dddddd;">
        $tree = Category::defaultOrder()->descendantsOf(3)->toTree();   //_lft 由小到大排序
        $tree = Category::reversed()->descendantsOf(3)->toTree();       //_lft 由大到小排序
        print_r($tree);
    </pre>
    <pre style="background: #dddddd;">
        Array
        (
            [0] => Array
                (
                    [id] => 4
                    [name] => pen
                    [description] =>
                    [created_at] => 2016-09-17 08:37:18
                    [updated_at] => 2016-09-17 08:54:00
                    [_lft] => 11
                    [_rgt] => 14
                    [parent_id] => 3
                    [children] => Array
                        (
                            [0] => Array
                                (
                                    [id] => 5
                                    [name] => box
                                    [description] =>
                                    [created_at] => 2016-09-17 08:37:18
                                    [updated_at] => 2016-09-17 08:37:18
                                    [_lft] => 12
                                    [_rgt] => 13
                                    [parent_id] => 4
                                    [children] => Array
                                        (
                                        )

                                )

                        )

                )

            [1] => Array
                (
                    [id] => 8
                    [name] => cat
                    [description] =>
                    [created_at] => 2016-09-17 08:37:18
                    [updated_at] => 2016-09-17 08:37:18
                    [_lft] => 9
                    [_rgt] => 10
                    [parent_id] => 3
                    [children] => Array
                        (
                        )

                )

            [2] => Array
                (
                    [id] => 6
                    [name] => picture
                    [description] =>
                    [created_at] => 2016-09-17 08:37:18
                    [updated_at] => 2016-09-17 08:37:18
                    [_lft] => 7
                    [_rgt] => 8
                    [parent_id] => 3
                    [children] => Array
                        (
                        )

                )
        )
    </pre>
    <p>以嵌套数组形式输出后代子树中的所有节点，即节点的孩子，孩子，不含自己本身，当前按 由大到小排序</p>

</div>
</body>
</html>
