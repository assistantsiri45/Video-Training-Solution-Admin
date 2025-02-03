<?php

namespace App\Console\Commands;

use App\Models\EdugulpVideos;
use Illuminate\Console\Command;

class SyncVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Videos from api';

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
        $date = '2020-07-17%2007:09:11';
        $cp = curl_init();
        $url = 'http://edugulp.test/api/published-videos?date='.$date;

        curl_setopt($cp, CURLOPT_URL, $url);

        curl_setopt($cp, CURLOPT_RETURNTRANSFER, 1);
        $videos = curl_exec($cp);
        curl_close($cp);

        $edugulp_videos =  json_decode($videos);

        foreach($edugulp_videos  as  $edugulp_video){
            $insert_videos = new EdugulpVideos();
            $insert_videos->course = $edugulp_video->get_chapter->get_subject->get_course->course_title;
            $insert_videos->level = $edugulp_video->get_chapter->get_subject->get_level->level_name;
            $insert_videos->subject = $edugulp_video->get_chapter->get_subject->category_name;
            $insert_videos->chapter = $edugulp_video->get_chapter->video_name;
            $insert_videos->professor = $edugulp_video->get_professor->name;
            $insert_videos->title = $edugulp_video->video_title;
            $insert_videos->url = $edugulp_video->video_name_url;
            $insert_videos->media_id = $edugulp_video->video_url;
            $insert_videos->duration =$edugulp_video->video_timing;
            $insert_videos->description = $edugulp_video->description;
            $insert_videos->tags =$edugulp_video->tags;
            $insert_videos->language = $edugulp_video->get_language->language_name;
            $insert_videos->video_id = $edugulp_video->id;
            $insert_videos->save();

        }
        echo 'Videos saved successfully';

    }
}
