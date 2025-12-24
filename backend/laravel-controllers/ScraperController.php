<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\Sentiment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    // store comments
    public function runScraper()
    {
        $output = shell_exec('python public/scraper/scraper.py');
        $result = json_decode($output, true);

        // Truncate the tables
        // Post::truncate();
        PostComment::truncate();
        foreach ($result as $postData) {
            $post = Post::updateOrCreate(['post_id' => $postData['post_id']]);
            foreach ($postData['comments'] as $commentData) {
                // Parse the "created_time" string using Carbon
                $createdDateTime = Carbon::parse($commentData['created_time']);
                $comment = new PostComment([
                    'comment_id' => $commentData['id'],
                    'message' => $commentData['message'],
                    'created_date' => $createdDateTime->toDateString(),
                    'created_time' => $createdDateTime->toTimeString(),
                ]);
                $post->comments()->save($comment);
            }
        }
    }
    // store sentiment
    public function runPipeline()
    {
        // set date & time hourly
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i:s');
        $previousHourStartTime = now()->subHour()->format('H:00:00');
        // dd($previousHourStartTime);
        $previousHourEndTime = now()->format('H:00:00');
        // retrieving comments for last hour
        $lastHourComments  = PostComment::whereDate('created_date', $currentDate)
            ->whereBetween('created_time', [$previousHourStartTime, $previousHourEndTime])
            ->orderBy('created_time')
            ->get();
        $comments = $lastHourComments->pluck('message')->toArray();
        // dd($lastHourComments);
        $commentsString = json_encode($comments);
        $escapedCommentsString = str_replace('"', '\\"', $commentsString);
        // executing command
        $output = shell_exec("python public/scraper/pipeline.py \"$escapedCommentsString\"");
        // dd($output);
        $result = json_decode($output, true);
        // Extract sentiment values
        $negative = $result['negative'];
        $neutral = $result['neutral'];
        $positive = $result['positive'];
        $veryPositive = $result['very positive'];
        // Store sentiment in the database
        $currentDateTime = Carbon::now();
        $sentiment = Sentiment::create([
            'negative' => $negative,
            'neutral' => $neutral,
            'positive' => $positive,
            'very-positive' => $veryPositive,
            'date' => $currentDateTime->toDateString(),
            'time' => $currentDateTime->addHours(5)->addMinutes(30)->toTimeString(),
        ]);
    }
    // run commands
    //  public function runHour()
    //  {
    //     $result = exec("C:\\xampp\\htdocs\\sentanal\\public\\scraper\\runcommand.bat");
    //  }
}
