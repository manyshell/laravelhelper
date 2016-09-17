<?php
/**
 *
 * Created by Hzg.
 * Date: 2016-09-16
 * Time: 0:01
 */

namespace App\Http\Controllers;

use App\Models\Columns;

class NestedsetController extends Controller
{

    public function index()
    {
        return view('nestedset');
    }

    public function test()
    {
//        $node = Columns::create([
//            'name' => 'Foo',
//
//            'children' => [
//                [
//                    'name' => 'Bar',
//
//                    'children' => [
//                        [ 'name' => 'Baz' ],
//                    ],
//                ],
//            ],
//        ]);
//        print_r($node);exit;
        //---当前节点的所有父级---\
//        $node = Columns::find(4);
//        $tree = Columns::whereAncestorOf($node)->get();
//        echo "<pre>";print_r($tree->toArray());
        //---当前节点的所有父级---/

        //---当前节点的所有父级---\
//        $node = Columns::find(4);
//        $tree = $node->ancestors()->get()->toTree();
//        echo "<pre>";print_r($tree->toArray());
        //---当前节点的所有父级---/


        //---由小到大排序---\
//        $tree = Columns::defaultOrder()->get()->toTree()->toArray();
//        echo "<pre>";print_r($tree);

//        $root = Columns::where('id', '=', 3)->withDepth();
//        $tree = $root->defaultOrder()->get()->toTree();
//        $tree = Columns::defaultOrder()->where('id', '=', 3)->get()->toTree();
//        $tree = Columns::descendants(3)->defaultOrder()->toTree();
//        $tree = Columns::defaultOrder()->descendantsOf(3)->toTree();  //子节点
//        $tree = Columns::reversed()->descendantsOf(3)->toTree();  //子节点
//        $node = Columns::find(4);
//        $tree = $node->ancestors()->get()->toTree();
//        echo "<pre>";print_r($tree->toArray());

        //---由小到大排序---/
        //---由大到小排序---\
//        $tree = Columns::reversed()->get()->toTree()->toArray();
//        echo "<pre>";print_r($tree);
        //---由大到小排序---/

        //---按数据据结构原样输出---\
//        $nodes = Columns::get()->toFlatTree();
//        print_r($nodes->toArray());
        //---按数据据结构原样输出---/

        //---指定根id显示一棵树，含子目录---\
//        $root = Columns::find(1);
//        $tree = $root->descendants->toTree($root);
//        $tree = $root->descendants;
//        $tree = Columns::descendantsOf(1)->toTree();
//        echo "<pre>\n";
//        print_r($tree->toArray());
        //---指定根id显示一棵树，含子目录---/

        /*
        输出一棵树的结构图
        - Foo
        -- Bar
        --- Baz
        */
//        $nodes = Columns::get()->toTree();
//        $traverse = function ($categories, $prefix = '-') use (&$traverse) {
//            foreach ($categories as $category) {
//                echo PHP_EOL.$prefix.' '.$category->name;
//
//                $traverse($category->children, $prefix.'-');
//            }
//        };
//        echo "<pre>\n";
//        echo $traverse($nodes);exit;

        //中国－四川省－攀枝花市－某某区
        //中国－四川省－重庆市－某某区
        //---创建根目录:国家---\
//        $node = ['name' => '中国'];
//        $category_root = Columns::create($node);
//        print_r($category_root);
//        $parentId = $category_root->id;
//        echo "id=".$parentId;      //19
        //---创建根目录---/

        //---创建一级目录:省、直辖市---\
        //通过parentid创建子目录
//        $node = ['name' => '重庆'];
//        $category_child = Columns::create($node);
//        $category_child->parent_id = 19;
//        $category_child->save();
//        print_r($category_child);
//        echo "id=".$category_child->id;

        //通过parent对像创建子目录
//        $node = ['name' => '重庆'];
//        $category_child = $category_root->children()->create($node);
//        print_r($category_child);
//        echo "id=".$category_child->id;

        //通过parent的对像创建子目录
//        $category_root = Columns::find(21);
//        $node = ['name' => '北京'];
//        $category_child = $category_root->children()->create($node);
//        echo "id=".$category_child->id;

        //通过parent的对像创建子目录
//        $category_root = Columns::find(22);
//        $node = ['name' => '江北区','description' => '富人聚集地'];
//        $category_child = Columns::create($node, $category_root);
//        echo "id=".$category_child->id;


        //添加描述
//        $category_root = Columns::find(21);
//        $node = ['name' => '贵州省','description' => '出土匪'];
//        $category_child = $category_root->children()->create($node);
//        echo "id=".$category_child->id;

        //---创建一级目录---/

        //---创建二级目录:区、县、地及市---\
//        $category_root = Columns::find(22);
//        $node = ['name' => '渝中区','description' => '重庆最霸道的区'];
//        $node = ['name' => '南岸区','description' => '很安逸的空气'];
//        $category_child = $category_root->children()->create($node);
//        echo "id=".$category_child->id;


        //---创建二级目录:区、县、地及市---/

        //---列出兄弟节点，不含子节点---\
//        $node = Columns::findOrFail(26);
//        $tree = $node->siblings()->withDepth()->get(); // OK
//        print_r($tree->toArray());
        //---列出兄弟节点，不含子节点---/

        //---排序上提一名---\
//        $node = Columns::findOrFail(28);
//        $bool = $node->up();
//        dd($bool);
        //---排序上提一名---/

        //---排序下移二名---\
        //移动的参数不能超过当前节点的总数-1
//        $node = Columns::findOrFail(28);
//        $bool = $node->down(2);
//        dd($bool);
        //---排序下移二名---/

        //---指定根id显示所有节点及子目录的数组---\
//        $node = Columns::findOrFail(22);
//        $result = Columns::whereDescendantOf($node)->get();
//        print_r($result->toArray());
        //---指定根id显示所有节点及子目录的数组---/

        //---除了根id以外的所有节点的数组---\
//        $node = Columns::findOrFail(28);
//        $result = Columns::whereNotDescendantOf($node)->get();
//        print_r($result->toArray());
        //---除了根id以外的所有节点的数组---/

        //---删除当前节点及子节点---\
//        $node = Columns::findOrFail(25);
//        $node->delete();
        //---删除当前节点及子节点---/

        //---将节点[$node]移动到[$neighbor]所在的父节点，排序位于[$neighbor]的后面---\
//        $node = Columns::findOrFail(24);        //贵州
//        $neighbor = Columns::findOrFail(26);    //江北
//        $result = $node->insertAfterNode($neighbor);
//        dd($result);
        //---将节点[$node]移动到[$neighbor]所在的父节点，排序位于[$neighbor]的后面---/

        //---将节点[$node]移动到[$neighbor]所在的父节点，排序位于[$neighbor]的前面---\
//        $node = Columns::findOrFail(24);
//        $neighbor = Columns::findOrFail(26);
//        $result = $node->insertBeforeNode($neighbor);
//        dd($result);
        //---将节点[$node]移动到[$neighbor]所在的父节点，排序位于[$neighbor]的前面---/

        //---将节点[$node]移动到[$parent]下---\
//        $parent = Columns::findOrFail(22);
//        $node = Columns::findOrFail(28);
//        $result = $parent->appendNode($node);
//        dd($result);
        //---将节点[$node]移动到[$parent]下---/

        //---用途为重置排序---\
//        if ($node->save()) {
//            $moved = $node->hasMoved();
//        }
        //---用途为重置排序---、

    }

    public function structure()
    {
        $nodes = Columns::get()->toTree();
        $traverse = function ($categories, $prefix = '-') use (&$traverse) {
            foreach ($categories as $category) {
                echo PHP_EOL . $prefix . ' ' . $category->name . ' - ' . $category->id;

                $traverse($category->children, $prefix . '-');
            }
        };
        echo "<pre>\n";
        echo $traverse($nodes);
    }


}
