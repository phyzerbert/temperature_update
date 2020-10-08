<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;

use App\User;
use App\Temperature;
use App\Card;
use App\Notification;

use DB;

class LenelImport implements ToArray
{
    public function array(Array $rows)
    {
        ini_set('max_execution_time', '0');
        array_shift($rows);

        DB::beginTransaction();

        foreach ($rows as $row) {
            $user = User::where('employee_id', intval($row[2]))->first();
            if(!$user) {
                $user = User::create([
                    'employee_id' => intval($row[2]),
                    'name' => $row[1],
                    'role' => 'user',
                ]);
            }
            
            // Create Card
            $card = Card::where('card_id', intval($row[0]))->first();
            if(!$card) {
                $card = Card::create(['card_id' => intval($row[0])]);
            }
            if($card->user_id != $user->id) {
                $card->update(['user_id' => $user->id]);
            }
            Temperature::where('card_id', $card->card_id)->update(['user_id' => $user->id]);
            Notification::where('card_id', $card->card_id)->update(['user_id' => $user->id]);
        }

        DB::commit();
    }
}
