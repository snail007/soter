<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Test
 *
 * @author pengmeng
 */
class Controller_Test extends Soter_Controller {

    public function do_m() {
	
    }

    public function do_wx() {
	$http = Sr::extension('Http');
	$http->get('http://weixin.sogou.com/');
	echo $http->get('http://weixin.sogou.com/websearch/art.jsp?sg=2kVjpB5lvwP_5Pq_wCCcwd4d5JCwogS1ZMykK8lRdl7JD3SRvBqgtA0hmuaECDWmzJ7mtX9nU2HK1z9mood5CTFfnGMZisygmtFzCsWC2v4L9-ZKswiVj9TRYtEdua2nbxH0bYLTzu_LqsaZUK7G5A..&url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8wi2zlX9joQkTLabMYIEDBDNFJmAPOq06Mx8S8IK4g9sh1LvkvBrfA0sV5HZrOvvfxP2RYxvxntd5DEr0w2_qRDUsKSb_q3oUE9Yy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk');
    }

    public function do_index() {
	//$typeid = 213; //所属主栏目编号【必填】，后台文章栏目id
	$typeid = 2;
	$mid = 1; //发布文章的会员id
	$dutyadmin = 0; //审核该文章的管理员的id
	$title = '文章标题';
	$content = '文章内容';
	$source = '南京'; //来源【填文交所名，如：南京】
	$pubdate = strtotime(date('Y-m-d H:i:00')); //发布时间【必填】
	$senddate = strtotime(date('Y-m-d H:i:00'));  //投稿时间【必填】
	$prefix = 'dede_';
	if ($this->isExists($title, $pubdate, $source, $prefix)) {
	    Sr::dump('already exists');
	    return;
	}
	$arctiny = array(
	    'typeid' => $typeid, //主栏目id
	    'typeid2' => 0, //副栏目id
	    'arcrank' => -1, //浏览权限：0已审核 (开放浏览)，-1未审核，-2已删除
	    'channel' => 1, //频道类型
	    'senddate' => $senddate, //投稿日期
	    'sortrank' => 0, //文档排序
	    'mid' => $mid, //会员id
	);
	if (Sr::db()->insert($prefix . 'arctiny', $arctiny)->execute()) {
	    Sr::dump('insert dede_arctiny okay');
	    $aid = Sr::db()->lastId();
	    $archives = array(
		'id' => $aid,
		'typeid' => $typeid, //所属主栏目编号【必填】
		'typeid2' => 0, //所属副栏目编号
		'sortrank' => 0, //文章排序(置顶方法)
		'flag' => '', //属性
		'ismake' => -1, //是否生成静态html, -1动态
		'channel' => 1, //文章所属频道模型
		'arcrank' => -1, //浏览权限：0已审核 (开放浏览)，-1未审核，-2已删除
		'click' => 0, //点击次数
		'money' => 0, //消费点数
		'title' => $title, //文章标题【必填】
		'shorttitle' => '', //短标题
		'color' => '', //标题颜色
		'writer' => '', //作者
		'source' => $source, //来源【填文交所名，如：南京】
		'litpic' => '', //缩略图
		'pubdate' => $pubdate, //发布时间【必填】
		'senddate' => $senddate, //投稿时间【必填】
		'mid' => $mid, //会员id
		'keywords' => '', //关键词
		'lastpost' => 0, //最后回复
		'scores' => 0, //消耗积分
		'goodpost' => 0, //好评
		'badpost' => 0, //差评
		'voteid' => 0,
		'notpost' => 0, //不允许回复
		'description' => '', //描述
		'filename' => '', //自定义文件名
		'dutyadmin' => $dutyadmin, //审核该文章的管理员的id
		'tackid' => 0,
		'mtype' => 0, //用户自定义分类
		'weight' => 0, //权重
//		    'buy_time' => 0,
//		    'send_time' => 0,
//		    'tips' => '',
//		    'is_show' => 0
	    );

	    if (Sr::db()->insert($prefix . 'archives', $archives)->execute()) {
		Sr::dump('insert dede_archives okay');
		$article = array(
		    'aid' => $aid, //文章id
		    'typeid' => $typeid, //栏目id
		    //'video' => '', //
		    'body' => $content, //文章内容
		    'redirecturl' => '', //调转url
		    'templet' => '', //自定义模板
		    'userip' => '', //用户ip
			//'push' => 0, //0未推送，1已推送
		);
		if (Sr::db()->insert($prefix . 'addonarticle', $article)->execute()) {
		    Sr::dump('insert dede_addonarticle okay');
		} else {
		    Sr::dump('insert dede_addonarticle fail');
		}
	    } else {
		Sr::dump('insert dede_archives fail');
	    }
	} else {
	    Sr::dump('insert dede_arctiny fail');
	}
    }

    private function isExists($title, $pubdate, $source, $prefix) {
	$where = array(
	    'title' => $title,
	    'pubdate' => $pubdate,
	    'source' => $source,
	);
	$total = Sr::db()->from($prefix . 'archives')->where($where)->limit(0, 1)->execute()->total();
	return $total;
    }

    public function do_test() {
	echo Sr::db()->select('rrr.*,r.*,rr,*')
		->from('admin_resource', 'r')
		->join(array('admin_role_resource_relation' => 'rrr'), 'rrr.admin_resource_id=r.id')
		->join(array('admin_role_relation' => 'rr'), 'rr.admin_role_id=rrr.admin_role_id')
		->where(array('rr.admin_id' => 0))
		->orderBy('r.sort', 'asc');
    }

    public function do_temp() {
	echo Sr::db()->select('count('.Sr::db()->wrap('user.id').') as total,id')->from('c')
								->limit(0,1);
    }

}
