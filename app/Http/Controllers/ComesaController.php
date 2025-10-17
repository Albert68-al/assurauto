<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComesaController extends Controller
{
    /**
     * Afficher la page d'accueil du module COMESA
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('comesa.index', [
            'title' => 'Module COMESA',
            // Ajoutez ici les données nécessaires pour la vue
        ]);
    }

    // Ajoutez ici d'autres méthodes pour le module COMESA selon vos besoins
}
