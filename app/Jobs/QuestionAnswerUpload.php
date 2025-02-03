<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Input;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use App\Exceptions\Handler;
use Zip;
use Mail;
use Storage;
use File;
Use DB;
use Alert;
use Validator;

class QuestionAnswerUpload implements ShouldQueue {


    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $readexcel;
    public $storage_folder_Path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($readexcel,$storage_folder_Path) {
        // dd(array_filter($readexcel['sheet_data']));
        $this->readexcel = $readexcel;
        $this->storage_folder_Path = $storage_folder_Path;

      // dd(1);
       $insert_data = array();
       $result_show = array();

        

        $sheet_data =array_filter($readexcel['sheet_data']);
        // dd($sheet_data);

        unset($sheet_data[1]);

        foreach ($sheet_data as $sheet_data_loop) {
            // echo "<pre>"; print_r($sheet_data_loop); echo "</pre>"; die('anil testing'); echo date("l jS \of F Y h:i:s A");
            
            $validator = Validator::make($sheet_data_loop, [
                'A' => 'required',
                'B' => 'required',
                'C' => 'required',
                'D' => 'required',
                'E' => 'required',
                'G' => 'required',
                'H' => 'required',
                'I' => 'required',
                'J' => 'required',
                'K' => 'required_if:G,Text',
                'L' => 'required_if:G,!=,Text',
                'P' => 'required',
                'Q' => 'required_if:P,Yes',
                'R' => 'required_if:P,Yes',
                'S' => 'required',
                'T' => 'required',
                'U' => 'required',
                'V' => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $questions    = new \App\Models\Quiz\Question;
            
            $board       = new \App\Models\Quiz\Board;
            $grade       = new \App\Models\Quiz\Grade;
            $subject     = new \App\Models\Quiz\Subjects;
            $chapter     = new \App\Models\Quiz\Chapters;
            $concept     = new \App\Models\Quiz\Concept;
            $instruction = new \App\Models\Quiz\Instruction;
            $paragraph = new \App\Models\Quiz\Paragraph;

            $board_id       = $board->getBoardId($sheet_data_loop['A']);
            $grade_id       = $grade->getGradeId($sheet_data_loop['B']);
            $subject_id     = $subject->getSubjectId($sheet_data_loop['C']);
            $chapter_id     = $chapter->getChapterId($sheet_data_loop['D']);
            // $concept_id     = $concept->getConceptId($sheet_data_loop['E']);
            $instruction_id = $instruction->getInstructionId($sheet_data_loop['E']);
            if($instruction_id != 0){
                $questions->instruction_id  = $instruction_id;
            }
            $paragraph_id   = $paragraph->getParagraphId($sheet_data_loop['P']);
            if($paragraph_id != 0){
                $questions->paragraph_id  = $paragraph_id;
            }
            $ans_count = $sheet_data_loop['U'];
            // dd($sheet_data_loop['J']);
            $questions->board_id           = $board_id;
            $questions->grade_id           = $grade_id;
            $questions->subject_id         = $subject_id;
            $questions->chapter_id         = $chapter_id;
            $questions->concept_id         = 1;
            
            $questions->content_type       = $sheet_data_loop['F'];
            $questions->question_type      = $sheet_data_loop['G'];
            $questions->difficulty         = $sheet_data_loop['H'];
            $questions->question           = $sheet_data_loop['I'];
            $questions->question_desc      = $sheet_data_loop['J'];
            $questions->attachment         = $sheet_data_loop['K'];
            $questions->correct_feedback   = $sheet_data_loop['L'];
            $questions->incorrect_feedback = $sheet_data_loop['M'];
            $questions->partially_feedback = $sheet_data_loop['N'];
            if($sheet_data_loop['O'] =='Yes'){
                $questions->is_paragraph       = 1;
            }
            
            $questions->score              = $sheet_data_loop['R'];
            $questions->order_by           = $sheet_data_loop['S'];
            $questions->time               = $sheet_data_loop['S']/60;
            $questions->status             = 1;
            $questions->created_by         = 1;
            $questions->updated_by         = 1;
            $questions->save();
            $j ='V';
            for($i=0; $i<$ans_count; $i++)
            {
                $answer               = new \App\Models\Quiz\Answer;
                $answer->question_id  = $questions->id;
                $answer->content_type = $sheet_data_loop[$j];
                $j++;
                $answer->answer       = $sheet_data_loop[$j];
                $j++;
                $answer->attachment   = $sheet_data_loop[$j];
                $j++;
                if($sheet_data_loop[$j] =='Yes'){
                    $answer->is_correct = 1;
                }
                $j++;
                $answer->feedback   = $sheet_data_loop[$j];
                $j++;
                // dd($j);
                $answer->order_by     = 1;
                $answer->status       = 1;
                $answer->created_by   = 1;
                $answer->updated_by   = 1;
                $answer->save();  
                  
            }
            
    }


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function deleteimages($qbid, $questions) {
        if (!empty($questions)) {

            foreach ($questions as $question_id) {

                $storage_folder_Path = storage_path('app/public/questionbank/' . $qbid . '/' . $question_id);
                \File::deleteDirectory($storage_folder_Path);
            }
        }
    }

    public function handle() {
      // dd(1);
    //    $insert_data = array();
    //    $result_show = array();

    //     $readexcel = $this->readexcel;
    //     $storage_folder_Path = $this->storage_folder_Path;

    //     $sheet_data =array_filter($readexcel['sheet_data']);
    //     // dd($sheet_data);
    //     unset($sheet_data[1]);

    //     foreach ($sheet_data as $sheet_data_loop) {
    //         $validator = Validator::make($sheet_data_loop, [
    //             'A' => 'required',
    //             'B' => 'required',
    //             'C' => 'required',
    //             'D' => 'required',
    //             'E' => 'required',
    //             'G' => 'required',
    //             'H' => 'required',
    //             'I' => 'required',
    //             'J' => 'required',
    //             'K' => 'required_if:G,Text',
    //             'L' => 'required_if:G,!=,Text',
    //             'P' => 'required',
    //             'Q' => 'required_if:P,Yes',
    //             'R' => 'required_if:P,Yes',
    //             'S' => 'required',
    //             'T' => 'required',
    //             'U' => 'required',
    //             'V' => 'required',
    //             ]);

    //         if ($validator->fails()) {
    //             return Redirect::back()
    //                 ->withErrors($validator)
    //                 ->withInput();
    //         }
    //         $questions    = new \App\Models\Quiz\Question;
            
    //         $board       = new \App\Models\Quiz\Board;
    //         $grade       = new \App\Models\Quiz\Grade;
    //         $subject     = new \App\Models\Quiz\Subject;
    //         $chapter     = new \App\Models\Quiz\Chapter;
    //         $concept     = new \App\Models\Quiz\Concept;
    //         $instruction = new \App\Models\Quiz\Instruction;
    //         $paragraph = new \App\Models\Quiz\Paragraph;

    //         $board_id       = $board->getBoardId($sheet_data_loop['A']);
    //         $grade_id       = $grade->getGradeId($sheet_data_loop['B']);
    //         $subject_id     = $subject->getSubjectId($sheet_data_loop['C']);
    //         $chapter_id     = $chapter->getChapterId($sheet_data_loop['D']);
    //         $concept_id     = $concept->getConceptId($sheet_data_loop['E']);
    //         $instruction_id = $instruction->getInstructionId($sheet_data_loop['F']);
    //         $paragraph_id   = $paragraph->getParagraphId($sheet_data_loop['Q']);
            
    //         $ans_count = $sheet_data_loop['V'];
    //         // dd($sheet_data_loop['J']);
    //         $questions->board_id           = $board_id;
    //         $questions->grade_id           = $grade_id;
    //         $questions->subject_id         = $subject_id;
    //         $questions->chapter_id         = $chapter_id;
    //         $questions->concept_id         = $concept_id;
    //         $questions->instruction_id     = $instruction_id;
    //         $questions->content_type       = $sheet_data_loop['G'];
    //         $questions->question_type      = $sheet_data_loop['H'];
    //         $questions->difficulty         = $sheet_data_loop['I'];
    //         $questions->question           = $sheet_data_loop['J'];
    //         $questions->question_desc      = $sheet_data_loop['K'];
    //         $questions->attachment         = $sheet_data_loop['L'];
    //         $questions->correct_feedback   = $sheet_data_loop['M'];
    //         $questions->incorrect_feedback = $sheet_data_loop['N'];
    //         $questions->partially_feedback = $sheet_data_loop['O'];
    //         if($sheet_data_loop['P'] =='Yes'){
    //             $questions->is_paragraph       = 1;
    //         }
    //         $questions->paragraph_id       = $paragraph_id;
    //         $questions->score              = $sheet_data_loop['S'];
    //         $questions->order_by           = $sheet_data_loop['T'];
    //         $questions->time               = $sheet_data_loop['T']/60;
    //         $questions->status             = 1;
    //         $questions->created_by         = 1;
    //         $questions->updated_by         = 1;
    //         $questions->save();
    //         $j ='W';
    //         for($i=0; $i<$ans_count; $i++)
    //         {
    //             $answer               = new \App\Models\Quiz\Answer;
    //             $answer->question_id  = $questions->id;
    //             $answer->content_type = $sheet_data_loop[$j];
    //             $j++;
    //             $answer->answer       = $sheet_data_loop[$j];
    //             $j++;
    //             $answer->attachment   = $sheet_data_loop[$j];
    //             $j++;
    //             if($sheet_data_loop[$j] =='Yes'){
    //                 $answer->is_correct = 1;
    //             }
    //             $j++;
    //             $answer->feedback   = $sheet_data_loop[$j];
    //             $j++;
    //             // dd($j);
    //             $answer->order_by     = 1;
    //             $answer->status       = 1;
    //             $answer->created_by   = 1;
    //             $answer->updated_by   = 1;
    //             $answer->save();    
    //         }
            
    // }
}
}
