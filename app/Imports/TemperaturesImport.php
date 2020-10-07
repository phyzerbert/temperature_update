<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;

use App\User;
use App\Temperature;
use App\Card;
use App\Tfile;
use App\Setting;
use App\Notification;

use App\Events\DetectFever;

class TemperaturesImport implements ToArray
{
    /**
    * @param Collection $collection
    */
    public $file_path;
    public $entrance;

    public function __construct($file_path, $entrance) {
        $this->file_path = $file_path;
        $this->entrance = $entrance;
    }
    public function array(Array $rows)
    {   
        ini_set('max_execution_time', '0');
        $top_limit = Setting::find(1)->top_limit;
        array_shift($rows);
        foreach ($rows as $row) {
            if(date('Y-m-d H:i:s', strtotime($row[0])) != $row[0]) continue;
            if($row[3] == 0) continue;
            $card = Card::where('card_id', intval($row[2]))->first();
            $user = '';
            if(!$card) {
                $card = Card::create(['card_id' => intval($row[2])]);
            } else if ($card->user) {
                $user = $card->user;
            }
            
            $temperature = Temperature::create([
                'entrance' => $this->entrance,
                'user_id' => $user == '' ? null : $user->id,
                'card_id' => intval($row[2]),
                'datetime' => $row[0],
                'temperature' => $row[3],
            ]);

            if($row[3] > $top_limit) {
                if($user) {
                    $message = $user->name . " has a high fever of " . number_format($temperature->temperature, 2);
                } else {
                    $message = 'A employee has a high fever of ' . number_format($temperature->temperature, 2);
                }
                
                $notification = Notification::create([
                    'user_id' => $user == '' ? null : $user->id,
                    'card_id' => intval($row[2]),
                    'temperature_id' => $temperature->id,
                    'message' => $message,
                ]);
                // event(new DetectFever($notification));
            }

        }

        Tfile::create(['path' => $this->file_path]);
        
    }
}
