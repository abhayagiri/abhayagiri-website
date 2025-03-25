# Backup Strategy

3-2-1 backup strategy with:

* Production on the server
* One backup copy in Google Drive (offsite)
* One backup copy in Digital Ocean Spaces (different provider)

## Daily backups

Backups are taken daily at 1:30AM UTC. There is a forge cron configured to run the [backup.sh](scripts/forge/backup.sh) script.

The script generates this structure:

```txt
/backup
    /src (source code)
    /database
    /logs (nginx error and access, laravel.log)
```
