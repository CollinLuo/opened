<?php
/**
 * 扩展函数库
 * ============================================================================
 * 版权所有 2005-2080 Lessismore，并保留所有权利。
 * 网站地址: http://www.trydemo.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: lsq & Lessismore & D.Apache.Luo
 * $Id: ArrayHelper.class.php 2014-4-2 Lessismore $
*/

namespace Org\Util;
/**
 * ArrayList实现类
 * @category   Think
 * @package  Think
 * @subpackage  Util
 * @author lsq <trydemo@126.com>
 */
class ArrayHelper{
    
    /**
     * 从数组中删除空白的元素（包括只有空白字符的元素）
     *
     * 用法：
     * @code php
     * $arr = array('', 'test', '   ');
     * ArrayHelper::removeEmpty($arr);
     *
     * dump($arr);
     *   // 输出结果中将只有 'test'
     * @endcode
     *
     * @param array $arr 要处理的数组
     * @param boolean $trim 是否对数组元素调用 trim 函数
     */
    static function removeEmpty(& $arr, $trim = TRUE)
    {
        foreach ($arr as $key => $value)
        {
            if (is_array($value))
            {
                self::removeEmpty($arr[$key]);
            }
            else
            {
                $value = trim($value);
                if ($value == '')
                {
                    unset($arr[$key]);
                }
                elseif ($trim)
                {
                    $arr[$key] = $value;
                }
            }
        }
    }
    
    /**
     * 从一个二维数组中返回指定键的所有值
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $values = ArrayHelper::getCols($rows, 'value');
     *
     * dump($values);
     *   // 输出结果为
     *   // array(
     *   //   '1-1',
     *   //   '2-1',
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $col 要查询的键
     *
     * @return array 包含指定键所有值的数组
     */
    static function getCols($arr, $col)
    {
        $ret = array();
        foreach ($arr as $row)
        {
            if (isset($row[$col])) {
                $ret[] = $row[$col];
            }
        }
        return $ret;
    }
    
    /**
     * 将一个二维数组转换为 HashMap，并返回结果
     *
     * 用法1：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = ArrayHelper::toHashmap($rows, 'id', 'value');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => '1-1',
     *   //   2 => '2-1',
     *   // )
     * @endcode
     *
     * 如果省略 $valueField 参数，则转换结果每一项为包含该项所有数据的数组。
     *
     * 用法2：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = ArrayHelper::toHashmap($rows, 'id');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => array('id' => 1, 'value' => '1-1'),
     *   //   2 => array('id' => 2, 'value' => '2-1'),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $keyField 按照什么键的值进行转换
     * @param string $valueField 对应的键值
     *
     * @return array 转换后的 HashMap 样式数组
     */
    static function toHashmap($arr, $keyField, $valueField = NULL)
    {
        $ret = array();
        if ($valueField)
        {
            foreach ($arr as $row)
            {
                $ret[$row[$keyField]] = $row[$valueField];
            }
        }
        else
        {
            foreach ($arr as $row)
            {
                $ret[$row[$keyField]] = $row;
            }
        }
        return $ret;
    }
    
    /**
     * 将一个二维数组按照指定字段的值分组
     *
     * 用法：
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $keyField 作为分组依据的键名
     *
     * @return array 分组后的结果
     */
    static function groupBy($arr, $keyField)
    {
        $ret = array();
        foreach ($arr as $row)
        {
            $key = $row[$keyField];
            $ret[$key][] = $row;
        }
        return $ret;
    }
    
    /**
     * 将一个平面的二维数组按照指定的字段转换为树状结构
     *
     *
     * 如果要获得任意节点为根的子树，可以使用 $refs 参数：
     * @code php
     * $refs = null;
     * $tree = ArrayHelper::toTree($rows, 'id', 'parent', 'nodes', $refs);
     *
     * // 输出 id 为 3 的节点及其所有子节点
     * $id = 3;
     * dump($refs[$id]);
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $keyNodeId 节点ID字段名
     * @param string $keyParentId 节点父ID字段名
     * @param string $keyChildren 保存子节点的字段名
     * @param boolean $refs 是否在返回结果中包含节点引用
     *
     * return array 树形结构的数组
     */
    static function toTree($arr, $keyNodeId, $keyParentId = 'parent_id', $keyChildren = 'children', & $refs = NULL)
    {
        $refs = array();
        foreach ($arr as $offset => $row)
        {
            $arr[$offset][$keyChildren] = array();
            $refs[$row[$keyNodeId]] =& $arr[$offset];
        }
    
        $tree = array();
        foreach ($arr as $offset => $row)
        {
            $parentId = $row[$keyParentId];
            if ($parentId)
            {
                if (!isset($refs[$parentId]))
                {
                    $tree[] =& $arr[$offset];
                    continue;
                }
                $parent =& $refs[$parentId];
				$parent[$keyChildren][] =& $arr[$offset];
            }
            else
            {
                $tree[] =& $arr[$offset];
            }
        }
        return $tree;
    }
    
    /**
     * 将树形数组展开为平面的数组
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $keyChildren 包含子节点的键名
     *
     * @return array 展开后的数组
     */
    static function treeToArray($tree, $keyChildren = 'children')
    {
        $ret = array();
        if (isset($tree[$keyChildren]) && is_array($tree[$keyChildren]))
        {
            foreach ($tree[$keyChildren] as $child)
            {
                $ret = array_merge($ret, self::treeToArray($child, $keyChildren));
            }
            unset($tree[$keyChildren]);
            $ret[] = $tree;
        }
        else
        {
            $ret[] = $tree;
        }
        return $ret;
    }

	/**
     * 将树形数组生成二维的树形结构（模版遍历专用）
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $keyChildren 包含子节点的键名
     *
     * @return array 按从上到下的顺序展开后的数组
     */
    static function treeToHtml($tree, $keyChildren = 'children',$now_role_id = 0, $level = 0, $html = '--')
    {
        $ret = array();
		if(!empty($tree) && is_array($tree)){
			foreach($tree as $key=>$value){
				if(isset($value[$keyChildren]) && is_array($value[$keyChildren])){
					$tmp_arr = $value;
					unset($tmp_arr[$keyChildren]);
					$tmp_arr['level'] = $level;
					$tmp_arr['html'] = str_repeat($html, $level);
					if($value['id'] == $now_role_id){
						$tmp_arr['is_edit'] = 1;
					}else{
						$tmp_arr['is_edit'] = 0;
					}
					//$tmp_arr['is_edit'] = 0;
					$ret[] = $tmp_arr;
					foreach ($value[$keyChildren] as $child){
						if($now_role_id == $value['id']){
							$ret = array_merge($ret, self::getChild($child, $keyChildren, $now_role_id, $level+1, $html='--', true));
						}else{
							$ret = array_merge($ret, self::getChild($child, $keyChildren, $now_role_id, $level+1, $html='--', false));
						}	
					}
				}else{
					$ret[] = $value;
				}
			}
		}
        
		return $ret;
    }
	
	/**
     * 将树形数组生成二维的树形结构（新增用户模版专用）
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $keyChildren 包含子节点的键名
     *
     * @return array 按从上到下的顺序展开后的数组
     */
    static function treeToHtmlForAdd($tree, $keyChildren = 'children',$now_role_id = 0, $level = 0, $html = '--')
    {
        $ret = array();
		if(!empty($tree) && is_array($tree)){
			foreach($tree as $key=>$value){
				if(isset($value[$keyChildren]) && is_array($value[$keyChildren])){
					$tmp_arr = $value;
					unset($tmp_arr[$keyChildren]);
					$tmp_arr['level'] = $level;
					$tmp_arr['html'] = str_repeat($html, $level);
					if($value['id'] == $now_role_id){
						$tmp_arr['is_edit'] = 0;
					}else{
						$tmp_arr['is_edit'] = 1;
					}
					//$tmp_arr['is_edit'] = 0;
					$ret[] = $tmp_arr;
					foreach ($value[$keyChildren] as $child){
						if($now_role_id == $value['id']){
							$ret = array_merge($ret, self::getChild($child, $keyChildren, $now_role_id, $level+1, $html='--', true));
						}else{
							$ret = array_merge($ret, self::getChild($child, $keyChildren, $now_role_id, $level+1, $html='--', false));
						}	
					}
				}else{
					$ret[] = $value;
				}
			}
		}
		return $ret;
    }
	
	/**
     * 将树形数组生成二维的树形结构（新增角色模版专用）
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $keyChildren 包含子节点的键名
     *
     * @return array 按从上到下的顺序展开后的数组
     */
    static function treeToHtmlForRoleAdd($tree, $keyChildren = 'children',$now_role_id = 0, $level = 0, $html = '--')
    {
        $ret = array();
		if(!empty($tree) && is_array($tree)){
			foreach($tree as $key=>$value){
				if(isset($value[$keyChildren]) && is_array($value[$keyChildren])){
					$tmp_arr = $value;
					unset($tmp_arr[$keyChildren]);
					$tmp_arr['level'] = $level;
					$tmp_arr['html'] = str_repeat($html, $level);
					if($value['id'] == $now_role_id){
						$tmp_arr['is_edit'] = 0;
					}else{
						$tmp_arr['is_edit'] = 1;
					}
					//$tmp_arr['is_edit'] = 0;
					$ret[] = $tmp_arr;
					foreach ($value[$keyChildren] as $child){
						if($now_role_id == $value['pid']){
							$ret = array_merge($ret, self::getChild($child, $keyChildren, $now_role_id, $level+1, $html='--', true));
						}else{
							$ret = array_merge($ret, self::getChild($child, $keyChildren, $now_role_id, $level+1, $html='--', false));
						}	
					}
				}else{
					$ret[] = $value;
				}
			}
		}
		return $ret;
    }

	/**
     * 获取子集（模版遍历专用）
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $keyChildren 包含子节点的键名
     *
     * @return array 获取子集
     */
    static function getChild($tree, $keyChildren = 'children', $now_role_id = 0, $level = 0, $html = '--', $flag = false)
    {
		$ret = array();
		$max_level = 1; //管理员最高等级
        if (isset($tree[$keyChildren]) && is_array($tree[$keyChildren])){
			$tmp_arr = $tree;
			unset($tmp_arr[$keyChildren]);
			$tmp_arr['level'] = $level;
			$tmp_arr['html'] = str_repeat($html, $level);
			if($flag && $level > ($max_level-1)){
				$tmp_arr['is_edit'] = 1;
			}else{
				$tmp_arr['is_edit'] = 0;
			}
			$ret[] = $tmp_arr;
            foreach ($tree[$keyChildren] as $child){
				if($flag && $level > ($max_level-1)){
					$ret = array_merge($ret, self::getChild($child, $keyChildren, $now_role_id, $level+1, $html='--', true));
				}else{
					$ret = array_merge($ret, self::getChild($child, $keyChildren, $now_role_id, $level+1, $html='--', false));
				}	
            }
        }else{
            $ret[] = $tree;
        }
        return $ret;
    }
    
    /**
     * 根据指定的键对数组排序
     *
     * @endcode
     *
     * @param array $array 要排序的数组
     * @param string $keyname 排序的键
     * @param int $dir 排序方向
     *
     * @return array 排序后的数组
     */
    static function sortByCol($array, $keyname, $dir = SORT_ASC)
    {
        return self::sortByMultiCols($array, array($keyname => $dir));
    }
    
    /**
     * 将一个二维数组按照多个列进行排序，类似 SQL 语句中的 ORDER BY
     *
     * 用法：
     * @code php
     * $rows = ArrayHelper::sortByMultiCols($rows, array(
     *     'parent' => SORT_ASC,
     *     'name' => SORT_DESC,
     * ));
     * @endcode
     *
     * @param array $rowset 要排序的数组
     * @param array $args 排序的键
     *
     * @return array 排序后的数组
     */
    static function sortByMultiCols($rowset, $args)
    {
        $sortArray = array();
        $sortRule = '';
        foreach ($args as $sortField => $sortDir)
        {
            foreach ($rowset as $offset => $row)
            {
                $sortArray[$sortField][$offset] = $row[$sortField];
            }
            $sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', ';
        }
        if (empty($sortArray) || empty($sortRule)) {
            return $rowset;
        }
        eval('array_multisort(' . $sortRule . '$rowset);');
        return $rowset;
    }
	
	/**
	 * 二维数组根据指定key值删除重复的元素并将元素中特定key的值累加到前者
	 * @access public
	 * @param string key
	 * @param array
	 * @param array
	 * @uses  action
	 * @return array
	 * @Author:lsq 2016-2-15
	 */ 
	static function new_array_unique($ke,$arr,$mark = null){
		$tmp_arr = array();
		if(count($arr)){
			if(empty($ke)){
				$tmp_arr = array_unique($arr);
			}else{
				$tmp = array();
				foreach($arr as $key=>$value){
					if(array_key_exists($ke,$value)){
						$tmp[$key] = $value[$ke];
					}else{
						// 元素不包含该key的时候删除该元素
						unset($arr[$key]);
					}
				}
				if(count($tmp)){
					$unique_arr = array_unique($tmp); 
					// 获取重复数据的数组 
					$repeat_arr = array_diff_assoc($tmp,$unique_arr);
					if(count($repeat_arr) && $mark){
						foreach($repeat_arr as $m=>$n){
							foreach($unique_arr as $key=>$value){
								if($value == $n){
									if(count($mark)){
										foreach($mark as $k=>$v){
											$arr[$key][$v] .= ",".$arr[$m][$v];
										}
									}
									unset($arr[$m]);
								}
							}
						}
					}else{
						foreach($repeat_arr as $m=>$n){
							unset($arr[$m]);
						}
					}
					$tmp_arr = $arr;
				}
			}
		}
		return $tmp_arr;
	}
    
}
?>