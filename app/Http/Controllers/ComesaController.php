<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        ]);
    }

    /**
     * Récupérer la configuration COMESA (JSON local)
     */
    public function getConfig()
    {
        $path = storage_path('app/comesa/config.json');
        if (!file_exists($path)) {
            return response()->json(['success' => true, 'config' => []]);
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true) ?: [];

        return response()->json(['success' => true, 'config' => $data]);
    }

    /**
     * Sauvegarder la configuration COMESA (JSON local)
     */
    public function saveConfig(Request $request)
    {
        // support JSON body or form-encoded
        $data = $request->json()->all();
        if (empty($data)) {
            $data = $request->all();
        }

        $dir = storage_path('app/comesa');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/config.json';
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json(['success' => true, 'message' => 'Configuration sauvegardée', 'config' => $data]);
    }

    /**
     * Récupérer les taux de change locaux
     */
    public function getRates()
    {
        $path = storage_path('app/comesa/rates.json');
        if (!file_exists($path)) {
            return response()->json(['success' => true, 'rates' => []]);
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true) ?: [];

        return response()->json(['success' => true, 'rates' => $data]);
    }

    /**
     * Sauvegarder les taux de change localement
     */
    public function saveRates(Request $request)
    {
        $data = $request->json()->all();
        if (empty($data)) {
            $data = $request->all();
        }

        $dir = storage_path('app/comesa');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/rates.json';
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json(['success' => true, 'message' => 'Taux sauvegardés']);
    }

    /**
     * Récupérer les règles régionales stockées localement
     */
    public function getRules()
    {
        $path = storage_path('app/comesa/rules.json');
        if (!file_exists($path)) {
            return response()->json(['success' => true, 'rules' => null]);
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        return response()->json(['success' => true, 'rules' => $data]);
    }

    /**
     * Sauvegarder les règles régionales localement
     */
    public function saveRules(Request $request)
    {
        // accept JSON payload or form
        $data = $request->json()->all();
        if (empty($data)) {
            $all = $request->all();
            if (isset($all['rules'])) {
                $raw = $all['rules'];
                $decoded = json_decode($raw, true);
                $data = $decoded === null ? $raw : $decoded;
            } else {
                $data = $all;
            }
        }

        $dir = storage_path('app/comesa');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/rules.json';
        try {
            if (is_array($data) || is_object($data)) {
                file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            } else {
                file_put_contents($path, (string) $data);
            }
        } catch (\Exception $e) {
            Log::error('Failed to save COMESA rules: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la sauvegarde des règles'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Règles sauvegardées', 'rules' => $data]);
    }
}
