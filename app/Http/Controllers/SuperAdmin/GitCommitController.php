<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;

class GitCommitController extends Controller
{
    /**
     * Display the Git Commit management page
     */
    public function index()
    {
        return view('super-admin.git-commit.index', [
            'pageTitle' => 'Git Commit Management'
        ]);
    }

    /**
     * Get current git status
     */
    public function status()
    {
        try {
            $result = Process::path(base_path())->run('git status --short');
            
            return response()->json([
                'success' => true,
                'status' => $result->output(),
                'has_changes' => !empty(trim($result->output()))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get git status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get git log (recent commits)
     */
    public function log()
    {
        try {
            $result = Process::path(base_path())->run('git log --oneline -10');
            
            return response()->json([
                'success' => true,
                'log' => $result->output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get git log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new git commit
     */
    public function commit(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:5|max:500',
            'files' => 'nullable|array'
        ]);

        try {
            $basePath = base_path();
            
            // Add files
            if ($request->has('files') && !empty($request->files)) {
                foreach ($request->files as $file) {
                    Process::path($basePath)->run(['git', 'add', $file]);
                }
            } else {
                // Add all changes
                Process::path($basePath)->run('git add -A');
            }

            // Commit
            $commitResult = Process::path($basePath)->run(['git', 'commit', '-m', $request->message]);
            
            if (!$commitResult->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commit failed: ' . $commitResult->errorOutput()
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Changes committed successfully',
                'output' => $commitResult->output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to commit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Push commits to remote repository
     */
    public function push()
    {
        try {
            $result = Process::path(base_path())
                ->timeout(60)
                ->run('git push');
            
            if (!$result->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Push failed: ' . $result->errorOutput()
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Changes pushed to remote repository successfully',
                'output' => $result->output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to push: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pull changes from remote repository
     */
    public function pull()
    {
        try {
            $result = Process::path(base_path())
                ->timeout(60)
                ->run('git pull');
            
            if (!$result->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pull failed: ' . $result->errorOutput()
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Changes pulled from remote repository successfully',
                'output' => $result->output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to pull: ' . $e->getMessage()
            ], 500);
        }
    }
}
