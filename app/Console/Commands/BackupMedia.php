<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupMedia extends Command
{
    use ArchiveTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:backup-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup media';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $mediaBackupUrl = config('archive.media_backup_url');
        if ($mediaBackupUrl) {
            $this->info("Backing up media to $mediaBackupUrl.");
            $this->exec([
                'rsync', '-i', '-avz',
                public_path('media/'), $mediaBackupUrl,
            ]);
        } else {
            $this->error('MEDIA_BACKUP_URL not defined in .env.');
        }
    }
}
