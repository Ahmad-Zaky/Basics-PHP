<?php

namespace App\Providers;

use App\Models\Note;

class EventServiceProvider
{
    public function register()
    {
        event()->bind('notes.after.saving', function (Note $note) {
            if ($note->completed) {
                $note->update([
                    'completed_at' => now(),
                ]);

                return;
            }

            $note->update([
                'completed_at' => NULL,
            ]);
        });
    }
}