<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_model extends CI_model {
	/************************ 前端内容 Begin ************************/
	//获取友情链接  友情链接col_id=8
	public function get_link_list() {
		$get_info = 'id, title, content, time, is_top';
		$status = $this->db->select($get_info)->order_by('is_top DESC, time DESC')->get_where('content', array('col_id' => 8))->result_array();
		return $status;
	}

	//获取栏目内容
	public function get_content_info($where_arr) {
		$get_info = 'id, title, content, time';
		$status = $this->db->select($get_info)->get_where('content', $where_arr)->result_array();
		return $status;
	}

	//获取栏目信息
	public function get_col_info($where_arr) {
		$status = $this->db->get_where('col', $where_arr)->result_array();
		return $status;
	}

	/************************ 前台业务逻辑 Begin ************************/
	/*
		用户验证
		email is username
	 */
	public function get_user_info($email, $password) {
		$get_info = 'user_id, identity, realname';
		$status = $this->db->select($get_info)->get_where('user', array('email' => $email, 'password' => md5($password), 'status' => 1))->result_array();
		return $status;
	}

	/*
		用户个人信息获取
	 */
	public function show_user_info($user_id) {
		$get_info = 'realname, sex, major, research_direction, address, phone, postcode, organization, qq, edu_background';
		$status = $this->db->select($get_info)->get_where('user', array('user_id' => $user_id))->result_array();
		return $status;
	}

	/*
		用户投稿列表获取
		根据status状态
	 */
	public function get_list_article($where_arr, $offset, $per_page = 10, $other_info = '') {
		$get_info = 'article_id, title, author, keywords, create_time, check_status' . $other_info;

		$status = $this->db->select($get_info)->order_by('check_status DESC, create_time DESC')->limit($per_page, $offset)->get_where('article', $where_arr)->result_array();
		return $status;
	}

	/*
		获取稿件信息
	 */
	public function get_info_article($where_arr, $other_info = '') {
		$get_info = 'article_id, title, author, keywords, create_time, check_status, abstract, attachment_url' . $other_info;
		$status = $this->db->select($get_info)->get_where('article', $where_arr)->result_array();
		return $status;
	}

	/*
		获取审核专家
	 */
	public function get_specialist_info($where_arr, $other_info = '') {
		$get_info = 'user_id, realname';
		$status = $this->db->select($get_info)->get_where('user', $where_arr)->result_array();
		return $status;
	}

	/*
		链接查询获取专家名字和审稿意见
	*/
	public function get_name_suggest($where_arr, $other_info = '') {
		$get_info = 'content, realname, time, suggest.rank' . $other_info;
		$status = $this->db->select($get_info)->join('user', 'suggest.user_id = user.user_id', 'inner')->order_by('rank ASC, time DESC')->get_where('suggest', $where_arr)->result_array();
		return $status;
	}

	/*
		获取专家审核意见表中的信息
	 */
	public function get_suggest_info($where_arr) {
		$status = $this->db->select('*')->get_where('suggest', $where_arr)->result_array();
		return $status;
	}

	/*
		获取该专家要审核的稿件
		通过article_id连接suggest和article两个表
	*/
	public function get_check_article($where_arr, $other_info = '') {
		$get_info = 'suggest.article_id, author, title, create_time, keywords, check_deadline, check_status' . $other_info;
		$status = $this->db->select($get_info)->join('article', 'suggest.article_id = article.article_id', 'inner')->get_where('suggest', $where_arr)->result_array();
		return $status;
	}
	/************************ 前台业务逻辑 End ************************/
}