<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Display a listing of backups.
     */
    public function index()
    {
        $backups = $this->getBackups();
        
        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Create a new backup.
     */
    public function create(Request $request)
    {
        try {
            $backupName = 'backup_' . date('Y-m-d_H-i-s');
            $backupPath = storage_path('app/backups');
            
            // Create backups directory if it doesn't exist
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $zipFile = $backupPath . '/' . $backupName . '.zip';
            $zip = new ZipArchive();
            
            if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Backup database
                $this->backupDatabase($zip, $backupName);
                
                // Backup important files
                $this->backupFiles($zip);
                
                $zip->close();
                
                // Log activity
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'create_backup',
                    'description' => 'Création d\'une sauvegarde : ' . $backupName,
                    'properties' => json_encode([
                        'backup_name' => $backupName,
                        'size' => filesize($zipFile),
                    ]),
                    'ip_address' => $request->ip(),
                ]);
                
                return redirect()->route('admin.backups.index')
                    ->with('success', 'Sauvegarde créée avec succès.');
            } else {
                throw new \Exception('Impossible de créer le fichier ZIP');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Erreur lors de la création de la sauvegarde : ' . $e->getMessage());
        }
    }

    /**
     * Download a backup.
     */
    public function download($backup)
    {
        $backupPath = storage_path('app/backups/' . $backup);
        
        if (!file_exists($backupPath)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Sauvegarde introuvable.');
        }
        
        return response()->download($backupPath);
    }

    /**
     * Delete a backup.
     */
    public function destroy(Request $request, $backup)
    {
        $backupPath = storage_path('app/backups/' . $backup);
        
        if (!file_exists($backupPath)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Sauvegarde introuvable.');
        }
        
        unlink($backupPath);
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_backup',
            'description' => 'Suppression de la sauvegarde : ' . $backup,
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->route('admin.backups.index')
            ->with('success', 'Sauvegarde supprimée avec succès.');
    }

    /**
     * Restore a backup.
     */
    public function restore(Request $request, $backup)
    {
        try {
            $backupPath = storage_path('app/backups/' . $backup);
            
            if (!file_exists($backupPath)) {
                return redirect()->route('admin.backups.index')
                    ->with('error', 'Sauvegarde introuvable.');
            }
            
            $zip = new ZipArchive();
            
            if ($zip->open($backupPath) === true) {
                $extractPath = storage_path('app/backups/temp_restore');
                
                // Create temp directory
                if (!file_exists($extractPath)) {
                    mkdir($extractPath, 0755, true);
                }
                
                $zip->extractTo($extractPath);
                $zip->close();
                
                // Restore database
                $sqlFile = $extractPath . '/database.sql';
                if (file_exists($sqlFile)) {
                    $this->restoreDatabase($sqlFile);
                }
                
                // Clean up temp directory
                $this->deleteDirectory($extractPath);
                
                // Log activity
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'restore_backup',
                    'description' => 'Restauration de la sauvegarde : ' . $backup,
                    'ip_address' => $request->ip(),
                ]);
                
                return redirect()->route('admin.backups.index')
                    ->with('success', 'Sauvegarde restaurée avec succès.');
            } else {
                throw new \Exception('Impossible d\'ouvrir le fichier ZIP');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Erreur lors de la restauration : ' . $e->getMessage());
        }
    }

    /**
     * Get list of backups.
     */
    private function getBackups()
    {
        $backupPath = storage_path('app/backups');
        
        if (!file_exists($backupPath)) {
            return [];
        }
        
        $files = scandir($backupPath);
        $backups = [];
        
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $filePath = $backupPath . '/' . $file;
                $backups[] = [
                    'name' => $file,
                    'size' => filesize($filePath),
                    'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                ];
            }
        }
        
        // Sort by date descending
        usort($backups, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $backups;
    }

    /**
     * Backup database to ZIP.
     */
    private function backupDatabase($zip, $backupName)
    {
        $databasePath = database_path('database.sqlite');
        
        if (file_exists($databasePath)) {
            // For SQLite, just copy the database file
            $zip->addFile($databasePath, 'database.sqlite');
            
            // Also create a SQL dump
            $sqlDump = $this->createSqlDump();
            $zip->addFromString('database.sql', $sqlDump);
        }
    }

    /**
     * Create SQL dump.
     */
    private function createSqlDump()
    {
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $dump = "-- Database Backup\n";
        $dump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($tables as $table) {
            $tableName = $table->name;
            
            // Get table schema
            $schema = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name=?", [$tableName]);
            if (!empty($schema)) {
                $dump .= "\n-- Table: {$tableName}\n";
                $dump .= "DROP TABLE IF EXISTS {$tableName};\n";
                $dump .= $schema[0]->sql . ";\n\n";
            }
            
            // Get table data
            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $values = array_map(function ($value) {
                    return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                }, (array) $row);
                
                $dump .= "INSERT INTO {$tableName} VALUES (" . implode(', ', $values) . ");\n";
            }
        }
        
        return $dump;
    }

    /**
     * Backup important files.
     */
    private function backupFiles($zip)
    {
        // Backup .env file
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $zip->addFile($envPath, '.env');
        }
        
        // Backup storage/app/public (uploaded files)
        $publicPath = storage_path('app/public');
        if (file_exists($publicPath)) {
            $this->addDirectoryToZip($zip, $publicPath, 'storage/public');
        }
    }

    /**
     * Add directory to ZIP recursively.
     */
    private function addDirectoryToZip($zip, $directory, $zipPath)
    {
        if (!is_dir($directory)) {
            return;
        }
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipPath . '/' . substr($filePath, strlen($directory) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    /**
     * Restore database from SQL file.
     */
    private function restoreDatabase($sqlFile)
    {
        $sql = file_get_contents($sqlFile);
        
        // Split SQL into individual statements
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function ($statement) {
                return !empty($statement) && !str_starts_with($statement, '--');
            }
        );
        
        // Execute each statement
        DB::beginTransaction();
        try {
            foreach ($statements as $statement) {
                DB::statement($statement);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete directory recursively.
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        
        rmdir($dir);
    }
}
