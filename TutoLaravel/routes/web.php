<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// parametre par defaut
Route::get('/', function () {
    return view('Welcome');
});
/*
si on veut regrouper plusieurs 'Route' qui ont le 'prefixe' on peut les entourer de la methode suivante, 
en precisaant leur prefixe commun, ici /blog : 
Route::prefix('/blog')->group(function (){....}
Il faudra supprimer 'blog dans les différentes Route : Route::get('/{slug}-{'id'}')....
car il est commun à toutes les Routes qu on a regroupé.
cela permet de pouvoir changer le prefixe 'blog'une seule fois si besoin. 
->name('blog.') permet de dire que toutes ses routes auront le meme nom de début blog. 
pour eviter de le preciser partout dans le name()
*/
Route::prefix('/blog')->name('blog.')->group(function (){


//ici j applique une methode '->name' sur la Route pour la nommer, cela permet de retrouver les routes meme si on change la structure de l url
Route::get('/', function (Request $request) {
    $post = new \App\Models\Post;//chemin du modele post (nom de la table posts au singulier), créé en ligne de commande 'artisan make:model Post' : créé le fichier dans app\model\Post.php
    $post->title = 'mon 1er article';
    $post->slug = 'mon-premier-article';
    $post->content = 'Mon contenu ...bla...bla.';
    $post->save(); //ceci est une methode dispo dans tous les modeles de bd qu on créé qui sauvegarde en bd
    return $post;
    return [ 
        // à la place de ceci, "link" => '/blog/mon-article-13', on va applique une methode 'route'sur le link
        "link" => \route('blog.show', ['slug'=>'article', 'id'=>13]),
    ];
})->name('index');

    Route::get('/{slug}-{id}', function (string $slug, string $id, Request $request){
        return [
            "slug" => $slug,
            "id" => $id,
            "name" => $request->input('name'),
        ];
    
    })->where([
        'id' => '[0-9]+',
        'slug' => '[a-z0-9\-]+'
    ])->name('show');
    
});



//fonction pour recupérer des parametres (nom et article et l age)
// quand j ai cela dans mon url : http://localhost/LaravelProjet/TutoLaravel/public/blog?name=john&age=12  j affiche les éléments ci dessous
/*Route::get('/blog', function(Request $request){
    return [ 
        "name" => $request ->input('name', 'valeur par defaut'),
        "age" => $request ->input('age', '0'),
        "article" => "Article 1"
    ];
});*/

/*
Quand le met dans l url : http://localhost/LaravelProjet/TutoLaravel/public/blog/mon-premier-article-12?name=john
apres le get, on met entre parenthèse le chemin de l url que cette fonction concerne: '/blog...' 
puis les parametres à afficher dans l url ici le slug et l'id, avec le request on recup le name en input, cela permet de tout regrouper
puis on return les elements à afficher 
Dans le where on specifie ce qu est l'id, un number et ce qu est le slug avec les expression reguliere qui vont bien (le plus c est pour repeter l action) */


// ***pour voir toutes les routes : taper ceci dans le terminal :
//PS C:\laragon\www\LaravelProjet\TutoLaravel> php artisan route:list

