<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    // private int $duration;

    public function calculate(Program $program): string
    {
        $totalDurationMinutes = 0;
        $totalDurationTime = 0;

        $seasons = $program->getSeasons();

        foreach ($seasons as $season) {
            $episodes = $season->getEpisodes();
            foreach ($episodes as $episode) {
                $episodeDuration = $episode->getDuration();
                $totalDurationMinutes += $episodeDuration;
                $totalDurationSeconds = $totalDurationMinutes * 60;
                $totalDurationTime = $this->secondsToTime($totalDurationSeconds);
            }
        }
        return $totalDurationTime;
    }

    function secondsToTime($seconds)
    {
        $dtOrigin = new \DateTime('@0');
        $dtTarget = new \DateTime("@$seconds");
        return $dtOrigin->diff($dtTarget)->format('%a jour(s), %h heures, %i minutes');
    }
}
