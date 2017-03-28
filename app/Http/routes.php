<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|


Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/

// 首页
Route::get('/', 'HomeController@index');
Route::post('/getRecommendUrl', 'HomeController@recommendUrl');
Route::get('/hot', 'HomeController@index_hot');
Route::get('load_next_page', 'HomeController@load_next_page');
Route::get('load_next_hot_page', 'HomeController@load_next_hot_page');


//	身份验证
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function()
{
	Route::get('login', 'AuthController@login');
	Route::get('register', 'AuthController@register');
	Route::get('logout', 'AuthController@logout');
	Route::post('login_sumbit', 'AuthController@login_sumbit');
	Route::post('register_sumbit', 'AuthController@register_sumbit');
	Route::post('register_sumbit_user_email', 'AuthController@register_sumbit_user_email');
	Route::post('register_sumbit_user_name', 'AuthController@register_sumbit_user_name');
	Route::post('alert_password_sumbit', 'AuthController@alert_password_sumbit');
	Route::get('alert_password', 'AuthController@alert_password');
});

//	话题
Route::group(['prefix' => 'topics', 'namespace' => 'Topics'], function()
{
	Route::get('/', 'TopicsHomeController@index');
	Route::get('load_topic_next_page', 'TopicsHomeController@load_topic_next_page');
	Route::get('load_topic_next_hot_page', 'TopicsHomeController@load_topic_next_hot_page');
	Route::get('{name}', 'TopicsHomeController@topic');
	Route::get('{name}/hot', 'TopicsHomeController@topic_hot');
});

//	用户
Route::group(['prefix' => 'user', 'namespace' => 'User'], function()
{
	Route::get('load_next_userurl_page', 'UserHomeController@load_next_userurl_page');
	Route::get('load_next_hot_userurl_page', 'UserHomeController@load_next_hot_userurl_page');
	Route::get('{userName}', 'UserHomeController@user_urls');
	Route::get('{userName}/hot', 'UserHomeController@user_urls_hot');
});

//	跳转
Route::group(['prefix' => 'to', 'namespace' => 'GotoUrl'], function()
{
	Route::get('/', 'GotoUrlController@index');
	Route::get('{gotoUrl}', 'GotoUrlController@gotoUrl');
});

//	管理
Route::group(['prefix' => 'manage', 'namespace' => 'Manage'], function()
{
	Route::get('{userName}', 'ManageController@index');
	Route::get('{userName}/allurls/', 'ManageController@allurls_index');
	Route::get('{userName}/allurls/{page}', 'ManageController@allurls');
});

//	系统
Route::group(['prefix' => 'syshide', 'namespace' => 'Syshide'], function()
{
	Route::post('url_submit', 'SyshideHomeController@url_submit');
	Route::post('url_del', 'SyshideHomeController@url_del');
	Route::post('url_pause_share', 'SyshideHomeController@url_pause_share');
	Route::post('url_continue_share', 'SyshideHomeController@url_continue_share');
	Route::post('normal_search', 'SyshideHomeController@normal_search');
	Route::post('load_next_normal_search_page', 'SyshideHomeController@load_next_normal_search_page');
	Route::post('load_next_more_search_page', 'SyshideHomeController@load_next_more_search_page');
	Route::post('more_search', 'SyshideHomeController@more_search');
	Route::post('myurl_search', 'SyshideHomeController@myurl_search');
	Route::post('upload_user_img', 'SyshideHomeController@upload_user_img');
	Route::post('upload_topic_img', 'SyshideHomeController@upload_topic_img');
	Route::post('delete_topic', 'SyshideHomeController@delete_topic');
	Route::post('report_url', 'SyshideHomeController@report_url');
});

//	管理员
Route::group(['prefix' => 'administrator', 'namespace' => 'Administrator'], function()
{
	Route::get('/', 'AdministratorController@index');
	Route::get('allurl/{page}', 'AdministratorController@allurl');
	Route::get('messageboard', 'AdministratorController@messageboard');
	Route::get('messageboard/{state_name}', 'AdministratorController@many_state');
	Route::post('messageboard_next_page', 'AdministratorController@messageboard_next_page');
	Route::get('topics', 'AdministratorController@topics');
	Route::get('users', 'AdministratorController@users');
	Route::get('users/admin', 'AdministratorController@users_admin');
	Route::get('users/normal', 'AdministratorController@users_normal');
	Route::get('users/forbid', 'AdministratorController@users_forbid');
	Route::get('users/sortforinvalid', 'AdministratorController@sort_for_invalid');
	Route::get('users/sortforharmful', 'AdministratorController@sort_for_harmful');
	Route::post('set_user_state', 'AdministratorController@set_user_state');
	Route::post('set_user_group', 'AdministratorController@set_user_group');
	Route::get('white_list', 'AdministratorController@white_list');
	Route::post('white_list/add', 'AdministratorController@white_list_add');
	Route::post('white_list/del', 'AdministratorController@white_list_del');
});

//	url信息
Route::group(['prefix' => 'urlinfo', 'namespace' => 'Urlinfo'], function()
{
	Route::get('{shortUrl}', 'UrlinfoController@index');
});

//	错误页
Route::group(['prefix' => 'error', 'namespace' => 'Error'], function()
{
	Route::get('/', 'ErrorController@index');
});

//	留言板
Route::group(['prefix' => 'messageboard', 'namespace' => 'Messageboard'], function()
{
	Route::get('/', 'MessageboardController@index');
	Route::post('submit_messsage', 'MessageboardController@submit_messsage');
	Route::post('next_page', 'MessageboardController@next_page');
	Route::get('manage/{state_name}/{manage_id}', 'MessageboardController@manage');
	Route::get('{state_name}', 'MessageboardController@many_state');
});

//	关于我们
Route::group(['prefix' => 'aboutus', 'namespace' => 'Aboutus'], function()
{
	Route::get('/', 'AboutusController@index');
});

//	分享
Route::group(['prefix' => 'share', 'namespace' => 'Share'], function()
{
	Route::get('/', 'ShareHomeController@index');
});