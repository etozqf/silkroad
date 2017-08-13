<?php
/**
 * NLP 接口
 *
 * 该NLP服务包括四个功能：
 *     1、查找相关文档，根据调用参数的不同可用于相关推荐和专题推荐。
 *     2、查找相同文档，用于排重。
 *     3、提取文档关键词。
 *     4、提取文档摘要。
 */
final class Nlp {

    private $_config;

    function __construct($config)
    {
        $this->_config = $config;
    }

    /**
     * 预处理文档信息
     * @param  $params array(
     *             modelid    文档类型
     *             content  文档内容
     *             title    文档标题
     *             tag      文档tag
     *             created  文档创建时间(timestamp)
     *             sw       摘要最大字数
     *             kc       关键词最大数量
     *         )
     *  @return array(
     *              (array)s    相同的文档列表
     *              (array)k    关键词列表
     *              (string)m   摘要
     *          )
     */
    public function pretreat($params)
    {
        $url = $this->_config['url'] . '/' . __FUNCTION__;
        $data = array(
            'cus'   => $this->_config['prefix'] . value($params, 'modelid', 1),
            'ctn'   => value($params, 'content'),
            'ti'    => value($params, 'title'),
            'tag'   => value($params, 'tag'),
            'dt'    => value($params, 'created', TIME),
            'sw'    => value($params,'sw', 140),
            'kc'    => value($params, 'kc', 5)
        );
        $result = request($url, $data, 40, false);
        if ($result['httpcode'] !== 200 || empty($result['content']) || $result['content'] === '-1') {
            return false;
        }
        $result = json_decode($result['content'], true);
        if (is_array($result['m'])) {
            $result['m'] = implode('', $result['m']);
        }
        return $result;
    }

    /**
     * 生成文档
     * @param  $params array(
     *             modelid      文档类型
     *             contentid    对应content的id
     *             content      文档内容
     *             title        文档标题
     *             tag          文档tag
     *             created      文档创建时间(timestamp)
     *             rc           推荐文档最大数量
     *             pc           推荐模式
     *         )
     *  @return array(
     *              (array)r    推荐文章id
     *          )
     */
    public function create($params)
    {
        $url = $this->_config['url'] . '/' . __FUNCTION__;
        $data = array(
            'cus'   => $this->_config['prefix'] . value($params, 'modelid', 1),
            'id'    => value($params, 'contentid'),
            'ctn'   => value($params, 'content'),
            'ti'    => value($params, 'title'),
            'tag'   => value($params, 'tag'),
            'dt'    => value($params, 'created', TIME),
            'rc'    => value($params,'rc', 5),
            'pc'    => value($params, 'pc', 0)
        );
        $result = request($url, $data, 40, false);
        if ($result['httpcode'] !== 200 || empty($result['content']) || $result['content'] === '-1') {
            return false;
        }
        $result = json_decode($result['content'], true);
        return $result;
    }

    /**
     * 获取关键词与重复文档
     * @param  $params array(
     *             modelid        文档类型
     *             contentid    对应content的id
     *             kc           关键词最大数量
     *             rc           推荐文档最大数量
     *             pc           推荐模式
     *         )
     *  @return array(
     *              (array)s    相同的文档列表
     *              (array)k    关键词列表
     *              (array)r    推荐文章id
     *          )
     */
    public function relate($params)
    {
        $url = $this->_config['url'] . '/' . __FUNCTION__;
        $data = array(
            'cus'   => $this->_config['prefix'] . value($params, 'modelid', 1),
            'id'    => value($params, 'contentid'),
            'kc'    => value($params, 'kc', 5),
            'rc'    => value($params,'rc', 5),
            'pc'    => value($params, 'pc', 0)
        );
        $result = request($url, $data, 40, false);
        if ($result['httpcode'] !== 200 || empty($result['content']) || $result['content'] === '-1') {
            return false;
        }
        $result = json_decode($result['content'], true);
        return $result;
    }

    /**
     * 摘要提取
     * @param  $params array(
     *             content  文档内容
     *             title    文档标题
     *             tag      文档tag
     *             sw       摘要最大字数
     *          )
     * @return (string)     摘要
     */
    public function summary($params)
    {
        $url = $this->_config['url'] . '/' . __FUNCTION__;
        $data = array(
            'ctn'   => value($params, 'content'),
            'ti'    => value($params, 'title'),
            'tag'   => value($params, 'tag'),
            'sw'    => value($params,'sw', 140)
        );
        $result = request($url, $data, 40, false);
        if ($result['httpcode'] !== 200 || empty($result['content']) || $result['content'] === '-1') {
            return false;
        }
        $result = json_decode($result['content'], true);
        if (is_array($result)) {
            $result = implode('', $result);
        }
        return $result;
    }

    /**
     * 删除文档
     * @param  $params array(
     *             modelid        文档类型
     *             contentid    对应content的id
     *         )
     * @return (boolean)
     */
    public function remove($params)
    {
        $url = $this->_config['url'] . '/' . __FUNCTION__;
        $data = array(
            'cus'   => $this->_config['prefix'] . value($params, 'modelid', 1),
            'id'    => value($params, 'contentid'),
        );
        $result = request($url, $data, 40, false);
        if ($result['httpcode'] !== 200 || empty($result['content']) || $result['content'] === '-1') {
            return false;
        }
        return true;
    }
}