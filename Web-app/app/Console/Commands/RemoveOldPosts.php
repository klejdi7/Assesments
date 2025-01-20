<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Posts;
use Illuminate\Support\Facades\Log;

class RemoveOldPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove posts that have had not a lot of activity';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = Posts::whereDoesntHave('comments', function ($query) {
            $query->where('created_at', '>=', now()->subYear());
        })->get();
    
        foreach ($posts as $post) {
            $post->delete();
        }
    
        $this->info('Old posts have been cleaned.');
        Log::info("Old posts cleaned at " . now());
    }
}
