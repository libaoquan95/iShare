<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Bu_userurl_topic;
use App\Bu_userurl;

class Bu_topic extends Model {

	public function belongsToManyUserurl()
	{
		return $this->belongsToMany('App\Bu_userurl', 'bu_userurl_topics', 'bu_topics_id', 'bu_userurls_id');
	}

}
