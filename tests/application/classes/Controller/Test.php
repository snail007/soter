<?php
namespace Controller;
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
class Test extends \Soter_Controller {

	public function do_m() {

	}

	public function do_wx() {
		$http = \Sr::extension('Http');
		$http->get('http://weixin.sogou.com/');
		echo $http->get('http://weixin.sogou.com/websearch/art.jsp?sg=2kVjpB5lvwP_5Pq_wCCcwd4d5JCwogS1ZMykK8lRdl7JD3SRvBqgtA0hmuaECDWmzJ7mtX9nU2HK1z9mood5CTFfnGMZisygmtFzCsWC2v4L9-ZKswiVj9TRYtEdua2nbxH0bYLTzu_LqsaZUK7G5A..&url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8wi2zlX9joQkTLabMYIEDBDNFJmAPOq06Mx8S8IK4g9sh1LvkvBrfA0sV5HZrOvvfxP2RYxvxntd5DEr0w2_qRDUsKSb_q3oUE9Yy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk');
	}

	public function do_index() {
		var_dump( \Sr::business('TestBusiness'));
		var_dump( \Sr::business('TestBusiness0'));
	}

	public function do_index1() {
		$data=array(
		    array('id'=>'a1','name'=>'a1111'),
		    array('id'=>'a2','name'=>'a2222'),
		);
	}

	public function do_test() {
		$a =array();
		for(;;){
			$a[]=  str_repeat("xxxx", 1000);
		}
	}

	public function do_temp() {
		echo \Sr::db()->select('count(' . \Sr::db()->wrap('user.id') . ') as total,id')
			->from('c')
			->limit(0, 1);
	}

}
