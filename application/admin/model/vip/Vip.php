<?php
namespace app\admin\model\vip;

use think\Model;

class Vip extends Model
{
	public function user()
	{
		return $this->belongsTo('app\admin\model\User', 'user_id');
	}

	public function admin()
	{
		return $this->belongsTo('app\admin\model\Admin', 'uid');
	}

	public function cate()
	{
		return $this->belongsTo('VipCate', 'cate_id');
	}
}
