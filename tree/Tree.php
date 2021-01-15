<?php

namespace marx\tree;

/**
 * 二维数组生成无限级树.
 *
 * 使用说明：(new Tree())->generate((new Model())->select()->column(null, 'id'));
 *
 * Class Tree
 */
class Tree
{
    /** @var string 当前节点的key键 */
    protected $key = 'id';

    /** @var string 父节点的key键 */
    protected $key_parent = 'parent_id';

    /** @var string 生成树的子节点键名 */
    protected $key_child_node = 'children';

    /**
     * 设置key.
     *
     * @param $key
     *
     * @return static
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * 设置父节点key.
     *
     * @param $key
     *
     * @return static
     */
    public function setParentKey($key)
    {
        $this->key_parent = $key;

        return $this;
    }

    /**
     * 设置生成树的子节点键名.
     *
     * @param $key
     *
     * @return static
     */
    public function setChildNodeKey($key)
    {
        $this->key_child_node = $key;

        return $this;
    }

    /**
     * 二维数组生成树.
     *
     * @param array $list 以Key的值为索引的二维数组
     *
     * @return array
     */
    public function generate(&$list)
    {
        $treeData = [];
        foreach ($list as &$item) {
            $parent_id = $item[$this->key_parent];
            if (isset($list[$parent_id]) && !empty($list[$parent_id])) {
                // 子节点
                if (!isset($list[$parent_id][$this->key_child_node])) {
                    $children = [];
                } else {
                    $children = $list[$parent_id][$this->key_child_node];
                }
                $children[] = &$item;

                $list[$parent_id][$this->key_child_node] = $children;
            } else {
                // 一级节点
                $treeData[] = &$item;
            }
        }
        unset($item);

        return $treeData;
    }

    /**
     * 二维数组生成树.
     * 用于没有以key当列表索引的数组.
     *
     * @param array $list 以Key的值为索引的二维数组
     *
     * @return array
     */
    public function generateNotIndexedByKey(&$list)
    {
        $tempList = [];
        foreach ($list as &$item) {
            $tempList[$item[$this->key]] = &$item;
        }
        unset($item);

        return $this->generate($tempList);
    }
}
