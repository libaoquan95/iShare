<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Bu_userurl;

class Bu_user extends Model {

	//	user �� userurl �Ķ�Ӧ��ϵ��һ��user�ж����userurl��
	public function hasManyUrls()
	{
		return $this->hasMany('App\Bu_userurl', 'user_id', 'id');
	}
}
