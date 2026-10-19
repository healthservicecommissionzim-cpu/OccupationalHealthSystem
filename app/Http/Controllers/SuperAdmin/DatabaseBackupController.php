<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DatabaseBackupController extends Controller
{
    private $backupPath = 'backups/database';

    public function index()
    {
        // Get all backup files
        $backups = $this->getBackupFiles();
        
        return view('system.database-backup', compact('backups'));
    }

    public function create(Request $request)
    {
        try {
            // Get database connection type
            $connection = config('database.default');
            $driver = config("database.connections.{$connection}.driver");
            
            // Ensure directory exists with full path
            $fullBackupPath = storage_path('app/' . $this->backupPath);
            if (!file_exists($fullBackupPath)) {
                mkdir($fullBackupPath, 0755, true);
            }
            
            // Also ensure using Storage facade
            if (!Storage::exists($this->backupPath)) {
                Storage::makeDirectory($this->backupPath);
            }
            
            // Handle different database drivers
            if ($driver === 'sqlite') {
                $result = $this->backupSqlite();
            } elseif (in_array($driver, ['mysql', 'mariadb'])) {
                $result = $this->backupMysql();
            } else {
                throw new \Exception("Unsupported database driver: {$driver}");
            }
            
            if ($result['success']) {
                Log::info('Database backup created successfully', [
                    'filename' => $result['filename'],
                    'driver' => $driver,
                    'user' => auth()->user()->email,
                    'size' => filesize($result['filepath'])
                ]);
                
                return back()->with('success', 'Database backup created successfully: ' . $result['filename']);
            } else {
                throw new \Exception($result['error']);
            }
            
        } catch (\Exception $e) {
            Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'user' => auth()->user()->email,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Backup operation failed: ' . $e->getMessage());
        }
    }
    
    private function backupSqlite()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d-His') . '.sqlite';
        $filepath = storage_path('app/' . $this->backupPath . '/' . $filename);
        
        // Get the database file path
        $dbPath = config('database.connections.sqlite.database');
        
        if (!file_exists($dbPath)) {
            return [
                'success' => false,
                'error' => 'SQLite database file not found at: ' . $dbPath
            ];
        }
        
        // Copy the database file
        if (!copy($dbPath, $filepath)) {
            return [
                'success' => false,
                'error' => 'Failed to copy SQLite database file'
            ];
        }
        
        return [
            'success' => true,
            'filename' => $filename,
            'filepath' => $filepath
        ];
    }
    
    private function backupMysql()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d-His') . '.sql';
        $filepath = storage_path('app/' . $this->backupPath . '/' . $filename);
        
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");
        $host = config("database.connections.{$connection}.host", '127.0.0.1');
        $port = config("database.connections.{$connection}.port", '3306');
        
        // Check if mysqldump is available
        $mysqldumpPath = $this->findMysqldump();
        if (!$mysqldumpPath) {
            return [
                'success' => false,
                'error' => 'mysqldump command not found. Please ensure MySQL client tools are installed and in your PATH.'
            ];
        }
        
        // Log what we're attempting
        Log::info('Attempting MySQL backup', [
            'mysqldump_path' => $mysqldumpPath,
            'database' => $database,
            'host' => $host,
            'port' => $port
        ]);
        
        // Build the command - use proc_open for better control on Windows
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["file", $filepath, "w"],
            2 => ["pipe", "w"]
        ];
        
        if (PHP_OS_FAMILY === 'Windows') {
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s --port=%s --single-transaction --quick --lock-tables=false --no-tablespaces %s',
                $mysqldumpPath,
                $username,
                $password,
                $host,
                $port,
                $database
            );
        } else {
            $command = sprintf(
                '%s --user=%s --password=%s --host=%s --port=%s --single-transaction --quick --lock-tables=false --no-tablespaces %s',
                escapeshellarg($mysqldumpPath),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($database)
            );
        }
        
        // Execute using proc_open for better output handling
        $process = proc_open($command, $descriptorspec, $pipes);
        $returnVar = 0;
        $stderrOutput = '';
        
        if (is_resource($process)) {
            if (!empty($pipes[2])) {
                $stderrOutput = stream_get_contents($pipes[2]);
                fclose($pipes[2]);
            }
            $returnVar = proc_close($process);
        } else {
            return [
                'success' => false,
                'error' => 'Failed to execute mysqldump process'
            ];
        }
        
        // Check if backup was successful
        if ($returnVar !== 0 || !file_exists($filepath) || filesize($filepath) === 0) {
            $errorMsg = 'mysqldump failed (exit code: ' . $returnVar . '). ';
            if (!empty($stderrOutput)) {
                $errorMsg .= 'Error: ' . $stderrOutput;
            }
            
            Log::error('MySQL backup failed', [
                'command' => preg_replace('/--password=[^\s]+/', '--password=***', $command),
                'return_code' => $returnVar,
                'stderr' => $stderrOutput,
                'file_exists' => file_exists($filepath),
                'file_size' => file_exists($filepath) ? filesize($filepath) : 0
            ]);
            
            // Clean up empty file if it exists
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            
            return [
                'success' => false,
                'error' => $errorMsg
            ];
        }
        
        return [
            'success' => true,
            'filename' => $filename,
            'filepath' => $filepath
        ];
    }
    
    private function findMysqldump()
    {
        // Common locations for mysqldump on Windows
        $possiblePaths = [
            'mysqldump', // If it's in PATH
            'C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.exe',
            'C:/Program Files/MySQL/MySQL Server 8.4/bin/mysqldump.exe',
            'C:/Program Files/MySQL/MySQL Server 9.0/bin/mysqldump.exe',
            'C:/xampp/mysql/bin/mysqldump.exe',
            'C:/wamp64/bin/mysql/mysql*/bin/mysqldump.exe',
            'C:/Program Files/MySQL/MySQL Server */bin/mysqldump.exe',
            'C:/laragon/bin/mysql/mysql-*/bin/mysqldump.exe',
        ];
        
        foreach ($possiblePaths as $path) {
            // Handle wildcards
            if (strpos($path, '*') !== false) {
                $matches = glob($path);
                if (!empty($matches)) {
                    $path = $matches[0];
                }
            }
            
            // Check if file exists directly first
            if (file_exists($path)) {
                return $path;
            }
            
            // Test if the command exists in PATH
            $output = [];
            $testCommand = PHP_OS_FAMILY === 'Windows' 
                ? "where \"{$path}\" 2>nul"
                : "command -v {$path} 2>/dev/null";
                
            exec($testCommand, $output, $returnVar);
            
            if ($returnVar === 0 && !empty($output)) {
                return trim($output[0]);
            }
        }
        
        return null;
    }

    public function download($filename)
    {
        try {
            $filepath = storage_path('app/' . $this->backupPath . '/' . $filename);
            
            if (!file_exists($filepath)) {
                return back()->with('error', 'Backup file not found.');
            }
            
            Log::info('Database backup downloaded', [
                'filename' => $filename,
                'user' => auth()->user()->email
            ]);
            
            return response()->download($filepath);
            
        } catch (\Exception $e) {
            Log::error('Database backup download failed', [
                'error' => $e->getMessage(),
                'filename' => $filename,
                'user' => auth()->user()->email
            ]);
            
            return back()->with('error', 'Failed to download backup file.');
        }
    }

    public function delete($filename)
    {
        try {
            $filepath = $this->backupPath . '/' . $filename;
            
            if (Storage::exists($filepath)) {
                Storage::delete($filepath);
                
                Log::info('Database backup deleted', [
                    'filename' => $filename,
                    'user' => auth()->user()->email
                ]);
                
                return back()->with('success', 'Backup deleted successfully.');
            }
            
            return back()->with('error', 'Backup file not found.');
            
        } catch (\Exception $e) {
            Log::error('Database backup deletion failed', [
                'error' => $e->getMessage(),
                'filename' => $filename,
                'user' => auth()->user()->email
            ]);
            
            return back()->with('error', 'Failed to delete backup file.');
        }
    }

    private function getBackupFiles()
    {
        $backups = [];
        
        // Check using full path
        $fullBackupPath = storage_path('app/' . $this->backupPath);
        
        if (!file_exists($fullBackupPath)) {
            return $backups;
        }
        
        // Use glob to get all files
        $files = glob($fullBackupPath . '/*.*');
        
        foreach ($files as $filepath) {
            if (file_exists($filepath) && is_file($filepath)) {
                $filename = basename($filepath);
                
                $backups[] = [
                    'filename' => $filename,
                    'size' => $this->formatBytes(filesize($filepath)),
                    'size_bytes' => filesize($filepath),
                    'date' => Carbon::createFromTimestamp(filemtime($filepath))->format('Y-m-d H:i:s'),
                    'timestamp' => filemtime($filepath)
                ];
            }
        }
        
        // Sort by date descending
        usort($backups, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return $backups;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
