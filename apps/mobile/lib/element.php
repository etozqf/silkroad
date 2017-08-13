<?php
class mobile_element extends form_element
{
    static function category($catid = '')
    {
        $catid = is_array($catid) ? implode_ids($catid) : $catid;
        $html = '<div class="ui-checkable"><input type="hidden" name="catid" value="'.$catid.'"/>';
        foreach (mobile_category() as $category) {
            $has_priv = mobile_priv::category($category['catid']);
            $html .= '<span'.($has_priv ? '' : ' data-disabled="1" title="您没有此频道的操作权限"').' data-value="'.$category['catid'].'" data-catid-bind="'.$category['catid_bind'];
            $html .= '" class="ui-checkable-item">'.$category['catname'].'</span>';
        }
        $html .= '</div>';
        return $html;
    }

    static function classify($modelid, $classifyid = '')
    {
        $mobile_classify = mobile_classify($modelid);
        if(false == $mobile_classify) return '';
        $html = '<dt>分类：</dt>';
        $html .= '<dd><div class="ui-checkable"><input type="hidden" name="classifyid" value="'. ($classifyid).'"/>';
        foreach ($mobile_classify as $classify) {
            $html .= '<span'.' data-value="'.$classify['classifyid'];
            $html .= '" class="ui-checkable-item">'.$classify['classname'].'</span>';
        }
        $html .= '</div></dd>';
        return $html;
    }

    static function width_height($name, $width, $height)
    {
        $result = array();
        if ($width) {
            $result[] = '宽度 '.$width;
        } else {
            $result[] = '宽度不限';
        }
        if ($height) {
            $result[] = '高度 '.$height;
        } else {
            $result[] = '高度不限';
        }
        return $name.implode('，', $result);
    }
}