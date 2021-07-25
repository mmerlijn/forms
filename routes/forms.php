<?php
use Illuminate\Support\Facades\Route;


Route::get('/forms',function(){
    return "Hallo world!";
})->name("forms.index")->middleware(['web','auth']);

Route::prefix('forms')->middleware(['web','auth'])->group(function(){ //
    Route::get('/{id}/{step}/view/', [\mmerlijn\forms\Http\Controllers\FormController::class, 'view'])->name('forms.view');
    Route::get('{test}/create/{source?}/{id?}', [\mmerlijn\forms\Http\Controllers\FormController::class, 'create'])->name('forms.create');
    Route::get('/{step}/edit/{id}', [\mmerlijn\forms\Http\Controllers\FormController::class, 'edit'])->name('forms.edit');

    Route::get('/overzicht/{test?}/{step?}', [\mmerlijn\forms\Http\Controllers\FormController::class, 'overzicht'])->name('forms.overzicht');
    Route::get('/docs/{test?}', [\mmerlijn\forms\Http\Controllers\FormController::class, 'docs'])->name('forms.docs');
});

/*

//Route::get('/fundusfoto/{file}', [FundusFotoController::class, 'serve'])->name('fundusfoto');

*/