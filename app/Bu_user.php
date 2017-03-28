<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Bu_userurl;

class Bu_user extends Model {

	//	user 与 userurl 的对应关系（一个user有多个个userurl）
	public function hasManyUrls()
	{
		return $this->hasMany('App\Bu_userurl', 'user_id', 'id');
	}
}
