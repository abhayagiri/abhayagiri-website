## Space

### rclone

Install/setup `rclone`: https://rclone.org/s3/#digitalocean-spaces

Usage: https://rclone.org/docs/

### s3cmd

Install/setup `s3cmd`: https://www.digitalocean.com/docs/spaces/resources/s3cmd/

Usage: https://www.digitalocean.com/docs/spaces/resources/s3cmd-usage/

```sh
# Fix all permissions on /media
s3cmd setacl -v s3://abhayagiri/media/ --acl-public --recursive
s3cmd setacl -v s3://abhayagiri-staging/media/ --acl-public --recursive
```
