<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Trang chủ
Route::get('/', 'PagesController@showHomePage')->name('Index');

// Đăng Ký - Đăng Nhập - Đăng Xuất
Route::post('/register', 'UsersController@Register');
Route::post('/login', 'UsersController@LogIn');
Route::post('/logout', 'UsersController@LogOut');
Route::post('/login-with-facebook', 'UsersController@FBLogIn');

// Trang danh sách tất cả anime
Route::get('/list-anime.html', 'PagesController@listPage');

// Tìm kiếm
Route::get('/advanced-search.html', 'PagesController@searchPage');
Route::post('/search', 'SearchController@Find');
Route::post('/adv-search', 'SearchController@Find');

Route::get('/{type?}/{id?}.anime4a', 'PagesController@showHomePage');
Route::post('/search/category/{id?}', 'SearchController@SearchFilmByCategory');

// Xem thông tin bộ phim
Route::get('/xem-thong-tin/{name?}/{id?}.a4a', 'PagesController@showFilmInfoPage');

// Xem phim
Route::get('/xem-phim/{name?}/{anime_id?}.a4a', 'PagesController@showVideoViewPage');
Route::get('/xem-phim/{name?}/{anime_id?}/{episode_id?}.a4a', 'PagesController@showVideoViewPage');
Route::get('/xem-phim/{name?}/{anime_id?}/{episode_id?}/{fansub_id?}.a4a', 'PagesController@showVideoViewPage');
Route::get('/xem-phim/{name?}/{anime_id?}/{episode_id?}/{fansub_id?}/{server_id?}.a4a', 'PagesController@showVideoViewPage');

// Video Data
Route::get('/get-video-{id?}', 'VideoController@GetVideo'); // unUseable

// Filter Animes Data
Route::post('/get-list-newUpdated', 'AnimesController@newUpdated');
Route::post('/get-list-newestAnime', 'AnimesController@newestAnime');
Route::post('/get-list-mostView', 'AnimesController@mostView');
Route::get('/get-list-mostView', 'AnimesController@mostView');

// Bookmark
Route::post('/bookmark', 'AnimesController@Bookmark');
Route::post('/bookmark-delete', 'AnimesController@BookmarkDelete');

// Admincp
Route::get('/admincp', 'ACPIndexPagesController@index')->name('Admincp');
Route::post('/admincp', 'ACPIndexPagesController@index');
Route::get('/admincp/anime', 'ACPAnimePagesController@AnimeList');
Route::post('/admincp/anime/new', 'ACPAnimePagesController@AnimeEditor');
Route::post('/admincp/anime/del', 'ACPAnimePagesController@AnimeDelete');
Route::post('/admincp/anime/edit', 'ACPAnimePagesController@AnimeEditor');
Route::post('/admincp/anime/save', 'ACPAnimePagesController@AnimeSave');

Route::get('/admincp/episode', 'ACPEpisodePagesController@AnimeList');
Route::post('/admincp/episode2', 'ACPEpisodePagesController@EpisodeList');
Route::post('/admincp/add-episode', 'ACPEpisodePagesController@EpisodeAdd');
Route::post('/admincp/episode/new', 'ACPEpisodePagesController@EpisodeEditor');
Route::post('/admincp/episode/del', 'ACPEpisodePagesController@EpisodeDelete');
Route::post('/admincp/episode/edit', 'ACPEpisodePagesController@EpisodeEditor');
Route::post('/admincp/episode/save', 'ACPEpisodePagesController@EpisodeSave');
Route::post('/admincp/get-video', 'ACPVideoController@VideoList');
Route::post('/admincp/edit-video', 'ACPVideoController@VideoEdit');
Route::post('/admincp/save-video', 'ACPVideoController@VideoSave');
Route::post('/admincp/add-video', 'ACPVideoController@VideoAdd');

Route::get('/admincp/video', 'ACPVideoPagesController@VideoList');
Route::post('/admincp/video/new', 'ACPVideoPagesController@VideoEditor');
Route::post('/admincp/video/del', 'ACPVideoPagesController@VideoDelete');
Route::post('/admincp/video/edit', 'ACPVideoPagesController@VideoEditor');
Route::post('/admincp/video/save', 'ACPVideoPagesController@VideoSave');
Route::post('/admincp/video/getEpisode', 'ACPVideoPagesController@GetEpisode');

Route::get('/admincp/country', 'ACPCountryPagesController@_List');
Route::post('/admincp/country/new', 'ACPCountryPagesController@_Edit');
Route::post('/admincp/country/del', 'ACPCountryPagesController@_Delete');
Route::post('/admincp/country/edit', 'ACPCountryPagesController@_Edit');
Route::post('/admincp/country/save', 'ACPCountryPagesController@_Save');

Route::get('/admincp/category', 'ACPCategoryPagesController@_List');
Route::post('/admincp/category/new', 'ACPCategoryPagesController@_Edit');
Route::post('/admincp/category/del', 'ACPCategoryPagesController@_Delete');
Route::post('/admincp/category/edit', 'ACPCategoryPagesController@_Edit');
Route::post('/admincp/category/save', 'ACPCategoryPagesController@_Save');

Route::get('/admincp/char', 'ACPCharPagesController@_List');
Route::post('/admincp/char/new', 'ACPCharPagesController@_Edit');
Route::post('/admincp/char/del', 'ACPCharPagesController@_Delete');
Route::post('/admincp/char/edit', 'ACPCharPagesController@_Edit');
Route::post('/admincp/char/save', 'ACPCharPagesController@_Save');

Route::get('/admincp/director', 'ACPDirectorPagesController@_List');
Route::post('/admincp/director/new', 'ACPDirectorPagesController@_Edit');
Route::post('/admincp/director/del', 'ACPDirectorPagesController@_Delete');
Route::post('/admincp/director/edit', 'ACPDirectorPagesController@_Edit');
Route::post('/admincp/director/save', 'ACPDirectorPagesController@_Save');

Route::get('/admincp/producer', 'ACPProducerPagesController@_List');
Route::post('/admincp/producer/new', 'ACPProducerPagesController@_Edit');
Route::post('/admincp/producer/del', 'ACPProducerPagesController@_Delete');
Route::post('/admincp/producer/edit', 'ACPProducerPagesController@_Edit');
Route::post('/admincp/producer/save', 'ACPProducerPagesController@_Save');

Route::get('/admincp/fansub', 'ACPFansubPagesController@_List');
Route::post('/admincp/fansub/new', 'ACPFansubPagesController@_Edit');
Route::post('/admincp/fansub/del', 'ACPFansubPagesController@_Delete');
Route::post('/admincp/fansub/edit', 'ACPFansubPagesController@_Edit');
Route::post('/admincp/fansub/save', 'ACPFansubPagesController@_Save');

Route::get('/admincp/type', 'ACPTypePagesController@_List');
Route::post('/admincp/type/new', 'ACPTypePagesController@_Edit');
Route::post('/admincp/type/del', 'ACPTypePagesController@_Delete');
Route::post('/admincp/type/edit', 'ACPTypePagesController@_Edit');
Route::post('/admincp/type/save', 'ACPTypePagesController@_Save');

Route::get('/admincp/server', 'ACPServerPagesController@_List');
Route::post('/admincp/server/new', 'ACPServerPagesController@_Edit');
Route::post('/admincp/server/del', 'ACPServerPagesController@_Delete');
Route::post('/admincp/server/edit', 'ACPServerPagesController@_Edit');
Route::post('/admincp/server/save', 'ACPServerPagesController@_Save');

Route::get('/admincp/status', 'ACPStatusPagesController@_List');
Route::post('/admincp/status/new', 'ACPStatusPagesController@_Edit');
Route::post('/admincp/status/del', 'ACPStatusPagesController@_Delete');
Route::post('/admincp/status/edit', 'ACPStatusPagesController@_Edit');
Route::post('/admincp/status/save', 'ACPStatusPagesController@_Save');

Route::get('/admincp/trailer', 'ACPTrailerPagesController@_List');
Route::post('/admincp/trailer/new', 'ACPTrailerPagesController@_Edit');
Route::post('/admincp/trailer/del', 'ACPTrailerPagesController@_Delete');
Route::post('/admincp/trailer/edit', 'ACPTrailerPagesController@_Edit');
Route::post('/admincp/trailer/save', 'ACPTrailerPagesController@_Save');

Route::get('/admincp/tag', 'ACPTagPagesController@_List');
Route::post('/admincp/tag/new', 'ACPTagPagesController@_Edit');
Route::post('/admincp/tag/del', 'ACPTagPagesController@_Delete');
Route::post('/admincp/tag/edit', 'ACPTagPagesController@_Edit');
Route::post('/admincp/tag/save', 'ACPTagPagesController@_Save');


// test
Route::get('/test', function () {
    return view('test');
});
Route::post('/test-gg', 'TestController@Test');

