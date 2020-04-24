<?php

namespace App\Console\Commands;

use App\Battle;
use App\User;
use App\Vote;
use App\Entry;
use App\Events\BattleChanged;
use App\Events\BattleFinished;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessBattles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'battles:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes finished games.';

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
      $this->info('========');
      // active battles
      $active_battles = Battle::where('status', config('battle.status.upcoming'))
        ->where('start_time', '<', Carbon::now('UTC'))->get();
      foreach ($active_battles as $battle) {
        $battle->status = config('battle.status.active');
        $battle->save();
        broadcast(new BattleChanged($battle->id));
      }

      // voting battles
      $voting_battles = Battle::where('status', config('battle.status.active'))
        ->where('end_time', '<', Carbon::now('UTC'))->get();
      foreach ($voting_battles as $battle) {
        $battle->status = config('battle.status.voting');
        $battle->save();
        broadcast(new BattleChanged($battle->id));
      }

      // finished battles
      $finished_battles = Battle::where('status', config('battle.status.voting'))
        ->where('voting_time', '<', Carbon::now('UTC'))->get();
      foreach ($finished_battles as $battle) {
        $winning_entry = Entry::withCount('votes')
          ->where('battle_id', $battle->id)
          ->whereHas('user', function($q) use($battle) {
            $q->whereHas('votes', function($q2) use($battle) {
              $q2->where('battle_id', $battle->id);
            });
          })
          ->orderBy('votes_count', 'desc')
          ->first();
          $this->info('Winner: ' . $winning_entry->user->name);
        if (!$winning_entry) {
          $this->error('No winning entry.');
        } else {
          $battle->winner_id = $winning_entry->user->id;
        }
        $battle->status = config('battle.status.finished');
        $battle->save();
        broadcast(new BattleFinished($battle->id));
      }

      $this->info(count($active_battles) . " new active battles");
      $this->info(count($voting_battles) . " new voting battles");
      $this->info(count($finished_battles) . " new finished battles");
    }
}
