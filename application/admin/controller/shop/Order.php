<?php
namespace app\admin\controller\shop;

use think\Session;
use think\Request;
use app\admin\model\User;
use app\admin\model\Admin;
use app\admin\controller\Base;
use app\admin\model\shop\Product;
use app\admin\model\shop\Order as shopOrder;

class Order extends Base
{
	public function lst() 
	{
		$o_orders = shopOrder::where('is_del', '1')
			->order('time', 'desc')
			->paginate(3, false, [
                'query' => Request::instance()->param(),//不丢失已存在的url参数
            ]);
		$this->assign('orders', $o_orders);

		return $this->fetch('lst');
	}

	public function add()
	{
		if (Request::instance()->isGet()) {
			return $this->fetch('add');
		}
		if (Request::instance()->isAjax()) {
			$user_name = input('user_name', '');
			$pro_id = input('pro_id', '');
			$tel = input('tel', '');
			$name = input('name', '');
			$address = input('address', '');
			if (empty($user_name) || empty($pro_id) || empty($tel) || empty($name) || empty($address)) {
				return $this->no("您有信息未填写，请填写完整");
			}
			$o_user = User::where('name', $user_name)
				->where('status', '1')
				->find();
			if (!$o_user) {
				return $this->no("用户不存在");
			}
			$o_product = Product::where('id', $pro_id)
				->where('status', '1')
				->find();
			if (!$o_product) {
				return $this->no("商品不存在");
			}
			$order = new shopOrder;
			$order->user_name = $user_name;
			$order->pro_id = $pro_id;
			$order->tel = $tel;
			$order->name = $name;
			$order->address = $address;
			$order->time = time();
			$order->admin_id = $this->admin_id;
			$res = $order->save();
			if (!$res) {
				return $this->no("新增失败");
			}

			return $this->yes("新增成功");
		}
	}

	public function edit()
	{
		$id = input('id');
		if (Request::instance()->isGet()) {
			$o_order = shopOrder::where('id', $id)
				->where('is_del', '1')
				->find();
			$this->assign('order', $o_order);

			return $this->fetch('edit');
		}
		if (Request::instance()->isAjax()) {
			$user_name = input('user_name', '');
			$pro_id = input('pro_id', '');
			$tel = input('tel', '');
			$name = input('name', '');
			$address = input('address', '');
			if (empty($user_name) || empty($pro_id) || empty($tel) || empty($name) || empty($address)) {
				return $this->no("您有信息未填写，请填写完整");
			}
			$o_user = User::where('name', $user_name)
				->where('status', '1')
				->find();
			if (!$o_user) {
				return $this->no("用户不存在");
			}
			$o_product = Product::where('id', $pro_id)
				->where('status', '1')
				->find();
			if (!$o_product) {
				return $this->no("商品不存在");
			}
			$order = new shopOrder;
			$o_order = shopOrder::where('id', $id)
				->where('is_del', '1')
				->find();
			$o_order->user_name = $user_name;
			$o_order->pro_id = $pro_id;
			$o_order->tel = $tel;
			$o_order->name = $name;
			$o_order->address = $address;
			$o_order->admin_id = $this->admin_id;
			$res = $o_order->save();
			if (!$res) {
				return $this->no("修改失败");
			}

			return $this->yes("修改成功");
		}

	}

	public function del()
	{
		$id = input('id');
		$o_order = shopOrder::where('id', $id)
			->where('is_del', '1')
			->find();
		if (!$o_order) {
			return $this->no("对象属性错误");
		}
		$o_order->is_del = 0;
		$o_order->save();

		return $this->yes("删除成功");
	}

	public function fahuo()
	{
		$id = input('id');
		$information = input('information', '');
		$o_order = shopOrder::where('id', $id)
			->where('is_del', '1')
			->find();
		if (!$o_order) {
			return $this->no("对象属性错误");
		}
		$o_order->admin_id = $this->admin_id;
		$o_order->information = $information;
		$o_order->status = 1;
		$o_order->save();

		return $this->yes("发货成功");	
	}

}
