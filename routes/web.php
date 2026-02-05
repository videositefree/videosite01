<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DatabaseController;

use App\Http\Controllers\FilmsController;

use App\Http\Controllers\TagsController;

use App\Http\Controllers\TagsStarsController;

use App\Http\Controllers\TagsStudiosController;

use App\Http\Controllers\StarsController;

use App\Http\Controllers\ProducersController;

use App\Http\Controllers\FilmsSearchController;

use App\Http\Controllers\Filtrscontroller;

use App\Http\Controllers\UploadFilesController;



// ========================================================================== ADMIN AREA  ============================================================ //
use App\Http\Controllers\AdminController;

use App\Http\Controllers\BackupFilesController;

use App\Http\Controllers\AdminFilmsController;

use App\Http\Controllers\AdminTagsStarsController;

use App\Http\Controllers\AdminTagsStudiosController;

use App\Http\Controllers\AdminTagsSitesController;

use App\Http\Controllers\MisingFilesControllers;

use App\Http\Controllers\AjaxTagController;

use App\Http\Controllers\AdminTagsController;

use App\Http\Controllers\AdminStarsController;

use App\Http\Controllers\AdminStudiosController;

use App\Http\Controllers\AdminSitesController;

use App\Http\Controllers\AdminVideoController;

use App\Http\Controllers\OperationDatabaseController;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

// ===================================================================== DATABASE =============================================================== //

Route::get('/migrations_db', [DatabaseController::class, 'migrations_db']); // IF TABLE NOT EXIST IN DATABASE CREATE MIGRATIONS

Route::get('/connection_db', [DatabaseController::class, 'connection_db']); // CHECK CONNECTION TO DATABASE

Route::post('/config_db', [DatabaseController::class, 'config_db']); // CHECK CONNECTION TO DATABASE

Route::get('/install_db', [DatabaseController::class, 'install_db']); // CHECK CONNECTION TO DATABASE


// ===================================================================== END =============================================================== //





Route::get('', [FilmsController::class, 'index']);
// ============================================================== FILTR FOR INDEX SITE ===================================================== //

Route::get('/index_asc', [FilmsController::class, 'index_asc']);

Route::get('/index_name_asc', [FilmsController::class, 'index_name_asc']);

Route::get('/index_name_desc', [FilmsController::class, 'index_name_desc']);

Route::get('/index_rating_asc', [FilmsController::class, 'index_rating_asc']);

Route::get('/index_rating_desc', [FilmsController::class, 'index_rating_desc']);

Route::get('/index_duration_asc', [FilmsController::class, 'index_duration_asc']);

Route::get('/index_duration_desc', [FilmsController::class, 'index_duration_desc']);

Route::get('/index_random', [FilmsController::class, 'index_random']);

// ===================================================================== END =============================================================== //









// ===================================================================== WATCH FILMS =============================================================== //

Route::get('/watch/{id}', [FilmsController::class, 'watch'])->name('watch');

Route::get('/index_random_film_watch', [FilmsController::class, 'index_random_film_watch']);

// ===================================================================== END =============================================================== //



// ===================================================================== SEARCH FILMS =============================================================== //

Route::get('/search', [FilmsSearchController::class, 'search']);



// advanced method search

Route::get('/search/method', [Filtrscontroller::class, 'relevance']);

// ===================================================================== END =============================================================== //








// ===================================================================== Display SELECT TAGS, STARS, STUDIOS =============================================================== //

Route::get('/select_categories/{id}', [FilmsController::class, 'select_categories']);

Route::get('/select_categories_asc/{id}', [FilmsController::class, 'select_categories_asc']);

Route::get('/select_categories_films_asc/{id}', [FilmsController::class, 'select_categories_films_asc']);

Route::get('/select_categories_films_desc/{id}', [FilmsController::class, 'select_categories_films_desc']);

Route::get('/select_categories_films_stars_asc/{id}', [FilmsController::class, 'select_categories_films_stars_asc']);

Route::get('/select_categories_films_stars_desc/{id}', [FilmsController::class, 'select_categories_films_stars_desc']);

Route::get('/select_categories_random/{id}', [FilmsController::class, 'select_categories_random']);



Route::get('/select_stars/{id}', [FilmsController::class, 'select_stars']);

Route::get('/select_stars_asc/{id}', [FilmsController::class, 'select_stars_asc']);

Route::get('/select_stars_films_asc/{id}', [FilmsController::class, 'select_stars_films_asc']);

Route::get('/select_stars_films_desc/{id}', [FilmsController::class, 'select_stars_films_desc']);

Route::get('/select_stars_films_stars_asc/{id}', [FilmsController::class, 'select_stars_films_stars_asc']);

Route::get('/select_stars_films_stars_desc/{id}', [FilmsController::class, 'select_stars_films_stars_desc']);

Route::get('/select_stars_random/{id}', [FilmsController::class, 'select_stars_random']);



Route::get('/select_studios/{id}', [FilmsController::class, 'select_studios']);

Route::get('/select_studios_asc/{id}', [FilmsController::class, 'select_studios_asc']);

Route::get('/select_studios_films_asc/{id}', [FilmsController::class, 'select_studios_films_asc']);

Route::get('/select_studios_films_desc/{id}', [FilmsController::class, 'select_studios_films_desc']);

Route::get('/select_studios_films_stars_asc/{id}', [FilmsController::class, 'select_studios_films_stars_asc']);

Route::get('/select_studios_films_stars_desc/{id}', [FilmsController::class, 'select_studios_films_stars_desc']);

Route::get('/select_studios_random/{id}', [FilmsController::class, 'select_studios_random']);

// ===================================================================== END =============================================================== //







// ===================================================================== TAGS =============================================================== //

Route::get('/tags', [TagsController::class, 'categories']);

Route::get('/tags_stars_db_film', [TagsController::class, 'tags_stars_db_film']);

Route::get('/tags_studios_db_film', [TagsController::class, 'tags_studios_db_film']);

// ===================================================================== FILTR FOR TAGS =============================================================== //


Route::get('/tags_asc', [TagsController::class, 'categories_asc']);

Route::get('/tags_name_asc', [TagsController::class, 'categories_name_asc']);

Route::get('/tags_name_desc', [TagsController::class, 'categories_name_desc']);

Route::get('/categories_user_random', [TagsController::class, 'categories_user_random']);

// ===================================================================== END =============================================================== //

// ===================================================================== FILTR FOR TAGS FILMS - Stars =============================================================== //


Route::get('/tags_id_asc_db_films_stars_user', [TagsController::class, 'tags_id_asc_db_films_stars_user']);

Route::get('/tags_name_asc_db_films_stars_user', [TagsController::class, 'tags_name_asc_db_films_stars_user']);

Route::get('/tags_name_desc_db_films_stars_user', [TagsController::class, 'tags_name_desc_db_films_stars_user']);

Route::get('/tags_random_db_films_stars_user', [TagsController::class, 'tags_random_db_films_stars_user']);

// ===================================================================== END =============================================================== //
// ===================================================================== FILTR FOR TAGS FILMS - Studios =============================================================== //


Route::get('/tags_id_asc_db_films_studios_user', [TagsController::class, 'tags_id_asc_db_films_studios_user']);

Route::get('/tags_name_asc_db_films_studios_user', [TagsController::class, 'tags_name_asc_db_films_studios_user']);

Route::get('/tags_name_desc_db_films_studios_user', [TagsController::class, 'tags_name_desc_db_films_studios_user']);

Route::get('/tags_random_db_films_studios_user', [TagsController::class, 'tags_random_db_films_studios_user']);

// ===================================================================== END =============================================================== //






// ===================================================================== TAGS STARS =============================================================== //

Route::get('/tags_stars', [TagsStarsController::class, 'categories_stars']);

// ===================================================================== FILTR FOR TAGS =============================================================== //


Route::get('/tags_stars_asc', [TagsStarsController::class, 'categories_stars_asc']);

Route::get('/tags_stars_name_asc', [TagsStarsController::class, 'categories_stars_name_asc']);

Route::get('/tags_stars_name_desc', [TagsStarsController::class, 'categories_stars_name_desc']);

Route::get('/tags_stars_random', [TagsStarsController::class, 'categories_stars_random']);

// ===================================================================== END =============================================================== //




// ===================================================================== TAGS STUDIOS =============================================================== //

Route::get('/tags_studios', [TagsStudiosController::class, 'categories_studios']);

// ===================================================================== FILTR FOR TAGS =============================================================== //


Route::get('/tags_studios_asc', [TagsStudiosController::class, 'categories_studios_asc']);

Route::get('/tags_studios_name_asc', [TagsStudiosController::class, 'categories_studios_name_asc']);

Route::get('/tags_studios_name_desc', [TagsStudiosController::class, 'categories_studios_name_desc']);

Route::get('/categories_studios_random', [TagsStudiosController::class, 'categories_studios_random']);


// ===================================================================== END =============================================================== //




// ===================================================================== STARS =============================================================== //

Route::get('/stars', [StarsController::class, 'stars']);

// ===================================================================== FILTR FOR STARS =============================================================== //


Route::get('/stars_asc', [StarsController::class, 'stars_asc']);

Route::get('/stars_name_asc', [StarsController::class, 'stars_name_asc']);

Route::get('/stars_name_desc', [StarsController::class, 'stars_name_desc']);

Route::get('/stars_rating_asc', [StarsController::class, 'stars_rating_asc']);

Route::get('/stars_rating_desc', [StarsController::class, 'stars_rating_desc']);

Route::get('/stars_gender_male_asc', [StarsController::class, 'stars_gender_male_asc']);

Route::get('/stars_gender_female_desc', [StarsController::class, 'stars_gender_female_desc']);

Route::get('/stars_random', [StarsController::class, 'stars_random']);
// ===================================================================== END =============================================================== //








// ===================================================================== STUDIOS =============================================================== //

Route::get('/studios', [ProducersController::class, 'studios']);

// ===================================================================== FILTR FOR STUDIOS =============================================================== //


Route::get('/studios_asc', [ProducersController::class, 'studios_asc']);

Route::get('/studios_name_asc', [ProducersController::class, 'studios_name_asc']);

Route::get('/studios_name_desc', [ProducersController::class, 'studios_name_desc']);

Route::get('/studios_rating_asc', [ProducersController::class, 'studios_rating_asc']);

Route::get('/studios_rating_desc', [ProducersController::class, 'studios_rating_desc']);

Route::get('/studios_random', [ProducersController::class, 'studios_random']);

// ===================================================================== END =============================================================== //




// ===================================================================== UPLOAD FILES =============================================================== //

Route::get('/add_films', [UploadFilesController::class, 'add_films']);

Route::post('/add_films_save', [UploadFilesController::class, 'save']);

// ===================================================================== END =============================================================== //



















// ===================================================================== ADMIN =============================================================== //



















// ===================================================================== ADMIN AREA =============================================================== //


Route::get('/admin_index', [AdminController::class, 'admin']);

Route::get('/admin_help', [AdminController::class, 'help']);  // admin information

// ===================================================================== END =============================================================== //







// ================================================================= BACKUP DATABASE ============================================================== //

Route::get('/admin_database_copy', [BackupFilesController::class, 'db_copy_blade']); // blade databse table

Route::get('/open_main_folder_db', [BackupFilesController::class, 'open_main_folder_db']); // blade databse table

Route::get('/copy_db', [BackupFilesController::class, 'copy_db']); // create zip files database

Route::get('/delete_db_copy/{file}', [BackupFilesController::class, 'delete_db_copy']); // delete chose zip files database

Route::get('/delete_db_copy_all', [BackupFilesController::class, 'delete_db_copy_all']); // delete zip files database


// ===================================================================== END =============================================================== //







// ================================================================= BACKUP DATABASE ============================================================== //

Route::get('/admin_folder_copy', [BackupFilesController::class, 'copy_folder_blade']); // blade databse table

Route::get('/open_main_folder_thumbnail_copy', [BackupFilesController::class, 'open_main_folder_thumbnail_copy']);

Route::get('/copy_folder', [BackupFilesController::class, 'copy_folder']); // create zip files database

Route::get('/delete_folder_copy/{file}', [BackupFilesController::class, 'delete_folder_copy_thumbnail']); // delete chose zip files database

Route::get('/delete_folder_copy_all', [BackupFilesController::class, 'delete_folder_thumbnail_all']); // delete zip files database


// ===================================================================== END =============================================================== //







// ================================================================= Database operations ============================================================== //


Route::get('/operation_database', [OperationDatabaseController::class, 'operation_database']);




Route::get('/create_site_txt', [OperationDatabaseController::class, 'create_site_txt']);

Route::get('/open_main_folder_out_videosite', [OperationDatabaseController::class, 'open_main_folder_out_videosite']);

Route::get('/create_tags_txt', [OperationDatabaseController::class, 'create_tags_txt']);

Route::get('/create_stars_txt', [OperationDatabaseController::class, 'create_stars_txt']);

Route::get('/create_studios_txt', [OperationDatabaseController::class, 'create_studios_txt']);

Route::get('/create_special_txt', [OperationDatabaseController::class, 'create_special_txt']);



Route::get('/change_film_name', [OperationDatabaseController::class, 'change_film_name']);

Route::get('/change_film_name_md5', [OperationDatabaseController::class, 'change_film_name_md5']);


Route::get('/change_tags_name', [OperationDatabaseController::class, 'change_tags_name']);

Route::get('/change_tags_name_md5', [OperationDatabaseController::class, 'change_tags_name_md5']);


Route::get('/change_stars_name', [OperationDatabaseController::class, 'change_stars_name']);

Route::get('/change_stars_name_md5', [OperationDatabaseController::class, 'change_stars_name_md5']);


Route::get('/create_films_txt', [OperationDatabaseController::class, 'create_films_txt']);


Route::get('/change_studios_name', [OperationDatabaseController::class, 'change_studios_name']);

Route::get('/change_studios_name_md5', [OperationDatabaseController::class, 'change_studios_name_md5']);


Route::get('/change_thumbnail_name', [OperationDatabaseController::class, 'change_thumbnail_name']);

Route::get('/change_thumbnail_name_md5', [OperationDatabaseController::class, 'change_thumbnail_name_md5']);


// ===================================================================== END =============================================================== //







// ===================================================== Absence films, tags, stars, studios ==================================================== //


Route::get('/absence_films', [MisingFilesControllers::class, 'absence_films']);

Route::get('/absence_tags', [MisingFilesControllers::class, 'absence_tags']);

Route::get('/absence_tags_stars', [MisingFilesControllers::class, 'absence_tags_stars']);

Route::get('/absence_tags_studios', [MisingFilesControllers::class, 'absence_tags_studios']);

Route::get('/absence_tags_sites', [MisingFilesControllers::class, 'absence_tags_sites']);

Route::get('/absence_stars', [MisingFilesControllers::class, 'absence_stars']);

Route::get('/absence_studios', [MisingFilesControllers::class, 'absence_studios']);

Route::get('/absence_files_films', [MisingFilesControllers::class, 'absence_files_films']);

Route::get('/absence_files_short', [MisingFilesControllers::class, 'absence_files_short']);

Route::get('/absence_files_thumbnail', [MisingFilesControllers::class, 'absence_files_thumbnail']);

Route::get('/absence_files_tags', [MisingFilesControllers::class, 'absence_files_tags']);

Route::get('/absence_files_tags_stars', [MisingFilesControllers::class, 'absence_files_tags_stars']);

Route::get('/absence_files_tags_studios', [MisingFilesControllers::class, 'absence_files_tags_studios']);

Route::get('/absence_files_tags_sites', [MisingFilesControllers::class, 'absence_files_tags_sites']);

Route::get('/absence_files_stars', [MisingFilesControllers::class, 'absence_files_stars']);

Route::get('/absence_files_studios', [MisingFilesControllers::class, 'absence_files_studios']);



// ===================================================================== Delete and Show =============================================================== //

Route::post('/delete_absence_files', [MisingFilesControllers::class, 'delete_absence_files']);

Route::post('/show_absence_files', [MisingFilesControllers::class, 'show_absence_files']);


// ===================================================================== END =============================================================== //



Route::get('/delete_files_from_admin_search_films/{id}/', [AdminFilmsController::class, 'delete_files_from_admin_search_films']);

Route::get('/delete_files_from_admin_search_films_save/{id}/', [AdminFilmsController::class, 'delete_files_from_admin_search_films_save']); // delte

Route::get('/delete_files_from_admin_search_tags/{id}/', [AdminTagsController::class, 'delete_files_from_admin_search_tags']);

Route::get('/delete_files_from_admin_search_tags_save/{id}/', [AdminTagsController::class, 'delete_files_from_admin_search_tags_save']); // delte

Route::get('/delete_files_from_admin_search_stars/{id}/', [AdminStarsController::class, 'delete_files_from_admin_search_stars']);

Route::get('/delete_files_from_admin_search_stars_save/{id}/', [AdminStarsController::class, 'delete_files_from_admin_search_stars_save']); // delte

Route::get('/delete_files_from_admin_search_studios/{id}/', [AdminStudiosController::class, 'delete_files_from_admin_search_studios']);

Route::get('/delete_files_from_admin_search_studios_save/{id}/', [AdminStudiosController::class, 'delete_files_from_admin_search_studios_save']); // delte

Route::get('/delete_files_from_admin_search_site/{id}/', [AdminSitesController::class, 'delete_files_from_admin_search_site']);

Route::get('/delete_files_from_admin_search_site_save/{id}/', [AdminSitesController::class, 'delete_files_from_admin_search_site_save']); // delte



// ================================================================= FILMS ============================================================== //

Route::get('/admin_films', [AdminFilmsController::class, 'films']); 

    // ============================================================== FILTR FOR INDEX SITE ===================================================== //

    Route::get('/open_main_folder_film/', [AdminFilmsController::class, 'open_main_folder_film']);

    Route::get('/open_main_folder_thumbnail/', [AdminFilmsController::class, 'open_main_folder_thumbnail']);
    
    Route::get('/open_main_folder_short/', [AdminFilmsController::class, 'open_main_folder_short']);

    Route::get('/films_id_asc', [AdminFilmsController::class, 'films_id_asc']);

    Route::get('/films_name_asc', [AdminFilmsController::class, 'films_name_asc']);

    Route::get('/films_name_desc', [AdminFilmsController::class, 'films_name_desc']);

    Route::get('/films_rating_asc', [AdminFilmsController::class, 'films_rating_asc']);

    Route::get('/films_rating_desc', [AdminFilmsController::class, 'films_rating_desc']);

    Route::get('/films_on_desc', [AdminFilmsController::class, 'films_on_desc']);

    Route::get('/films_off_desc', [AdminFilmsController::class, 'films_off_desc']);

    Route::get('/unique_tags', [AdminFilmsController::class, 'unique_tags']);
    
    Route::get('/unique_stars', [AdminFilmsController::class, 'unique_stars']);

    Route::get('/unique_studios', [AdminFilmsController::class, 'unique_studios']);

    // ================================================================= END ============================================================== //



    Route::get('/admin_films_on', [AdminFilmsController::class, 'films_on']);

    Route::get('/admin_films_off', [AdminFilmsController::class, 'films_off']);

    Route::get('/delete_films/{id}', [AdminFilmsController::class, 'delete_films']);

    Route::get('/delete_all_films/', [AdminFilmsController::class, 'delete_all_films']);




    Route::get('/edit_films/{id}', [AdminFilmsController::class, 'edit_films']);

    Route::get('/open_folder_film/{id}', [AdminFilmsController::class, 'open_folder_film']);

    Route::get('/open_folder_film_next/{id}', [AdminFilmsController::class, 'open_folder_film_next']);

    Route::get('/open_folder_film_short/{id}', [AdminFilmsController::class, 'open_folder_film_short']);

    Route::get('/open_folder_film_thumbnail/{id}', [AdminFilmsController::class, 'open_folder_film_thumbnail']);

    Route::post('/edit_films_save', [AdminFilmsController::class, 'edit_films_save']);

    Route::post('/edit_films_trailer_save', [AdminFilmsController::class, 'edit_films_trailer_save']);

    Route::post('/edit_films_thumbnail_save', [AdminFilmsController::class, 'edit_films_thumbnail_save']);

    Route::post('/edit_films_add_tag', [AdminFilmsController::class, 'edit_films_add_tag']);




    // ====================================================== DELETE NEW TAGS, STARS, STUDIOS IN EDIT BLADE ========================================== //

    Route::post('/searchfilms_admin', [AdminFilmsController::class, 'searchfilms_admin']);

    Route::post('/edit_films_ajax_delete_tag', [AdminFilmsController::class, 'edit_films_ajax_delete_tag']);   

    Route::post('/edit_films_ajax_delete_star', [AdminFilmsController::class, 'edit_films_ajax_delete_star']);

    Route::post('/edit_films_ajax_delete_studio', [AdminFilmsController::class, 'edit_films_ajax_delete_studio']);


    // ===================================================================== END =============================================================== //



    


    // ====================================================== SEARCH NEW TAGS, STARS, STUDIOS IN ADD FILMS ========================================== //

    Route::get('/gettag', [AjaxTagController::class, 'gettag']); // add new films search tag

    Route::get('/gettag_stars', [AjaxTagController::class, 'gettag_stars']); // add new films search stars

    Route::get('/gettag_studios', [AjaxTagController::class, 'gettag_studios']); // add new films search studios
    
    Route::get('/gettag_sites', [AjaxTagController::class, 'gettag_sites']); // search tag

    Route::get('/getstar', [AjaxTagController::class, 'getstar']); // search stars   

    Route::get('/getstudios', [AjaxTagController::class, 'getstudios']); // search studios


        // ===================================================== SEARCH NEW TAGS, STARS, STUDIOS IN EDIT BLADE ============================================ //

            Route::get('/gettagg', [AjaxTagController::class, 'gettagg']); // search tag  

            Route::get('/getstarr', [AjaxTagController::class, 'getstarr']); // search stars     

            Route::get('/getstudioss', [AjaxTagController::class, 'getstudioss']); // search studios   
        // ===================================================================== END =============================================================== //



    Route::post('/searchtag', [AjaxTagController::class, 'searchtag']); // tags blade

    Route::post('/searchtag_tags_stars_db_film', [TagsController::class, 'searchtag_tags_stars_db_film']); // tags blade

    Route::post('/searchtag_tags_studios_db_film', [TagsController::class, 'searchtag_tags_studios_db_film']); // tags blade

    Route::post('/searchtag_stars', [AjaxTagController::class, 'searchtag_stars']); // tags stars blade

    Route::post('/searchtag_studios', [AjaxTagController::class, 'searchtag_studios']); // tags sars blade

    Route::post('/searchstar', [AjaxTagController::class, 'searchstar']); // stars blade

    Route::post('/searchstudios', [AjaxTagController::class, 'searchstudios']); // studios blade
    // ===================================================================== END =============================================================== //





// ===================================================================== END FILMS =============================================================== //







// ================================================================= TAGS ============================================================== //

    Route::get('/admin_tags', [AdminTagsController::class, 'tags']); 

    Route::get('/admin_tags_stars_db_film', [AdminTagsController::class, 'admin_tags_stars_db_film']); 

    Route::get('/admin_tags_studios_db_film', [AdminTagsController::class, 'admin_tags_studios_db_film']); 

    Route::get('/admin_tags_sites_db_film', [AdminTagsController::class, 'admin_tags_sites_db_film']); 

    // ============================================================== FILTR FOR TAGS SITE ===================================================== //

        Route::get('/open_main_folder_tags', [AdminTagsController::class, 'open_main_folder_tags']);

        Route::get('/tags_id_asc_admin', [AdminTagsController::class, 'tags_id_asc']);

        Route::get('/tags_name_asc_admin', [AdminTagsController::class, 'tags_name_asc']);

        Route::get('/tags_name_desc_admin', [AdminTagsController::class, 'tags_name_desc']);

    // ================================================================= END ============================================================== //


    // ============================================================== FILTR FOR TAGS FILMS USE IN STARS SORT ===================================================== //

        Route::get('/tags_id_asc_admin_db_films_stars', [AdminTagsController::class, 'tags_id_asc_db_films_stars']);
    
        Route::get('/tags_name_asc_admin_db_films_stars', [AdminTagsController::class, 'tags_name_asc_db_films_stars']);
    
        Route::get('/tags_name_desc_admin_db_films_stars', [AdminTagsController::class, 'tags_name_desc_db_films_stars']);
    
    // ================================================================= END ============================================================== //
        
    // ==============================================================  FILTR FOR TAGS FILMS USE IN STUDIOS SORT  ===================================================== //

        Route::get('/tags_id_asc_admin_db_films_studios', [AdminTagsController::class, 'tags_id_asc_db_films_studios']);

        Route::get('/tags_name_asc_admin_db_films_studios', [AdminTagsController::class, 'tags_name_asc_db_films_studios']);

        Route::get('/tags_name_desc_admin_db_films_studios', [AdminTagsController::class, 'tags_name_desc_db_films_studios']);

    // ================================================================= END ============================================================== //


    // ==============================================================  FILTR FOR TAGS FILMS USE IN SITES SORT ===================================================== //

        Route::get('/tags_id_asc_admin_db_films_sites', [AdminTagsController::class, 'tags_id_asc_db_films_sites']);
    
        Route::get('/tags_name_asc_admin_db_films_sites', [AdminTagsController::class, 'tags_name_asc_db_films_sites']);
    
        Route::get('/tags_name_desc_admin_db_films_sites', [AdminTagsController::class, 'tags_name_desc_db_films_sites']);
    
    // ================================================================= END ============================================================== //

    Route::get('/add_tags', [AdminTagsController::class, 'add_tags']);

    Route::post('/save_tags', [AdminTagsController::class, 'save_tags']);

    Route::get('/edit_tags/{id}', [AdminTagsController::class, 'edit_tags']);

    Route::get('/open_folder_tags/{id}', [AdminTagsController::class, 'open_folder_tags']);

    Route::get('/open_folder_tags_next/{id}', [AdminTagsController::class, 'open_folder_tags_next']);

    Route::post('/edit_tags_save', [AdminTagsController::class, 'edit_tags_save']);

    Route::get('/delete_tags/{id}', [AdminTagsController::class, 'delete_tags']);

    Route::get('/delete_all_tags', [AdminTagsController::class, 'delete_all_tags']);

    Route::post('/searchtag_admin', [AdminTagsController::class, 'searchtag_admin']);

    Route::post('/searchtag_admin_tags_stars_db_film', [AdminTagsController::class, 'searchtag_admin_tags_stars_db_film']);

    Route::post('/searchtag_admin_tags_studios_db_film', [AdminTagsController::class, 'searchtag_admin_tags_studios_db_film']);

    Route::post('/searchtag_admin_tags_sites_db_film', [AdminTagsController::class, 'searchtag_admin_tags_sites_db_film']);

   

// ===================================================================== END =============================================================== //




// ================================================================= STARS ============================================================== //

Route::get('/admin_stars', [AdminStarsController::class, 'stars']); 
    // ============================================================== FILTR FOR TAGS SITE ===================================================== //

    Route::get('/open_main_folder_stars', [AdminStarsController::class, 'open_main_folder_stars']);
    
    Route::get('/stars_id_asc_admin', [AdminStarsController::class, 'stars_id_asc']);

    Route::get('/stars_name_asc_admin', [AdminStarsController::class, 'stars_name_asc']);

    Route::get('/stars_gender_male_admin', [AdminStarsController::class, 'stars_gender_male']);

    Route::get('/stars_gender_female_admin', [AdminStarsController::class, 'stars_gender_female']);

    Route::get('/stars_name_desc_admin', [AdminStarsController::class, 'stars_name_desc']);

    Route::get('/stars_rating_asc_admin', [AdminStarsController::class, 'stars_rating_asc']);

    Route::get('/stars_rating_desc_admin', [AdminStarsController::class, 'stars_rating_desc']);

    // ================================================================= END ============================================================== //

    Route::get('/add_stars', [AdminStarsController::class, 'add_stars']);

    Route::post('/save_stars', [AdminStarsController::class, 'save_stars']);

    Route::get('/edit_stars/{id}', [AdminStarsController::class, 'edit_stars']);

    Route::get('/open_folder_stars/{id}', [AdminStarsController::class, 'open_folder_stars']);

    Route::get('/open_folder_stars_next/{id}', [AdminStarsController::class, 'open_folder_stars_next']);

    Route::post('/edit_stars_save', [AdminStarsController::class, 'edit_stars_save']);

    Route::get('/delete_stars/{id}', [AdminStarsController::class, 'delete_stars']);

    Route::get('/delete_all_stars', [AdminStarsController::class, 'delete_all_stars']);

    Route::post('/searchstar_admin', [AdminStarsController::class, 'searchstar_admin']);

   

// ===================================================================== END =============================================================== //




// ================================================================= TAGS STARS ============================================================== //

Route::get('/admin_tags_stars', [AdminTagsStarsController::class, 'tags_stars']);
 // ============================================================== FILTR FOR TAGS SITE ===================================================== //

 Route::get('/open_main_folder_tags_stars', [AdminTagsStarsController::class, 'open_main_folder_tags_stars']);

 Route::get('/tags_id_asc_admin_stars_stars', [AdminTagsStarsController::class, 'tags_id_asc_stars']);

 Route::get('/tags_name_asc_admin_stars', [AdminTagsStarsController::class, 'tags_name_asc_stars']);

 Route::get('/tags_name_desc_admin_stars', [AdminTagsStarsController::class, 'tags_name_desc_stars']);

 // ================================================================= END ============================================================== //

 Route::get('/add_tags_stars', [AdminTagsStarsController::class, 'add_tags_stars']);

 Route::post('/save_tags_stars', [AdminTagsStarsController::class, 'save_tags_stars']);

 Route::get('/edit_tags_stars/{id}', [AdminTagsStarsController::class, 'edit_tags_stars']);

 Route::get('/open_folder_tags_stars/{id}', [AdminTagsStarsController::class, 'open_folder_tags_stars']);

 Route::get('/open_folder_tags_next_stars/{id}', [AdminTagsStarsController::class, 'open_folder_tags_next_stars']);

 Route::post('/edit_tags_stars_save', [AdminTagsStarsController::class, 'edit_tags_stars_save']);

 Route::get('/delete_tags_stars/{id}', [AdminTagsStarsController::class, 'delete_tags_stars']);

 Route::get('/delete_all_tags_stars', [AdminTagsStarsController::class, 'delete_all_tags_stars']);

 Route::post('/searchtag_stars_admin', [AdminTagsStarsController::class, 'searchtag_stars_admin']);
 
 Route::get('/delete_files_from_admin_search_tags_stars/{id}/', [AdminTagsStarsController::class, 'delete_files_from_admin_search_tags_stars']);

 Route::get('/delete_files_from_admin_search_tags_stars_save/{id}/', [AdminTagsStarsController::class, 'delete_files_from_admin_search_tags_stars_save']); // delte
  


 Route::get('/select_categories_stars/{id}', [AdminTagsStarsController::class, 'select_categories_stars']);
 // ================================================== FILTR UP ===================================================== //
 Route::get('/select_categories_stars_desc/{id}', [AdminTagsStarsController::class, 'select_categories_stars_desc']);

 Route::get('/select_categories_stars_date_asc/{id}', [AdminTagsStarsController::class, 'select_categories_stars_date_asc']);

 Route::get('/select_categories_stars_date_desc/{id}', [AdminTagsStarsController::class, 'select_categories_stars_date_desc']);

 Route::get('/select_categories_stars_rating_asc/{id}', [AdminTagsStarsController::class, 'select_categories_stars_rating_asc']);

 Route::get('/select_categories_stars_rating_desc/{id}', [AdminTagsStarsController::class, 'select_categories_stars_rating_desc']);

 Route::get('/select_categories_stars_random/{id}', [AdminTagsStarsController::class, 'select_categories_stars_random']);


 // ==================================================== END =========================================================//



 Route::get('/select_categories_stars_db_films/{id}', [AdminTagsStarsController::class, 'select_categories_stars_db_films']);
 
 Route::post('/edit_films_ajax_delete_tag_stars', [AdminTagsStarsController::class, 'edit_films_ajax_delete_tag_stars']);

 Route::post('/edit_films_ajax_delete_tag_stars_films', [AdminTagsStarsController::class, 'edit_films_ajax_delete_tag_stars_films']);
 
 Route::post('/stars_tag_add_edit_site', [AdminTagsStarsController::class, 'stars_tag_add_edit_site']);

// ===================================================================== END =============================================================== //









// ================================================================= STUDIOS ============================================================== //

Route::get('/admin_studios', [AdminStudiosController::class, 'studios']); 
    // ============================================================== FILTR FOR TAGS SITE ===================================================== //

    Route::get('/open_main_folder_studios', [AdminStudiosController::class, 'open_main_folder_studios']);

    Route::get('/studios_id_asc_admin', [AdminStudiosController::class, 'studios_id_asc']);

    Route::get('/studios_name_asc_admin', [AdminStudiosController::class, 'studios_name_asc']);

    Route::get('/studios_name_desc_admin', [AdminStudiosController::class, 'studios_name_desc']);

    Route::get('/studios_rating_asc_admin', [AdminStudiosController::class, 'studios_rating_asc']);

    Route::get('/studios_rating_desc_admin', [AdminStudiosController::class, 'studios_rating_desc']);

    // ================================================================= END ============================================================== //

    Route::get('/add_studios', [AdminStudiosController::class, 'add_studios']);

    Route::post('/save_studios', [AdminStudiosController::class, 'save_studios']);

    Route::get('/edit_studios/{id}', [AdminStudiosController::class, 'edit_studios']);
    
    Route::get('/open_folder_studios/{id}', [AdminStudiosController::class, 'open_folder_studios']);
    
    Route::get('/open_folder_studios_next/{id}', [AdminStudiosController::class, 'open_folder_studios_next']);

    Route::post('/edit_studios_save', [AdminStudiosController::class, 'edit_studios_save']);

    Route::get('/delete_studios/{id}', [AdminStudiosController::class, 'delete_studios']);

    Route::get('/delete_all_studios', [AdminStudiosController::class, 'delete_all_studios']);

    Route::post('/searchstudios_admin', [AdminStudiosController::class, 'searchstudios_admin']);

   

// ===================================================================== END =============================================================== //




// ================================================================= TAGS Studios ============================================================== //
Route::get('/admin_tags_studios', [AdminTagsStudiosController::class, 'tags_studios']);
 // ============================================================== FILTR FOR TAGS SITE ===================================================== //

 Route::get('/open_main_folder_tags_studios', [AdminTagsStudiosController::class, 'open_main_folder_tags_studios']);

 Route::get('/tags_id_asc_admin_studios_studios', [AdminTagsStudiosController::class, 'tags_id_asc_studios']);

 Route::get('/tags_name_asc_admin_studios', [AdminTagsStudiosController::class, 'tags_name_asc_studios']);

 Route::get('/tags_name_desc_admin_studios', [AdminTagsStudiosController::class, 'tags_name_desc_studios']);

 // ================================================================= END ============================================================== //

 Route::get('/add_tags_studios', [AdminTagsStudiosController::class, 'add_tags_studios']);

 Route::post('/save_tags_studios', [AdminTagsStudiosController::class, 'save_tags_studios']);

 Route::get('/edit_tags_studios/{id}', [AdminTagsStudiosController::class, 'edit_tags_studios']);

 Route::get('/open_folder_tags_studios/{id}', [AdminTagsStudiosController::class, 'open_folder_tags_studios']);

 Route::get('/open_folder_tags_next_studios/{id}', [AdminTagsStudiosController::class, 'open_folder_tags_next_studios']);

 Route::post('/edit_tags_studios_save', [AdminTagsStudiosController::class, 'edit_tags_studios_save']);

 Route::get('/delete_tags_studios/{id}', [AdminTagsStudiosController::class, 'delete_tags_studios']);

 Route::get('/delete_all_tags_studios', [AdminTagsStudiosController::class, 'delete_all_tags_studios']);

 Route::post('/searchtag_studios_admin', [AdminTagsStudiosController::class, 'searchtag_studios_admin']);
 
 Route::get('/delete_files_from_admin_search_tags_studios/{id}/', [AdminTagsStudiosController::class, 'delete_files_from_admin_search_tags_studios']);

 Route::get('/delete_files_from_admin_search_tags_studios_save/{id}/', [AdminTagsStudiosController::class, 'delete_files_from_admin_search_tags_studios_save']); // delte
  


 Route::get('/select_categories_studios/{id}', [AdminTagsStudiosController::class, 'select_categories_studios']);

  // ================================================== FILTR UP ===================================================== //
  Route::get('/select_categories_studios_desc/{id}', [AdminTagsStudiosController::class, 'select_categories_studios_desc']);

  Route::get('/select_categories_studios_date_asc/{id}', [AdminTagsStudiosController::class, 'select_categories_studios_date_asc']);
 
  Route::get('/select_categories_studios_date_desc/{id}', [AdminTagsStudiosController::class, 'select_categories_studios_date_desc']);
 
  Route::get('/select_categories_studios_rating_asc/{id}', [AdminTagsStudiosController::class, 'select_categories_studios_rating_asc']);
 
  Route::get('/select_categories_studios_rating_desc/{id}', [AdminTagsStudiosController::class, 'select_categories_studios_rating_desc']);
 
  Route::get('/select_categories_studios_random/{id}', [AdminTagsStudiosController::class, 'select_categories_studios_random']);

  // ==================================================== END =========================================================//





 Route::get('/select_categories_studios_db_films/{id}', [AdminTagsStudiosController::class, 'select_categories_studios_db_films']);
 
 Route::post('/edit_films_ajax_delete_tag_studios', [AdminTagsStudiosController::class, 'edit_films_ajax_delete_tag_studios']);
 
 Route::post('/studios_tag_add_edit_site', [AdminTagsStudiosController::class, 'studios_tag_add_edit_site']);
// ===================================================================== END =============================================================== //





// ================================================================= SITE ============================================================== //

Route::get('/admin_sites', [AdminSitesController::class, 'site']); 
    // ============================================================== FILTR FOR TAGS SITE ===================================================== //

    Route::get('/site_id_asc', [AdminSitesController::class, 'site_id_asc']);

    Route::get('/site_name_asc', [AdminSitesController::class, 'site_name_asc']);

    Route::get('/site_name_desc', [AdminSitesController::class, 'site_name_desc']);

    Route::get('/site_description_asc', [AdminSitesController::class, 'site_description_asc']);

    Route::get('/site_description_desc', [AdminSitesController::class, 'site_description_desc']);

    Route::get('/site_rating_asc', [AdminSitesController::class, 'site_rating_asc']);

    Route::get('/site_rating_desc', [AdminSitesController::class, 'site_rating_desc']);

    // ================================================================= END ============================================================== //

    Route::get('/add_site', [AdminSitesController::class, 'add_site']);

    Route::post('/save_site', [AdminSitesController::class, 'save_site']);

    Route::get('/edit_site/{id}', [AdminSitesController::class, 'edit_site']);

    Route::post('/edit_site_save', [AdminSitesController::class, 'edit_site_save']);

    Route::get('/delete_site/{id}', [AdminSitesController::class, 'delete_site']);

    Route::get('/delete_all_site', [AdminSitesController::class, 'delete_all_site']);

    Route::post('/searchsite_admin', [AdminSitesController::class, 'searchsite_admin']);

   

// ===================================================================== END =============================================================== //







// ================================================================= TAGS Sites ============================================================== //
Route::get('/admin_tags_sites', [AdminTagsSitesController::class, 'tags_sites']);
 // ============================================================== FILTR FOR TAGS SITE ===================================================== //

 Route::get('/open_main_folder_tags_sites', [AdminTagsSitesController::class, 'open_main_folder_tags_sites']);

 Route::get('/tags_id_asc_admin_sites_sites', [AdminTagsSitesController::class, 'tags_id_asc_sites']);

 Route::get('/tags_name_asc_admin_sites', [AdminTagsSitesController::class, 'tags_name_asc_sites']);

 Route::get('/tags_name_desc_admin_sites', [AdminTagsSitesController::class, 'tags_name_desc_sites']);

 // ================================================================= END ============================================================== //

 Route::get('/add_tags_sites', [AdminTagsSitesController::class, 'add_tags_sites']);

 Route::post('/save_tags_sites', [AdminTagsSitesController::class, 'save_tags_sites']);

 Route::get('/edit_tags_sites/{id}', [AdminTagsSitesController::class, 'edit_tags_sites']);

 Route::get('/open_folder_tags_sites/{id}', [AdminTagsSitesController::class, 'open_folder_tags_sites']);

 Route::get('/open_folder_tags_next_sites/{id}', [AdminTagsSitesController::class, 'open_folder_tags_next_sites']);

 Route::post('/edit_tags_sites_save', [AdminTagsSitesController::class, 'edit_tags_sites_save']);

 Route::get('/delete_tags_sites/{id}', [AdminTagsSitesController::class, 'delete_tags_sites']);

 Route::get('/delete_all_tags_sites', [AdminTagsSitesController::class, 'delete_all_tags_sites']);

 Route::post('/searchtag_sites_admin', [AdminTagsSitesController::class, 'searchtag_sites_admin']);
 
 Route::get('/delete_files_from_admin_search_tags_sites/{id}/', [AdminTagsSitesController::class, 'delete_files_from_admin_search_tags_sites']);

 Route::get('/delete_files_from_admin_search_tags_sites_save/{id}/', [AdminTagsSitesController::class, 'delete_files_from_admin_search_tags_sites_save']); // delte
  

 
 Route::get('/select_categories_sites/{id}', [AdminTagsSitesController::class, 'select_categories_sites']);
 // ===================================================================== FILTRS =============================================================== //
 Route::get('/select_categories_sites_date_asc/{id}', [AdminTagsSitesController::class, 'select_categories_sites_date_asc']);

 Route::get('/select_categories_sites_name_asc/{id}', [AdminTagsSitesController::class, 'select_categories_sites_name_asc']);

 Route::get('/select_categories_sites_name_desc/{id}', [AdminTagsSitesController::class, 'select_categories_sites_name_desc']);

 Route::get('/select_categories_sites_rating_asc/{id}', [AdminTagsSitesController::class, 'select_categories_sites_rating_asc']);

 Route::get('/select_categories_sites_rating_desc/{id}', [AdminTagsSitesController::class, 'select_categories_sites_rating_desc']);

 Route::get('/select_categories_sites_random/{id}', [AdminTagsSitesController::class, 'select_categories_sites_random']);
 // ===================================================================== END =============================================================== //


 Route::get('/select_categories_sites_db_films/{id}', [AdminTagsSitesController::class, 'select_categories_sites_db_films']);

 Route::post('/edit_films_ajax_delete_tag_sites', [AdminTagsSitesController::class, 'edit_films_ajax_delete_tag_sites']);
 
 Route::post('/sites_tag_add_edit_site', [AdminTagsSitesController::class, 'sites_tag_add_edit_site']);
// ===================================================================== END =============================================================== //









// ================================================================= video - VideoController ============================================================== //


    Route::get('/cut_films', [AdminVideoController::class, 'cut']);

    Route::post('/cut_save', [AdminVideoController::class, 'cut_save']);

    Route::get('/join_films', [AdminVideoController::class, 'join']);

    Route::get('/open_main_folder_cut', [AdminVideoController::class, 'open_main_folder_cut']);

    Route::get('/open_main_folder_join', [AdminVideoController::class, 'open_main_folder_join']);

    Route::post('/join_save', [AdminVideoController::class, 'join_save']);

    


    Route::get('/cut_image', [AdminVideoController::class, 'cut_image']);

    Route::post('/cut_image_save', [AdminVideoController::class, 'cut_image_save']);


    Route::get('/simply_conversion', [AdminVideoController::class, 'simply_conversion']);

    Route::get('/open_main_folder_conversion', [AdminVideoController::class, 'open_main_folder_conversion']);

    Route::post('/simply_convert_save', [AdminVideoController::class, 'simply_convert_save']);


    Route::get('/conversion', [AdminVideoController::class, 'conversion']);

    Route::post('/convert_save', [AdminVideoController::class, 'convert_save']);

   

// ===================================================================== END =============================================================== //











Auth::routes();
Route::get('/home', [FilmsController::class, 'index']);


