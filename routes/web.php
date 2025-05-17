<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ExplorerController;
//use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\ApiController;
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

Route::get('/',[ExplorerController::class,'index'])->name('home');
Route::get('/VerifiedWallets',[ExplorerController::class,'VerifiedWallets'])->name('VerifiedWallets');
Route::get('/tx/{tx}',[ExplorerController::class,'Transactions'])->name('tx');
Route::get('/block/{hash}',[ExplorerController::class,'Blocks'])->name('block');
Route::get('/wallet/{wallet}',[ExplorerController::class,'Wallet'])->name('wallet');
Route::get('/PriceCheck',[ExplorerController::class,'PriceCheck'])->name('PriceCheck');
Route::get('/TPS',[ExplorerController::class,'TPS'])->name('TPS');

Route::get("/search",[ExplorerController::class,'Search'])->name('search');

Route::get("/richlist",[TopController::class,'Index'])->name('richlist');


Route::get('/syncs',[ExplorerController::class,'syncs'])->name('syncs');

Route::get('/UpdateRichlist',[ExplorerController::class,'UpdateRichlist'])->name('UpdateRichlist');
Route::get('/syncNFTS',[ExplorerController::class,'syncNFTS'])->name('syncNFTS');
Route::get('/GetNFTS',[ExplorerController::class,'GetNFTS'])->name('GetNFTS');
Route::get('/NFTStakeTotal',[ExplorerController::class,'NFTStakeTotal'])->name('NFTStakeTotal');

Route::get('/StakeWallet',[ExplorerController::class,'StakeWallet'])->name('StakeWallet');
//Route::get("/migrate",[TransactionController::class,'migrate']);

Route::group(['prefix' => 'expadmin'], function () {
    Voyager::routes();
});

//Route::get("/trans",[TransactionController::class,'index']);

Route::get('/api/wallet/{wallet}',[ApiController::class,'Wallet'])->name('apiWallet');
Route::get('/api/pendingtx',[ApiController::class,'Pendingtx'])->name('apiPendingtx');
Route::get('/api/wallet/{wallet}/page/{page}',[ApiController::class,'Wallet'])->name('apiWallet');


Route::get('/GetWalletTransaction',[ExplorerController::class,'GetWalletTransaction'])->name('GetWalletTransaction');
Route::get('/getNetworkInfo',[ExplorerController::class,'getNetworkInfo'])->name('getNetworkInfo');

Route::group(['middleware' => 'cors'], function () {
    Route::get('api/GetMinebaleAndCirculatingSupplyInfo',[ExplorerController::class,'GetMinebaleAndCirculatingSupplyInfo'])->name('GetMinebaleAndCirculatingSupplyInfo');
    Route::get('api/GetAPICirculatingSupply',[ExplorerController::class,'GetAPICirculatingSupply'])->name('GetAPICirculatingSupply');
    Route::get('api/GetAPITotalSupply',[ExplorerController::class,'GetAPITotalSupply'])->name('GetAPITotalSupply');
    Route::get('/GetMinebaleAndCirculatingSupplyInfo',[ExplorerController::class,'GetMinebaleAndCirculatingSupplyInfo'])->name('GetMinebaleAndCirculatingSupplyInfo');
    Route::get('/GetAPICirculatingSupply',[ExplorerController::class,'GetAPICirculatingSupply'])->name('GetAPICirculatingSupply');
    Route::get('/GetAPITotalSupply',[ExplorerController::class,'GetAPITotalSupply'])->name('GetAPITotalSupply');
});