<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2017/4/7
 * Time: 10:07
 */

class controller_iprpage extends article_controller_abstract
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->iprpage = loader::model('iprpage');

    }

    public function index()
    {
        //传入菜单id(0开始),当前页码，catid，分页条数
        $id = empty($_GET['id']) ? 1 : intval($_GET['id']);
        $page = empty($_GET['page']) ? 1 : intval($_GET['page']);
        $catid = empty($_GET['catid']) ? 294 : intval($_GET['catid']);
        $pagesize = empty($_GET['pagesize']) ? 10 : intval($_GET['pagesize']);

        /*打印测试数据*/
        console($id);
        console($page);
        console($pagesize);
        console($catid);
        /*打印测试数据*/

        //根据菜单id来生成menu
        $menu = $this->iprpage->menu($id);

        //右侧所有内容全部拼接到show中 1.开头 2.标题 3.列表 4.分页按钮 5.结尾
        $show = "";
        //开头
        $show .= $this->iprpage->gethead($id);
        //标题
        if ($id !== 4 && $id !== 5 && $id !==6)
        {
            $show .= $this->iprpage->gettitle($id);
        }

        //列表 根据菜单id来查数据（因为不同菜单查询的数据结构不一，先判断再看调用何种方法）,返回list以及pagebutton
        $listpage = $this->iprpage->getlist($id,$page,$pagesize,$catid);
        $show .= $listpage['list'];

        //分页按钮（传入page，total，totalpage，id（根据栏目id来加链接））
        $show .= $listpage['pagebutton'];

        //结尾
        $show .= $this->iprpage->getbootom($id);

        $data = array('menu'=>$menu,'show'=>$show);

        console('下方是json传出去的');
        console($data);
        echo $_GET['callback']."(".json_encode($data).")";
    }


    /**
     * @param $contentid
     * ajax获取文章详情（所有的详情都在这里获取）
     */
    public function getarticle()
    {
        //传入contentid，catid,来查文章详情，以及前一篇，后一篇
        $contentid  = empty($_GET['contentid']) ? 1 : intval($_GET['contentid']);
        $catid      = empty($_GET['catid']) ? 1 : intval($_GET['catid']);
        $id      = empty($_GET['id']) ? 1 : intval($_GET['id']);

        //为开新页面做准备
        $menu = $this->iprpage->menu($id);

        $data = $this->iprpage->getarticle($contentid,$catid,$id);
        $data['menu'] = $menu;

        echo $_GET['callback']."(".json_encode($data).")";
    }
    //新页面思路，在获取文章详情的同时，取menu信息，同时搞过去

}