<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Bu_userurl_topic;
use App\Bu_topic;
use App\Bu_user;

class Bu_userurl extends Model {

	//	userurl 与 topics 的对应关系（一个userurl有多个topics）
	public function belongsToManyTopic()
	{
		return $this->belongsToMany('App\Bu_topic', 'bu_userurl_topics', 'bu_userurls_id', 'bu_topics_id');
	}

	//	userurl 与 user 的对应关系（一个userurl有一个user）
	public function belongsToUser()
	{
		return $this->belongsTo('App\Bu_user', 'user_id', 'id');
	}

}
