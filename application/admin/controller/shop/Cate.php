<?php
namespace app\admin\controller\shop;

use think\Request;
use app\admin\controller\Base;
use app\admin\model\shop\Cate as shopCate;

class Cate extends Base
{
	public function lst() 
	{
		$o_cates = shopCate::where('status', '1')
			->where('status', '1')
			->paginate(3);
		$this->assign('cates', $o_cates);

		return $this->fetch('lst');
	}

	public function add()
	{
		header("Content-type:text/html;charset=utf-8");
		$cate = new shopCate;
		if (Request::instance()->isGet()) {
			$titles = $cate->where('status', '1')
				->order('weight', 'desc')
				->select();
			$this->assign('titles', $titles);

			return $this->fetch('add');
		}
		if (Request::instance()->isAjax()) {
			$title = input('title', '');
			$weight = input('weight', '100');
			$pid = input('pid');
			if (empty($title)) {
				return $this->no("名称不能为空");
			}
			$cate->title = $title;
			$cate->weight = $weight;
			$cate->pid = $pid;
			$res = $cate->save();
			if (!$res) {
				return $this->no("增加失败");
			}

			return $this->yes("增加成功");
		}
	}

	public function edit()
	{
		$cate = new shopCate;
		$id = input('id');
		if (Request::instance()->isGet()) {
			$titles = $cate->where('status', '1')
				->order('weight', 'desc')
				->select();
			$o_cate = $cate->where('id', $id)
				->where('status', '1')
				->find();
			$this->assign('cate', $o_cate);
			$this->assign('titles', $titles);

			return $this->fetch('edit');
		}
		if (Request::instance()->isAjax()) {
			$title = input('title', '');
			$weight = input('weight', '100');
			$pid = input('pid');
			if (empty($title)) {
				return $this->no("名称不能为空");
			}
			$o_cate = $cate->where('id', $id)
				->where('status', '1')
				->find();
			$o_cate->title = $title;
			$o_cate->weight = $weight;
			$o_cate->pid = $pid;
			$res = $o_cate->save();
			if (!$res) {
				return $this->no("修改失败");
			}

			return $this->yes("修改成功");
		}
	}

	public function del()
	{
		$id = input('id');
		$o_cate = shopCate::where('id', $id)
			->where('status', '1')
			->find();
		if (!$o_cate) {
			return $this->no("对象属性错误");
		}
		$o_cate->status = 0;
		$o_cate->save();
		$o_products = $o_cate->product()->where('status', '1')->select();
		foreach ($o_products as $vv) {
			$vv->status = 0;
			$vv->save();
		}

		return $this->yes("删除成功");

	}

}
