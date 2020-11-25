<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//

Route::group([
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'AuthController@login')->withoutMiddleware('jwt');
    Route::post('register', 'AuthController@register')->withoutMiddleware('jwt');
    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
    Route::get('test', 'AuthController@test');
    Route::post('refresh', 'AuthController@refresh');
});

//工作列表
//一、根据城市首字母查询城市列表
//二、根据上级ID查询职位类型
//三、公司行业
//四、热门城市
//五、根据城市ID查询城区列表
//六、工作经验等筛选条件
//七、工作列表
//八、反馈
//工作详情
//一、工作详情
//二、相关工作列表
//三、推荐职位列表
//四、举报类型列表
//五、根据举报类型ID查询具体情况
//六、图片验证码
//七、上传图片
//八、提交举报数据
//求职者
//一、用户个人信息
//二、提交用户个人信息
//三、个人优势
//四、提交个人优势
//五、工作经验列表
//六、提交工作经验
//七、新增工作经验
//八、删除工作经验
//九、附件列表
//删除附件

// 城市
Route::group([
    'prefix' => 'city'
], function ($router) {
    // url路径传参，怎么写？怎么接收参数？url中用{p}，控制器中将$p作为方法的参数。
//    Route::get('list/{firstLetter}', 'CityController@getListByFirstLetter');
    Route::get('list/first-letter', 'CityController@getListByFirstLetter');
    Route::get('list/hot', 'CityController@getListByHot');
    Route::get('list/children', 'CityController@getListByParentId');
});
// 工作
Route::group([
    'prefix' => 'job'
], function ($router) {
    // 工作列表
    Route::get('list', 'JobController@getList');
    // 相关工作列表
    Route::get('list/relate', 'JobController@getListRelated');
    // 推荐职位列表
    Route::get('list/recommend', 'JobController@getListRecommend');
    // 工作详情
    Route::get('detail/{id}', 'JobController@getDetailById');

    //根据上级ID查询职位类型
    Route::get('child-position-type-list', 'JobController@getPositionTypeListByParentId');
    // 公司行业
    Route::get('industry-list', 'JobController@getIndustryList');
    // 工作列表搜索筛选配置
    Route::get('search-filter-config', 'JobController@getSearchFilterConfig');

});

// 用户
Route::group([
    'prefix' => 'user'
], function ($router) {
    Route::get('', 'UserController@getById');
    // 客服列表
    Route::get('list', 'UserController@getUsers');
    // 保存用户信息
    Route::post('', 'UserController@save');
    // 保存求职者个人优势
    Route::put('advantage', 'UserController@saveAdvantage');

    // 获取工作经验列表
    Route::get('/experience-list', 'ExperienceController@list');
    // 提交工作经验
    Route::post('/experience', 'ExperienceController@save');
    // 新增工作经验
//    Route::post('/experience', 'ExperienceController@create');
    // 删除工作经验
    Route::delete('/experience', 'ExperienceController@delete');
});


// 会话
Route::group([
    'prefix' => 'session'
], function ($router) {
    Route::get('', 'UserController@getById');
    // 客服列表
    Route::get('list', 'SessionController@getList');
});
