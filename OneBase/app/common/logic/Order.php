<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\common\logic;

/**
 * 小程序-订单逻辑
 */
class Order extends LogicBase
{
    
    /**
     * 文章分类编辑
     */
    public function articleCategoryEdit($data = [])
    {
        
        $validate_result = $this->validateArticleCategory->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateArticleCategory->getError()];
        }
        
        $url = url('articleCategoryList');
        
        $result = $this->modelArticleCategory->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '文章分类' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelArticleCategory->getError()];
    }
    
    /**
     * 获取订单列表
     */
    public function getOrderList($where = [], $field = 'a.*,m.nickname,c.name as supplier_name', $order = '')
    {
        echo("123456");
        $this->modelOrder->alias('a');

//        dump($this->modelOrder->alias('a'));

       $join = [
                    [SYS_DB_PREFIX . 'user m', 'a.client_id = m.id'],
                    [SYS_DB_PREFIX . 'supplier c', 'a.supplier_id = c.id'],
                ];

//        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];

        $this->modelOrder->join = $join;

//        dump($this->modelOrder->join = $join);
//        dump($this->modelOrder->getList($where, $field, $order));

        return $this->modelOrder->getList($where, $field, $order);


    }
    
    /**
     * 获取订单列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = [];
        
        !empty($data['search_data']) && $where['a.name|a.describe'] = ['like', '%'.$data['search_data'].'%'];
        
        return $where;
    }
    
    /**
     * 文章信息编辑
     */
    public function articleEdit($data = [])
    {
        
        $validate_result = $this->validateArticle->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateArticle->getError()];
        }
        
        $url = url('articleList');
        
        empty($data['id']) && $data['member_id'] = MEMBER_ID;
        
        $result = $this->modelArticle->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '文章' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '文章操作成功', $url] : [RESULT_ERROR, $this->modelArticle->getError()];
    }

    /**
     * 获取文章信息
     */
    public function getArticleInfo($where = [], $field = 'a.*,m.nickname,c.name as category_name')
    {
        
        $this->modelArticle->alias('a');
        
        $join = [
                    [SYS_DB_PREFIX . 'member m', 'a.member_id = m.id'],
                    [SYS_DB_PREFIX . 'article_category c', 'a.category_id = c.id'],
                ];
        
        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        $this->modelArticle->join = $join;
        
        return $this->modelArticle->getInfo($where, $field);
    }
    
    /**
     * 获取分类信息
     */
    public function getArticleCategoryInfo($where = [], $field = true)
    {
        
        return $this->modelArticleCategory->getInfo($where, $field);
    }
    
    /**
     * 获取文章分类列表
     */
    public function getArticleCategoryList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelArticleCategory->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 文章分类删除
     */
    public function articleCategoryDel($where = [])
    {
        
        $result = $this->modelArticleCategory->deleteInfo($where);
        
        $result && action_log('删除', '文章分类删除，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '文章分类删除成功'] : [RESULT_ERROR, $this->modelArticleCategory->getError()];
    }
    
    /**
     * 文章删除
     */
    public function articleDel($where = [])
    {
        
        $result = $this->modelArticle->deleteInfo($where);
        
        $result && action_log('删除', '文章删除，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '文章删除成功'] : [RESULT_ERROR, $this->modelArticle->getError()];
    }
}
