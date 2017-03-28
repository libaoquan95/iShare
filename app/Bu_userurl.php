<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Bu_userurl_topic;
use App\Bu_topic;
use App\Bu_user;

class Bu_userurl extends Model {

	//	userurl �� topics �Ķ�Ӧ��ϵ��һ��userurl�ж��topics��
	public function belongsToManyTopic()
	{
		return $this->belongsToMany('App\Bu_topic', 'bu_userurl_topics', 'bu_userurls_id', 'bu_topics_id');
	}

	//	userurl �� user �Ķ�Ӧ��ϵ��һ��userurl��һ��user��
	public function belongsToUser()
	{
		return $this->belongsTo('App\Bu_user', 'user_id', 'id');
	}

}
