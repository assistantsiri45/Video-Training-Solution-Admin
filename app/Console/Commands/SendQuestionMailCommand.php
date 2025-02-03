<?php

namespace App\Console\Commands;

use App\AskAQuestion;
use App\Mail\Questions\Reminder48;
use App\Models\Package;
use App\Models\Professor;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Questions\Reminder24;

class SendQuestionMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:question-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $fromDate = Carbon::parse('2021-06-24');

        $questionsOlderThanADay = AskAQuestion::query()
            ->whereBetween('created_at', [Carbon::now()->subDays(2), Carbon::now()->subDay()])
            ->where('deleted_at', null)
            ->whereDoesntHave('answer')
            ->latest()
            ->get();

        foreach ($questionsOlderThanADay as $question) {
            try {
                $user = User::query()->find($question->user_id);
                $video = Video::query()->find($question->video_id);
                $package = Package::query()->find($question->package_id);
                $professor = Professor::query()->find($video->professor_id);


                $attributes = [
                    'user_name' => $user->name,
                    'video_name' => $video->title,
                    'package_name' => $package->name,
                    'professor_name' => $professor->name,
                    'professor_email' => $professor->email,
                    'question' => $question->question,
                    'logo_url' => env('WEB_URL') . '/assets/images/logo.png',
                    'web_url' => env('WEB_URL'),
                    'answer_portal_url' => env('WEB_URL') . '/answer-portal?question_id=' . $question->id
                ];

                Mail::send(new Reminder24($attributes));
            } catch (\Exception $exception) {
                info($exception->getTraceAsString());
            }
        }

        $questionsOlderThanTwoDays = AskAQuestion::query()
            ->whereBetween('created_at', [$fromDate, Carbon::now()->subDays(2)])
            ->where('deleted_at', null)
            ->whereDoesntHave('answer')
            ->latest()
            ->get();

        foreach ($questionsOlderThanTwoDays as $question) {
            try {
                $user = User::query()->find($question->user_id);
                $video = Video::query()->find($question->video_id);
                $package = Package::query()->find($question->package_id);
                $professor = Professor::query()->find($video->professor_id);

                $attributes = [
                    'user_name' => $user->name,
                    'video_name' => $video->title,
                    'package_name' => $package->name,
                    'professor_name' => $professor->name,
                    'professor_email' => $professor->email,
                    'question' => $question->question,
                    'logo_url' => env('WEB_URL') . '/assets/images/logo.png',
                    'web_url' => env('WEB_URL'),
                    'answer_portal_url' => env('WEB_URL') . '/answer-portal?question_id=' . $question->id
                ];

                Mail::send(new Reminder48($attributes));
            } catch (\Exception $exception) {
                info($exception->getTraceAsString());
            }
        }
    }
}
